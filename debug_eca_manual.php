<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

try {
    echo "DEBUG: Manual Gamepass Check untuk eca0905\n";
    echo "==========================================\n\n";
    
    $userId = 1702623297; // User ID dari eca0905
    $requiredPrice = 1286; // Harga yang dibutuhkan untuk 900 Robux
    
    echo "User ID: {$userId}\n";
    echo "Required Price: {$requiredPrice} Robux\n";
    echo "Amount yang diminta: 900 Robux\n\n";
    
    // Method 1: Coba primary API
    echo "METHOD 1: Primary API (apis.roblox.com)\n";
    echo "----------------------------------------\n";
    $gamepassUrl = "https://apis.roblox.com/game-passes/v1/users/{$userId}/game-passes";
    echo "URL: {$gamepassUrl}\n";
    
    $inv = Http::timeout(10)->get($gamepassUrl, ['count' => 100]);
    
    echo "Status: " . $inv->status() . "\n";
    echo "Success: " . ($inv->successful() ? 'YA' : 'TIDAK') . "\n";
    echo "Response Body:\n";
    echo $inv->body() . "\n\n";
    
    if ($inv->successful()) {
        $response = $inv->json();
        $items = $response['gamePasses'] ?? $response['data'] ?? [];
        
        echo "Total Gamepass Found: " . count($items) . "\n\n";
        
        if (count($items) > 0) {
            echo "DETAIL GAMEPASS:\n";
            echo "===============\n";
            
            foreach ($items as $idx => $gamepass) {
                $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? 'unknown';
                $price = (int)($gamepass['price'] ?? 0);
                $isForSale = (bool)($gamepass['isForSale'] ?? false);
                $creator = $gamepass['creator']['creatorId'] ?? null;
                $creatorType = $gamepass['creator']['creatorType'] ?? null;
                $name = $gamepass['name'] ?? 'Unknown';
                
                $isOwnedByUser = ($creator === $userId) || 
                               ($creatorType === 'User' && $creator === $userId) ||
                               ($creatorType === 'Group' && $creator === $userId);
                
                $matchesPrice = ($price === $requiredPrice);
                $isValid = $matchesPrice && $isForSale && $isOwnedByUser;
                
                echo "Gamepass #" . ($idx + 1) . ":\n";
                echo "  - Name: {$name}\n";
                echo "  - Asset ID: {$assetId}\n";
                echo "  - Price: {$price} Robux\n";
                echo "  - Required: {$requiredPrice} Robux\n";
                echo "  - Price Match: " . ($matchesPrice ? 'YA' : 'TIDAK') . "\n";
                echo "  - For Sale: " . ($isForSale ? 'YA' : 'TIDAK') . "\n";
                echo "  - Creator ID: {$creator}\n";
                echo "  - Creator Type: {$creatorType}\n";
                echo "  - Owned by User: " . ($isOwnedByUser ? 'YA' : 'TIDAK') . "\n";
                echo "  - VALID: " . ($isValid ? 'YA' : 'TIDAK') . "\n";
                echo "  - Link: https://www.roblox.com/game-pass/{$assetId}\n";
                echo "\n";
            }
        } else {
            echo "User tidak memiliki gamepass sama sekali!\n\n";
        }
    }
    
    // Method 2: Coba via games API
    echo "METHOD 2: Via Games API\n";
    echo "----------------------\n";
    $gamesUrl = "https://games.roblox.com/v2/users/{$userId}/games";
    echo "URL: {$gamesUrl}\n";
    
    $gamesResponse = Http::timeout(10)->get($gamesUrl, ['limit' => 50]);
    
    echo "Status: " . $gamesResponse->status() . "\n";
    echo "Success: " . ($gamesResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($gamesResponse->successful()) {
        $gamesData = $gamesResponse->json();
        $games = $gamesData['data'] ?? [];
        
        echo "Total Games Found: " . count($games) . "\n\n";
        
        if (count($games) > 0) {
            echo "GAMES LIST:\n";
            echo "===========\n";
            
            foreach ($games as $idx => $game) {
                $gameId = $game['id'] ?? 'unknown';
                $gameName = $game['name'] ?? 'Unknown';
                $universeId = $game['universe']['id'] ?? null;
                
                echo "Game #" . ($idx + 1) . ":\n";
                echo "  - Name: {$gameName}\n";
                echo "  - Game ID: {$gameId}\n";
                echo "  - Universe ID: {$universeId}\n";
                
                if ($universeId) {
                    // Cek gamepass untuk universe ini
                    $universeGamepassUrl = "https://apis.roblox.com/game-passes/v1/games/{$universeId}/game-passes";
                    $universeResponse = Http::timeout(5)->get($universeGamepassUrl);
                    
                    if ($universeResponse->successful()) {
                        $universeData = $universeResponse->json();
                        $universeGamepasses = $universeData['gamePasses'] ?? [];
                        
                        echo "  - Gamepasses in this game: " . count($universeGamepasses) . "\n";
                        
                        foreach ($universeGamepasses as $gpIdx => $gamepass) {
                            $assetId = $gamepass['gamePassId'] ?? $gamepass['id'] ?? null;
                            $price = (int)($gamepass['price'] ?? 0);
                            $isForSale = (bool)($gamepass['isForSale'] ?? false);
                            $creator = $gamepass['creator']['creatorId'] ?? null;
                            $creatorType = $gamepass['creator']['creatorType'] ?? null;
                            $name = $gamepass['name'] ?? 'Unknown';
                            
                            $isOwnedByUser = ($creator === $userId) || 
                                           ($creatorType === 'User' && $creator === $userId) ||
                                           ($creatorType === 'Group' && $creator === $userId);
                            
                            $matchesPrice = ($price === $requiredPrice);
                            $isValid = $matchesPrice && $isForSale && $isOwnedByUser;
                            
                            echo "    Gamepass #" . ($gpIdx + 1) . ":\n";
                            echo "      - Name: {$name}\n";
                            echo "      - Price: {$price} Robux\n";
                            echo "      - Price Match: " . ($matchesPrice ? 'YA' : 'TIDAK') . "\n";
                            echo "      - For Sale: " . ($isForSale ? 'YA' : 'TIDAK') . "\n";
                            echo "      - Owned by User: " . ($isOwnedByUser ? 'YA' : 'TIDAK') . "\n";
                            echo "      - VALID: " . ($isValid ? 'YA' : 'TIDAK') . "\n";
                            
                            if ($isValid) {
                                echo "      - *** MATCH FOUND! ***\n";
                                echo "      - Link: https://www.roblox.com/game-pass/{$assetId}\n";
                            }
                        }
                    } else {
                        echo "  - Error getting gamepasses: " . $universeResponse->status() . "\n";
                    }
                }
                echo "\n";
            }
        }
    } else {
        echo "Response Body:\n";
        echo $gamesResponse->body() . "\n";
    }
    
    echo "\nKESIMPULAN:\n";
    echo "===========\n";
    echo "User eca0905 perlu membuat gamepass dengan harga {$requiredPrice} Robux\n";
    echo "agar bisa menerima 900 Robux setelah dipotong 30% oleh Roblox.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}





