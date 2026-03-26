<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

try {
    echo "Debug Group Membership API\n";
    echo "=========================\n\n";
    
    $username = 'rizkimulyawan110404';
    $groupId = 35148970;
    
    // Step 1: Get user ID
    echo "Step 1: Getting user ID for {$username}\n";
    $userResponse = Http::timeout(10)->post('https://users.roblox.com/v1/usernames/users', [
        'usernames' => [$username],
        'excludeBannedUsers' => true,
    ]);
    
    echo "Status: " . $userResponse->status() . "\n";
    echo "Success: " . ($userResponse->successful() ? 'YA' : 'TIDAK') . "\n";
    
    if ($userResponse->successful()) {
        $userData = $userResponse->json();
        $userId = $userData['data'][0]['id'] ?? null;
        echo "User ID: {$userId}\n\n";
        
        if ($userId) {
            // Step 2: Check group membership
            echo "Step 2: Checking group membership for user {$userId}\n";
            $groupResponse = Http::timeout(10)->get("https://groups.roblox.com/v1/users/{$userId}/groups/roles");
            
            echo "Status: " . $groupResponse->status() . "\n";
            echo "Success: " . ($groupResponse->successful() ? 'YA' : 'TIDAK') . "\n";
            echo "Response Body:\n";
            echo $groupResponse->body() . "\n\n";
            
            if ($groupResponse->successful()) {
                $groupData = $groupResponse->json();
                $groups = $groupData['data'] ?? [];
                
                echo "Total groups found: " . count($groups) . "\n";
                
                $found = false;
                foreach ($groups as $group) {
                    echo "Group ID: " . $group['group']['id'] . ", Name: " . $group['group']['name'] . "\n";
                    if ($group['group']['id'] == $groupId) {
                        $found = true;
                        echo "*** FOUND TARGET GROUP! ***\n";
                        echo "Role: " . $group['role']['name'] . "\n";
                        echo "Created: " . $group['role']['created'] . "\n";
                        break;
                    }
                }
                
                if (!$found) {
                    echo "Target group not found in user's groups\n";
                }
            }
        }
    } else {
        echo "Response Body:\n";
        echo $userResponse->body() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

