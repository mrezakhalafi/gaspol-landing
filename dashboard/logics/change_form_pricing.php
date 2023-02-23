<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$type = $_POST['type'];
$price = $_POST['price'];
$prov_id = $_POST['prov_id'];

// UPDATE PRICING

if ($type == 1){
    $query = "UPDATE PROVINCE SET KIS_PRICE = '".$price."' WHERE PROV_ID = '".$prov_id."'";
}else{
    $query = "UPDATE REGISTRATION_TYPE SET REG_FEE = '".$price."' WHERE REG_ID = '".$type."'";
}

if (mysqli_query($dbconn, $query)){
    echo(1);
}else{
    echo(0);
}

?>