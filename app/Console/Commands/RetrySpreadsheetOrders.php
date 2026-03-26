<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RetrySpreadsheetOrders extends Command
{
    protected $signature = 'spreadsheet:retry-pending 
        {--limit=50 : Max orders to process per run}
        {--max-attempts=20 : Stop retrying after this many attempts}
        {--cooldown=5 : Minimum minutes between attempts per order}';

    protected $description = 'Retry sending completed orders to Google Spreadsheet if they have not been sent yet';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $maxAttempts = (int) $this->option('max-attempts');
        $cooldownMinutes = (int) $this->option('cooldown');

        $query = Order::query()
            ->where('payment_status', 'Completed')
            ->whereNull('spreadsheet_sent_at')
            ->where('spreadsheet_attempts', '<', $maxAttempts)
            ->where(function ($q) use ($cooldownMinutes) {
                $q->whereNull('spreadsheet_last_attempt_at')
                    ->orWhere('spreadsheet_last_attempt_at', '<=', now()->subMinutes($cooldownMinutes));
            })
            ->orderBy('created_at', 'asc')
            ->limit($limit);

        $orders = $query->get();

        $this->info('Found ' . $orders->count() . ' orders to retry.');

        $ok = 0;
        $fail = 0;

        foreach ($orders as $order) {
            $order->forceFill([
                'spreadsheet_attempts' => (int) ($order->spreadsheet_attempts ?? 0) + 1,
                'spreadsheet_last_attempt_at' => now(),
            ])->save();

            try {
                $result = GoogleSheetsService::addOrderToSpreadsheet($order);

                if ($result) {
                    $order->forceFill([
                        'spreadsheet_sent_at' => now(),
                        'spreadsheet_last_error' => null,
                    ])->save();

                    $ok++;
                    $this->line('OK  ' . $order->order_id);
                } else {
                    $order->forceFill([
                        'spreadsheet_last_error' => 'GoogleSheetsService returned false',
                    ])->save();

                    $fail++;
                    $this->warn('FAIL ' . $order->order_id . ' (returned false)');
                }
            } catch (\Exception $e) {
                $order->forceFill([
                    'spreadsheet_last_error' => $e->getMessage(),
                ])->save();

                Log::error('spreadsheet.retry.exception', [
                    'order_id' => $order->order_id,
                    'error' => $e->getMessage(),
                ]);

                $fail++;
                $this->warn('FAIL ' . $order->order_id . ' (exception)');
            }
        }

        $this->info("Done. ok={$ok} fail={$fail}");

        return Command::SUCCESS;
    }
}
