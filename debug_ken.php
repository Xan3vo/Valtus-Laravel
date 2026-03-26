<?php

$username = 'Ken085856';
echo "Testing username: $username\n";
echo "================================\n";

// Test 1: Get User ID
echo "1. Getting User ID...\n";
$response = file_get_contents('http://localhost:8000/api/roblox/username?username=' . urlencode($username));
echo "Raw response: $response\n";
$data = json_decode($response, true);
echo "Decoded data: ";
print_r($data);

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
echo "Raw response: $response\n";
$data = json_decode($response, true);
echo "Decoded data: ";
print_r($data);


