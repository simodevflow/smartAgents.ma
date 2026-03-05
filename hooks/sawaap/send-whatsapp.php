<?php
// send-whatsapp.php
function sendWhatsAppMessage($formData) {
    $token = "EAANrZAKLWz7oBQwFO8shOIh8R9WSGZBWgVLKSVC09zZAlzNX44Bfd7OsDm22edZC5x5yJXWvBmqoIzLpyyq2bJ8z52AK4MfGDLtbDn6cf8hRJwE4z7tzEimQJlQdxZA5djW7OtRjoSZAlUZCnTgOIX3aWsuOAbahRey7KZBZATdXvBz4ZBcI5CCHbedwsGmH9yuPrnhmvCYR8xkixhIV4AeS23X0dvauYqsK2oo1ZADBA2fkQNXCFddJYDUJMFveULUtR6VcaZB8JEabU5zfcwV1tBbZA"; // secure, server-side
    $phoneId = "1065173526670720"; // WhatsApp Business phone ID
    $recipient = "+212725354292"; // e.g., "15551234567"

    $message = "New Form Submission:\n";
    foreach ($formData as $key => $value) {
        $message .= "$key: $value\n";
    }

    $data = [
        "messaging_product" => "whatsapp",
        "to" => $recipient,
        "type" => "text",
        "text" => ["body" => $message]
    ];

    $ch = curl_init("https://graph.facebook.com/v17.0/$phoneId/messages");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// If called via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = json_decode(file_get_contents('php://input'), true);
    echo sendWhatsAppMessage($formData);
}
?>