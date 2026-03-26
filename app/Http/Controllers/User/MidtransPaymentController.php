<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\GoogleSheetsService;
use App\Services\RobuxStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransPaymentController extends Controller
{
    protected $midtransService;
    
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    
    /**
     * Show Midtrans Snap payment page
     */
    public function show($orderId)
    {
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan');
        }
        
        // Check if order is expired
        if ($order->expires_at && now()->gt($order->expires_at)) {
            $order->update(['order_status' => 'expired']);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan.');
        }
        
        // Check if payment mode is gateway
        $notes = [];
        if (is_array($order->notes)) {
            $notes = $order->notes;
        } elseif (is_string($order->notes) && !empty($order->notes)) {
            $decoded = @json_decode((string) $order->notes, true);
            if (is_array($decoded)) {
                $notes = $decoded;
            }
        }
        
        if (($notes['payment_mode'] ?? 'manual') !== 'gateway') {
            return redirect()->route('user.payment-methods')->with('error', 'Mode pembayaran tidak valid');
        }

        // Re-check Robux stock before starting payment (prevent race condition)
        if ($order->game_type === 'Robux' && $order->amount) {
            $purchaseMethod = $order->purchase_method ?? ($notes['purchase_method'] ?? 'gamepass');
            if ($purchaseMethod === 'group') {
                $availableStock = RobuxStockService::getCurrentGroupStock();
            } else {
                $availableStock = RobuxStockService::getCurrentStock();
            }
            if ($availableStock < (int) $order->amount) {
                $methodLabel = ($purchaseMethod === 'group') ? 'Group' : 'Gamepass';
                $errorMessage = 'Stok Robux (' . $methodLabel . ') tidak mencukupi untuk melanjutkan pembayaran. Silakan tunggu pengisian ulang.';
                Log::warning('Blocked Midtrans payment due to insufficient stock', [
                    'order_id' => $order->order_id,
                    'purchase_method' => $purchaseMethod,
                    'requested_amount' => (int) $order->amount,
                    'available_stock' => $availableStock,
                ]);
                return redirect()->route('user.payment-methods')->with('error', $errorMessage);
            }
        }
        
        try {
            // Log detailed info about order and payment page access
            Log::info('Midtrans payment page accessed', [
                'order_id' => $order->order_id,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'purchase_method' => $order->purchase_method,
                'game_type' => $order->game_type,
                'amount' => $order->amount,
                'price' => $order->price,
                'username' => $order->username,
                'email' => $order->email,
                'expires_at' => $order->expires_at?->toDateTimeString(),
                'is_expired' => $order->expires_at && now()->gt($order->expires_at),
                'payment_mode' => $notes['payment_mode'] ?? 'unknown',
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
                'referer' => request()->header('referer'),
            ]);
            
            // Create Snap token
            $snapToken = $this->midtransService->createSnapToken($order);
            $clientKey = $this->midtransService->getClientKey();
            
            // Log Snap token creation success
            $environment = \App\Models\Setting::getValue('midtrans_environment', 'sandbox');
            Log::info('Midtrans Snap token generated for payment page', [
                'order_id' => $order->order_id,
                'snap_token_length' => strlen($snapToken),
                'snap_token_prefix' => substr($snapToken, 0, 20) . '...',
                'client_key_length' => strlen($clientKey),
                'client_key_prefix' => substr($clientKey, 0, 10) . '...',
                'environment' => $environment,
            ]);
            
            // Store order ID in session
            session(['current_order_id' => $order->order_id]);
            
            return view('user.midtrans-payment', [
                'order' => $order,
                'snapToken' => $snapToken,
                'clientKey' => $clientKey,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to initialize Midtrans payment', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Check if it's an order_id collision error (order_id already taken)
            $errorMessage = $e->getMessage();
            $isOrderIdCollision = (
                strpos(strtolower($errorMessage), 'order_id') !== false && 
                strpos(strtolower($errorMessage), 'already') !== false
            ) || (
                strpos(strtolower($errorMessage), 'order_id') !== false && 
                strpos(strtolower($errorMessage), 'taken') !== false
            );
            
            if ($isOrderIdCollision) {
                // Order ID collision detected - generate new order_id and retry
                Log::warning('Order ID collision detected, generating new order_id', [
                    'old_order_id' => $order->order_id,
                ]);
                
                // Generate new unique order_id
                $newOrderId = $this->generateUniqueOrderId();
                
                // Update order with new order_id
                $order->update(['order_id' => $newOrderId]);
                
                // Update session
                session(['current_order_id' => $newOrderId]);
                
                Log::info('Order ID updated, retrying Midtrans payment', [
                    'old_order_id' => $orderId,
                    'new_order_id' => $newOrderId,
                ]);
                
                // Retry creating Snap token with new order_id
                try {
                    $snapToken = $this->midtransService->createSnapToken($order);
                    $clientKey = $this->midtransService->getClientKey();
                    
                    return view('user.midtrans-payment', [
                        'order' => $order,
                        'snapToken' => $snapToken,
                        'clientKey' => $clientKey,
                    ]);
                } catch (\Exception $retryException) {
                    Log::error('Failed to initialize Midtrans payment after order_id retry', [
                        'new_order_id' => $newOrderId,
                        'error' => $retryException->getMessage(),
                    ]);
                    
                    // Fall through to error handling below
                    $errorMessage = 'Gagal menginisialisasi pembayaran setelah retry. Silakan coba lagi.';
                }
            }
            
            // Check if it's a configuration error
            if (strpos($errorMessage, '401') !== false || strpos($errorMessage, 'unauthorized') !== false) {
                $errorMessage = 'Gagal menginisialisasi pembayaran. Pastikan Server Key dan Client Key sudah benar dan sesuai dengan Environment (Sandbox/Production).';
            } elseif (strpos($errorMessage, '400') !== false && !$isOrderIdCollision) {
                $errorMessage = 'Gagal menginisialisasi pembayaran. Pastikan payment methods sudah ter-enable di Midtrans Dashboard.';
            } elseif (!$isOrderIdCollision) {
                $errorMessage = 'Gagal menginisialisasi pembayaran. Silakan coba lagi atau hubungi admin.';
            }
            
            return redirect()->route('user.payment-methods')
                ->with('error', $errorMessage);
        }
    }
    
    /**
     * Handle Midtrans webhook notification
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('Midtrans webhook received', [
                'order_id' => $request->order_id,
                'transaction_status' => $request->transaction_status,
                'payment_type' => $request->payment_type,
            ]);
            
            // Verify signature
            if (!$this->midtransService->verifyNotification($request)) {
                Log::warning('Midtrans webhook signature verification failed', [
                    'order_id' => $request->order_id,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }
            
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? null;
            
            // CRITICAL: Use database transaction with lock to prevent race condition
            // This ensures only one webhook can process the order at a time
            // Multiple webhooks (capture + settlement) can arrive simultaneously, so we need locking
            $order = null;
            $wasAlreadyCompleted = false;
            $shouldAddToSpreadsheet = false;
            
            // Lock and check order status in transaction
            \DB::transaction(function () use ($request, &$order, &$wasAlreadyCompleted) {
                // Lock the order row for update to prevent concurrent processing
                $order = Order::lockForUpdate()->where('order_id', $request->order_id)->first();
                
                if (!$order) {
                    Log::warning('Order not found for Midtrans webhook', [
                        'order_id' => $request->order_id,
                    ]);
                    return;
                }
                
                // Check if order was already completed (before any updates)
                // This is critical to prevent duplicate spreadsheet entries
                $wasAlreadyCompleted = $order->payment_status === 'Completed';
            });
            
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            
            // CRITICAL: Only process if order was NOT already completed
            // This prevents duplicate entries when Midtrans sends multiple webhooks (capture + settlement)
            $shouldAddToSpreadsheet = false;
            
            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                // Credit card payment
                if ($fraudStatus == 'challenge') {
                    // Transaction is flagged, need manual review
                    $order->update([
                        'payment_status' => 'challenge',
                        'payment_reference' => $request->transaction_id,
                    ]);
                } else if ($fraudStatus == 'accept') {
                    // Transaction is safe, mark as paid and completed
                    // Use 'Completed' for payment_status to match admin orders query
                    $order->update([
                        'payment_status' => 'Completed',
                        'order_status' => 'pending',
                        'payment_gateway' => 'midtrans', // Mark as Midtrans payment
                        'payment_reference' => $request->transaction_id,
                        'completed_at' => now(),
                    ]);

                    // Deduct stock once when payment becomes completed
                    if ($order->game_type === 'Robux' && $order->amount && !$wasAlreadyCompleted) {
                        $purchaseMethod = $order->purchase_method ?? 'gamepass';
                        $notes = [];
                        if (is_array($order->notes)) {
                            $notes = $order->notes;
                        } elseif (is_string($order->notes) && !empty($order->notes)) {
                            $decoded = @json_decode((string) $order->notes, true);
                            if (is_array($decoded)) {
                                $notes = $decoded;
                            }
                        }

                        if (empty($notes['stock_deducted_at'])) {
                            if (!RobuxStockService::reduceStock((int) $order->amount, $purchaseMethod)) {
                                Log::error('Midtrans payment completed but stock deduction failed - marking order as failed', [
                                    'order_id' => $order->order_id,
                                    'purchase_method' => $purchaseMethod,
                                    'amount' => (int) $order->amount,
                                ]);
                                $order->update([
                                    'payment_status' => 'failed',
                                    'order_status' => null,
                                ]);
                            } else {
                                $notes['stock_deducted_at'] = now()->toISOString();
                                $notes['stock_deducted_by'] = 'midtrans_webhook';
                                $notes['stock_deducted_method'] = $purchaseMethod;
                                $order->update(['notes' => json_encode($notes)]);
                            }
                        }
                    }
                    
                    // Refresh order to get latest data before sending to spreadsheet
                    $order->refresh();
                    
                    // Send payment confirmed email (only if not already sent)
                    if (!$wasAlreadyCompleted) {
                        $this->sendPaymentConfirmedEmail($order);
                    }
                    
                    // Only add to spreadsheet if order was NOT already completed
                    // This prevents duplicate entries when webhook is called multiple times
                    $shouldAddToSpreadsheet = !$wasAlreadyCompleted;
                }
            } else if ($transactionStatus == 'settlement') {
                // Payment is settled (for bank transfer, e-wallet)
                // Use 'Completed' for payment_status to match admin orders query
                $order->update([
                    'payment_status' => 'Completed',
                    'order_status' => 'pending',
                    'payment_gateway' => 'midtrans', // Mark as Midtrans payment
                    'payment_reference' => $request->transaction_id,
                    'completed_at' => now(),
                ]);

                // Deduct stock once when payment becomes completed
                if ($order->game_type === 'Robux' && $order->amount && !$wasAlreadyCompleted) {
                    $purchaseMethod = $order->purchase_method ?? 'gamepass';
                    $notes = [];
                    if (is_array($order->notes)) {
                        $notes = $order->notes;
                    } elseif (is_string($order->notes) && !empty($order->notes)) {
                        $decoded = @json_decode((string) $order->notes, true);
                        if (is_array($decoded)) {
                            $notes = $decoded;
                        }
                    }

                    if (empty($notes['stock_deducted_at'])) {
                        if (!RobuxStockService::reduceStock((int) $order->amount, $purchaseMethod)) {
                            Log::error('Midtrans payment completed but stock deduction failed - marking order as failed', [
                                'order_id' => $order->order_id,
                                'purchase_method' => $purchaseMethod,
                                'amount' => (int) $order->amount,
                            ]);
                            $order->update([
                                'payment_status' => 'failed',
                                'order_status' => null,
                            ]);
                        } else {
                            $notes['stock_deducted_at'] = now()->toISOString();
                            $notes['stock_deducted_by'] = 'midtrans_webhook';
                            $notes['stock_deducted_method'] = $purchaseMethod;
                            $order->update(['notes' => json_encode($notes)]);
                        }
                    }
                }
                
                // Refresh order to get latest data before sending to spreadsheet
                $order->refresh();
                
                // Send payment confirmed email (only if not already sent)
                if (!$wasAlreadyCompleted) {
                    $this->sendPaymentConfirmedEmail($order);
                }
                
                // Only add to spreadsheet if order was NOT already completed
                // This prevents duplicate entries when webhook is called multiple times
                $shouldAddToSpreadsheet = !$wasAlreadyCompleted;
            }
            
            // Add to spreadsheet only if order was just completed (not already completed)
            // This prevents duplicate entries when Midtrans sends multiple webhooks
            if ($shouldAddToSpreadsheet) {
                // Add to spreadsheet with delay to prevent concurrent request issues
                // For Midtrans webhook: use longer delay range (200ms-2000ms) and timestamp-based offset
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

                        Log::info('Midtrans order added to spreadsheet successfully', [
                            'order_id' => $order->order_id,
                            'delay_ms' => round($totalDelay / 1000, 2),
                            'transaction_status' => $transactionStatus,
                        ]);
                    } else {
                        $order->forceFill([
                            'spreadsheet_last_error' => 'GoogleSheetsService returned false',
                        ])->save();

                        Log::warning('Midtrans order spreadsheet add returned false', [
                            'order_id' => $order->order_id,
                            'transaction_status' => $transactionStatus,
                        ]);
                    }
                } catch (\Exception $e) {
                    try {
                        $order->forceFill([
                            'spreadsheet_last_error' => $e->getMessage(),
                        ])->save();
                    } catch (\Exception $ignored) {
                        // ignore
                    }

                    // Log error but don't fail the webhook processing
                    // GoogleSheetsService already has retry mechanism, so this is a last resort
                    Log::error('Failed to add Midtrans order to spreadsheet (exception)', [
                        'order_id' => $order->order_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'transaction_status' => $transactionStatus,
                    ]);
                }
            } else if ($wasAlreadyCompleted && ($transactionStatus == 'capture' || $transactionStatus == 'settlement')) {
                // Log that we skipped adding to spreadsheet because order was already completed
                Log::info('Midtrans webhook received for already completed order - skipping spreadsheet add', [
                    'order_id' => $order->order_id,
                    'transaction_status' => $transactionStatus,
                    'previous_payment_status' => $wasAlreadyCompleted ? 'Completed' : $order->payment_status,
                ]);
            } else if ($transactionStatus == 'pending') {
                // Payment is pending (waiting for customer to pay)
                $order->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $request->transaction_id,
                ]);
            } else if ($transactionStatus == 'deny' || 
                       $transactionStatus == 'expire' || 
                       $transactionStatus == 'cancel') {
                // Payment failed/cancelled
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $request->transaction_id,
                ]);
            }
            
            Log::info('Midtrans webhook processed successfully', [
                'order_id' => $order->order_id,
                'transaction_status' => $transactionStatus,
                'payment_status' => $order->payment_status,
            ]);
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Error processing Midtrans webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Generate unique 8-character order ID for Midtrans (retry mechanism)
     */
    private function generateUniqueOrderId()
    {
        $maxRetries = 20;
        $retryCount = 0;
        
        do {
            // Convert timestamp to base36 (shorter representation)
            $timestamp = time();
            $timestampMod = $timestamp % (36 * 36 * 36 * 36 * 36); // Mod to fit in 5 base36 digits
            $base36 = strtoupper(base_convert($timestampMod, 10, 36)); // Convert to base36
            $base36 = str_pad($base36, 5, '0', STR_PAD_LEFT); // Pad to 5 chars
            
            // Generate 3 random characters
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
            
            // Combine: 5 chars (base36 timestamp) + 3 chars (random) = 8 chars total
            $orderId = $base36 . $random;
            
            // Check if ID already exists in database
            $existsInDb = Order::where('order_id', $orderId)->exists();
            
            if (!$existsInDb) {
                return $orderId;
            }
            
            $retryCount++;
            
            // If collision, use microsecond component for extra randomness
            if ($retryCount > 0) {
                $micros = substr(str_replace('.', '', microtime(true)), -2); // Last 2 digits
                $microsBase36 = strtoupper(base_convert(intval($micros), 10, 36));
                $microsBase36 = str_pad($microsBase36, 2, '0', STR_PAD_LEFT);
                
                // Use 4 chars timestamp + 2 chars microsecond + 2 chars random = 8 chars
                $timestampMod = $timestamp % (36 * 36 * 36 * 36); // Mod for 4 chars
                $base36 = strtoupper(base_convert($timestampMod, 10, 36));
                $base36 = str_pad($base36, 4, '0', STR_PAD_LEFT);
                $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
                $orderId = $base36 . $microsBase36 . $random;
                
                // Check again
                $existsInDb = Order::where('order_id', $orderId)->exists();
                if (!$existsInDb) {
                    return $orderId;
                }
            }
            
            // Small delay to get different timestamp/microsecond
            usleep(500); // 0.5ms delay
            
        } while ($retryCount < $maxRetries);
        
        // Fallback: use fresh timestamp with more randomness
        $timestamp = time();
        $timestampMod = $timestamp % (36 * 36 * 36 * 36 * 36);
        $base36 = strtoupper(base_convert($timestampMod, 10, 36));
        $base36 = str_pad($base36, 5, '0', STR_PAD_LEFT);
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
        return $base36 . $random;
    }
    
    /**
     * Send payment confirmed email
     */
    private function sendPaymentConfirmedEmail(Order $order)
    {
        try {
            // Apply email config
            $this->applyEmailConfig();
            
            Mail::to($order->email)->send(new \App\Mail\PaymentConfirmedNotification($order));
            
            Log::info('Payment confirmed email sent', [
                'order_id' => $order->order_id,
                'email' => $order->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmed email', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Apply email config from database
     */
    private function applyEmailConfig()
    {
        try {
            $mailer = \App\Models\Setting::getValue('mail_mailer', 'log');
            $host = \App\Models\Setting::getValue('mail_host', '');
            $port = \App\Models\Setting::getValue('mail_port', '587');
            $username = \App\Models\Setting::getValue('mail_username', '');
            $password = \App\Models\Setting::getValue('mail_password', '');
            $encryption = \App\Models\Setting::getValue('mail_encryption', 'tls');
            $fromAddress = \App\Models\Setting::getValue('mail_from_address', 'hello@example.com');
            $fromName = \App\Models\Setting::getValue('mail_from_name', 'Valtus');
            
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
                'mail.mailers.smtp.timeout' => 60,
                'mail.from.address' => $fromAddress ?: 'hello@example.com',
                'mail.from.name' => $fromName ?: 'Valtus',
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to apply email config: ' . $e->getMessage());
        }
    }
}

