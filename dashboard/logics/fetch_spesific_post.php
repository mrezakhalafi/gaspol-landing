<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}

// $query = $dbconn->prepare("SELECT s.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID AS SHOP_THUMB_ID FROM POST p LEFT JOIN SHOP s ON p.MERCHANT = s.CODE WHERE p.POST_ID='$product_code'");
$sql = "SELECT CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS NAME, p.DESCRIPTION, p.CREATED_DATE, ul.IMAGE AS THUMB_ID FROM POST p LEFT JOIN USER_LIST ul ON p.F_PIN = ul.F_PIN WHERE p.POST_ID='$product_code'";
// $query = $dbconn->prepare("SELECT p.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID as SHOP_THUMB_ID FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.CODE='$product_code'");
$query = $dbconn->prepare($sql);
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
