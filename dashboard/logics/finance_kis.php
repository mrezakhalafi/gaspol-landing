<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$province = $_POST['province'];

try {

    $query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU = REGISTRATION_PAYMENT.REF_ID WHERE REGISTRATION_PAYMENT.REG_TYPE = 8 AND KIS.PROVINCE = '".$province."' ORDER BY REGISTRATION_PAYMENT.DATE DESC");
    $query->execute();
    $kis = $query->get_result();
    $query->close();

    $rows = [];
    while ($row = $kis->fetch_assoc()){
        $rows[] = $row;
    }


    // IF DATA EXIST RETURN DATA


    if (isset($rows)){
        echo(json_encode($rows));
    }else{
        echo(0);
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}


