<?php

// HIT THIS PAGE TO GET USER STATUS STATUS

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = newnus();
$qr_code = $_GET['qr_code'];
session_start();

// STATUS
$query = $dbconn->prepare("SELECT * FROM WEB_LOGIN WHERE QR_CODE = ?");
$query->bind_param("s", $qr_code);
$query->execute();
$status = $query->get_result()->fetch_assoc();
$query->close();

if ($status){
    $_SESSION['is_scanned'] = $status['QR_CODE'];
    $_SESSION['f_pin'] = $status['F_PIN'];
}

// send user data as json
echo json_encode($status);
