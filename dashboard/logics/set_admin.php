<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$admin_type = $_POST['admin_type'];
$province = $_POST['province'];

// UPDATE PRICING

if($admin_type == 0){
    $province = 0;
}

$queryPost = "INSERT INTO ADMIN_PROVINCE (F_PIN, PROVINCE_ID, BE_ID) VALUES ('".$f_pin."','".$province."','282')";

if (mysqli_query($dbconn, $queryPost)){
    echo(1);
} else{
    echo(0);
}

?>