<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$token  = 'YOUR_TOKEN_HERE';
$target = 'https://crm.smartagents.ma/backendapi/formsubmit';
$body   = file_get_contents('php://input');

$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_POST            => true,
    CURLOPT_POSTFIELDS      => $body,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_TIMEOUT         => 15,
    CURLOPT_FOLLOWLOCATION  => true,
    CURLOPT_MAXREDIRS       => 5,
    CURLOPT_SSL_VERIFYPEER  => false,
    CURLOPT_SSL_VERIFYHOST  => false,
    CURLOPT_VERBOSE         => false,
    CURLOPT_HTTPHEADER      => [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-API-TOKEN: ' . $token,
    ],
]);

$response    = curl_exec($ch);
$httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError   = curl_error($ch);
$info        = curl_getinfo($ch);
curl_close($ch);

// Always return debug info until fixed
echo json_encode([
    'debug'         => true,
    'http_code'     => $httpCode,
    'curl_error'    => $curlError,
    'response'      => $response,
    'response_size' => strlen($response ?? ''),
    'redirect_url'  => $info['redirect_url'] ?? '',
    'effective_url' => $info['url'] ?? '',
    'total_time'    => $info['total_time'] ?? '',
]);