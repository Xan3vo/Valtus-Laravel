<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\RetrySpreadsheetOrders;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('spreadsheet:retry-pending {--limit=50} {--max-attempts=20} {--cooldown=5}', function () {
    $this->call(RetrySpreadsheetOrders::class, [
        '--limit' => $this->option('limit'),
        '--max-attempts' => $this->option('max-attempts'),
        '--cooldown' => $this->option('cooldown'),
    ]);
})->describe('Retry sending completed orders to Google Sheets that have not been sent yet.');
