<?php

$username = 'Ken085856';
echo "Testing username: $username\n";
echo "================================\n";

// Test 1: Get User ID
echo "1. Getting User ID...\n";
$response = file_get_contents('http://localhost:8000/api/roblox/username?username=' . urlencode($username));
$data = json_decode($response, true);
if ($data['success']) {
    $userId = $data['user']['id'];
    echo "   User ID: $userId\n";
    echo "   Username: {$data['user']['name']}\n";
    echo "   Display Name: {$data['user']['displayName']}\n";
} else {
    echo "   Error: {$data['message']}\n";
    exit;
}

echo "\n";

// Test 2: Check Group Membership
echo "2. Checking Group Membership...\n";
$postData = json_encode(['username' => $username]);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $postData
    ]
]);
$response = file_get_contents('http://localhost:8000/api/roblox/group-membership', false, $context);
$data = json_decode($response, true);

if ($data['success']) {
    echo "   Success: {$data['message']}\n";
    echo "   Is Member: " . ($data['is_member'] ? 'Yes' : 'No') . "\n";
    echo "   Can Purchase: " . ($data['can_purchase'] ? 'Yes' : 'No') . "\n";
    echo "   Membership Days: {$data['membership_days']}\n";
} else {
    echo "   Error: {$data['message']}\n";
}

echo "\n";

// Test 3: Get Group Info
echo "3. Getting Group Info...\n";
$response = file_get_contents('http://localhost:8000/api/roblox/group-info');
$data = json_decode($response, true);

if ($data['success']) {
    echo "   Group Name: {$data['group']['name']}\n";
    echo "   Group ID: {$data['group']['id']}\n";
    echo "   Member Count: {$data['group']['member_count']}\n";
    echo "   Group URL: {$data['group']['url']}\n";
} else {
    echo "   Error: {$data['message']}\n";
}

echo "\n";
echo "================================\n";
echo "Test completed for username: $username\n";


