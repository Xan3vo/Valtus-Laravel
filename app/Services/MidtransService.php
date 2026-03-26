<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private $serverKey;
    private $clientKey;
    private $isProduction;
    
    public function __construct()
    {
        $this->serverKey = Setting::getValue('midtrans_server_key', '');
        $this->clientKey = Setting::getValue('midtrans_client_key', '');
        $environment = Setting::getValue('midtrans_environment', 'sandbox');
        $this->isProduction = $environment === 'production';
        
        // Set Midtrans config
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    /**
     * Create Snap transaction and return snap token
     */
    public function createSnapToken(Order $order)
    {
        try {
            // Log start of Snap token creation
            Log::info('Starting Midtrans Snap token creation', [
                'order_id' => $order->order_id,
                'order_payment_method' => $order->payment_method,
                'order_purchase_method' => $order->purchase_method,
                'order_price' => $order->price,
                'order_amount' => $order->amount,
                'order_game_type' => $order->game_type,
                'server_key_configured' => !empty($this->serverKey),
                'client_key_configured' => !empty($this->clientKey),
                'is_production' => $this->isProduction,
            ]);
            
            // Map payment method to Midtrans payment type
            $paymentMethod = $order->payment_method ?? 'qris';
            $paymentType = $this->mapPaymentMethodToMidtransType($paymentMethod);
            
            // Log payment method mapping
            Log::info('Payment method mapped', [
                'order_id' => $order->order_id,
                'original_payment_method' => $paymentMethod,
                'mapped_payment_type' => $paymentType,
            ]);
            
            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $order->order_id,
                'gross_amount' => (int) $order->price,
            ];
            
            // Prepare customer details
            $customerDetails = [
                'first_name' => $order->username,
                'email' => $order->email,
                'phone' => '',
            ];
            
            // Prepare item details with more descriptive names
            $itemDetails = [];
            if ($order->game_type === 'Robux') {
                $itemDetails[] = [
                    'id' => 'ROBUX-' . $order->order_id,
                    'price' => (int) $order->price,
                    'quantity' => 1,
                    'name' => number_format($order->amount, 0, ',', '.') . ' Robux - ' . $order->username,
                    'brand' => 'Roblox',
                    'category' => 'Digital Currency',
                ];
            } else {
                $itemDetails[] = [
                    'id' => 'PRODUCT-' . $order->order_id,
                    'price' => (int) $order->price,
                    'quantity' => 1,
                    'name' => ($order->product_name ?? $order->game_type) . ' - ' . $order->username,
                    'brand' => $order->game_type,
                    'category' => 'Game Item',
                ];
            }
            
            // Prepare Snap parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];
            
            // Enable all available payment methods (let user choose in Midtrans UI)
            // CRITICAL: Midtrans v1.34.0+ changed how they handle GoPay/QRIS
            // - On mobile: GoPay may be shown as QRIS (payment_type becomes "qris")
            // - On PC: QRIS may be shown as GoPay (payment_type becomes "gopay")
            // Solution: Enable both and let Midtrans decide based on device
            // IMPORTANT: QRIS MUST be FIRST in array to ensure it appears on mobile devices
            // IMPORTANT: Both QRIS and GoPay must be enabled to show on all devices
            $enabledPayments = [
                'qris',           // QRIS (General - MUST be first for mobile display)
                'gopay',          // GoPay (E-Wallet - can show QRIS on mobile or app on PC)
                'dana',           // DANA (E-Wallet, not QRIS)
                'ovo',            // OVO (E-Wallet, not QRIS)
                'linkaja',        // LinkAja (E-Wallet, not QRIS)
                'shopeepay',      // ShopeePay (E-Wallet, not QRIS)
                'bca_va',         // BCA Virtual Account
                'mandiri_va',     // Mandiri Virtual Account
                'bni_va',         // BNI Virtual Account
                'permata_va',     // Permata Virtual Account
                'credit_card',    // Credit Card
            ];
            
            // Exclude e-wallet specific QRIS variants to avoid confusion
            // These would create options like "GoPay QRIS", "ShopeePay QRIS" which are confusing
            // We want only one clear "QRIS" option
            
            // CRITICAL: For Midtrans v1.34.0+ compatibility
            // Always keep both QRIS and GoPay enabled regardless of selected method
            // This ensures both appear on all devices (mobile and PC)
            // Midtrans will automatically show the appropriate option based on device
            if ($paymentType && in_array($paymentType, $enabledPayments)) {
                // Prioritize selected method by putting it first
                // BUT: Always keep both QRIS and GoPay enabled (don't remove them)
                if ($paymentType !== 'qris' && $paymentType !== 'gopay') {
                    // Remove selected method from current position
                    $enabledPayments = array_filter($enabledPayments, function($method) use ($paymentType) {
                        return $method !== $paymentType;
                    });
                    // Add selected method to beginning
                    array_unshift($enabledPayments, $paymentType);
                    // Ensure both QRIS and GoPay are in the list (after selected method)
                    $enabledPayments = array_values($enabledPayments); // Re-index array
                    // Ensure QRIS is in the list (if not already)
                    if (!in_array('qris', $enabledPayments)) {
                        array_splice($enabledPayments, 1, 0, 'qris');
                    }
                    // Ensure GoPay is in the list (if not already)
                    if (!in_array('gopay', $enabledPayments)) {
                        $qrisIndex = array_search('qris', $enabledPayments);
                        if ($qrisIndex !== false) {
                            array_splice($enabledPayments, $qrisIndex + 1, 0, 'gopay');
                        } else {
                            array_splice($enabledPayments, 1, 0, 'gopay');
                        }
                    }
                } elseif ($paymentType === 'qris') {
                    // If QRIS is selected, ensure it's first, but keep GoPay too
                    $enabledPayments = array_filter($enabledPayments, function($method) {
                        return $method !== 'qris';
                    });
                    array_unshift($enabledPayments, 'qris');
                    // Ensure GoPay is still in the list
                    if (!in_array('gopay', $enabledPayments)) {
                        array_splice($enabledPayments, 1, 0, 'gopay');
                    }
                    $enabledPayments = array_values($enabledPayments);
                } elseif ($paymentType === 'gopay') {
                    // If GoPay is selected, ensure it's first, but keep QRIS too
                    $enabledPayments = array_filter($enabledPayments, function($method) {
                        return $method !== 'gopay';
                    });
                    array_unshift($enabledPayments, 'gopay');
                    // Ensure QRIS is still in the list
                    if (!in_array('qris', $enabledPayments)) {
                        array_splice($enabledPayments, 1, 0, 'qris');
                    }
                    $enabledPayments = array_values($enabledPayments);
                }
            }
            
            // CRITICAL: Ensure QRIS is always first in enabled_payments array
            // This is important for mobile devices to show QRIS option
            // Re-sort to ensure QRIS is first, then GoPay, then others
            $qrisIndex = array_search('qris', $enabledPayments);
            $gopayIndex = array_search('gopay', $enabledPayments);
            
            if ($qrisIndex !== false && $qrisIndex !== 0) {
                // Move QRIS to first position
                unset($enabledPayments[$qrisIndex]);
                array_unshift($enabledPayments, 'qris');
                $enabledPayments = array_values($enabledPayments);
            }
            
            // Ensure GoPay is second (after QRIS) if it exists
            if ($gopayIndex !== false) {
                $currentGopayIndex = array_search('gopay', $enabledPayments);
                if ($currentGopayIndex !== false && $currentGopayIndex !== 1) {
                    unset($enabledPayments[$currentGopayIndex]);
                    array_splice($enabledPayments, 1, 0, 'gopay');
                    $enabledPayments = array_values($enabledPayments);
                }
            }
            
            $params['enabled_payments'] = $enabledPayments;
            
            // Set expiry time (10 minutes)
            $params['expiry'] = [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 10
            ];
            
            Log::info('Creating Midtrans Snap transaction', [
                'order_id' => $order->order_id,
                'payment_method' => $paymentMethod,
                'payment_type' => $paymentType,
                'enabled_payments' => $enabledPayments,
                'enabled_payments_count' => count($enabledPayments),
                'qris_position' => array_search('qris', $enabledPayments),
                'gopay_position' => array_search('gopay', $enabledPayments),
                'first_payment_method' => $enabledPayments[0] ?? 'none',
                'amount' => $order->price,
                'server_key_prefix' => substr($this->serverKey, 0, 10) . '...',
                'is_production' => $this->isProduction,
            ]);
            
            // Log full params before sending to Midtrans (for debugging)
            Log::info('Midtrans Snap API request params', [
                'order_id' => $order->order_id,
                'transaction_details' => $params['transaction_details'] ?? null,
                'customer_details' => [
                    'first_name' => $params['customer_details']['first_name'] ?? null,
                    'last_name' => $params['customer_details']['last_name'] ?? null,
                    'email' => $params['customer_details']['email'] ?? null,
                    'phone' => $params['customer_details']['phone'] ?? null,
                ],
                'item_details' => $params['item_details'] ?? null,
                'enabled_payments' => $params['enabled_payments'] ?? null,
                'enabled_payments_count' => count($params['enabled_payments'] ?? []),
                'expiry' => $params['expiry'] ?? null,
                'callbacks' => $params['callbacks'] ?? null,
                'params_keys' => array_keys($params),
            ]);
            
            // Create Snap transaction
            try {
                $snapToken = Snap::getSnapToken($params);
                
                // Log successful response details
                Log::info('Midtrans Snap API response received', [
                    'order_id' => $order->order_id,
                    'snap_token_length' => strlen($snapToken),
                    'snap_token_prefix' => substr($snapToken, 0, 30) . '...',
                    'response_received_at' => now()->toDateTimeString(),
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                
                // Check if it's an order_id collision error
                $isOrderIdCollision = (
                    strpos(strtolower($errorMessage), 'order_id') !== false && 
                    (strpos(strtolower($errorMessage), 'already') !== false ||
                     strpos(strtolower($errorMessage), 'taken') !== false)
                );
                
                Log::error('Failed to create Snap token', [
                    'order_id' => $order->order_id,
                    'error' => $errorMessage,
                    'is_order_id_collision' => $isOrderIdCollision,
                    'params' => [
                        'order_id' => $params['transaction_details']['order_id'],
                        'amount' => $params['transaction_details']['gross_amount'],
                        'enabled_payments_count' => count($params['enabled_payments']),
                    ],
                ]);
                
                // Re-throw exception with original message for proper error handling
                throw $e;
            }
            
            Log::info('Midtrans Snap token created successfully', [
                'order_id' => $order->order_id,
                'snap_token' => substr($snapToken, 0, 20) . '...',
                'snap_token_full_length' => strlen($snapToken),
                'enabled_payments' => $enabledPayments,
                'enabled_payments_count' => count($enabledPayments),
                'qris_position' => array_search('qris', $enabledPayments),
                'gopay_position' => array_search('gopay', $enabledPayments),
                'first_payment_method' => $enabledPayments[0] ?? 'none',
                'payment_method_selected' => $paymentMethod,
                'payment_type_mapped' => $paymentType,
                'environment' => $this->isProduction ? 'production' : 'sandbox',
                'created_at' => now()->toDateTimeString(),
            ]);
            
            return $snapToken;
            
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans Snap token', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Map payment method code to Midtrans payment type
     */
    private function mapPaymentMethodToMidtransType($paymentMethod)
    {
        $mapping = [
            'qris' => 'qris',
            'bca_va' => 'bca_va',
            'mandiri_va' => 'mandiri_va',
            'bni_va' => 'bni_va',
            'permata_va' => 'permata_va',
            'gopay' => 'gopay',
            'dana' => 'dana',
            'ovo' => 'ovo',
            'linkaja' => 'linkaja',
            'shopeepay' => 'shopeepay',
            'credit_card' => 'credit_card',
        ];
        
        return $mapping[$paymentMethod] ?? null;
    }
    
    /**
     * Verify Midtrans notification signature
     */
    public function verifyNotification($request)
    {
        try {
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $serverKey = $this->serverKey;
            
            // Create signature
            $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            
            // Verify signature
            if ($signatureKey !== $request->signature_key) {
                Log::warning('Midtrans signature verification failed', [
                    'order_id' => $orderId,
                    'expected' => $signatureKey,
                    'received' => $request->signature_key,
                ]);
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error verifying Midtrans notification', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Get client key for frontend Snap.js
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }
}

