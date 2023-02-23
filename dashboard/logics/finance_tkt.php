<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$province = $_POST['province'];

try {

    $query = $dbconn->prepare("SELECT * FROM TKT LEFT JOIN REGISTRATION_PAYMENT ON TKT.F_PIN = REGISTRATION_PAYMENT.F_PIN WHERE REGISTRATION_PAYMENT.REG_TYPE = 6 AND TKT.PROVINCE = '".$province."' GROUP BY TKT.CLUB_UID ORDER BY REGISTRATION_PAYMENT.DATE DESC");
    $query->execute();
    $tkt = $query->get_result();
    $query->close();

    $rows = [];
    while ($row = $tkt->fetch_assoc()){
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


