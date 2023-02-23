<?php 

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $f_pin = $_POST['f_pin'];

    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthplace = $_POST['birthplace'];
    $date_birth = $_POST['date_birth'];
    $gender_radio = $_POST['gender_radio'];
    $bloodtype = $_POST['bloodtype'];
    $nationality = $_POST['nationality'];
    $hobby = $_POST['hobby'];
    $hobby_desc = $_POST['hobby_desc'];
    $is_android = $_POST['is_android'];

    $address = $_POST['address'];
    $rt = $_POST['rt'];
    $rw = $_POST['rw'];

    $postcode = $_POST['postcode'];

    // GET POST CODE
    $query_postcode = $dbconn->prepare("SELECT * FROM POSTAL_CODE WHERE POSTAL_CODE = '$postcode'");
    $query_postcode->execute();
    $show_postcode = $query_postcode->get_result()->fetch_assoc();
    $query_postcode->close();

    // print_r($show_postcode['SUBDIS_ID']);
    $province = $show_postcode['PROV_ID'];
    $city = $show_postcode['CITY_ID'];
    $district = $show_postcode['DIS_ID'];
    $district_word = $show_postcode['SUBDIS_ID'];

    $ektp = $_POST['ektp'];

    $pasFoto = $_POST['pasFoto'];
    $fotoEktp = $_POST['fotoEktp'];

    $status_anggota = $_POST['status_anggota'];

    $id_anggota = rand(100000000,999999999);

    $queryPost = "INSERT INTO KTA (NAME, EMAIL, BIRTHPLACE, DATEBIRTH, GENDER, BLOODTYPE, NATIONALITY, HOBBY, ADDRESS, RTRW,
                      PROVINCE, CITY, DISTRICT, DISTRICT_WORD, POSTCODE, EKTP, PROFILE_IMAGE, EKTP_IMAGE, STATUS_ANGGOTA, NO_ANGGOTA, HOBBY_DESC, IS_ANDROID) VALUES ('".$name."',
                      '".$email."','".$birthplace."','".$date_birth."','".$gender_radio."','".$bloodtype."','".$nationality."','".$hobby."','".$address."','".$rt. "/" .$rw."','".$province."',
                      '".$city."','".$district."','".$district_word."','".$postcode."','".$ektp."','default.png','default.png','".$status_anggota."','".'0'.$id_anggota."','".$hobby_desc."',0)";

    if (mysqli_query($dbconn, $queryPost)){
      echo($id_anggota."|".$status_anggota);
    }else{
      echo("Data gagal di input.".$queryPost);
      http_response_code(400);
    }
