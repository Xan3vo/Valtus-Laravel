<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

try {
    echo "INVESTIGASI MENDALAM: Kenapa eca0905 tidak bisa diakses?\n";
    echo "======================================================\n\n";
    
    $ecaUserId = 1702623297;
    $rizkiUserId = 1429646404;
    
    echo "User eca0905 ID: {$ecaUserId}\n";
    echo "User rizkimulyawan110404 ID: {$rizkiUserId}\n\n";
    
    // Test 1: Cek apakah user masih aktif
    echo "TEST 1: Cek Status User\n";
    echo "=======================\n";
    
    $userUrl = "https://users.roblox.com/v1/users/{$ecaUserId}";
    echo "URL: {$userUrl}\n";
    
    $userResponse = Http::timeout(10)->get($userUrl);
    echo "Status: " . $userResponse->status() . "\n";
    echo "Success: " . ($userResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($userResponse->successful()) {
        $userData = $userResponse->json();
        echo "User Data:\n";
        echo "- Name: " . ($userData['name'] ?? 'Unknown') . "\n";
        echo "- Display Name: " . ($userData['displayName'] ?? 'Unknown') . "\n";
        echo "- Description: " . ($userData['description'] ?? 'No description') . "\n";
        echo "- Is Banned: " . ($userData['isBanned'] ?? 'Unknown') . "\n";
        echo "- Has Verified Badge: " . ($userData['hasVerifiedBadge'] ?? 'Unknown') . "\n";
        echo "- Created: " . ($userData['created'] ?? 'Unknown') . "\n";
    } else {
        echo "Response Body:\n";
        echo $userResponse->body() . "\n";
    }
    echo "\n";
    
    // Test 2: Cek apakah user memiliki avatar (indikator akun aktif)
    echo "TEST 2: Cek Avatar User\n";
    echo "======================\n";
    
    $avatarUrl = "https://thumbnails.roblox.com/v1/users/avatar-headshot";
    $avatarResponse = Http::timeout(10)->get($avatarUrl, [
        'userIds' => $ecaUserId,
        'size' => '150x150',
        'format' => 'Png',
        'isCircular' => 'false'
    ]);
    
    echo "Status: " . $avatarResponse->status() . "\n";
    echo "Success: " . ($avatarResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($avatarResponse->successful()) {
        $avatarData = $avatarResponse->json();
        if (isset($avatarData['data'][0])) {
            echo "Avatar URL: " . $avatarData['data'][0]['imageUrl'] . "\n";
        } else {
            echo "No avatar data found\n";
        }
    } else {
        echo "Response Body:\n";
        echo $avatarResponse->body() . "\n";
    }
    echo "\n";
    
    // Test 3: Cek apakah user memiliki games
    echo "TEST 3: Cek Games User\n";
    echo "=====================\n";
    
    $gamesUrl = "https://games.roblox.com/v2/users/{$ecaUserId}/games";
    $gamesResponse = Http::timeout(10)->get($gamesUrl, ['limit' => 50]);
    
    echo "Status: " . $gamesResponse->status() . "\n";
    echo "Success: " . ($gamesResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($gamesResponse->successful()) {
        $gamesData = $gamesResponse->json();
        $games = $gamesData['data'] ?? [];
        
        echo "Total Games: " . count($games) . "\n";
        
        if (count($games) > 0) {
            foreach ($games as $idx => $game) {
                echo "Game #" . ($idx + 1) . ":\n";
                echo "- Name: " . ($game['name'] ?? 'Unknown') . "\n";
                echo "- Game ID: " . ($game['id'] ?? 'Unknown') . "\n";
                echo "- Universe ID: " . ($game['universe']['id'] ?? 'NULL') . "\n";
                echo "- Place ID: " . ($game['rootPlace']['id'] ?? 'NULL') . "\n";
                echo "- Is Active: " . ($game['isActive'] ?? 'Unknown') . "\n";
                echo "- Created: " . ($game['created'] ?? 'Unknown') . "\n";
                echo "- Updated: " . ($game['updated'] ?? 'Unknown') . "\n";
                echo "\n";
            }
        }
    } else {
        echo "Response Body:\n";
        echo $gamesResponse->body() . "\n";
    }
    echo "\n";
    
    // Test 4: Coba akses gamepass dengan berbagai cara
    echo "TEST 4: Coba Akses Gamepass dengan Berbagai Cara\n";
    echo "===============================================\n";
    
    // Cara 1: Direct user gamepass API
    echo "Cara 1: Direct User Gamepass API\n";
    $gamepassUrl1 = "https://apis.roblox.com/game-passes/v1/users/{$ecaUserId}/game-passes";
    $gpResponse1 = Http::timeout(10)->get($gamepassUrl1, ['count' => 100]);
    echo "URL: {$gamepassUrl1}\n";
    echo "Status: " . $gpResponse1->status() . "\n";
    echo "Response: " . $gpResponse1->body() . "\n\n";
    
    // Cara 2: Coba dengan parameter berbeda
    echo "Cara 2: Dengan Parameter Berbeda\n";
    $gamepassUrl2 = "https://apis.roblox.com/game-passes/v1/users/{$ecaUserId}/game-passes";
    $gpResponse2 = Http::timeout(10)->get($gamepassUrl2, [
        'count' => 50,
        'sortOrder' => 'Asc'
    ]);
    echo "URL: {$gamepassUrl2}\n";
    echo "Status: " . $gpResponse2->status() . "\n";
    echo "Response: " . $gpResponse2->body() . "\n\n";
    
    // Cara 3: Coba akses via inventory
    echo "Cara 3: Via Inventory API\n";
    $inventoryUrl = "https://inventory.roblox.com/v2/users/{$ecaUserId}/inventory/GamePass";
    $invResponse = Http::timeout(10)->get($inventoryUrl, [
        'assetType' => 'GamePass',
        'limit' => 100
    ]);
    echo "URL: {$inventoryUrl}\n";
    echo "Status: " . $invResponse->status() . "\n";
    echo "Response: " . $invResponse->body() . "\n\n";
    
    // Test 5: Bandingkan dengan user yang berhasil
    echo "TEST 5: Bandingkan dengan User yang Berhasil\n";
    echo "============================================\n";
    
    echo "User rizkimulyawan110404 (BERHASIL):\n";
    $rizkiGamepassUrl = "https://apis.roblox.com/game-passes/v1/users/{$rizkiUserId}/game-passes";
    $rizkiResponse = Http::timeout(10)->get($rizkiGamepassUrl, ['count' => 100]);
    echo "Status: " . $rizkiResponse->status() . "\n";
    echo "Success: " . ($rizkiResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($rizkiResponse->successful()) {
        $rizkiData = $rizkiResponse->json();
        $rizkiGamepasses = $rizkiData['gamePasses'] ?? [];
        echo "Total Gamepass: " . count($rizkiGamepasses) . "\n";
    }
    echo "\n";
    
    // Test 6: Cek apakah ada perbedaan dalam headers
    echo "TEST 6: Analisis Headers Response\n";
    echo "=================================\n";
    
    echo "Headers eca0905:\n";
    $headers = $gpResponse1->headers();
    foreach ($headers as $key => $value) {
        if (is_array($value)) {
            echo "- {$key}: " . implode(', ', $value) . "\n";
        } else {
            echo "- {$key}: {$value}\n";
        }
    }
    echo "\n";
    
    echo "Headers rizkimulyawan110404:\n";
    $rizkiHeaders = $rizkiResponse->headers();
    foreach ($rizkiHeaders as $key => $value) {
        if (is_array($value)) {
            echo "- {$key}: " . implode(', ', $value) . "\n";
        } else {
            echo "- {$key}: {$value}\n";
        }
    }
    echo "\n";
    
    // Kesimpulan
    echo "KESIMPULAN INVESTIGASI:\n";
    echo "======================\n";
    echo "1. Jika user eca0905 masih aktif tapi API gamepass gagal:\n";
    echo "   - Kemungkinan privacy settings yang sangat ketat\n";
    echo "   - Kemungkinan akun terbatas (limited account)\n";
    echo "   - Kemungkinan akun baru yang belum fully verified\n\n";
    
    echo "2. Jika user eca0905 tidak aktif:\n";
    echo "   - Akun mungkin sudah dihapus atau banned\n";
    echo "   - Username mungkin sudah berubah\n\n";
    
    echo "3. Solusi yang bisa dicoba:\n";
    echo "   - Minta user cek privacy settings\n";
    echo "   - Minta user verify email/phone\n";
    echo "   - Minta user buat gamepass baru\n";
    echo "   - Coba dengan username yang berbeda\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}





