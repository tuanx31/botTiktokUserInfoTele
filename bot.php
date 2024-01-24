<?php
include "getdata.php";
include "conn.php";
function chuyenDoiSo($so)
{
    if ($so >= 1000000) {
        // Nếu số lớn hơn hoặc bằng 1 triệu, chuyển đổi thành triệu
        $soChuyenDoi = $so / 1000000;
        $soLamTron = number_format($soChuyenDoi, 1);
        return $soLamTron . 'M';
    } elseif ($so >= 1000) {
        // Nếu số lớn hơn hoặc bằng 1000, chuyển đổi thành nghìn
        $soChuyenDoi = $so / 1000;
        $soLamTron = number_format($soChuyenDoi, 1);
        return $soLamTron . 'K';
    } else {
        // Nếu số nhỏ hơn 1000, không thay đổi
        return $so;
    }
}
$token = "6872045298:AAFD3ijj_yWjDt2pkEhPDDAYzySKSOtJ-QI";

$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$query = "SELECT file_name FROM `file_user` WHERE user_id='" . $chatId . "'";
$sql_result = mysqli_query($conn, $query);
$file_name = mysqli_fetch_array($sql_result);
if (strpos($message, "/laydata") === 0) {
    $fdata = array();
    $myfile = fopen($file_name['file_name'], "r") or die("Unable to open file!");
    while (!feof($myfile)) {
        $userid = trim(fgets($myfile));
        $datas = $getTiktokUser->details('@' . $userid);
        $datatmp = json_decode($datas, true);
        $tichxanh = $datatmp["user"]["verified"] ? "Có" : "Không";
        $strtmp = "link : https://www.tiktok.com/@" . $datatmp["user"]["profileName"] . " ;\nusername : " . $datatmp["user"]["username"] . " ;\nuser Id : " . $datatmp["user"]["profileName"] . " ;\nvị trí : " . $datatmp["user"]["region"] . " ;\ntích xanh : " . $tichxanh . " ;\nfollowing : " . chuyenDoiSo($datatmp["stats"]["following"]) . " ;\nfollower : " . chuyenDoiSo($datatmp["stats"]["follower"]) . " ;\nvideo : " . $datatmp["stats"]["video"] . " ;\nlike : " . chuyenDoiSo($datatmp["stats"]["like"]);
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
if (strpos($message, "/getfile") === 0) {
    $datapost = array(
        "chat_id" => $chatId,
        "text" => "tên file : " . $file_name['file_name']
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
if (strpos($message, "/start") === 0) {
    $datapost = array(
        "chat_id" => $chatId,
        "text" => "id Tele :" . $chatId
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