<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$token  = 'YOUR_TOKEN_HERE';

// ── Use localhost directly — bypasses loopback block ──────────────────
$target = 'http://127.0.0.1/backendapi/formsubmit';

$body = file_get_contents('php://input');

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
        'Accept: application/json',
        'Host: crm.smartagents.ma',        // ← tells Laravel which app
        'X-API-TOKEN: ' . $token,
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if (empty($response)) {
    http_response_code(500);
    echo json_encode([
        'status'    => 'error',
        'message'   => 'Empty response',
        'http_code' => $httpCode,
        'error'     => $curlError,
    ]);
    exit;
}

http_response_code(200);
echo $response;