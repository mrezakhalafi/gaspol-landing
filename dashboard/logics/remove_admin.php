<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];

$queryPost = "DELETE FROM ADMIN_PROVINCE WHERE F_PIN = '".$f_pin."'";

if (mysqli_query($dbconn, $queryPost)){
    echo(1);
} else{
    echo(0);
}

?>