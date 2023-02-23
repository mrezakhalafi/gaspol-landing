<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['user_f_pin'])){
//     $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
// $f_pin = $_POST['f_pin'];
// }

// $uploadOk = 1;

// POST

$f_pins = $_POST['kta_fpin'];
$no_kta = $_POST['no_kta'];
$count_number = $_POST['count_button'];

$count_number = $count_number + 1;


// GET KTA
$query = $dbconn->prepare("SELECT KTA.*, TKT.*, PROVINCE.*, DOWNLOAD_CARD.*, KTA.F_PIN AS K_PIN FROM KTA LEFT JOIN TKT ON KTA.F_PIN = TKT.F_PIN LEFT JOIN PROVINCE ON PROVINCE.PROV_ID = KTA.PROVINCE LEFT JOIN DOWNLOAD_CARD ON KTA.F_PIN = DOWNLOAD_CARD.F_PIN WHERE DOWNLOAD_CARD.F_PIN = '$f_pins'");
$query->execute();
$ktatkt = $query->get_result()->fetch_assoc();
$query->close();

// END POST

    if (isset($ktatkt['TOTAL_DOWNLOAD'])) {
        $queryDownloadCard = "UPDATE DOWNLOAD_CARD SET TOTAL_DOWNLOAD = '$count_number' WHERE F_PIN = '$f_pins'";
    }
    else {
        $queryDownloadCard = "INSERT INTO DOWNLOAD_CARD (F_PIN, NO_KTA, TOTAL_DOWNLOAD) VALUES ('".$f_pins."', ".$no_kta.", '1')";
    }

    if (mysqli_query($dbconn, $queryDownloadCard)) {
        echo("Koneksi Database Berhasil");
    }
    else {
        echo($queryDownloadCard);
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }

?>