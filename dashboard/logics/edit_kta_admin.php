<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

$f_pin = $_POST['f_pin'];
$name = $_POST['name'];
$email = $_POST['email'];
$bloodtype = $_POST['bloodtype'];
$birthplace = $_POST['birthplace'];
$gender = $_POST['gender'];
$hobby = $_POST['hobby'];
$nationality = $_POST['nationality'];
$datebirth = $_POST['datebirth'];
$ektp = $_POST['ektp'];

// UPDATE KTA ADMIN

$query = "UPDATE KTA SET NAME = '".$name."', EMAIL = '".$email."', BLOODTYPE = '".$bloodtype."', BIRTHPLACE = '".$birthplace."', GENDER = '".$gender."'
, HOBBY = '".$hobby."', NATIONALITY = '".$nationality."', DATEBIRTH = '".$datebirth."', EKTP = '".$ektp."' WHERE F_PIN = '".$f_pin."'";

if (mysqli_query($dbconn, $query)){
    echo(1);
}else{
    echo(0);
}

?>