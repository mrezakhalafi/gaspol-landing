<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['user_f_pin'])){
//     $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
// }

// FOR UID

function getRandomString($n) {
    $characters = '0123456789';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
  }

$province = $_POST['province'];

$sql = "SELECT PROV_ID FROM `PROVINCE` WHERE `PROV_ID` = '".$province."'";

$query = $dbconn->prepare($sql);
$query->execute();
$province_id = $query->get_result();
$province_id = $province_id->fetch_assoc()['PROV_ID'];
$query->close();
$unq = false;
do{
  $uid = str_pad($province_id, 2, "0", STR_PAD_LEFT).getRandomString(14);
  $sql = "SELECT * FROM `TKT` WHERE `CLUB_UID` = '".$uid."'";
  $query = $dbconn->prepare($sql);
  $query->execute();
  $sql_uid = $query->get_result()->fetch_assoc();
  if(is_null($sql_uid)){
    $unq = true;
  }
} while(!$unq);

// FILE PATH DOCUMENTS

$uploadOk = 1;
$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/documents/';
$target_files = array();

// $uploadOk = 1;
$target_direct = $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/images/';
$target_file_loc = array();

// PP IMAGE
$image = strtolower(pathinfo($_FILES["fotoProfile"]["name"],PATHINFO_EXTENSION));
$ppimage = "PPIMICLUB-".$f_pin . time() . ".webp";
$target_file_loc['fotoProfile'] = $target_direct . $ppimage;

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
// $profile_image = $_POST['fotoProfile'];
$club_name = $_POST['club_name'];
$club_type = $_POST['clubtype_radio'];
$category = $_POST['category'];
$club_location = $_POST['club_location'];
$description = $_POST['desc'];

$address = $_POST['address'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$postcode = $_POST['postcode'];
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

// TURN ON WHILE ACC

// if(isset($_POST['president_phone'])){
//     $president_phone = $_POST['president_phone'];
// }else{
//     $president_phone = "";
// }

// if(isset($_POST['president_ktp'])){
//     $president_ktp = $_POST['president_ktp'];
// }else{
//     $president_ktp = "";
// }

// if(isset($_POST['president_kta'])){
//     $president_kta = $_POST['president_kta'];
// }else{
//     $president_kta = "";
// }

// if(isset($_POST['secretary_phone'])){
//     $secretary_phone = $_POST['secretary_phone'];
// }else{
//     $secretary_phone = "";
// }

// if(isset($_POST['secretary_ktp'])){
//     $secretary_ktp = $_POST['secretary_ktp'];
// }else{
//     $secretary_ktp = "";
// }

// if(isset($_POST['secretary_kta'])){
//     $secretary_kta = $_POST['secretary_kta'];
// }else{
//     $secretary_kta = "";
// }

// if(isset($_POST['admin_phone'])){
//     $admin_phone = $_POST['admin_phone'];
// }else{
//     $admin_phone = "";
// }

// if(isset($_POST['admin_ktp'])){
//     $admin_ktp = $_POST['admin_ktp'];
// }else{
//     $admin_ktp = "";
// }

// if(isset($_POST['admin_kta'])){
//     $admin_kta = $_POST['admin_kta'];
// }else{
//     $admin_kta = "";
// }

// if(isset($_POST['finance_phone'])){
//     $finance_phone = $_POST['finance_phone'];
// }else{
//     $finance_phone = "";
// }

// if(isset($_POST['finance_ktp'])){
//     $finance_ktp = $_POST['finance_ktp'];
// }else{
//     $finance_ktp = "";
// }

// if(isset($_POST['finance_kta'])){
//     $finance_kta = $_POST['finance_kta'];
// }else{
//     $finance_kta = "";
// }

// if(isset($_POST['vice_president_phone'])){
//     $vice_president_phone = $_POST['vice_president_phone'];
// }else{
//     $vice_president_phone = "";
// }

// if(isset($_POST['vice_president_ktp'])){
//     $vice_president_ktp = $_POST['vice_president_ktp'];
// }else{
//     $vice_president_ktp = "";
// }

// if(isset($_POST['vice_president_kta'])){
//     $vice_president_kta = $_POST['vice_president_kta'];
// }else{
//     $vice_president_kta = "";
// }

// if(isset($_POST['human_resource_phone'])){
//     $human_resource_phone = $_POST['human_resource_phone'];
// }else{
//     $human_resource_phone = "";
// }

// if(isset($_POST['human_resource_ktp'])){
//     $human_resource_ktp = $_POST['human_resource_ktp'];
// }else{
//     $human_resource_ktp = "";
// }

// if(isset($_POST['human_resource_kta'])){
//     $human_resource_kta = $_POST['human_resource_kta'];
// }else{
//     $human_resource_kta = "";
// }

$president_phone = $_POST['president_phone'];
$president_ktp = $_POST['president_ktp'];
$president_kta = $_POST['president_kta'];

$secretary_phone = $_POST['secretary_phone'];
$secretary_ktp = $_POST['secretary_ktp'];
$secretary_kta = $_POST['secretary_kta'];

$admin_phone = $_POST['admin_phone'];
$admin_ktp = $_POST['admin_ktp'];
$admin_kta = $_POST['admin_kta'];

$finance_phone = $_POST['finance_phone'];
$finance_ktp = $_POST['finance_ktp'];
$finance_kta = $_POST['finance_kta'];

$vice_president_phone = $_POST['vice_president_phone'];
$vice_president_ktp = $_POST['vice_president_ktp'];
$vice_president_kta = $_POST['vice_president_kta'];

$human_resource_phone = $_POST['human_resource_phone'];
$human_resource_ktp = $_POST['human_resource_ktp'];
$human_resource_kta = $_POST['human_resource_kta'];
$is_android = $_POST['is_android'];

// END POST

$start_upload = true;

$start_upload = move_uploaded_file($_FILES["fotoProfile"]["tmp_name"], $target_file_loc["fotoProfile"]);
$start_upload = move_uploaded_file($_FILES["docAdart"]["tmp_name"], $target_files["docAdart"]);
$start_upload = move_uploaded_file($_FILES["docCertificate"]["tmp_name"], $target_files["docCertificate"]);
$start_upload = move_uploaded_file($_FILES["docAdditional"]["tmp_name"], $target_files["docAdditional"]);

if($start_upload){
    // POST QUERY
    // $postquery = "INSERT INTO TKT (F_PIN, PROFILE_IMAGE, CLUB_NAME, CLUB_TYPE, CLUB_CATEGORY, CLUB_LOCATION, CLUB_UID, CLUB_DESC, ADDRESS, RTRW, POSTCODE, PROVINCE, CITY, DISTRICT, SUBDISTRICT, BANK, BANK_NUMBER, BANK_NAME, KETUA, SEKRETARIS, ADMIN, BENDAHARA, WAKIL, HRD, AD_ART, CERTIFICATE, ADDITIONAL) VALUES ('".$f_pin."', '".$ppimage."', '".$club_name."', '".$club_type."', '".$category."', '".$club_location."', '".$uid."','".$description."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."', '".$secretary."', '".$clubadmin."', '".$finance."', '".$vicepresident."', '".$hrd."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."')";

    if ($f_pin != "") {
        $postquery = "INSERT INTO TKT (F_PIN, PROFILE_IMAGE, CLUB_NAME, CLUB_TYPE, CLUB_CATEGORY, CLUB_LOCATION, CLUB_UID, CLUB_DESC, ADDRESS, RTRW, POSTCODE, PROVINCE, CITY, 
        DISTRICT, SUBDISTRICT, BANK, BANK_NUMBER, BANK_NAME, KETUA, SEKRETARIS, ADMIN, BENDAHARA, WAKIL, HRD, AD_ART, CERTIFICATE, ADDITIONAL, PRESIDENT_PHONE, PRESIDENT_KTP,
        PRESIDENT_KTA, SECRETARY_PHONE, SECRETARY_KTP, SECRETARY_KTA, ADMIN_PHONE, ADMIN_KTP, ADMIN_KTA, FINANCE_PHONE, FINANCE_KTP, FINANCE_KTA, VICE_PRESIDENT_PHONE, 
        VICE_PRESIDENT_KTP, VICE_PRESIDENT_KTA, HUMAN_RESOURCE_PHONE, HUMAN_RESOURCE_KTP, HUMAN_RESOURCE_KTA, IS_ANDROID) VALUES ('".$f_pin."', '".$ppimage."', '".$club_name."', 
        '".$club_type."', '".$category."', '".$club_location."', '".$uid."','".$description."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', 
        '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."', '".$secretary."', '".$clubadmin."', '".$finance."', 
        '".$vicepresident."', '".$hrd."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$president_phone."', '".$president_ktp."', '".$president_kta."', 
        '".$secretary_phone."', '".$secretary_ktp."', '".$secretary_kta."', '".$admin_phone."', '".$admin_ktp."', '".$admin_kta."', '".$finance_phone."', '".$finance_ktp."', 
        '".$finance_kta."', '".$vice_president_phone."', '".$vice_president_ktp."', '".$vice_president_kta."', '".$human_resource_phone."', '".$human_resource_ktp."', '".$human_resource_kta."', ".$is_android.")";
    }
    else {
        $postquery = "INSERT INTO TKT (PROFILE_IMAGE, CLUB_NAME, CLUB_TYPE, CLUB_CATEGORY, CLUB_LOCATION, CLUB_UID, CLUB_DESC, ADDRESS, RTRW, POSTCODE, PROVINCE, CITY, 
        DISTRICT, SUBDISTRICT, BANK, BANK_NUMBER, BANK_NAME, KETUA, SEKRETARIS, ADMIN, BENDAHARA, WAKIL, HRD, AD_ART, CERTIFICATE, ADDITIONAL, PRESIDENT_PHONE, PRESIDENT_KTP,
        PRESIDENT_KTA, SECRETARY_PHONE, SECRETARY_KTP, SECRETARY_KTA, ADMIN_PHONE, ADMIN_KTP, ADMIN_KTA, FINANCE_PHONE, FINANCE_KTP, FINANCE_KTA, VICE_PRESIDENT_PHONE, 
        VICE_PRESIDENT_KTP, VICE_PRESIDENT_KTA, HUMAN_RESOURCE_PHONE, HUMAN_RESOURCE_KTP, HUMAN_RESOURCE_KTA, IS_ANDROID) VALUES ('".$ppimage."', '".$club_name."', 
        '".$club_type."', '".$category."', '".$club_location."', '".$uid."','".$description."', '".$address."', '".$rt. "/" .$rw."', '".$postcode."', '".$province."', 
        '".$city."', '".$district."', '".$subdistrict."', '".$bank."', '".$acc_number."', '".$acc_name."', '".$president."', '".$secretary."', '".$clubadmin."', '".$finance."', 
        '".$vicepresident."', '".$hrd."', '".$dokadart."', '".$dokcertificate."', '".$dokAdditional."', '".$president_phone."', '".$president_ktp."', '".$president_kta."', 
        '".$secretary_phone."', '".$secretary_ktp."', '".$secretary_kta."', '".$admin_phone."', '".$admin_ktp."', '".$admin_kta."', '".$finance_phone."', '".$finance_ktp."', 
        '".$finance_kta."', '".$vice_president_phone."', '".$vice_president_ktp."', '".$vice_president_kta."', '".$human_resource_phone."', '".$human_resource_ktp."', '".$human_resource_kta."', ".$is_android.")";
    }

    if (mysqli_query($dbconn, $postquery)) {
        echo("Koneksi Database Berhasil");
        echo($postquery);
    }
    else {
        
        echo("Koneksi Query Gagal");
        http_response_code(400);
        echo(mysqli_error($dbconn));
    }

}else{
    echo("Upload File gagal.");
}
// END QUERY
?>