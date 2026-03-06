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
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-API-TOKEN: ' . $token,
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// If response is empty, return something meaningful
if (empty($response)) {
    http_response_code(500);
    echo json_encode([
        'status'     => 'error',
        'message'    => 'Empty response from CRM',
        'http_code'  => $httpCode,
        'curl_error' => $curlError,
    ]);
    exit;
}

// Force 200 — JS doesn't care about 201 vs 200
http_response_code(200);
echo $response;