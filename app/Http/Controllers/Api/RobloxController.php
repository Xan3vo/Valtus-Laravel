<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RobloxController extends Controller
{
    // GET /api/roblox/username?username=foo
    public function checkUsername(Request $request)
    {
        $username = trim((string) $request->query('username', ''));
        if ($username === '') {
            Log::warning('roblox.username.empty');
            return response()->json(['ok' => false, 'error' => 'EMPTY_USERNAME'], 400);
        }

        // Langsung gunakan POST (lebih reliable dan cepat)
        $postUrl = 'https://users.roblox.com/v1/usernames/users';
        $resp = Http::timeout(3)->post($postUrl, [
            'usernames' => [$username],
            'excludeBannedUsers' => true,
        ]);
        $data = null;
        if ($resp->successful()) {
            $json = $resp->json();
            $data = $json['data'][0] ?? null;
            Log::info('roblox.username.post', ['username' => $username, 'ok' => true, 'found' => (bool)$data]);
        } else {
            Log::warning('roblox.username.post_failed', ['username' => $username, 'status' => $resp->status()]);
        }

        if (!$data) {
            Log::info('roblox.username.not_found', ['username' => $username]);
            return response()->json(['ok' => false, 'found' => false]);
        }

        $userId = $data['id'] ?? null;
        $name = $data['name'] ?? $username;
        $display = $data['displayName'] ?? $name;

        // Avatar headshot thumbnail (optimized timeout)
        $avatarUrl = null;
        if ($userId) {
            $thumb = Http::timeout(3)->get('https://thumbnails.roblox.com/v1/users/avatar-headshot', [
                'userIds' => $userId,
                'size' => '150x150',
                'format' => 'Png',
                'isCircular' => 'false',
            ]);
            if ($thumb->successful()) {
                $tj = $thumb->json();
                $avatarUrl = $tj['data'][0]['imageUrl'] ?? null;
                Log::info('roblox.avatar.ok', ['userId' => $userId, 'hasAvatar' => (bool)$avatarUrl]);
            } else {
                Log::warning('roblox.avatar.failed', ['userId' => $userId, 'status' => $thumb->status()]);
            }
        }

        return response()->json([
            'ok' => true,
            'found' => true,
            'blacklisted' => Blacklist::isUsernameBlocked($name),
            'id' => $userId,
            'name' => $name,
            'displayName' => $display,
            'avatar' => $avatarUrl,
        ]);
    }

    // GET /api/roblox/gamepass-check?userId=123&amount=100
    public function checkGamepass(Request $request)
    {
        $userId = (int) $request->query('userId');
        $amount = (int) $request->query('amount');
        if ($userId <= 0 || $amount <= 0) {
            Log::warning('roblox.gamepass.bad_params', ['userId' => $userId, 'amount' => $amount]);
            return response()->json(['ok' => false, 'error' => 'BAD_PARAMS'], 400);
        }

        // Roblox potong 30%, jadi customer dapat 70% dari GamePass
        // Rumus: GamePass = (Robux yang mau didapatkan) × 100/70
        $requiredPrice = (int) ceil($amount * (100 / 70));

        $found = null;
        $apiMethod = null;

        // Method 1: Try primary API endpoint
        try {
        $gamepassUrl = "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes";
        $inv = Http::timeout(10)->get($gamepassUrl, ['count' => 100]);
        
        if ($inv->successful()) {
            $response = $inv->json();
            $items = $response['gamePasses'] ?? $response['data'] ?? [];
            Log::info('roblox.gamepasses.scan', ['userId' => $userId, 'count' => count($items), 'requiredPrice' => $requiredPrice]);
            
            if (empty($items)) {
                Log::info('roblox.gamepasses.empty', ['userId' => $userId, 'message' => 'User has no gamepasses in inventory']);
            } else {
                    // Direct match from API response
                foreach ($items as $gamepass) {
                    $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? null;
                    $price = (int)($gamepass['price'] ?? 0);
                    $isForSale = (bool)($gamepass['isForSale'] ?? false);
                    $creator = $gamepass['creator']['creatorId'] ?? null;
                    $creatorType = $gamepass['creator']['creatorType'] ?? null;
                    
                    Log::info('roblox.gamepass.check', [
                        'assetId' => $assetId, 
                        'price' => $price, 
                        'required' => $requiredPrice,
                        'creator' => $creator,
                        'creatorType' => $creatorType,
                        'isForSale' => $isForSale,
                        'name' => $gamepass['name'] ?? 'Unknown'
                    ]);
                    
                    // Match if: correct price, for sale, and owned by user
                    $isOwnedByUser = ($creator === $userId) || 
                                   ($creatorType === 'User' && $creator === $userId) ||
                                   ($creatorType === 'Group' && $creator === $userId);
                    
                    if ($price === $requiredPrice && $isForSale && $isOwnedByUser) {
                        $found = [
                            'assetId' => $assetId,
                            'name' => $gamepass['name'] ?? 'Gamepass',
                            'price' => $price,
                        ];
                            $apiMethod = 'primary_api';
                        Log::info('roblox.gamepass.match', [
                            'userId' => $userId, 
                            'assetId' => $assetId, 
                            'price' => $price, 
                            'required' => $requiredPrice,
                            'creator' => $creator,
                            'creatorType' => $creatorType,
                            'via' => 'apis.roblox.com-direct'
                        ]);
                        break;
                    }
                }
            }
        } else {
                Log::warning('roblox.gamepasses.failed', [
                    'userId' => $userId, 
                    'status' => $inv->status(),
                    'error' => $inv->body(),
                    'method' => 'primary_api'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('roblox.gamepasses.exception', [
                'userId' => $userId,
                'error' => $e->getMessage(),
                'method' => 'primary_api'
            ]);
        }

        // Method 2: Fallback - Try alternative approach if primary fails
        if (!$found) {
            try {
                Log::info('roblox.gamepass.fallback', ['userId' => $userId, 'requiredPrice' => $requiredPrice]);
                
                // Alternative: Try to get user's games and check for gamepasses
                $gamesUrl = "https://games.roblox.com/v2/users/{$userId}/games";
                $gamesResponse = Http::timeout(10)->get($gamesUrl, ['limit' => 50]);
                
                if ($gamesResponse->successful()) {
                    $gamesData = $gamesResponse->json();
                    $games = $gamesData['data'] ?? [];
                    
                    Log::info('roblox.games.found', ['userId' => $userId, 'gamesCount' => count($games)]);
                    
                    // For each game, try to find gamepasses
                    foreach ($games as $game) {
                        $universeId = $game['universe']['id'] ?? null;
                        if (!$universeId) continue;
                        
                        // Try to get gamepasses for this universe
                        $universeGamepassUrl = "https://apis.roblox.com/game-passes/v1/games/{$universeId}/game-passes";
                        $universeResponse = Http::timeout(5)->get($universeGamepassUrl);
                        
                        if ($universeResponse->successful()) {
                            $universeData = $universeResponse->json();
                            $universeGamepasses = $universeData['gamePasses'] ?? [];
                            
                            foreach ($universeGamepasses as $gamepass) {
                                $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? null;
                                $price = (int)($gamepass['price'] ?? 0);
                                $isForSale = (bool)($gamepass['isForSale'] ?? false);
                                $creator = $gamepass['creator']['creatorId'] ?? null;
                                $creatorType = $gamepass['creator']['creatorType'] ?? null;
                                
                                // Check if this gamepass belongs to our user
                                $isOwnedByUser = ($creator === $userId) || 
                                               ($creatorType === 'User' && $creator === $userId) ||
                                               ($creatorType === 'Group' && $creator === $userId);
                                
                                if ($price === $requiredPrice && $isForSale && $isOwnedByUser) {
                                    $found = [
                                        'assetId' => $assetId,
                                        'name' => $gamepass['name'] ?? 'Gamepass',
                                        'price' => $price,
                                    ];
                                    $apiMethod = 'fallback_universe_api';
                                    Log::info('roblox.gamepass.match.fallback', [
                                        'userId' => $userId, 
                                        'assetId' => $assetId, 
                                        'price' => $price, 
                                        'required' => $requiredPrice,
                                        'universeId' => $universeId,
                                        'via' => 'universe-gamepass-api'
                                    ]);
                                    break 2; // Break both loops
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('roblox.gamepasses.fallback.exception', [
                    'userId' => $userId,
                    'error' => $e->getMessage(),
                    'method' => 'fallback_universe_api'
                ]);
            }
        }

        // Method 3: If still not found, return helpful information for manual setup
        if (!$found) {
            Log::warning('roblox.gamepass.not_found', [
                'userId' => $userId, 
                'requiredPrice' => $requiredPrice,
                'message' => 'No matching gamepass found. User needs to create one.'
            ]);
        }

        Log::info('roblox.gamepass.result', [
            'userId' => $userId, 
            'requiredPrice' => $requiredPrice, 
            'found' => (bool)$found,
            'method' => $apiMethod
        ]);
        
        return response()->json([
            'ok' => true,
            'requiredPrice' => $requiredPrice,
            'found' => (bool) $found,
            'gamepass' => $found,
            'gamepass_link' => $found ? "https://www.roblox.com/game-pass/{$found['assetId']}" : null,
            'api_method' => $apiMethod,
            'message' => $found ? 'Gamepass ditemukan' : 'Gamepass tidak ditemukan. Silakan buat gamepass dengan harga yang sesuai.'
        ]);
    }

    // Debug method for testing gamepass logic
    public function debugGamepass(Request $request)
    {
        $username = trim((string) $request->query('username', ''));
        $amount = (int) $request->query('amount', 100);
        
        if ($username === '') {
            return response()->json(['error' => 'Username required'], 400);
        }

        $startTime = microtime(true);
        $debug = [
            'input' => [
                'username' => $username,
                'amount' => $amount,
                'timestamp' => now()->toISOString(),
                'start_time' => $startTime
            ],
            'steps' => []
        ];

        // Step 1: Get user ID
        $debug['steps']['1_username_check'] = [
            'action' => 'Checking username via Roblox API',
            'url' => 'https://users.roblox.com/v1/usernames/users',
            'start_time' => microtime(true)
        ];

        $postUrl = 'https://users.roblox.com/v1/usernames/users';
        $resp = Http::timeout(5)->post($postUrl, [
            'usernames' => [$username],
            'excludeBannedUsers' => true,
        ]);

        $debug['steps']['1_username_check']['response'] = [
            'status' => $resp->status(),
            'success' => $resp->successful(),
            'duration_ms' => round((microtime(true) - $debug['steps']['1_username_check']['start_time']) * 1000, 2)
        ];

        if (!$resp->successful()) {
            $debug['error'] = 'Username check failed';
            return response()->json($debug, 400);
        }

        $json = $resp->json();
        $data = $json['data'][0] ?? null;
        
        if (!$data) {
            $debug['error'] = 'Username not found';
            return response()->json($debug, 404);
        }

        $userId = $data['id'] ?? null;
        $debug['steps']['1_username_check']['result'] = [
            'userId' => $userId,
            'displayName' => $data['displayName'] ?? $data['name'] ?? $username
        ];

        // Step 2: Calculate required price
        $requiredPrice = (int) ceil($amount * (100 / 70));
        $debug['steps']['2_price_calculation'] = [
            'amount' => $amount,
            'requiredPrice' => $requiredPrice,
            'formula' => "ceil({$amount} * (100 / 70)) = {$requiredPrice}"
        ];

        // Step 3: Get gamepasses using recommended endpoint
        $debug['steps']['3_gamepass_check'] = [
            'action' => 'Getting user gamepasses via Roblox API',
            'url' => "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes",
            'start_time' => microtime(true)
        ];

        $invUrl = "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes";
        $inv = Http::timeout(10)->get($invUrl, ['count' => 100]);

        $debug['steps']['3_gamepass_check']['response'] = [
            'status' => $inv->status(),
            'success' => $inv->successful(),
            'duration_ms' => round((microtime(true) - $debug['steps']['3_gamepass_check']['start_time']) * 1000, 2),
            'response_body' => $inv->body(),
            'headers' => $inv->headers()
        ];

        if (!$inv->successful()) {
            $debug['error'] = 'Gamepass API check failed';
            $debug['possible_causes'] = [
                'user_has_no_gamepasses' => 'User mungkin tidak memiliki gamepass sama sekali',
                'user_account_restricted' => 'User account mungkin terbatas atau banned',
                'api_endpoint_changed' => 'Roblox API endpoint mungkin berubah',
                'user_id_invalid' => 'User ID mungkin tidak valid meskipun username check berhasil'
            ];
            
            return response()->json($debug, 500);
        }

        $response = $inv->json();
        $items = $response['gamePasses'] ?? $response['data'] ?? [];
        $debug['steps']['3_gamepass_check']['result'] = [
            'total_items' => count($items),
            'items_preview' => array_slice(array_map(function($item) {
                return [
                    'gamePassId' => $item['gamePassId'] ?? $item['id'] ?? 'unknown',
                    'name' => $item['name'] ?? 'unknown',
                    'price' => $item['price'] ?? 'unknown',
                    'isForSale' => $item['isForSale'] ?? false,
                    'creator' => $item['creator']['creatorId'] ?? 'unknown'
                ];
            }, $items), 0, 10)
        ];

        // Step 4: Direct match from API response (optimized!)
        $debug['steps']['4_direct_match'] = [
            'action' => 'Direct matching from API response',
            'start_time' => microtime(true),
            'checks' => []
        ];

        $found = null;
        $debug['steps']['4_direct_match']['total_items'] = count($items);

        foreach ($items as $idx => $gamepass) {
            if ($found) break;

            $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? null;
            $price = (int)($gamepass['price'] ?? 0);
            $isForSale = (bool)($gamepass['isForSale'] ?? false);
            $creator = $gamepass['creator']['creatorId'] ?? null;
            $creatorType = $gamepass['creator']['creatorType'] ?? null;

            $debug['steps']['4_direct_match']['checks']["item_{$idx}"] = [
                'gamePassId' => $assetId,
                'name' => $gamepass['name'] ?? 'Unknown',
                'price' => $price,
                'isForSale' => $isForSale,
                'creator' => $creator,
                'creatorType' => $creatorType,
                'matches_price' => $price === $requiredPrice,
                'matches_creator' => ($creator === $userId || $creatorType === 'User'),
                'is_valid' => $price === $requiredPrice && $isForSale && ($creator === $userId || $creatorType === 'User')
            ];

            if ($price === $requiredPrice && $isForSale && ($creator === $userId || $creatorType === 'User')) {
                $found = [
                    'assetId' => $assetId,
                    'name' => $gamepass['name'] ?? 'Gamepass',
                    'price' => $price,
                ];
                $debug['steps']['4_direct_match']['checks']["item_{$idx}"]['FOUND'] = true;
                break;
            }
        }

        $debug['steps']['4_direct_match']['total_duration_ms'] = round((microtime(true) - $debug['steps']['4_direct_match']['start_time']) * 1000, 2);

        // Final result
        $debug['result'] = [
            'found' => (bool)$found,
            'gamepass' => $found,
            'gamepass_link' => $found ? "https://www.roblox.com/game-pass/{$found['assetId']}" : null,
            'total_duration_ms' => round((microtime(true) - $debug['input']['start_time']) * 1000, 2)
        ];

        return response()->json($debug);
    }

}


