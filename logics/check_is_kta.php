<?php

// HIT THIS PAGE TO GET USER STATUS STATUS

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$f_pin = $_POST['f_pin'];
session_start();

// STATUS
$query = $dbconn->prepare("SELECT * FROM KTA WHERE F_PIN = '".$f_pin."'");
$query->execute();
$ktaData = $query->get_result()->fetch_assoc();
$query->close();

if ($ktaData['STATUS_ANGGOTA'] == 0 || $ktaData['STATUS_ANGGOTA'] == 1){
    echo(json_encode($ktaData));
}else{
    echo(0);
}

