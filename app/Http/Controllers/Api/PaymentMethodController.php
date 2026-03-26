<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Get available payment methods from Midtrans
     * Returns all supported payment methods when gateway mode is active
     */
    public function getPaymentMethods()
    {
        try {
            $paymentMode = Setting::getValue('payment_mode', 'manual');
            $paymentGateway = Setting::getValue('payment_gateway', 'none');
            
            if ($paymentMode === 'manual') {
                // Manual mode - only QRIS
                return response()->json([
                    'payment_mode' => 'manual',
                    'methods' => [
                        [
                            'code' => 'qris',
                            'name' => 'QRIS Transfer',
                            'description' => 'Scan QR Code untuk pembayaran instan',
                            'icon' => 'qris',
                            'type' => 'manual'
                        ]
                    ]
                ]);
            }
            
            if ($paymentMode === 'gateway' && $paymentGateway === 'midtrans') {
                // Gateway mode with Midtrans - return all Midtrans payment methods
                // Urutan: QRIS > Dompet Digital > Bank > Credit Card
                return response()->json([
                    'payment_mode' => 'gateway',
                    'payment_gateway' => 'midtrans',
                    'methods' => [
                        // 1. QRIS (Paling Atas - Paling Diutamakan)
                        [
                            'code' => 'qris',
                            'name' => 'QRIS',
                            'description' => 'Scan QR Code untuk pembayaran instan',
                            'icon' => 'qris',
                            'type' => 'qris'
                        ],
                        // 2. E-Wallets / Dompet Digital
                        [
                            'code' => 'dana',
                            'name' => 'DANA',
                            'description' => 'Pembayaran via DANA',
                            'icon' => 'dana',
                            'type' => 'ewallet'
                        ],
                        [
                            'code' => 'gopay',
                            'name' => 'GoPay',
                            'description' => 'Pembayaran via GoPay',
                            'icon' => 'gopay',
                            'type' => 'ewallet'
                        ],
                        [
                            'code' => 'ovo',
                            'name' => 'OVO',
                            'description' => 'Pembayaran via OVO',
                            'icon' => 'ovo',
                            'type' => 'ewallet'
                        ],
                        [
                            'code' => 'linkaja',
                            'name' => 'LinkAja',
                            'description' => 'Pembayaran via LinkAja',
                            'icon' => 'linkaja',
                            'type' => 'ewallet'
                        ],
                        [
                            'code' => 'shopeepay',
                            'name' => 'ShopeePay',
                            'description' => 'Pembayaran via ShopeePay',
                            'icon' => 'shopeepay',
                            'type' => 'ewallet'
                        ],
                        // 3. Virtual Accounts / Bank Transfer
                        [
                            'code' => 'bca_va',
                            'name' => 'BCA Virtual Account',
                            'description' => 'Transfer ke rekening BCA',
                            'icon' => 'bca',
                            'type' => 'bank_transfer',
                            'bank' => 'bca'
                        ],
                        [
                            'code' => 'mandiri_va',
                            'name' => 'Mandiri Virtual Account',
                            'description' => 'Transfer ke rekening Mandiri',
                            'icon' => 'mandiri',
                            'type' => 'bank_transfer',
                            'bank' => 'mandiri'
                        ],
                        [
                            'code' => 'bni_va',
                            'name' => 'BNI Virtual Account',
                            'description' => 'Transfer ke rekening BNI',
                            'icon' => 'bni',
                            'type' => 'bank_transfer',
                            'bank' => 'bni'
                        ],
                        [
                            'code' => 'permata_va',
                            'name' => 'Permata Virtual Account',
                            'description' => 'Transfer ke rekening Permata',
                            'icon' => 'permata',
                            'type' => 'bank_transfer',
                            'bank' => 'permata'
                        ],
                        // 4. Credit Cards (Terakhir)
                        [
                            'code' => 'credit_card',
                            'name' => 'Kartu Kredit/Debit',
                            'description' => 'Visa, Mastercard, JCB, dll',
                            'icon' => 'credit_card',
                            'type' => 'credit_card'
                        ],
                    ]
                ]);
            }
            
            // Default fallback
            return response()->json([
                'payment_mode' => 'manual',
                'methods' => [
                    [
                        'code' => 'qris',
                        'name' => 'QRIS Transfer',
                        'description' => 'Scan QR Code untuk pembayaran instan',
                        'icon' => 'qris',
                        'type' => 'manual'
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load payment methods',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

