<?php

$myfile = fopen("data.txt", "r") or die("Unable to open file!");
if (isset($_POST['up']) && isset($_FILES['data'])) {
    move_uploaded_file($_FILES['data']['tmp_name'], $_FILES['data']['name']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="data">
        <button type="submit" name="up">Ok</button>
    </form>
    <br />
    <?php include "getdata.php";
    $myfile = fopen("data.txt", "r") or die("Unable to open file!");

    while (!feof($myfile)) {
        $userid = trim(fgets($myfile));
        echo $userid . "<br/>";
        echo $getTiktokUser->details('@' . $userid);
        echo "<hr/>";
    }
    fclose($myfile); ?>
</body>

</html>