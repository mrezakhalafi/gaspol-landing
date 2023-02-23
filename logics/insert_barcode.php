<?php

// WHEN USER OPEN THIS PAGE, CREATE QR CODE
// IF USER SCANS THE QR CODE, CHECK USER STATUS AND REDIRECT TO CHAT_PAGE

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

session_start();
$dbconn = newnus();

// GENERATE NEW RECORD IN WEB_LOGIN
$query = $dbconn->prepare("INSERT INTO WEB_LOGIN (QR_CODE) VALUES (MD5(RAND()))");
$status = $query->execute();
while( false===$status ){
    $query = $dbconn->prepare("INSERT INTO WEB_LOGIN (QR_CODE) VALUES (MD5(RAND()))");
    $status = $query->execute();
}
$id_qr = $query->insert_id;
$query->close();

$query = $dbconn->prepare("SELECT * FROM WEB_LOGIN WHERE ID = ?");
$query->bind_param("i", $id_qr);
$query->execute();
$qr = $query->get_result()->fetch_assoc();

$_SESSION['web_login'] = $qr['QR_CODE'];
$query->close();

echo($_SESSION['web_login']);