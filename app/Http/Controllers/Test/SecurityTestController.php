<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecurityTestController extends Controller
{
    public function showTestPage($hash)
    {
        // Verify hash
        if ($hash !== '2879165') {
            abort(404);
        }

        $robuxPricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
        $robuxMinOrder = (int) Setting::getValue('robux_min_order', '100');
        $groupRobuxPricePer100 = (float) Setting::getValue('group_robux_price_per_100', '10000');
        $groupName = Setting::getValue('group_name', 'Valtus Studios');
        $minMembershipDays = (int) Setting::getValue('min_membership_days', '14');

        return view('test.security-test', [
            'robuxPricePer100' => $robuxPricePer100,
            'robuxMinOrder' => $robuxMinOrder,
            'groupRobuxPricePer100' => $groupRobuxPricePer100,
            'groupName' => $groupName,
            'minMembershipDays' => $minMembershipDays,
        ]);
    }

    public function checkUsername(Request $request, $hash)
    {
        // Verify hash
        if ($hash !== '2879165') {
            abort(404);
        }

        // Support both query parameter and request body
        $username = trim((string) ($request->query('username') ?? $request->input('username', '')));
        if ($username === '') {
            Log::warning('test.username.empty');
            return response()->json(['ok' => false, 'error' => 'EMPTY_USERNAME'], 400);
        }

        // Retry mechanism for username check (same as RobloxController)
        $postUrl = 'https://users.roblox.com/v1/usernames/users';
        $data = null;
        $maxRetries = 3;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $resp = \Illuminate\Support\Facades\Http::timeout(3)->post($postUrl, [
                    'usernames' => [$username],
                    'excludeBannedUsers' => true,
                ]);
                
                if ($resp->successful()) {
                    $json = $resp->json();
                    $data = $json['data'][0] ?? null;
                    Log::info('test.username.post', ['username' => $username, 'ok' => true, 'found' => (bool)$data, 'attempt' => $attempt]);
                    break; // Success, exit retry loop
                } else {
                    Log::warning('test.username.post_failed', ['username' => $username, 'status' => $resp->status(), 'attempt' => $attempt]);
                    if ($attempt < $maxRetries) {
                        usleep(500000); // Wait 500ms before retry
                        continue;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('test.username.exception', ['username' => $username, 'error' => $e->getMessage(), 'attempt' => $attempt]);
                if ($attempt < $maxRetries) {
                    usleep(500000); // Wait 500ms before retry
                    continue;
                }
            }
        }

        if (!$data) {
            Log::info('test.username.not_found', ['username' => $username]);
            return response()->json(['ok' => false, 'found' => false]);
        }

        $userId = $data['id'] ?? null;
        $name = $data['name'] ?? $username;
        $display = $data['displayName'] ?? $name;

        // Avatar headshot thumbnail (optimized timeout) - same as RobloxController
        $avatarUrl = null;
        if ($userId) {
            $thumb = \Illuminate\Support\Facades\Http::timeout(3)->get('https://thumbnails.roblox.com/v1/users/avatar-headshot', [
                'userIds' => $userId,
                'size' => '150x150',
                'format' => 'Png',
                'isCircular' => 'false',
            ]);
            if ($thumb->successful()) {
                $tj = $thumb->json();
                $avatarUrl = $tj['data'][0]['imageUrl'] ?? null;
                Log::info('test.avatar.ok', ['userId' => $userId, 'hasAvatar' => (bool)$avatarUrl]);
            } else {
                Log::warning('test.avatar.failed', ['userId' => $userId, 'status' => $thumb->status()]);
            }
        }

        return response()->json([
            'ok' => true,
            'found' => true,
            'id' => $userId,
            'name' => $name,
            'displayName' => $display,
            'avatar' => $avatarUrl,
        ]);
    }

    public function checkGamepass(Request $request, $hash)
    {
        // Verify hash
        if ($hash !== '2879165') {
            abort(404);
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
        ]);

        $username = trim($request->username);
        $amount = (int) $request->amount;

        try {
            // First get user ID with retry
            $userId = null;
            $maxRetries = 3;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $userResponse = \Illuminate\Support\Facades\Http::timeout(5)->post('https://users.roblox.com/v1/usernames/users', [
                        'usernames' => [$username],
                        'excludeBannedUsers' => true,
                    ]);

                    if ($userResponse->successful()) {
                        $userData = $userResponse->json();
                        $userInfo = $userData['data'][0] ?? null;
                        if ($userInfo && isset($userInfo['id'])) {
                            $userId = $userInfo['id'];
                            break;
                        }
                    }
                    
                    if ($attempt < $maxRetries) {
                        usleep(500000); // Wait 500ms before retry
                    }
                } catch (\Exception $e) {
                    Log::warning('Test gamepass: Failed to get user ID', ['username' => $username, 'attempt' => $attempt, 'error' => $e->getMessage()]);
                    if ($attempt < $maxRetries) {
                        usleep(500000);
                    }
                }
            }

            if (!$userId) {
                return response()->json([
                    'ok' => false,
                    'found' => false,
                    'error' => 'User not found',
                ]);
            }
            
            // Calculate required price (Roblox takes 30% tax)
            $taxRate = (float) Setting::getValue('gamepass_tax_rate', '30');
            $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
            $requiredPrice = (int) ceil($amount * (100 / (100 - $taxRate)));

            $found = null;
            $allGamepasses = [];
            
            // Method 1: Try primary API endpoint (same as RobloxController)
            try {
                $gamepassUrl = "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes";
                $inv = \Illuminate\Support\Facades\Http::timeout(10)->get($gamepassUrl, ['count' => 100]);
                
                if ($inv->successful()) {
                    $response = $inv->json();
                    $items = $response['gamePasses'] ?? $response['data'] ?? [];
                    Log::info('test.gamepasses.scan', ['userId' => $userId, 'count' => count($items), 'requiredPrice' => $requiredPrice]);
                    
                    if (!empty($items)) {
                        foreach ($items as $gamepass) {
                            $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? null;
                            $price = (int)($gamepass['price'] ?? 0);
                            $isForSale = (bool)($gamepass['isForSale'] ?? false);
                            $creator = $gamepass['creator']['creatorId'] ?? null;
                            $creatorType = $gamepass['creator']['creatorType'] ?? null;
                            $gamepassName = $gamepass['name'] ?? 'Unknown';
                            
                            // Store all gamepasses for display
                            $allGamepasses[] = [
                                'id' => $assetId,
                                'name' => $gamepassName,
                                'price' => $price,
                                'isForSale' => $isForSale,
                                'link' => $assetId ? "https://www.roblox.com/game-pass/{$assetId}" : null,
                            ];
                            
                            // Match if: correct price, for sale, and owned by user
                            $isOwnedByUser = ($creator === $userId) || 
                                           ($creatorType === 'User' && $creator === $userId) ||
                                           ($creatorType === 'Group' && $creator === $userId);
                            
                            if ($price === $requiredPrice && $isForSale && $isOwnedByUser && !$found) {
                                $found = [
                                    'assetId' => $assetId,
                                    'name' => $gamepassName,
                                    'price' => $price,
                                    'link' => "https://www.roblox.com/game-pass/{$assetId}",
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('test.gamepasses.exception', ['userId' => $userId, 'error' => $e->getMessage()]);
            }

            return response()->json([
                'ok' => true,
                'found' => (bool)$found,
                'gamepass_link' => $found['link'] ?? null,
                'gamepass_name' => $found['name'] ?? null,
                'required_price' => $requiredPrice,
                'all_gamepasses' => $allGamepasses, // Show all gamepasses for testing
            ]);
        } catch (\Exception $e) {
            Log::error('Gamepass check error', [
                'username' => $username,
                'amount' => $amount,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'ok' => false,
                'error' => 'Failed to check gamepass: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkGroupMembership(Request $request, $hash)
    {
        // Verify hash
        if ($hash !== '2879165') {
            abort(404);
        }

        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $username = trim($request->username);

        try {
            // Get user ID
            $userId = null;
            $maxRetries = 3;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $userResponse = \Illuminate\Support\Facades\Http::timeout(5)->post('https://users.roblox.com/v1/usernames/users', [
                        'usernames' => [$username],
                        'excludeBannedUsers' => true,
                    ]);

                    if ($userResponse->successful()) {
                        $userData = $userResponse->json();
                        $userInfo = $userData['data'][0] ?? null;
                        if ($userInfo && isset($userInfo['id'])) {
                            $userId = $userInfo['id'];
                            break;
                        }
                    }
                    
                    if ($attempt < $maxRetries) {
                        usleep(500000);
                    }
                } catch (\Exception $e) {
                    Log::warning('Test group: Failed to get user ID', ['username' => $username, 'attempt' => $attempt, 'error' => $e->getMessage()]);
                    if ($attempt < $maxRetries) {
                        usleep(500000);
                    }
                }
            }

            if (!$userId) {
                return response()->json([
                    'ok' => false,
                    'is_member' => false,
                    'error' => 'User not found',
                ]);
            }

            // Get group ID from settings
            $groupId = (int) Setting::getValue('group_id', '35148970');
            $groupName = Setting::getValue('group_name', 'Valtus Studios');
            $minMembershipDays = (int) Setting::getValue('min_membership_days', '14');
            
            // Check group membership
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get("https://groups.roblox.com/v1/users/{$userId}/groups/roles");
            
            if (!$response->successful()) {
                return response()->json([
                    'ok' => false,
                    'is_member' => false,
                    'error' => 'Failed to check group membership',
                ]);
            }

            $data = $response->json();
            $groups = $data['data'] ?? [];
            
            $isMember = false;
            $membershipDays = 0;
            $joinedDate = null;
            
            foreach ($groups as $group) {
                if ($group['group']['id'] == $groupId) {
                    $isMember = true;
                    $joinedDate = $group['role']['created'] ?? null;
                    
                    if ($joinedDate) {
                        $joined = new \DateTime($joinedDate);
                        $now = new \DateTime();
                        $membershipDays = $now->diff($joined)->days;
                    }
                    break;
                }
            }

            return response()->json([
                'ok' => true,
                'is_member' => $isMember,
                'membership_days' => $membershipDays,
                'joined_date' => $joinedDate,
                'group_name' => $groupName,
                'min_membership_days' => $minMembershipDays,
                'group_id' => $groupId,
            ]);
        } catch (\Exception $e) {
            Log::error('Group membership check error', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'ok' => false,
                'is_member' => false,
                'error' => 'Failed to check group membership: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createTestOrder(Request $request, $hash)
    {
        // Verify hash
        if ($hash !== '2879165') {
            abort(404);
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|integer|min:1',
            'gamepass_link' => 'nullable|string',
            'purchase_method' => 'nullable|string|in:gamepass,group',
        ]);

        try {
            // Get purchase method (default to gamepass)
            $purchaseMethod = $request->purchase_method ?? 'gamepass';
            
            // Get price per 100 Robux from settings based on method
            $amount = (int) $request->amount;
            $pricePer100 = $purchaseMethod === 'group' 
                ? (float) Setting::getValue('group_robux_price_per_100', '10000')
                : (float) Setting::getValue('robux_price_per_100', '10000');
            
            // Calculate price: same formula as normal system (pricePer100 * (amount / 100))
            $price = $pricePer100 * ($amount / 100);
            $totalAmount = $price;
            
            Log::info('Test order price calculation', [
                'purchase_method' => $purchaseMethod,
                'price_per_100' => $pricePer100,
                'amount' => $amount,
                'calculated_price' => $price,
                'formula' => "{$pricePer100} * ({$amount} / 100) = {$price}",
            ]);

            // Generate unique order ID
            $orderId = $this->generateOrderId();

            // Create order with payment_status = 'Completed', payment_gateway = 'midtrans', order_status = 'pending'
            $order = Order::create([
                'order_id' => $orderId,
                'username' => trim($request->username),
                'email' => trim($request->email),
                'game_type' => 'Robux',
                'amount' => $amount,
                'price' => $price,
                'tax' => 0,
                'total_amount' => $totalAmount,
                'payment_status' => 'Completed', // Directly completed for testing
                'order_status' => 'pending',
                'payment_method' => 'Qris',
                'purchase_method' => $purchaseMethod,
                'payment_gateway' => 'midtrans', // Mark as Midtrans
                'payment_reference' => 'qris-' . time(),
                'gamepass_link' => ($purchaseMethod === 'gamepass') ? ($request->gamepass_link ?? null) : null,
                'notes' => json_encode([
                    'payment_mode' => 'gateway',
                    'selected_method' => 'midtrans',
                    'purchase_method' => $purchaseMethod,
                    'test_order' => true,
                    'created_via' => 'security_test_page',
                ]),
                'completed_at' => now(),
            ]);

            // Add to spreadsheet with delay
            $baseDelay = rand(200000, 2000000);
            $timestampOffset = (intval(substr($order->order_id, -1)) % 10) * 100000;
            $totalDelay = $baseDelay + $timestampOffset;
            usleep($totalDelay);

            try {
                $result = GoogleSheetsService::addOrderToSpreadsheet($order);
                if ($result) {
                    Log::info('Test order added to spreadsheet successfully', [
                        'order_id' => $order->order_id,
                        'delay_ms' => round($totalDelay / 1000, 2),
                    ]);
                } else {
                    Log::warning('Test order spreadsheet add returned false', [
                        'order_id' => $order->order_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to add test order to spreadsheet', [
                    'order_id' => $order->order_id,
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'order_id' => $order->order_id,
                'order' => [
                    'id' => $order->order_id,
                    'username' => $order->username,
                    'amount' => $order->amount,
                    'total_amount' => $order->total_amount,
                    'payment_status' => $order->payment_status,
                    'order_status' => $order->order_status,
                    'payment_gateway' => $order->payment_gateway,
                    'purchase_method' => $order->purchase_method,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Test order creation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create order: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function generateOrderId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $orderId = '';
        
        $length = rand(6, 7);
        for ($i = 0; $i < $length; $i++) {
            $orderId .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        while (Order::where('order_id', $orderId)->exists()) {
            $orderId = '';
            for ($i = 0; $i < $length; $i++) {
                $orderId .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        
        return $orderId;
    }
}

