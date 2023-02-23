<?php 

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    // =================== SECTION KTA =======================

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

    // $province = $_POST['province'];
    // $city = $_POST['city'];
    // $district = $_POST['district'];
    // $district_word = $_POST['subdistrict'];

    $postcode = $_POST['postcode'];

    // GET POST CODE
    $query_postcode = $dbconn->prepare("SELECT * FROM POSTAL_CODE WHERE POSTAL_CODE = '$postcode'");
    $query_postcode->execute();
    $show_postcode = $query_postcode->get_result()->fetch_assoc();
    $query_postcode->close();

    // print_r($show_postcode['SUBDIS_ID']);
    $provinces = $show_postcode['PROV_ID'];
    $city = $show_postcode['CITY_ID'];
    $district = $show_postcode['DIS_ID'];
    $district_word = $show_postcode['SUBDIS_ID'];

    $ektp = $_POST['ektp'];

    $pasFoto = $_POST['pasFoto'];
    $fotoEktp = $_POST['fotoEktp'];

    $statusanggota = $_POST['status_anggota'];

    $id_anggota = rand(100000000,999999999);

    $queryPost = "INSERT INTO KTA (NAME, EMAIL, BIRTHPLACE, DATEBIRTH, GENDER, BLOODTYPE, NATIONALITY, HOBBY, ADDRESS, RTRW,
                      PROVINCE, CITY, DISTRICT, DISTRICT_WORD, POSTCODE, EKTP, PROFILE_IMAGE, EKTP_IMAGE, STATUS_ANGGOTA, NO_ANGGOTA, HOBBY_DESC, IS_ANDROID) VALUES ('".$name."',
                      '".$email."','".$birthplace."','".$date_birth."','".$gender_radio."','".$bloodtype."','".$nationality."','".$hobby."','".$address."','".$rt. "/" .$rw."','".$provinces."',
                      '".$city."','".$district."','".$district_word."','".$postcode."','".$ektp."','default.png','default.png','".$statusanggota."','".'0'.$id_anggota."','".$hobby_desc."',0)";

    if (mysqli_query($dbconn, $queryPost)){
      // echo("Berhasil");
    }else{
      echo("Data gagal di input.".$queryPost);
      http_response_code(400);
    }

    // =================== SECTION KIS =======================

    $f_pin = $_POST['f_pin'];

    $unique_number = rand(100000000,999999999);

    $name = $_POST['name'];
    $domisili = $_POST['domisili'];
    $pasFoto = $_POST['fotoKta'];
    $kategoriKis = $_POST['kategoriKis'];

    $simA = $_POST['sim-a'];
    $simC = $_POST['sim-c'];

    $province = $_POST['province'];
    $kk = $_POST['kk'];
    $is_android = $_POST['is_android'];

    // FOR GET F_PIN FROM KTA NUMBER

    $kta_number = $id_anggota;

    $sqlData = "SELECT * FROM KTA WHERE NO_ANGGOTA = '".$kta_number."'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $ktaData = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    $f_pin = $ktaData['F_PIN'];

    $pasFoto = $_POST['pasFoto'];
    $fotoSIMC = $_POST['fotoSIMC'];
    $fotoKK = $_POST['fotoKK'];
    $fotoSIMA = $_POST['fotoSIMA'];

    // FOR PRICE LOOP BY CATEGORY | AND CHECK PRICE BY PROVINCE

    $sqlData = "SELECT * FROM PROVINCE WHERE PROV_ID = '".$province."'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $provinceData = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    $catSingle = explode("|", $kategoriKis);
    $price = $provinceData['KIS_PRICE'];
    // $price = intval($province['PRICE']);

    $totalCat = count($catSingle);

    $totalPrice = $price * $totalCat;

    // INSERT

    $queryPost = "INSERT INTO KIS (F_PIN, NOMOR_KARTU, FOTO_PROFIL, `NAME`, DOMISILI, FOTO_SIM_C, FOTO_PERSETUJUAN, KATEGORI, `EXPIRY_DATE`, NO_SIM_C, FOTO_SIM_A, NO_SIM_A, NO_KK, PROVINCE, IS_ANDROID, NO_KTA) VALUES 
                        ('".$f_pin."','".'0'.$unique_number."','default.png','".$name."','".$domisili."','default.png','default.png','".$kategoriKis."', (NOW() + INTERVAL 1 YEAR),'".$simC."','default.png','".$simA."','".$kk."','".$province."',0,'".'0'.$kta_number."')";
    
    if (mysqli_query($dbconn, $queryPost)){
        echo($id_anggota."|".$statusanggota."|".$unique_number."|".$totalPrice);
    }else{
        echo("Data failed to add. ".$sql.mysqli_error($dbconn));
    }

  ?>