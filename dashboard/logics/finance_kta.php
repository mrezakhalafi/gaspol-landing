<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$province = $_POST['province'];
$type = $_POST['type'];

try {

    if($type == 0){

        $query = $dbconn->prepare("
        SELECT 
        * 
        FROM 
        KTA 
        LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA = REGISTRATION_PAYMENT.REF_ID 
        WHERE 
        REGISTRATION_PAYMENT.REG_TYPE = 2 
        AND KTA.NO_ANGGOTA IS NOT NULL 
        AND KTA.STATUS_ANGGOTA = 0 
        AND KTA.PROVINCE = '".$province."' 
        ORDER BY 
        REGISTRATION_PAYMENT.DATE DESC");

        $query->execute();
        $ktaDataMob = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $ktaDataMob->fetch_assoc()){
            $rows[] = $row;
        }


    }else if($type == 1){

        $query = $dbconn->prepare("SELECT * FROM KTA LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA = REGISTRATION_PAYMENT.REF_ID WHERE REGISTRATION_PAYMENT.REG_TYPE = 3 AND KTA.NO_ANGGOTA IS NOT NULL AND KTA.STATUS_ANGGOTA = 1 AND KTA.PROVINCE = '".$province."' ORDER BY REGISTRATION_PAYMENT.DATE DESC");
        $query->execute();
        $ktaDataPro = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $ktaDataPro->fetch_assoc()){
            $rows[] = $row;
        }
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


