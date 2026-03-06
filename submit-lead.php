<?php
// TEMPORARY DEBUG — remove after fixing
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Test 1: Is PHP running?
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['debug' => 'PHP is working, but no POST received']);
    exit;
}

// Test 2: Is curl available?
if (!function_exists('curl_init')) {
    echo json_encode(['debug' => 'curl is NOT available on this server']);
    exit;
}

// Test 3: Can we reach the CRM?
$token  = 'YOUR_TOKEN_HERE';
$target = 'https://crm.smartagents.ma/backendapi/formsubmit';

$body = file_get_contents('php://input');

// Log what we received
file_put_contents(__DIR__ . '/debug.log',
    date('Y-m-d H:i:s') . "\n" .
    "Body: " . $body . "\n" .
    "---\n",
    FILE_APPEND
);

$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,   // ← temporarily disabled for debug
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-API-TOKEN: ' . $token,
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Return everything for debugging
echo json_encode([
    'debug'      => true,
    'http_code'  => $httpCode,
    'curl_error' => $curlError,
    'response'   => $response,   // raw response from CRM
    'body_sent'  => $body,
]);
