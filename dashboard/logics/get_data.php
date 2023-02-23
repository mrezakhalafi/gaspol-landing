<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$province = $_POST['province'];

try {

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE PROVINCE = '".$province."'");
    $query->execute();
    $ktaData = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = '".$province."'");
    $query->execute();
    $tktData = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE PROVINCE = '".$province."' AND STATUS_ANGGOTA = 0");
    $query->execute();
    $ktaDataMob = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM KTA WHERE PROVINCE = '".$province."' AND STATUS_ANGGOTA = 1");
    $query->execute();
    $ktaDataPro = $query->get_result();
    $query->close();

    // IF DATA EXIST RETURN DATA

    $kta = mysqli_num_rows($ktaData);
    $tkt = mysqli_num_rows($tktData);
    $ktaMob = mysqli_num_rows($ktaDataMob);
    $ktaPro = mysqli_num_rows($ktaDataPro);

    if (isset($kta) && isset($tkt) && isset($ktaMob) && isset($ktaPro)){
        echo($kta."|".$tkt."|".$ktaMob."|".$ktaPro);
    }else{
        echo(0);
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}


