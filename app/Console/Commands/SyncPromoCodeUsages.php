<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PromoCodeUsage;
use Illuminate\Console\Command;

class SyncPromoCodeUsages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo-codes:sync-usages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync promo code usages based on order payment_status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing promo code usages...');
        
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        
        // Get all orders with promo codes
        $orders = Order::whereNotNull('notes')
            ->get()
            ->filter(function($order) {
                $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes ?? '{}', true);
                return isset($notes['promo_code']['promo_code_id']);
            });
        
        $this->info("Found {$orders->count()} orders with promo codes.");
        
        foreach ($orders as $order) {
            try {
                $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes ?? '{}', true);
                $promoCodeId = $notes['promo_code']['promo_code_id'] ?? null;
                
                if (!$promoCodeId) {
                    $skipped++;
                    continue;
                }
                
                // Find promo code usage for this order
                $usage = PromoCodeUsage::where('order_id', $order->order_id)
                    ->where('promo_code_id', $promoCodeId)
                    ->first();
                
                if (!$usage) {
                    $skipped++;
                    continue;
                }
                
                // Determine status based on payment_status
                $paymentStatus = $order->payment_status;
                $shouldBePaid = ($paymentStatus === 'Completed');
                $usagePaymentStatus = $paymentStatus === 'Completed' ? 'Completed' : 
                                    ($paymentStatus === 'Failed' || $paymentStatus === 'Cancelled' ? 'Failed' : 'Pending');
                
                // Check if update is needed
                if ($usage->is_paid !== $shouldBePaid || $usage->payment_status !== $usagePaymentStatus) {
                    $usage->update([
                        'payment_status' => $usagePaymentStatus,
                        'is_paid' => $shouldBePaid,
                    ]);
                    $updated++;
                    $this->line("Updated: Order {$order->order_id} - Status: {$usagePaymentStatus}, Paid: " . ($shouldBePaid ? 'Yes' : 'No'));
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error processing order {$order->order_id}: " . $e->getMessage());
            }
        }
        
        $this->info("Sync completed!");
        $this->info("Updated: {$updated}");
        $this->info("Skipped (already synced): {$skipped}");
        $this->info("Errors: {$errors}");
        
        return Command::SUCCESS;
    }
}
