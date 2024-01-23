<?php
include "getdata.php";
$token = "6872045298:AAFD3ijj_yWjDt2pkEhPDDAYzySKSOtJ-QI";

$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
if (strpos($message, "/start") === 0) {
    $fdata = array();
    $myfile = fopen("data.txt", "r") or die("Unable to open file!");
    while (!feof($myfile)) {
        $userid = trim(fgets($myfile));
        $datas = $getTiktokUser->details('@' . $userid);
        $datatmp = json_decode($datas, true);
        $tichxanh = $datatmp["user"]["verified"] ? "Có" : "Không";
        $strtmp = "username : " . $datatmp["user"]["username"] . " ;\nuser Id : " . $datatmp["user"]["profileName"] . " ;\nvị trí : " . $datatmp["user"]["region"] . " ;\n tích xanh : " . $tichxanh . " ;\nfollowing : " . $datatmp["stats"]["following"] . " ;\nfollower : " . $datatmp["stats"]["follower"] . " ;\nvideo : " . $datatmp["stats"]["video"] . " ;\nlike : " . $datatmp["stats"]["like"];
        array_push($fdata, $strtmp);
    }
    fclose($myfile);
    foreach ($fdata as $value) {
        $datapost = array(
            "chat_id" => $chatId,
            "text" => $value
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
}
if (strpos($message, "/xinchao") === 0) {
    $response = "Chào cc \n";
    $datapost = array(
        "chat_id" => $chatId,
        "text" => $response
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