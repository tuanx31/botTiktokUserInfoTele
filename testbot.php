<?php
$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$token = "6473575319:AAFROImNzyfPc-u6spGlFJb-RUTdhwS68Nc";
if (strpos($message, "/start") === 0) {
    $datapost = array(
        "chat_id" => $chatId,
        "text" => "Welcome to bot"
    );
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datapost));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === FALSE) {
        // Handle error
        echo "Error occurred while making the request: " . curl_error($ch);
    } else {
        // Process the response
        echo $response;
    }
    curl_close($ch);

}
?>