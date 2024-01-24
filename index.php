<?php
include "conn.php";
if (isset($_POST['up']) && isset($_POST['idtele']) && isset($_FILES['data'])) {
    $id = $_POST['idtele'];
    $sql = "select * from file_user where user_id ='" . $id . "'";
    $sql2 = "select * from file_user where file_name ='" . $_FILES['data']['name'] . "'";
    $results = $conn->query($sql);

    if (mysqli_num_rows($results) > 0) {
        $qr = "select * from file_user where user_id ='" . $id . "'and file_name = '" . $_FILES['data']['name'] . "'";
        if (mysqli_num_rows($conn->query($qr))) {
            move_uploaded_file($_FILES['data']['tmp_name'], $_FILES['data']['name']);
            $querys = "UPDATE `file_user` SET `file_name` = '" . $_FILES['data']['name'] . "' WHERE `file_user`.`user_id` = '" . $id . "'";
            $conn->query($querys);
            $conn->error;
            echo "<p class = 'text-center mt-2'>update file thành công</p>";
        } else {
            echo "<p class = 'text-center mt-2'>vui lòng đổi tên file thành <span class ='text-danger'>" . mysqli_fetch_array($results)["file_name"] . "</span> rồi up lại</p>";
        }
    } else {
        if (mysqli_num_rows($conn->query($sql2)) > 0) {
            echo "<p class = 'text-center mt-2'>tên file đã có người sử dụng , vui lòng đổi tên file rồi up lại</p>";
        } else {
            $querys = "INSERT INTO `file_user`( `user_id`, `file_name`) VALUES ('" . $id . "','" . $_FILES['data']['name'] . "')";
            $conn->query($querys);
            $conn->error;
            echo "<p class = 'text-center mt-2'>tạo thành công . id tele : " . $id . "; file name : " . $_FILES['data']['name'] . "</p>";
            move_uploaded_file($_FILES['data']['tmp_name'], $_FILES['data']['name']);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>doucumnet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="d-flex justify-content-center align-item-center w-100" style="height: 100vh;">
        <div class="mt-3" style="height: 300px;">
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <input class="form-control" type="text" placeholder="id Telegram" name="idtele" required>
                <br />
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="data" id="inputGroupFile02">
                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                </div>
                <button class="btn btn-primary" type="submit" name="up">Ok</button>
            </form>
        </div>
    </div>

</body>

</html>