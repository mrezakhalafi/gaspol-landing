<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
session_start();

// print_r($_SESSION['web_login']);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconnNewNus = newnus();

$f_pin = $_SESSION["F_PIN"];
$qr_login = $_SESSION["web_login"];

$query = $dbconnNewNus->prepare("SELECT COUNT(*) AS CNT FROM WEB_LOGIN WHERE F_PIN = '$f_pin' AND QR_CODE = '$qr_login'");
$query->execute();
$status = $query->get_result()->fetch_assoc();
$query->close();

if ($status["CNT"] == 0) {
    $_SESSION['web_login'] = null;
    $_SESSION['is_scanned'] = null;
    $_SESSION['f_pin'] = null;
    $_SESSION['F_PIN'] = null;
    $_SESSION['ADMIN_PROVINCE'] = null;

    header("Location: http://108.136.138.242/gaspol_web/pages/gaspol-landing/");
    die();
}