<?php
include "getdata.php";
$datas = $getTiktokUser->details('@tuan_2kk4');
$datatmp = json_decode($datas, true);
echo $datatmp["code"];
?>