<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // Try GET (as requested), then POST fallback (official)
        $getUrl = 'https://users.roblox.com/v1/usernames/users?username=' . urlencode($username);
        $resp = Http::timeout(8)->get($getUrl);
        $data = null;
        if ($resp->successful()) {
            $json = $resp->json();
            $data = $json['data'][0] ?? null;
            Log::info('roblox.username.get', ['username' => $username, 'ok' => true, 'found' => (bool)$data]);
        } else {
            Log::warning('roblox.username.get_failed', ['username' => $username, 'status' => $resp->status()]);
        }

        if (!$data) {
            $postUrl = 'https://users.roblox.com/v1/usernames/users';
            $resp = Http::timeout(8)->post($postUrl, [
                'usernames' => [$username],
                'excludeBannedUsers' => true,
            ]);
            if ($resp->successful()) {
                $json = $resp->json();
                $data = $json['data'][0] ?? null;
                Log::info('roblox.username.post', ['username' => $username, 'ok' => true, 'found' => (bool)$data]);
            } else {
                Log::warning('roblox.username.post_failed', ['username' => $username, 'status' => $resp->status()]);
            }
        }

        if (!$data) {
            Log::info('roblox.username.not_found', ['username' => $username]);
            return response()->json(['ok' => false, 'found' => false]);
        }

        $userId = $data['id'] ?? null;
        $name = $data['name'] ?? $username;
        $display = $data['displayName'] ?? $name;

        // Avatar headshot thumbnail
        $avatarUrl = null;
        if ($userId) {
            $thumb = Http::timeout(8)->get('https://thumbnails.roblox.com/v1/users/avatar-headshot', [
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
        $specificAssetId = (int) $request->query('gamePassId');
        if ($userId <= 0 || $amount <= 0) {
            Log::warning('roblox.gamepass.bad_params', ['userId' => $userId, 'amount' => $amount]);
            return response()->json(['ok' => false, 'error' => 'BAD_PARAMS'], 400);
        }

        // Roblox potong 30%, jadi customer dapat 70% dari GamePass
        // Rumus: GamePass = (Robux yang mau didapatkan) × 100/70
        $requiredPrice = (int) ceil($amount * (100 / 70));

        // If specific assetId provided, verify it directly first
        if ($specificAssetId > 0) {
            // Quick inventory ownership check (optional but cheap)
            $ownUrl = "https://inventory.roblox.com/v1/users/{$userId}/items/GamePass/{$specificAssetId}";
            $ownRes = Http::timeout(10)->get($ownUrl);
            Log::info('roblox.gamepass.own_check', ['userId' => $userId, 'assetId' => $specificAssetId, 'status' => $ownRes->status()]);

            $detail = Http::timeout(10)->get("https://economy.roblox.com/v2/assets/{$specificAssetId}/details");
            if ($detail->successful()) {
                $dj = $detail->json();
                $creator = $dj['Creator']['Id'] ?? null;
                $creatorType = $dj['Creator']['Type'] ?? null;
                $price = (int) ($dj['PriceInRobux'] ?? 0);
                if (($creator === $userId || $creatorType === 'Group') && $price === $requiredPrice) {
                    Log::info('roblox.gamepass.direct_match', ['userId' => $userId, 'assetId' => $specificAssetId, 'price' => $price]);
                    return response()->json([
                        'ok' => true,
                        'requiredPrice' => $requiredPrice,
                        'found' => true,
                        'gamepass' => [
                            'assetId' => $specificAssetId,
                            'name' => $dj['Name'] ?? 'Gamepass',
                            'price' => $price,
                        ],
                        'gamepass_link' => "https://www.roblox.com/game-pass/{$specificAssetId}",
                        'via' => 'direct-asset',
                    ]);
                } else {
                    Log::info('roblox.gamepass.direct_mismatch', ['userId' => $userId, 'assetId' => $specificAssetId, 'price' => $price, 'required' => $requiredPrice, 'creator' => $creator, 'creatorType' => $creatorType]);
                }
            } else {
                Log::warning('roblox.gamepass.direct_detail_failed', ['assetId' => $specificAssetId, 'status' => $detail->status()]);
            }
            // If direct failed, continue to generic scan below
        }

        $found = null;

        // First try modern endpoint: apis.roblox.com game-passes list with pagination
        $next = null; $page = 0; $totalChecked = 0;
        do {
            $query = ['count' => 100];
            if ($next) { $query['exclusiveStartId'] = $next; }
            $url = "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes";
            $res = Http::timeout(12)->get($url, $query);
            if ($res->successful()) {
                $j = $res->json();
                $passes = $j['data'] ?? $j['gamePasses'] ?? []; // be flexible
                $next = $j['nextPageExclusiveStartId'] ?? $j['exclusiveStartId'] ?? null;
                $idsPreview = [];
                foreach ($passes as $pp) { $idp = $pp['id'] ?? $pp['assetId'] ?? $pp['gamePassId'] ?? null; if ($idp) { $idsPreview[] = (int)$idp; } }
                Log::info('roblox.gamepasses.list', [
                    'userId' => $userId,
                    'count' => is_countable($passes)?count($passes):0,
                    'page' => $page,
                    'ids' => array_slice($idsPreview, 0, 25),
                ]);
                // Try direct match using provided fields (price, isForSale, creator)
                foreach ($passes as $p) {
                    $priceField = $p['price'] ?? ($p['PriceInRobux'] ?? null);
                    $creatorObj = $p['creator'] ?? null;
                    $creatorIdField = is_array($creatorObj) ? ($creatorObj['creatorId'] ?? $creatorObj['Id'] ?? null) : null;
                    $isForSale = (bool)($p['isForSale'] ?? true);
                    if ($priceField !== null && $creatorIdField !== null && $isForSale) {
                        if ((int)$priceField === $requiredPrice && (int)$creatorIdField === (int)$userId) {
                            $found = [
                                'assetId' => (int)($p['gamePassId'] ?? $p['id'] ?? $p['assetId'] ?? 0),
                                'name' => $p['name'] ?? 'Gamepass',
                                'price' => (int)$priceField,
                            ];
                            Log::info('roblox.gamepass.match', ['userId' => $userId, 'assetId' => $found['assetId'], 'price' => $priceField, 'required' => $requiredPrice, 'via' => 'apis.roblox.com-inline']);
                            break;
                        }
                    }
                }
                if ($found) { break; }
                // Batch details via Catalog API to avoid 429 (only if inline data not sufficient)
                $assetIds = [];
                foreach ($passes as $p) {
                    $id = $p['id'] ?? $p['assetId'] ?? $p['gamePassId'] ?? null;
                    if ($id) { $assetIds[] = $id; }
                }
                $chunks = array_chunk($assetIds, 20);
                foreach ($chunks as $chunk) {
                    usleep(300000); // 300ms delay between batches to reduce 429/403
                    $filtered = array_values(array_filter($chunk, fn($id)=>is_numeric($id) && (int)$id>0));
                    if (empty($filtered)) { continue; }
                    $payload = ['items' => array_map(fn($id)=>['itemType'=>'Asset','id'=>(int)$id], $filtered)];
                    $headers = [
                        'User-Agent' => 'RobloxTopupBot/1.0',
                        'Origin' => 'https://www.roblox.com',
                        'Referer' => 'https://www.roblox.com/',
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ];
                    $detailRes = Http::withHeaders($headers)->timeout(12)->post('https://catalog.roblox.com/v1/catalog/items/details', $payload);
                    if ($detailRes->status() === 429) { // simple backoff once
                        usleep(500000);
                        $detailRes = Http::withHeaders($headers)->timeout(12)->post('https://catalog.roblox.com/v1/catalog/items/details', $payload);
                    }
                    if (!$detailRes->successful()) {
                        Log::warning('roblox.catalog.details_failed', ['status' => $detailRes->status()]);
                        continue;
                    }
                    $details = $detailRes->json()['data'] ?? [];
                    // log a compact view of this batch
                    $logItems = [];
                    foreach ($details as $djLog) {
                        $logItems[] = [
                            'id' => $djLog['id'] ?? null,
                            'price' => $djLog['price'] ?? ($djLog['priceInRobux'] ?? null),
                            'creator' => $djLog['creatorTargetId'] ?? null,
                        ];
                        if (count($logItems) >= 20) break;
                    }
                    Log::info('roblox.catalog.details_batch', ['count' => count($details), 'items' => $logItems]);
                    foreach ($details as $dj) {
                        $assetId = $dj['id'] ?? null;
                        $creator = ($dj['creatorTargetId'] ?? null);
                        $price = $dj['price'] ?? ($dj['priceInRobux'] ?? null);
                        if ($creator === $userId && (int)$price === $requiredPrice) {
                            $found = [
                                'assetId' => $assetId,
                                'name' => $dj['name'] ?? 'Gamepass',
                                'price' => $price,
                            ];
                            Log::info('roblox.gamepass.match', ['userId' => $userId, 'assetId' => $assetId, 'price' => $price, 'required' => $requiredPrice, 'via' => 'catalog.batch']);
                            break 3;
                        }
                    }
                }
                $page++;
            } else {
                Log::warning('roblox.gamepasses.list_failed', ['userId' => $userId, 'status' => $res->status()]);
                break; // fallback below
            }
        } while ($next && $page < 5);

        // Fallback: inventory.roblox.com minimal info list then details
        if (!$found) {
            $invUrl = "https://inventory.roblox.com/v1/users/{$userId}/items/GamePass";
            $inv = Http::timeout(10)->get($invUrl, ['limit' => 50]);
            if ($inv->successful()) {
                $items = $inv->json()['data'] ?? [];
                Log::info('roblox.inventory.ok', ['userId' => $userId, 'count' => is_array($items) ? count($items) : 0]);
                foreach ($items as $item) {
                    $assetId = $item['id'] ?? ($item['assetId'] ?? null);
                    if (!$assetId) { continue; }
                    $detail = Http::timeout(8)->get("https://economy.roblox.com/v2/assets/{$assetId}/details");
                    if (!$detail->successful()) { 
                        Log::warning('roblox.asset_detail.failed', ['assetId' => $assetId, 'status' => $detail->status()]);
                        continue; 
                    }
                    $dj = $detail->json();
                    $creator = $dj['Creator']['Id'] ?? null;
                    $price = $dj['PriceInRobux'] ?? null;
                    if ($creator === $userId && (int)$price === $requiredPrice) {
                        $found = [
                            'assetId' => $assetId,
                            'name' => $dj['Name'] ?? 'Gamepass',
                            'price' => $price,
                        ];
                        Log::info('roblox.gamepass.match', ['userId' => $userId, 'assetId' => $assetId, 'price' => $price, 'required' => $requiredPrice, 'via' => 'inventory.roblox.com']);
                        break;
                    }
                }
            } else {
                Log::warning('roblox.inventory.failed', ['userId' => $userId, 'status' => $inv->status()]);
            }
        }

        Log::info('roblox.gamepass.result', ['userId' => $userId, 'required' => $requiredPrice, 'found' => (bool)$found]);
        return response()->json([
            'ok' => true,
            'requiredPrice' => $requiredPrice,
            'found' => (bool) $found,
            'gamepass' => $found,
            'gamepass_link' => $found ? "https://www.roblox.com/game-pass/{$found['assetId']}" : null,
        ]);
    }
}


