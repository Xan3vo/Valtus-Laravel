<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\RobloxGroupController;
use Illuminate\Http\Request;

try {
    echo "Testing Group Membership Feature\n";
    echo "===============================\n\n";
    
    $controller = new RobloxGroupController();
    
    // Test dengan username yang valid
    $testUsernames = ['rizkimulyawan110404', 'eca0905'];
    
    foreach ($testUsernames as $username) {
        echo "Testing username: {$username}\n";
        echo "--------------------------------\n";
        
        $request = new Request();
        $request->merge(['username' => $username]);
        
        $result = $controller->checkGroupMembership($request);
        $data = $result->getData(true);
        
        echo "Success: " . ($data['success'] ? 'YA' : 'TIDAK') . "\n";
        echo "Is Member: " . ($data['is_member'] ?? 'N/A') . "\n";
        echo "Can Purchase: " . ($data['can_purchase'] ?? 'N/A') . "\n";
        echo "Membership Days: " . ($data['membership_days'] ?? 'N/A') . "\n";
        echo "Message: " . ($data['message'] ?? 'N/A') . "\n";
        echo "\n";
    }
    
    // Test group info
    echo "Testing Group Info\n";
    echo "==================\n";
    $groupInfoRequest = new Request();
    $groupInfoResult = $controller->getGroupInfo();
    $groupData = $groupInfoResult->getData(true);
    
    echo "Success: " . ($groupData['success'] ? 'YA' : 'TIDAK') . "\n";
    if ($groupData['success']) {
        echo "Group Name: " . $groupData['group']['name'] . "\n";
        echo "Group ID: " . $groupData['group']['id'] . "\n";
        echo "Member Count: " . $groupData['group']['member_count'] . "\n";
        echo "URL: " . $groupData['group']['url'] . "\n";
    } else {
        echo "Error: " . $groupData['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

