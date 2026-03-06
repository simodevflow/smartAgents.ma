<?php

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// ── Only accept POST ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// ── Config ────────────────────────────────────────────────────────────
$token  = 'YOUR_TOKEN_FROM_SETTINGS_PAGE';
$target = 'https://crm.smartagents.ma/backendapi/formsubmit';

// ── Read raw JSON body ────────────────────────────────────────────────
$body = file_get_contents('php://input');

if (empty($body)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Empty request body']);
    exit;
}

// ── Forward to CRM via curl ───────────────────────────────────────────
$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 3,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-API-TOKEN: ' . $token,
        'X-Forwarded-From: smartagents.ma',
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// ── Curl failed entirely ──────────────────────────────────────────────
if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Could not reach CRM server.',
    ]);
    exit;
}

// ── Return CRM response as-is to browser ─────────────────────────────
http_response_code($httpCode);
echo $response;
```

---

## Step 3 — Verify it's reachable

Open this URL directly in your browser:
```
https://smartagents.ma/submit-lead.php