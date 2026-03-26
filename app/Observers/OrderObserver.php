<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\PromoCodeUsage;
use App\Models\ReferralConversion;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if payment_status changed
        if ($order->isDirty('payment_status')) {
            $newStatus = $order->payment_status;
            $oldStatus = $order->getOriginal('payment_status');
            
            // Check if order has promo code in notes
            $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes ?? '{}', true);
            
            if (isset($notes['promo_code']['promo_code_id'])) {
                $promoCodeId = $notes['promo_code']['promo_code_id'];
                
                // Update promo code usage based on payment status
                // Logika: Pending = belum bayar, Selain Pending = sudah bayar (meski belum diverifikasi)
                if ($newStatus === 'Pending') {
                    // Payment pending - mark as unpaid
                    PromoCodeUsage::where('order_id', $order->order_id)
                        ->where('promo_code_id', $promoCodeId)
                        ->update([
                            'payment_status' => 'Pending',
                            'is_paid' => false,
                        ]);
                } else {
                    // Status selain Pending = sudah bayar (Completed, Failed, Cancelled, waiting_confirmation, dll)
                    // Semua selain Pending berarti sudah bayar, jadi is_paid = true
                    $usagePaymentStatus = $newStatus === 'Completed' ? 'Completed' : 
                                         ($newStatus === 'Failed' || $newStatus === 'Cancelled' ? 'Failed' : $newStatus);
                    
                    PromoCodeUsage::where('order_id', $order->order_id)
                        ->where('promo_code_id', $promoCodeId)
                        ->update([
                            'payment_status' => $usagePaymentStatus,
                            'is_paid' => true, // Semua selain Pending = sudah bayar
                        ]);
                }
            }

            // Sync referral conversion status (if any)
            if (isset($notes['referral']['code'])) {
                $normalized = strtolower((string) $newStatus);

                $targetStatus = null;
                if ($newStatus === 'Completed') {
                    $targetStatus = 'approved';
                } elseif (in_array($normalized, ['failed', 'cancelled', 'canceled', 'expire', 'expired', 'cancel', 'deny'], true)) {
                    $targetStatus = 'rejected';
                }

                if ($targetStatus) {
                    ReferralConversion::where('order_id', $order->order_id)
                        ->where('status', 'pending')
                        ->update([
                            'status' => $targetStatus,
                        ]);
                }
            }
        }
    }
}
