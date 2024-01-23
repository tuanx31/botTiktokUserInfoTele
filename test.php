<?php
$datapost = array(
    "chat_id" => "5824366283",
    "text" => "aaa"
);
$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($datapost),
    ],
];
$context = stream_context_create($options);
$url = "https://api.telegram.org/bot6420974422:AAFPJJ6eTlOm-85P1sY47GhOhy_AjX6DxQI/sendMessage";
$result = file_get_contents($url, false, $context);
?>