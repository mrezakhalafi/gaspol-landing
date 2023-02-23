<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['user_f_pin'])){
    $f_pin = $_SESSION['user_f_pin'];
}
else if(isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
}

// FILE PATH DOCUMENTS

$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/documents/';
$target_files = array();

// DOCUMENT AD / ART
$adartdocs = strtolower(pathinfo($_FILES["docAdart"]["name"],PATHINFO_EXTENSION));
$dokadart = "EAD-ART-".$f_pin . time() . "." . $adartdocs;
$target_files['docAdart'] = $target_dir . $dokadart;

// CERTIFICATE
$certificate = strtolower(pathinfo($_FILES["docCertificate"]["name"],PATHINFO_EXTENSION));
$dokcertificate = "Crtfct-".$f_pin . time() . "." . $certificate;
$target_files['docCertificate'] = $target_dir . $dokcertificate;

// ADDITIONAL DOCUMENT
$additional = strtolower(pathinfo($_FILES["docAdditional"]["name"],PATHINFO_EXTENSION));
$dokAdditional = "Add-".$f_pin . time() . "." . $additional;
$target_files['docAdditional'] = $target_dir . $dokAdditional;

// END OF FILE PATH DOCUMENT

// POST
$ass_name = $_POST['ass_name'];
$category = $_POST['category'];
$description = $_POST['desc'];

$adart = $_POST['docAdart'];
$certificate = $_POST['docCertificate'];
$additional = $_POST['docAdditional'];

$address = $_POST['address'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$postcode = $_POST['postcode'];
$province = $_POST['province'];
$city = $_POST['city'];
$district = $_POST['district'];
$subdistrict = $_POST['subdistrict'];

$bank = $_POST['bank-category'];
$acc_number = $_POST['acc-number'];
$acc_name = $_POST['acc-name'];

$president = $_POST['president'];
$secretary = $_POST['secretary'];
$clubadmin = $_POST['club-admin'];
$finance = $_POST['finance'];
$vicepresident = $_POST['vice-president'];
$hrd = $_POST['human-resource'];

$f_club = $_POST['club-category'];
$s_club = $_POST['club-category-2'];
$t_club = $_POST['club-category-3'];
$fth_club = $_POST['club-category-4'];
$fvth_club = $_POST['club-category-5'];
// END POST

// POST QUERY
$postquery = "INSERT INTO TAA(F_PIN, ASS_NAME, ASS_CATEGORY, ASS_DESC, DOCUMENT, CERTIFICATE, ADD_DOCS, ADDRESS, RTRW, POST_CODE, PROVINCE, CITY, DISTRICT, SUBDISTRICT, BANK, ACC_NUMBER, ACC_NAME, PRESIDENT, SECRETARY, CLUB_ADMIN, FINANCE, VICE_PRESIDENT, HUMAN_RESOURCE, CLUB_1, CLUB_2, CLUB_3, CLUB_4, CLUB_5) VALUES ('".$f_pin."', '".$ass_name."', '".$category."', '".$description."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."', '".$secretary."', '".$clubadmin."', '".$finance."', '".$vicepresident."', '".$hrd."', '".$f_club."', '".$s_club."', '".$t_club."', '".$fth_club."', '".$fvth_club."')";

if (mysqli_query($dbconn, $postquery)) {
    echo("Berhasil");
}
else {
    echo("Koneksi Gagal");
    http_response_code(400);
}
// END QUERY
?>