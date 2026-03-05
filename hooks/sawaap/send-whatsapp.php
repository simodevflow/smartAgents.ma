<?php
// send-whatsapp.php
function sendWhatsAppMessage($formData = []) {
    $token = "EAANrZAKLWz7oBQx6VEtTReaORqKp2ThmLZCoKojeghLEaJp0xiZAleiEiuDwWsiXpcrsOSZB90OZBKTK3tZBpbsoDPJMv2gzoBgf4ZBJFi9lovoznkQFYAhf0X9GZANgfuPn902l6kcCG0ZCN8AivrTzXXSd7A1bm4rcxZCHssguYZBNV7XN6odST8pl3yCopEC2occaHxvJZATgYKEwP54rEXYZAKsvbq3GSiBcbuH5DB5ItYhmxp61UXLGKnPqbDCYEH6E92bYxgOnnI4Sr4k8zxlh1gg8ZD"; // secure, server-side
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
echo sendWhatsAppMessage(["Test" => "This is a test message from the server."]);
?>