<?php 

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
    $dbconn = paliolite();

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

    $kta_number = $_POST['kta_number'];

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
                        ('".$f_pin."','".'0'.$unique_number."','default.png','".$name."','".$domisili."','default.png','default.png','".$kategoriKis."', (NOW() + INTERVAL 1 YEAR),'".$simC."','default.png','".$simA."','".$kk."','".$province."',0,'".$kta_number."')";
    
    if (mysqli_query($dbconn, $queryPost)){
        echo($unique_number."|".$totalPrice);
    }else{
        echo("Data failed to add. ".$sql.mysqli_error($dbconn));
    }

?>
