<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\RobloxController;
use Illuminate\Http\Request;

try {
    echo "Testing gamepass search untuk 2 user:\n";
    echo "=====================================\n\n";
    
    $controller = new RobloxController();
    
    // Test 1: eca0905 dengan 900 Robux
    echo "TEST 1: Username eca0905, Amount 900 Robux\n";
    echo "-------------------------------------------\n";
    $request1 = new Request();
    $request1->query->set('userId', '1702623297'); // User ID dari test sebelumnya
    $request1->query->set('amount', '900');
    
    $result1 = $controller->checkGamepass($request1);
    $data1 = $result1->getData(true);
    
    echo "Required Price: " . $data1['requiredPrice'] . " Robux\n";
    echo "Found: " . ($data1['found'] ? 'YA' : 'TIDAK') . "\n";
    echo "Message: " . $data1['message'] . "\n";
    echo "API Method: " . ($data1['api_method'] ?? 'Tidak ada') . "\n\n";
    
    // Test 2: rizkimulyawan110404 dengan 100 Robux
    echo "TEST 2: Username rizkimulyawan110404, Amount 100 Robux\n";
    echo "-------------------------------------------------------\n";
    
    // Pertama cek username untuk dapat userId
    $usernameRequest = new Request();
    $usernameRequest->query->set('username', 'rizkimulyawan110404');
    
    $usernameResult = $controller->checkUsername($usernameRequest);
    $usernameData = $usernameResult->getData(true);
    
    if ($usernameData['ok'] && $usernameData['found']) {
        echo "Username ditemukan!\n";
        echo "User ID: " . $usernameData['id'] . "\n";
        echo "Display Name: " . $usernameData['displayName'] . "\n\n";
        
        // Sekarang test gamepass
        $request2 = new Request();
        $request2->query->set('userId', $usernameData['id']);
        $request2->query->set('amount', '100');
        
        $result2 = $controller->checkGamepass($request2);
        $data2 = $result2->getData(true);
        
        echo "Required Price: " . $data2['requiredPrice'] . " Robux\n";
        echo "Found: " . ($data2['found'] ? 'YA' : 'TIDAK') . "\n";
        echo "Message: " . $data2['message'] . "\n";
        echo "API Method: " . ($data2['api_method'] ?? 'Tidak ada') . "\n";
        
        if ($data2['found']) {
            echo "Gamepass Details:\n";
            echo "- Asset ID: " . $data2['gamepass']['assetId'] . "\n";
            echo "- Name: " . $data2['gamepass']['name'] . "\n";
            echo "- Price: " . $data2['gamepass']['price'] . " Robux\n";
            echo "- Link: " . $data2['gamepass_link'] . "\n";
        }
    } else {
        echo "Username tidak ditemukan!\n";
        echo "Error: " . ($usernameData['error'] ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}





