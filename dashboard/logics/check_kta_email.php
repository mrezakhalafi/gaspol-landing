<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$email = $_POST['email'];

try {

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE EMAIL = '$email'");
    $query->execute();
    $data = $query->get_result()->fetch_assoc();
    $query->close();

    // IF DATA EXIST RETURN DATA

    if (isset($data)){
        echo("Ada");
    }else{
        echo("Tidak ada");
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    