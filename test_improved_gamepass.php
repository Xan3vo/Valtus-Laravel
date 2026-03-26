<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\RobloxController;
use Illuminate\Http\Request;

try {
    echo "Testing IMPROVED gamepass search for username: eca0905, amount: 900\n";
    echo "================================================\n\n";
    
    $controller = new RobloxController();
    $request = new Request();
    $request->query->set('userId', '1702623297'); // User ID dari test sebelumnya
    $request->query->set('amount', '900');
    
    $result = $controller->checkGamepass($request);
    $data = $result->getData(true);
    
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

