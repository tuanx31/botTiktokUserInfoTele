<?php
// $datapost = array(
//     "chat_id" => "5824366283",
//     "text" => "aaa"
// );
// $options = [
//     'http' => [
//         'header' => "Content-type: application/x-www-form-urlencoded\r\n",
//         'method' => 'POST',
//         'content' => http_build_query($datapost),
//     ],
// ];
// $context = stream_context_create($options);
// $url = "https://api.telegram.org/bot6420974422:AAFPJJ6eTlOm-85P1sY47GhOhy_AjX6DxQI/sendMessage";
// $result = file_get_contents($url, false, $context);
?>
<?php
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

$chatId = 6787246904;
$filePath = "user.json";
function changeStatus($status)
{
    $datachange = array(
        'chatid' => $GLOBALS['chatId'],
        "status" => $status
    );
    updateJsonByChatId($GLOBALS['filePath'], $GLOBALS['chatId'], $datachange);
}

echo searchByChatId($filePath, $chatId)['status'];
include "conn.php";
$query = "SELECT name_file FROM `file` WHERE user = '" . $chatId . "'";
$result = mysqli_fetch_all($conn->query($query), MYSQLI_ASSOC);
$arr = '';
echo "<br/>";
$s = 'b.txt';
foreach ($result as $key) {
    echo $key['name_file'];
    if ($s == $key['name_file']) {
        echo "ok";
    }
}
?>