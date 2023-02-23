<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$number = $_POST['number'];
$email = $_POST['email'];
$ektp = $_POST['ektp'];

try {

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE EMAIL = '$email'");
    $query->execute();
    $email = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE EKTP = '$ektp'");
    $query->execute();
    $ektp = $query->get_result();
    $query->close();

    if (mysqli_num_rows($email) > 0){
        echo("1|".$number);    // Email Exist
    }else if(mysqli_num_rows($ektp) > 0){
        echo("2|".$number);    // EKTP Exist
    }else{
        echo("0|".$number);
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}


