<?php

header('Content-Type: application/json');

// Block non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// ── Config ────────────────────────────────────────────────────────────
$token  = 'YOUR_SECRET_TOKEN_FROM_SETTINGS_PAGE';
$target = 'https://crm.smartagents.ma/backendapi/formsubmit';

// ── Forward request ───────────────────────────────────────────────────
$body = file_get_contents('php://input');

$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_POST            => true,
    CURLOPT_POSTFIELDS      => $body,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_TIMEOUT         => 10,
    CURLOPT_FOLLOWLOCATION  => true,   // ← follows the 307 redirect
    CURLOPT_SSL_VERIFYPEER  => true,
    CURLOPT_HTTPHEADER      => [
        'Content-Type: application/json',
        'X-API-TOKEN: ' . $token,
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

// ── Handle curl failure ───────────────────────────────────────────────
if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Service unavailable: ' . $error
    ]);
    exit;
}

http_response_code($httpCode);
echo $response;