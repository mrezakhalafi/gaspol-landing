<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_group = $_SESSION['id_group'];
$name_group = $_SESSION['name_group'];

// UPDATE GROUP

$query = "UPDATE TKT SET GROUP_ID = '".$id_group."' WHERE CLUB_NAME = '".$name_group."'";

if (mysqli_query($dbconn, $query)){
    echo(1);
}else{
    echo(0);
}

?>