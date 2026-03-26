<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.promo-codes', compact('promoCodes'));
    }

    public function create()
    {
        return view('admin.promo-code-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_method' => 'required|in:percentage,fixed_amount',
            'discount_value_min' => 'required|numeric|min:0',
            'discount_value_max' => 'required|numeric|min:0|gte:discount_value_min',
            'max_uses' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'custom_code' => 'nullable|string|max:50|unique:promo_codes,code',
        ]);

        // Generate code if not provided
        $code = $request->custom_code;
        if (empty($code)) {
            $code = PromoCode::generateCode(8);
        } else {
            // Check if custom code already exists
            if (PromoCode::where('code', $code)->exists()) {
                return back()->withErrors(['custom_code' => 'Kode promo sudah digunakan.'])->withInput();
            }
        }

        PromoCode::create([
            'code' => $code,
            'discount_method' => $request->discount_method,
            'discount_value_min' => $request->discount_value_min,
            'discount_value_max' => $request->discount_value_max,
            'max_uses' => $request->max_uses,
            'current_uses' => 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.promo-codes')->with('success', 'Kode promo berhasil dibuat: ' . $code);
    }

    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-code-form', compact('promoCode'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id,
            'discount_method' => 'required|in:percentage,fixed_amount',
            'discount_value_min' => 'required|numeric|min:0',
            'discount_value_max' => 'required|numeric|min:0|gte:discount_value_min',
            'max_uses' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Ensure max_uses is not less than current_uses
        if ($request->max_uses < $promoCode->current_uses) {
            return back()->withErrors(['max_uses' => 'Maksimal penggunaan tidak boleh kurang dari penggunaan saat ini (' . $promoCode->current_uses . ').'])->withInput();
        }

        $promoCode->update([
            'code' => $request->code,
            'discount_method' => $request->discount_method,
            'discount_value_min' => $request->discount_value_min,
            'discount_value_max' => $request->discount_value_max,
            'max_uses' => $request->max_uses,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.promo-codes')->with('success', 'Kode promo berhasil diperbarui.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('admin.promo-codes')->with('success', 'Kode promo berhasil dihapus.');
    }

    public function toggleStatus(PromoCode $promoCode)
    {
        $promoCode->update(['is_active' => !$promoCode->is_active]);
        $status = $promoCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Kode promo {$status}.");
    }

    public function usages(PromoCode $promoCode)
    {
        $usages = $promoCode->usages()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'usages' => $usages->map(function($usage) {
                return [
                    'order_id' => $usage->order_id,
                    'username' => $usage->username,
                    'email' => $usage->email,
                    'original_price' => (float) $usage->original_price,
                    'discount_amount' => (float) $usage->discount_amount,
                    'final_price' => (float) $usage->final_price,
                    'is_paid' => $usage->is_paid,
                    'payment_status' => $usage->payment_status,
                    'created_at' => $usage->created_at->toISOString(),
                ];
            })
        ]);
    }

    /**
     * Sync all promo code usages with order payment_status
     */
    public function syncUsages()
    {
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        
        // Get all orders with promo codes
        $orders = \App\Models\Order::whereNotNull('notes')
            ->get()
            ->filter(function($order) {
                $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes ?? '{}', true);
                return isset($notes['promo_code']['promo_code_id']);
            });
        
        foreach ($orders as $order) {
            try {
                $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes ?? '{}', true);
                $promoCodeId = $notes['promo_code']['promo_code_id'] ?? null;
                
                if (!$promoCodeId) {
                    $skipped++;
                    continue;
                }
                
                // Find promo code usage for this order
                $usage = \App\Models\PromoCodeUsage::where('order_id', $order->order_id)
                    ->where('promo_code_id', $promoCodeId)
                    ->first();
                
                if (!$usage) {
                    $skipped++;
                    continue;
                }
                
                // Determine status based on payment_status
                // Logika: Pending = belum bayar, Selain Pending = sudah bayar (meski belum diverifikasi)
                $paymentStatus = $order->payment_status;
                
                if ($paymentStatus === 'Pending') {
                    // Payment pending - mark as unpaid
                    $shouldBePaid = false;
                    $usagePaymentStatus = 'Pending';
                } else {
                    // Status selain Pending = sudah bayar (Completed, Failed, Cancelled, waiting_confirmation, dll)
                    // Semua selain Pending berarti sudah bayar, jadi is_paid = true
                    $shouldBePaid = true;
                    $usagePaymentStatus = $paymentStatus === 'Completed' ? 'Completed' : 
                                         ($paymentStatus === 'Failed' || $paymentStatus === 'Cancelled' ? 'Failed' : $paymentStatus);
                }
                
                // Check if update is needed
                if ($usage->is_paid !== $shouldBePaid || $usage->payment_status !== $usagePaymentStatus) {
                    $usage->update([
                        'payment_status' => $usagePaymentStatus,
                        'is_paid' => $shouldBePaid,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $errors++;
                \Illuminate\Support\Facades\Log::error("Error syncing promo code usage for order {$order->order_id}: " . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sync completed',
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }
}
