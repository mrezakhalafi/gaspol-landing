<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

$desc = $_POST['desc'];
$name = $_POST['name'];
// $type = $_POST['type'];
$category = $_POST['category'];
$link = $_POST['link'];
$id = $_POST['id'];

// UPDATE KTA ADMIN

$query = "UPDATE TKT SET CLUB_NAME = '".$name."', CLUB_DESC = '".$desc."', CLUB_LINK = '".$link."', CLUB_CATEGORY = ".$category." WHERE ID = ".$id;

if (mysqli_query($dbconn, $query)){
    echo(1);
}else{
    echo(0);
}

?>