<?php
$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
file_put_contents("d.txt", $chatId);
if (strpos($message, "/xinchao") === 0) {
    $response = "Chào bạn, BOT có thể giúp gì? \n";
    $datapost = array(
        "chat_id" => $chatId,
        "text" => $response
    );
    $url = "https://api.telegram.org/bot6420974422:AAFPJJ6eTlOm-85P1sY47GhOhy_AjX6DxQI/sendMessage";
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