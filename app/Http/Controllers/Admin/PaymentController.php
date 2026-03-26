<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Order;
use App\Services\GoogleSheetsService;
use App\Services\RobuxStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Default: show only waiting_confirmation orders
        $query = Order::where('payment_status', 'waiting_confirmation')
            ->whereNotNull('order_status');

        // If status filter is provided, show all relevant statuses
        if ($request->has('status') && $request->status) {
            if ($request->status === 'all') {
                // Show all statuses - include Failed orders even without order_status
                $query = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])
                    ->where(function ($q) {
                    $q->whereNotNull('order_status')
                        ->orWhere('payment_status', 'Failed');
                });
            }
            else {
                // Show specific status - map lowercase to proper case
                $statusMap = [
                    'waiting_confirmation' => 'waiting_confirmation',
                    'completed' => 'Completed',
                    'failed' => 'Failed'
                ];
                $mappedStatus = $statusMap[$request->status] ?? $request->status;

                if ($mappedStatus === 'Failed') {
                    // For Failed orders, show even without order_status
                    $query = Order::where('payment_status', 'Failed');
                }
                else {
                    // For other statuses, require order_status
                    $query = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])
                        ->whereNotNull('order_status')
                        ->where('payment_status', $mappedStatus);
                }
            }
        }

        // Search by username or order ID
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
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

        // Order by priority: waiting_confirmation first, then by created_at asc
        if ($request->has('status') && $request->status === 'all') {
            $orders = $query->orderByRaw("CASE WHEN payment_status = 'waiting_confirmation' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'asc')
                ->paginate(20);
        }
        else {
            $orders = $query->orderBy('created_at', 'asc')
                ->paginate(20);
        }

        // Get all orders for statistics (including completed and failed)
        $allOrders = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])
            ->where(function ($q) {
            $q->whereNotNull('order_status')
                ->orWhere('payment_status', 'Failed');
        })
            ->get();

        return view('admin.payments.index', compact('orders', 'allOrders'));
    }

    public function show(Order $order)
    {
        return view('admin.payments.show', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        // Validate request
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if order is still in waiting_confirmation status
        if ($order->payment_status !== 'waiting_confirmation') {
            return redirect()->back()->with('error', 'Order sudah diproses sebelumnya');
        }

        if ($request->action === 'approve') {
            $admin = Auth::guard('admin')->user();

            AdminActivityLog::create([
                'order_id' => $order->order_id,
                'action' => 'payment_approve',
                'admin_id' => $admin->id ?? null,
                'admin_name' => $admin->name ?? null,
                'admin_email' => $admin->email ?? null,
                'notes' => $request->notes ?? null,
            ]);

            $existingNotes = [];
            if (is_array($order->notes)) {
                $existingNotes = $order->notes;
            }
            else {
                $decoded = @json_decode((string)($order->notes ?? '{}'), true);
                if (is_array($decoded)) {
                    $existingNotes = $decoded;
                }
            }

            // Update order status
            $order->update([
                'payment_status' => 'Completed',
                'order_status' => 'pending',
                'notes' => json_encode(array_merge(
                $existingNotes,
                ['admin_notes' => $request->notes ?? 'Payment confirmed by admin'],
                ['confirmed_at' => now()->toISOString()]
            )),
            ]);

            // Update promo code usage if order has promo code
            $notes = json_decode($order->notes ?? '{}', true);
            if (isset($notes['promo_code']['promo_code_id'])) {
                $promoCodeId = $notes['promo_code']['promo_code_id'];
                \App\Models\PromoCodeUsage::where('order_id', $order->order_id)
                    ->where('promo_code_id', $promoCodeId)
                    ->update([
                    'payment_status' => 'Completed',
                    'is_paid' => true,
                ]);
            }

            // Refresh order to get latest data before sending to spreadsheet
            $order->refresh();

            // Send email notification
            try {
                // Re-apply email config from settings before sending
                $this->applyEmailConfig();

                Log::info('Attempting to send payment confirmed email', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                ]);

                \Mail::to($order->email)->send(new \App\Mail\PaymentConfirmedNotification($order));

                Log::info('Payment confirmed email sent successfully', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                ]);
            }
            catch (\Exception $e) {
                Log::error('Failed to send payment confirmed email', [
                    'order_id' => $order->order_id,
                    'email' => $order->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            // Don't fail payment confirmation if email fails
            }

            // Add to spreadsheet with delay to prevent concurrent request issues
            // Use same delay strategy as Midtrans for consistency
            // For manual payment: use longer delay range (200ms-2000ms) and timestamp-based offset
            // This ensures all orders get processed even if 3-5 orders come in at once
            $baseDelay = rand(200000, 2000000); // 200ms to 2000ms base delay
            $timestampOffset = (intval(substr($order->order_id, -1)) % 10) * 100000; // 0-900ms offset based on order ID
            $totalDelay = $baseDelay + $timestampOffset;
            usleep($totalDelay);

            // Try to add to spreadsheet, with error handling
            // This will retry up to 5 times automatically if it fails (for better concurrent handling)
            try {
                $order->forceFill([
                    'spreadsheet_attempts' => (int) ($order->spreadsheet_attempts ?? 0) + 1,
                    'spreadsheet_last_attempt_at' => now(),
                    'spreadsheet_last_error' => null,
                ])->save();

                $result = GoogleSheetsService::addOrderToSpreadsheet($order);
                if ($result) {
                    $order->forceFill([
                        'spreadsheet_sent_at' => now(),
                        'spreadsheet_last_error' => null,
                    ])->save();

                    Log::info('Manual order added to spreadsheet successfully', [
                        'order_id' => $order->order_id,
                        'delay_ms' => round($totalDelay / 1000, 2),
                    ]);
                }
                else {
                    $order->forceFill([
                        'spreadsheet_last_error' => 'GoogleSheetsService returned false',
                    ])->save();

                    Log::warning('Manual order spreadsheet add returned false', [
                        'order_id' => $order->order_id,
                    ]);
                }
            }
            catch (\Exception $e) {
                try {
                    $order->forceFill([
                        'spreadsheet_last_error' => $e->getMessage(),
                    ])->save();
                } catch (\Exception $ignored) {
                    // ignore
                }

                // Log error but don't fail the payment confirmation
                // GoogleSheetsService already has retry mechanism, so this is a last resort
                Log::error('Failed to add manual order to spreadsheet (exception)', [
                    'order_id' => $order->order_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $message = 'Payment confirmed successfully!';
        }
        else {
            // Restore stock if it was deducted during upload proof (manual reserve)
            if ($order->game_type === 'Robux' && $order->amount) {
                $existingNotes = [];
                if (is_array($order->notes)) {
                    $existingNotes = $order->notes;
                }
                else {
                    $decoded = @json_decode((string)($order->notes ?? '{}'), true);
                    if (is_array($decoded)) {
                        $existingNotes = $decoded;
                    }
                }

                $wasDeducted = !empty($existingNotes['stock_deducted_at']) && (($existingNotes['stock_deducted_by'] ?? null) === 'manual_upload_proof');
                $alreadyRestored = !empty($existingNotes['stock_restored_at']);

                if ($wasDeducted && !$alreadyRestored) {
                    $purchaseMethod = $order->purchase_method ?? ($existingNotes['stock_deducted_method'] ?? 'gamepass');
                    RobuxStockService::addStock((int)$order->amount, $purchaseMethod);
                    $existingNotes['stock_restored_at'] = now()->toISOString();
                    $existingNotes['stock_restored_by'] = 'manual_reject';
                    $order->update(['notes' => json_encode($existingNotes)]);
                    $order->refresh();
                }
            }

            $admin = Auth::guard('admin')->user();

            AdminActivityLog::create([
                'order_id' => $order->order_id,
                'action' => 'payment_reject',
                'admin_id' => $admin->id ?? null,
                'admin_name' => $admin->name ?? null,
                'admin_email' => $admin->email ?? null,
                'notes' => $request->notes ?? null,
            ]);

            $existingNotes = [];
            if (is_array($order->notes)) {
                $existingNotes = $order->notes;
            }
            else {
                $decoded = @json_decode((string)($order->notes ?? '{}'), true);
                if (is_array($decoded)) {
                    $existingNotes = $decoded;
                }
            }

            // Update order status for rejection
            $order->update([
                'payment_status' => 'Failed',
                'order_status' => null,
                'notes' => json_encode(array_merge(
                $existingNotes,
                ['admin_notes' => $request->notes ?? 'Payment rejected by admin'],
                ['rejected_at' => now()->toISOString()]
            ))
            ]);

            $message = 'Payment rejected successfully!';
        }

        return redirect()->route('admin.payments')->with('success', $message);
    }


    public function downloadProof(Order $order)
    {
        if (!$order->proof_file) {
            abort(404, 'Proof file not found');
        }

        $relativePath = 'proofs/' . $order->proof_file;

        if (!Storage::disk('local')->exists($relativePath)) {
            abort(404, 'Proof file not found on server');
        }

        $filePath = Storage::disk('local')->path($relativePath);

        return response()->download($filePath, $order->proof_file);
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
        }
        catch (\Exception $e) {
            Log::warning('Failed to apply email config from database: ' . $e->getMessage());
        }
    }
}