<?php
include "getdata.php";
include "conn.php";
$filePath = "user.json";
$token = "6872045298:AAFD3ijj_yWjDt2pkEhPDDAYzySKSOtJ-QI";
$idteleAdmin = 5824366283;

$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$query = "SELECT name_file FROM `file` WHERE user = '" . $chatId . "'";

$file_name = mysqli_fetch_all($conn->query($query), MYSQLI_ASSOC);
function insertStorage($filePath, $chatId, $userData)
{
    if (file_exists($filePath)) {
        // Read existing JSON content
        $existingData = file_get_contents($filePath);

        // Decode JSON to PHP array
        $existingArray = json_decode($existingData, true);
        $chatIdExists = false;
        foreach ($existingArray as $item) {
            if ($item['chatid'] == $chatId) {
                $chatIdExists = true;
                break;
            }
        }

        // Nếu 'chatid' không tồn tại, thêm mảng mới vào mảng hiện tại
        if (!$chatIdExists) {
            // Add new data to the existing array
            $existingArray[] = $userData;

            // Encode the updated array back to JSON
            $updatedData = json_encode($existingArray);

            // Write the updated JSON content back to the file
            file_put_contents($filePath, $updatedData);
        }

    } else {
        echo 'Error: JSON file not found.';
    }
}

function updateJsonByChatId($filePath, $targetChatId, $updatedData)
{
    // Kiểm tra xem tệp có tồn tại không
    if (file_exists($filePath)) {
        // Đọc nội dung JSON
        $jsonData = file_get_contents($filePath);

        // Giải mã JSON thành mảng PHP
        $dataArray = json_decode($jsonData, true);

        // Tìm kiếm theo chatid
        foreach ($dataArray as &$userData) {
            if ($userData['chatid'] == $targetChatId) {
                // Cập nhật dữ liệu
                $userData = array_merge($userData, $updatedData);
            }
        }

        // Mã hóa mảng đã cập nhật thành JSON
        $updatedJsonData = json_encode($dataArray, JSON_PRETTY_PRINT);

        // Ghi nội dung JSON đã cập nhật trở lại tệp
        file_put_contents($filePath, $updatedJsonData);

        return true;
    } else {
        return false;
    }
}

function searchByChatId($filePath, $targetChatId)
{
    // Kiểm tra xem tệp có tồn tại không
    if (file_exists($filePath)) {
        // Đọc nội dung JSON
        $jsonData = file_get_contents($filePath);

        // Giải mã JSON thành mảng PHP
        $dataArray = json_decode($jsonData, true);

        // Tìm kiếm theo chatid
        $foundUserData = array_filter($dataArray, function ($userData) use ($targetChatId) {
            return $userData['chatid'] == $targetChatId;
        });

        // Lấy giá trị đầu tiên của mảng kết quả
        $firstResult = reset($foundUserData);

        // Kiểm tra xem có dữ liệu tìm thấy không
        if ($firstResult !== false) {
            return $firstResult;
        } else {
            return null;
        }
    } else {
        return null;
    }
}
function getuserid($url)
{
    $pos = strpos($url, '@');

    if ($pos !== false) {
        // Lấy phần tên người dùng sau ký tự "@"
        $userName = substr($url, $pos + 1);

        return $userName;
    } else {
        return "khongdiashdasda";
    }
}
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

function changeStatus($status)
{
    $datachange = array(
        'chatid' => $GLOBALS['chatId'],
        "status" => $status
    );
    updateJsonByChatId($GLOBALS['filePath'], $GLOBALS['chatId'], $datachange);
}

if (strpos($message, "/start") === 0) {
    $userData = array(
        'chatid' => $chatId,
        "status" => ""
    );
    insertStorage($filePath, $chatId, $userData);

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

} elseif (strpos($message, "/getfile") === 0) {
    $arr = '';
    foreach ($file_name as $key) {
        $arr = $arr . $key['name_file'] . " , ";
    }
    $datapost = array(
        "chat_id" => $chatId,
        "text" => "tên file : " . $arr
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
    changeStatus("1");
} elseif (strpos($message, "/laydata") === 0) {
    $qrban = "select ban from `file_user` where user_id='" . $chatId . "'";
    if (mysqli_fetch_array($conn->query($qrban))['ban'] == 1) {
        $datapost = array(
            "chat_id" => $chatId,
            "text" => "bạn bị ban ,liên hệ admin để biết thêm thông tin chi tiết"
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
    } else {
        $response = "Nhập file txt cần lấy thông tin";
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
        changeStatus("waitForInput");
    }
} elseif (strpos($message, "/layidtele") === 0) {
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
    changeStatus("1");
}
if (searchByChatId($filePath, $chatId)['status'] == "waitForInput") {
    if ($chatId == $idteleAdmin) {
        $fdata = array();
        $myfile = fopen($message, "r") or die("Unable to open file!");
        while (!feof($myfile)) {
            $userid = trim(fgets($myfile));
            $datas = $getTiktokUser->details('@' . getuserid($userid));
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
        changeStatus("1");
    } else {
        $authen = false;
        foreach ($file_name as $key) {
            if ($key['name_file'] == $message) {
                $authen = true;
            }
        }

        if ($authen) {
            $fdata = array();
            $file_data = fopen($message, "r") or die("Unable to open file!");
            while (!feof($file_data)) {
                $userid = trim(fgets($file_data));
                $datas = $getTiktokUser->details('@' . getuserid($userid));
                $datatmp = json_decode($datas, true);
                $tichxanh = $datatmp["user"]["verified"] ? "Có" : "Không";
                $strtmp = "link : https://www.tiktok.com/@" . $datatmp["user"]["profileName"] . " ;\nusername : " . $datatmp["user"]["username"] . " ;\nuser Id : " . $datatmp["user"]["profileName"] . " ;\nvị trí : " . $datatmp["user"]["region"] . " ;\ntích xanh : " . $tichxanh . " ;\nfollowing : " . chuyenDoiSo($datatmp["stats"]["following"]) . " ;\nfollower : " . chuyenDoiSo($datatmp["stats"]["follower"]) . " ;\nvideo : " . $datatmp["stats"]["video"] . " ;\nlike : " . chuyenDoiSo($datatmp["stats"]["like"]);
                array_push($fdata, $strtmp);
            }
            fclose($file_data);
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
                changeStatus("1");
            }
        }

    }

}
?>