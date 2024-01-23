<?php
include "getdata.php";
$fdata = array();
$myfile = fopen("data.txt", "r") or die("Unable to open file!");

while (!feof($myfile)) {
    $userid = trim(fgets($myfile));
    $datas = $getTiktokUser->details('@' . $userid);
    $datatmp = json_decode($datas, true);
    $tichxanh = $datatmp["user"]["verified"] ? "Có" : "Không";
    $strtmp = "username : " . $datatmp["user"]["username"] . " ;\nuser Id : " . $datatmp["user"]["profileName"] . " ;\nvị trí : " . $datatmp["user"]["region"] . " ;\n tích xanh : " . $tichxanh . " ;\nfollowing : " . $datatmp["stats"]["following"] . " ;\nfollower : " . $datatmp["stats"]["follower"] . " ;\nvideo : " . $datatmp["stats"]["video"] . " ;\nlike : " . $datatmp["stats"]["like"];
    echo $strtmp . "<hr/>";

    array_push($fdata, $strtmp);
}
fclose($myfile);
$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
if (strpos($message, "/start") === 0) {
    foreach ($fdata as $value) {
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
            ],
        ];
        $token = "6516242817:AAE_EHuBBipxuKFFwpa5jr8lKjayEvKaP9M";
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatId . "&text=" . $value;
        $result = file_get_contents($url, false, $context);
    }
}

?>