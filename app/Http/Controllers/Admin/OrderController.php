<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Order;
use App\Models\Activity;
use App\Models\Setting;
use App\Services\RobuxStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Only show orders with payment_status = 'Completed'
        $query = Order::where('payment_status', 'Completed')
            ->whereNotNull('order_status');

        // Filter by game type
        $gameType = $request->get('type', 'robux'); // Default to robux
        
        if ($gameType === 'robux') {
            $query->where('game_type', 'Robux');
        } else {
            $query->where('game_type', '!=', 'Robux');
        }

        // Filter by purchase method (only for Robux orders)
        $purchaseMethod = $request->get('purchase_method');
        if ($gameType === 'robux' && in_array($purchaseMethod, ['gamepass', 'group'], true)) {
            $query->where('purchase_method', $purchaseMethod);
        }

        // Search by username or order_id
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('order_id', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by order_status - default to pending only
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        } else {
            // Default to pending orders only
            $query->where('order_status', 'pending');
        }

        // Dynamic sorting based on order status
        if ($request->has('status') && $request->status === 'completed') {
            // For completed orders: newest first (terbaru dulu)
            $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        } else {
            // For pending orders: oldest first (terlama dulu - FIFO)
            $orders = $query->orderBy('created_at', 'asc')->paginate(20);
        }

        // Get statistics for current game type
        $baseQuery = Order::where('payment_status', 'Completed')
            ->whereNotNull('order_status');
            
        if ($gameType === 'robux') {
            $baseQuery->where('game_type', 'Robux');
        } else {
            $baseQuery->where('game_type', '!=', 'Robux');
        }

        $stats = [
            'pending_orders' => (clone $baseQuery)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $baseQuery)->where('order_status', 'completed')->count(),
            'total_revenue' => (clone $baseQuery)->where('order_status', 'completed')->sum('total_amount'),
            'total_orders' => (clone $baseQuery)->count()
        ];

        return view('admin.orders', compact('orders', 'gameType', 'stats', 'purchaseMethod'));
    }

    public function show(Order $order)
    {
        $adminLogs = AdminActivityLog::where('order_id', $order->order_id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.order-detail', compact('order', 'adminLogs'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,completed'
        ]);

        $updateData = [
            'order_status' => $request->order_status
        ];

        // If setting status to completed, set completed_at based on game type
        if ($request->order_status === 'completed') {
            if ($order->game_type === 'Robux') {
                // For Robux orders, check purchase method
                $purchaseMethod = $order->purchase_method ?? 'gamepass';
                
                if ($purchaseMethod === 'group') {
                    // Group orders must be completed within 1 hour
                    $updateData['completed_at'] = now()->addHour();
                } else {
                    // Gamepass orders use auto_complete_days setting
                    $autoCompleteDays = \App\Models\Setting::getIntValue('auto_complete_days', 5);
                    $updateData['completed_at'] = now()->addDays($autoCompleteDays);
                }
            } else {
                // For other products, use 5 minutes
                $updateData['completed_at'] = now()->addMinutes(5);
            }
        }

        $order->update($updateData);

        if ($request->order_status === 'completed') {
            $admin = Auth::guard('admin')->user();

            AdminActivityLog::create([
                'order_id' => $order->order_id,
                'action' => 'order_processed',
                'admin_id' => $admin->id ?? null,
                'admin_name' => $admin->name ?? null,
                'admin_email' => $admin->email ?? null,
            ]);
        }
        
        // Send email notification when order is processed (status = completed)
        if ($request->order_status === 'completed') {
            try {
                // Calculate estimated completion message
                $estimatedCompletion = null;
                if ($order->game_type === 'Robux') {
                    $purchaseMethod = $order->purchase_method ?? 'gamepass';
                    if ($purchaseMethod === 'group') {
                        $estimatedCompletion = '1 jam ke depan';
                    } else {
                        $autoCompleteDays = \App\Models\Setting::getIntValue('auto_complete_days', 5);
                        $estimatedCompletion = $autoCompleteDays . ' hari ke depan';
                    }
                } else {
                    $estimatedCompletion = '5 menit ke depan';
                }
                
                // Re-apply email config from settings before sending
                $this->applyEmailConfig();
                
                \Illuminate\Support\Facades\Log::info('Attempting to send order processed email', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                ]);
                
                \Mail::to($order->email)->send(new \App\Mail\OrderProcessedNotification($order, $estimatedCompletion));
                
                \Illuminate\Support\Facades\Log::info('Order processed email sent successfully', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send order processed email', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Don't fail order processing if email fails
            }
        }

        // Create activity record when order is completed
        if ($request->order_status === 'completed') {
            Activity::createFromOrder($order);
        }

        // Generate success message based on order type and purchase method
        $message = 'Order status updated successfully.';
        if ($request->order_status === 'completed') {
            if ($order->game_type === 'Robux') {
                $purchaseMethod = $order->purchase_method ?? 'gamepass';
                if ($purchaseMethod === 'group') {
                    $message = "Order berhasil diproses! Harus dikerjakan dalam 1 jam ke depan.";
                } else {
                    $autoCompleteDays = \App\Models\Setting::getIntValue('auto_complete_days', 5);
                    $message = "Order berhasil diproses! Akan selesai dalam {$autoCompleteDays} hari.";
                }
            } else {
                $message = "Order berhasil diproses! Akan selesai dalam 5 menit.";
            }
        }

        return redirect()->back()->with('success', $message);
    }

    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Only allow reject for paid orders that are pending processing
        if ($order->payment_status !== 'Completed' || $order->order_status !== 'pending') {
            return redirect()->back()->with('error', 'Order tidak dalam status yang bisa ditolak');
        }

        // Restore stock if it was deducted
        if ($order->game_type === 'Robux' && $order->amount) {
            $existingNotes = [];
            if (is_array($order->notes)) {
                $existingNotes = $order->notes;
            } else {
                $decoded = @json_decode((string) ($order->notes ?? '{}'), true);
                if (is_array($decoded)) {
                    $existingNotes = $decoded;
                }
            }

            $alreadyRestored = !empty($existingNotes['stock_restored_at']);
            if (!$alreadyRestored && !empty($existingNotes['stock_deducted_at'])) {
                $purchaseMethod = $order->purchase_method ?? ($existingNotes['stock_deducted_method'] ?? 'gamepass');
                RobuxStockService::addStock((int) $order->amount, $purchaseMethod);
                $existingNotes['stock_restored_at'] = now()->toISOString();
                $existingNotes['stock_restored_by'] = 'admin_order_reject';
                $existingNotes['admin_reject_notes'] = $request->notes ?? 'Order rejected by admin';
                $order->update(['notes' => json_encode($existingNotes)]);
                $order->refresh();
            }
        }

        $order->update([
            'payment_status' => 'Failed',
            'order_status' => null,
        ]);

        $admin = Auth::guard('admin')->user();

        AdminActivityLog::create([
            'order_id' => $order->order_id,
            'action' => 'order_reject',
            'admin_id' => $admin->id ?? null,
            'admin_name' => $admin->name ?? null,
            'admin_email' => $admin->email ?? null,
            'notes' => $request->notes ?? null,
        ]);

        return redirect()->back()->with('success', 'Order berhasil ditolak dan stok telah dikembalikan');
    }
    
    /**
     * Apply email configuration from database settings
     * Real case: Pakai database settings
     */
    private function applyEmailConfig()
    {
        try {
            // PAKAI DATABASE SETTINGS (Real Case Implementation)
            $mailer = Setting::getValue('mail_mailer', 'log');
            $host = Setting::getValue('mail_host', '');
            $port = Setting::getValue('mail_port', '587');
            $username = Setting::getValue('mail_username', '');
            $password = Setting::getValue('mail_password', '');
            $encryption = Setting::getValue('mail_encryption', 'tls');
            $fromAddress = Setting::getValue('mail_from_address', 'hello@example.com');
            $fromName = Setting::getValue('mail_from_name', 'Valtus');
            
            // Normalize encryption
            if ($encryption === 'null' || $encryption === null || $encryption === '') {
                $encryption = null;
            }
            
            config([
                'mail.default' => $mailer ?: 'log',
                'mail.mailers.smtp.host' => $host ?: '127.0.0.1',
                'mail.mailers.smtp.port' => $port ?: '2525',
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => 60, // 60 seconds timeout
                'mail.from.address' => $fromAddress ?: 'hello@example.com',
                'mail.from.name' => $fromName ?: 'Valtus',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to apply email config from database: ' . $e->getMessage());
        }
    }
}
