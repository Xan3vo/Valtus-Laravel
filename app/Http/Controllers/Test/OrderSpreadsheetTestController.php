<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderSpreadsheetTestController extends Controller
{
    public function index(string $hash)
    {
        if ($hash !== '1102230') {
            abort(404);
        }

        $spreadsheetEnabled = Setting::getValue('spreadsheet_enabled', '0') === '1';
        $spreadsheetUrl = Setting::getValue('spreadsheet_url', '');
        $scriptUrl = Setting::getValue('spreadsheet_script_url', '');

        $robuxPricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
        $groupRobuxPricePer100 = (float) Setting::getValue('group_robux_price_per_100', '10000');

        return view('test.orderan', compact(
            'spreadsheetEnabled',
            'spreadsheetUrl',
            'scriptUrl',
            'robuxPricePer100',
            'groupRobuxPricePer100',
        ));
    }

    public function createAndSend(Request $request, string $hash)
    {
        if ($hash !== '1102230') {
            abort(404);
        }

        $request->validate([
            'purchase_method' => 'required|in:gamepass,group',
            'amount' => 'required|integer|min:1',
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $purchaseMethod = (string) $request->input('purchase_method');
        $amount = (int) $request->input('amount');

        $username = trim((string) ($request->input('username') ?? 'testuser'));
        if ($username === '') {
            $username = 'testuser';
        }

        $email = trim((string) ($request->input('email') ?? 'test@example.com'));
        if ($email === '') {
            $email = 'test@example.com';
        }

        $pricePer100 = $purchaseMethod === 'group'
            ? (float) Setting::getValue('group_robux_price_per_100', '10000')
            : (float) Setting::getValue('robux_price_per_100', '10000');

        $price = $pricePer100 * ($amount / 100);

        $order = Order::create([
            'order_id' => $this->generateOrderId(),
            'username' => $username,
            'email' => $email,
            'game_type' => 'Robux',
            'amount' => $amount,
            'price' => $price,
            'tax' => 0,
            'total_amount' => $price,
            'payment_status' => 'Completed',
            'order_status' => 'pending',
            'payment_method' => 'Qris',
            'purchase_method' => $purchaseMethod,
            'payment_gateway' => 'midtrans',
            'payment_reference' => 'test-' . time() . '-' . random_int(1000, 9999),
            'gamepass_link' => $purchaseMethod === 'gamepass' ? 'https://www.roblox.com/game-pass/000000' : null,
            'notes' => json_encode([
                'test_order' => true,
                'created_via' => 'order_spreadsheet_test',
                'purchase_method' => $purchaseMethod,
            ]),
            'completed_at' => now(),
        ]);

        $order->refresh();

        $result = false;
        $error = null;

        try {
            $result = GoogleSheetsService::addOrderToSpreadsheet($order);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('Order spreadsheet test failed (exception)', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'ok' => true,
            'sent' => (bool) $result,
            'error' => $error,
            'order' => [
                'order_id' => $order->order_id,
                'purchase_method' => $order->purchase_method,
                'amount' => (int) ($order->amount ?? 0),
                'total_amount' => (float) ($order->total_amount ?? 0),
                'username' => $order->username,
                'email' => $order->email,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'created_at' => optional($order->created_at)->toISOString(),
            ],
        ]);
    }

    private function generateOrderId(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 8;

        do {
            $orderId = '';
            for ($i = 0; $i < $length; $i++) {
                $orderId .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (Order::where('order_id', $orderId)->exists());

        return $orderId;
    }
}
