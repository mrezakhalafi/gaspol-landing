<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$month = json_decode($_POST['month']);
$year = json_decode($_POST['year']);
$day = json_decode($_POST['day']);

$arrayMobility = [];
$arrayPro = [];

try {

    for($i = 0; $i < count($month); $i++){

        $query = $dbconn->prepare("SELECT * FROM KTA WHERE STATUS_ANGGOTA = 0 AND CREATED_DATE LIKE '%".$year[$i]."-".$month[$i]."-".$day[$i]."%'");
        $query->execute();
        $ktaMData = $query->get_result();
        $query->close();

        if (isset($ktaMData)){
            array_push($arrayMobility, mysqli_num_rows($ktaMData));
        }

        $query = $dbconn->prepare("SELECT * FROM KTA WHERE STATUS_ANGGOTA = 1 AND CREATED_DATE LIKE '%".$year[$i]."-".$month[$i]."-".$day[$i]."%'");
        $query->execute();
        $ktaPData = $query->get_result();
        $query->close();

        if (isset($ktaMData)){
            array_push($arrayPro, mysqli_num_rows($ktaPData));
        }

        if ($i == 29){

            echo(json_encode($arrayMobility)."|".json_encode($arrayPro));

        }

    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    

