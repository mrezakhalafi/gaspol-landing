<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/logics/dashboard_session_check.php');
session_start();

// print_r($_SESSION['web_login']);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

if (isset($_GET['admin'])) {
    $admin = $_GET['admin'];
    $_SESSION['ADMIN_PROVINCE'] = $admin;
} else {
    $admin = $_SESSION['ADMIN_PROVINCE'];
}

// CHECK USER ID

if (!isset($admin)) {
    header("Location: http://108.136.138.242/gaspol_web/pages/gaspol-landing/");
}

// GET USER (ADMIN)

if (isset($_GET['f_pin'])) {
    $f_pin_admin = $_GET['f_pin'];
    $_SESSION['F_PIN'] = $f_pin_admin;
} else {
    $f_pin_admin = $_SESSION['F_PIN'];
}

$query = $dbconn->prepare("SELECT * FROM USER_LIST LEFT JOIN ADMIN_PROVINCE ON USER_LIST.F_PIN = ADMIN_PROVINCE.F_PIN WHERE USER_LIST.F_PIN = '" . $f_pin_admin . "'");
$query->execute();
$user = $query->get_result()->fetch_assoc();
$query->close();

if($user['PROVINCE_ID'] == 0){
    $admin_type = "( Admin Pusat )";
}else{
    $admin_type = "( Admin Provinsi )";
}

// GET KTA DATA

$no_anggota = $_GET['id'];

$query = $dbconn->prepare("SELECT KTA.*, REGISTRATION_PAYMENT.*, 
                        PROVINCE.PROV_NAME, DISTRICT.DIS_NAME, CITIES.CITY_NAME, 
                        CN.COUNTRY_NAME AS NATIONALITY_NAME, 
                        CB.COUNTRY_NAME AS BIRTHPLACE_NAME, 
                        SUBDISTRICT.SUBDIS_NAME, KTA.F_PIN AS KF_PIN, 
                        USER_LIST.IMAGE AS ULIMAGE,
                        KTA_HOBBY.NAME AS HOBBY_NAME
                        FROM KTA 
                        LEFT JOIN REGISTRATION_PAYMENT ON KTA.NO_ANGGOTA = REGISTRATION_PAYMENT.REF_ID 
                        LEFT JOIN PROVINCE ON PROVINCE.PROV_ID = KTA.PROVINCE
                        LEFT JOIN DISTRICT ON DISTRICT.DIS_ID = KTA.DISTRICT
                        LEFT JOIN CITIES ON CITIES.CITY_ID = KTA.CITY
                        LEFT JOIN COUNTRIES AS CN ON CN.ID = KTA.NATIONALITY
                        LEFT JOIN COUNTRIES AS CB ON CB.ID = KTA.BIRTHPLACE
                        LEFT JOIN SUBDISTRICT ON SUBDISTRICT.SUBDIS_ID = KTA.DISTRICT_WORD
                        LEFT JOIN USER_LIST ON KTA.F_PIN = USER_LIST.F_PIN
                        LEFT JOIN KTA_HOBBY ON KTA.HOBBY = KTA_HOBBY.ID
                        WHERE NO_ANGGOTA = '".$no_anggota."'");
$query->execute();
$ktaData = $query->get_result()->fetch_assoc();
$query->close();

// GET CLUB DATA

$query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP 
                        LEFT JOIN TKT ON TKT.ID = CLUB_MEMBERSHIP.CLUB_CHOICE
                        LEFT JOIN PROVINCE ON TKT.PROVINCE = PROVINCE.PROV_ID
                        LEFT JOIN CITIES ON CITIES.CITY_ID = TKT.CITY
                        WHERE CLUB_MEMBERSHIP.F_PIN = '".$ktaData['KF_PIN']."'");
$query->execute();
$tktData = $query->get_result()->fetch_assoc();
$query->close();

// GET KIS DATA

$query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU = REGISTRATION_PAYMENT.REF_ID WHERE KIS.F_PIN = '".$ktaData['KF_PIN']."'");
$query->execute();
$kisData = $query->get_result();
$query->close();

$msdate = $ktaData["CREATED_DATE"];
$todate = strtotime('+1 year',strtotime($msdate));

$no_member = $ktaData['NO_ANGGOTA'];

$query = $dbconn->prepare("SELECT * FROM KTA_HOBBY");
$query->execute();
$hobby = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM COUNTRIES");
$query->execute();
$countries = $query->get_result();
$query->close();

$isCetak = 1;

// GET TRANSACTION

$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT WHERE F_PIN = '".$no_anggota."'");
$query->execute();
$registration = $query->get_result();
$query->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gaspol! Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/jquery-ui.css?v=4">
    <link rel="stylesheet" href="css/paliobutton.css?v=910928953">
    <link rel="stylesheet" href="css/paliopay.css?v=4">

    <style>

        .header .col-12 {
            border-bottom: 2px solid #e3e3e3;
        }

        nav.static-top,
        div.content-wrapper,
        footer {
            background-color: #f5f5f5 !important;
        }

        b{
            color: black;
            font-weight: normal;
        }

        strong{
            color: black;
            font-size: 17px;
        }

        img, #card-structure{
            cursor: pointer;
        }

        .text-black{
            color: black;
            margin-left: 10px;
            margin-right: 20px;
        }

    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

                <div class="container-fluid mb-4 header mt-4">
                    <div class="row pt-2">
                        <div class="col-9">
                            <img src="../../../assets/img/membership-back.png" style="width: 30px; height: 30px; margin-left: 10px; margin-right: 20px" onclick="window.history.back()">
                            <span style="font-size: 18px" class="text-dark mt-2"><strong>Detil Anggota</strong></span>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <div onclick="cetakKTA()" style="background-color:#FF6B00; padding: 5px; border-radius: 20px; color: white !important; padding-left: 25px; padding-right: 25px; width: fit-content; height: 35px; font-weight: bold; cursor: pointer">Cetak KTA</div>
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-6">
                        <div id="section-detail-left" class="p-2" style="width: 99%; background-color: white; border-radius: 20px">
                            <div class="row p-3">

                                <?php if($ktaData['ULIMAGE']): ?>

                                    <div class="col-2" onclick="modalFotoProfile('<?= $ktaData['ULIMAGE'] ?>')">
                                        <img src="http://108.136.138.242/filepalio/image/<?= $ktaData['ULIMAGE'] ?>" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                                    </div>

                                <?php else: ?>

                                    <div class="col-2" onclick="modalFotoProfile('default.png')">
                                        <img src="http://108.136.138.242/gaspol_web/images/default.png" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                                    </div>

                                <?php endif; ?>

                                <div class="col-4">
                                    <p style="font-size: 14px">Foto profil Gaspol!</p>

                                    <?php if($ktaData['ULIMAGE']): ?>
                                        <p><b style="font-weight: bold"><?= substr_replace($ktaData['ULIMAGE'], "...", 20) ?></b></p>
                                    <?php else: ?>
                                        <p><b style="font-weight: bold">default.png</b></p>
                                    <?php endif; ?>

                                </div>
                                <div class="col-2" onclick="modalEKTP('<?= $ktaData['EKTP_IMAGE'] ?>')">
                                    <img src="/gaspol_web/images/<?= $ktaData['EKTP_IMAGE'] ?>" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                                </div>
                                <div class="col-4">
                                    <p style="font-size: 14px">Foto Identitas</p>
                                    <p><b style="font-weight: bold"><?= substr_replace($ktaData['EKTP_IMAGE'], "...", 20) ?></b></p>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header mt-5" style="margin-top: 40px">
                                <div class="row">
                                    <div class="col-8" onclick="modalEdit()" style="border-bottom: 2px solid #e3e3e3">
                                        <h5 class="text-dark mt-2"><strong>Informasi Pribadi</strong></h2>
                                    </div>
                                    <div class="col-4 text-right" style="border-bottom: 2px solid #e3e3e3; margin-top: 8px" onclick="modalEdit()">
                                        <img src="assets/img/edit.svg">
                                        <span style="font-size: 14px; color: #8f8f8f"><strong style="font-size: 14px">Edit</strong></span>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Nama</small>
                                            <div><b id="name-text"><?= $ktaData['NAME'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Email</small>
                                            <div><b id="email-text"><?= $ktaData['EMAIL'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Tempat Lahir</small>
                                            <div><b id="birthplace-text"><?= $ktaData['BIRTHPLACE_NAME'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Jenis Kelamin</small>

                                            <?php if ($ktaData['GENDER'] == 1): ?>
                                                <div><b id="gender-text">Pria</b></div>
                                            <?php else: ?>
                                                <div><b id="gender-text">Wanita</b></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>NIK</small>
                                            <div id="ektp-text"><b>  <div><b><?= substr($ktaData['EKTP'] ,0,2).' '.substr($ktaData['EKTP'] ,2,4).' '.substr($ktaData['EKTP'] ,6,6).' '.substr($ktaData['EKTP'] ,12,4) ?></b></div></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Tanggal Lahir</small>
                                            <div><b id="datebirth-text"><?= $newDate = date("d M Y", strtotime($ktaData['DATEBIRTH']))  ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Gol. Darah</small>
                                            <div><b id="bloodtype-text"><?= $ktaData['BLOODTYPE'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Warga Negara</small>
                                            <div><b id="nationality-text"><?= $ktaData['NATIONALITY_NAME'] ?></b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header mt-5">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-dark mt-2"><strong>Alamat</strong></h2>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Alamat Lengkap</small>
                                            <div><b><?= $ktaData['ADDRESS'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Kecamatan</small>
                                            <div><b><?= ucwords(strtolower($ktaData['DIS_NAME'])) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Provinsi</small>
                                            <div><b><?= ucwords(strtolower($ktaData['PROV_NAME'])) ?></b></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Desa</small>
                                            <div><b><?= ucwords(strtolower($ktaData['SUBDIS_NAME'])) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Kota/Kabupaten</small>
                                            <div><b><?= ucwords(strtolower($ktaData['CITY_NAME'])) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Kodepos</small>
                                            <div><b><?= $ktaData['POSTCODE'] ?></b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header mt-5">
                                <div class="row">
                                    <div class="col-8" style="border-bottom: 2px solid #e3e3e3">
                                        <h5 class="text-dark mt-2"><strong>Loyalty Program</strong></h2>
                                    </div>
                                    <div class="col-4 text-right" style="border-bottom: 2px solid #e3e3e3; margin-top: 5px" onclick="modalRincian()">
                                        <span style="font-size: 14px; color: #8f8f8f"><strong style="font-size: 14px">Lihat Rincian</strong></span>
                                        <img src="assets/img/chevron.svg">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Link Referal</small>
                                            <div><b>-</b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Referal</small>
                                            <div><b>-</b></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Jumlah Poin</small>
                                            <div><b>-</b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Penukaran Poin</small>
                                            <div><b>-</b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="section-detail-right" class="p-2" style="width: 99%; background-color: white; border-radius: 20px">
                            <div class="row p-3">
                                <div class="col-2" onclick="modalFotoKTA('<?= $ktaData['PROFILE_IMAGE'] ?>')">
                                    <img src="/gaspol_web/images/<?= $ktaData['PROFILE_IMAGE'] ?>" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                                </div>
                                <div class="col-2">
                                    <p style="font-size: 14px">Foto profil KTA</p>
                                    <p><b style="font-weight: bold"><?= substr_replace($ktaData['PROFILE_IMAGE'], "...", 20) ?></b></p>
                                </div>
                                <div class="col-4" onclick="modalKartuKTA()">
                                    <div id="card-structure" style="margin-top: -55px; -moz-transform: scale(0.50, 0.50); -webkit-transform: scale(0.50, 0.50); -o-transform: scale(0.50, 0.50); -ms-transform: scale(0.50, 0.50); transform: scale(0.50, 0.50); color: white; height: 215px; border-radius: 10px; background-color: black; padding-left: 10px; padding-right: 10px; width: 326px">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-center" style="margin-left: -11px; height: 350px; margin-top: -51px; width: 350px">
                                                <img src="../../output-kta-mobility-4.png" alt="" style="border-radius: 15px;height: 90%; margin-left: 9px">
                                                <div class="row gx-0">
                                                    <div class="col-12 d-flex justify-content-center" style="margin-top: 110px; margin-left: -159px">
                                                        <p id="no-anggota" class="data-person" style="margin-left: 31px; margin-top: -15px; font-size: 11px; font-weight:700"><?= $ktaData['NO_ANGGOTA'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="profile-picture col-3 d-flex justify-content-center">
                                                        <img id="image-anggota" src="/gaspol_web/images/<?= $ktaData['PROFILE_IMAGE'] ?>" alt="" style="width: 66px; height: 76px; margin-top: 130px; margin-left: -509px; max-width: none; object-fit: cover; object-position: center">
                                                    </div>
                                                    <div class="col-6 justify-content-center" style="position: absolute; margin-left: -234px; margin-top: 155px">
                                                        <div class="row gx-0">
                                                            <div class="col-12" style="margin-left: 22px;">
                                                                <p id="nama-anggota" class="data-person" style="margin-top: -25px; font-size: 11px; font-weight:700"><?= $ktaData['NAME'] ?></p>
                                                            </div>
                                                            <br>
                                                            <div class="col-12" style="margin-left: 22px">
                                                                <p id="alamat" class="data-person" style="width: 145px; font-size: 11px; font-weight:700; margin-top: -30px; height: 30px"><?= $ktaData['ADDRESS'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-0" style="margin-top: -44px">
                                                            <div class="col-12" style="position: absolute; margin-top: 40px; margin-left: 22px">
                                                                <p id="status-membership" class="data-person"></p>
                                                            </div>
                                                            <div class="row gx-0" style="margin-top: 20px">
                                                                <div class="col-3 justify-content-center" style="margin-left: -40px; margin-top: 30px; position: absolute">
                                                                    <p class="data-person" style="width: 50px; font-size: 9px; font-weight:700">Gol. Darah &nbsp; <span id="gol-darah" style="position: absolute" class="ms-2"><?= $ktaData['BLOODTYPE'] ?></span></p>
                                                                </div>
                                                                <div class="col-3 justify-content-center" style="margin-top: 30px; margin-left: 35px">
                                                                    <p class="data-person" style="width: 180px; font-size: 9px; font-weight:700">Jatuh tempo &nbsp; <span id="jatuh-tempo"><?= date("d-m-Y", $todate) ?></span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 d-flex justify-content-center" style="position: absolute; margin-top: 137px; margin-left: -40px; width: 60px; height: 60px">
                                                        <img id="barcode" onclick="modalQR()" src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $no_member ?>&amp;size=100x100" alt="" width="120" height="z0" style="color: green; z-index: 999;/* margin-top: -10px; */">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <p style="font-size: 14px">Kartu KTA</p>
                                    <p><b style="font-weight: bold">kta-card-<?= substr_replace($ktaData['NO_ANGGOTA'], "...", 11) ?></b></p>

                                    <?php if (isset($isCetak)): ?>
                                        <div style="background-color: #ededed; padding: 7px; border-radius: 20px; font-size: 13px; padding-left: 10px; padding-right: 10px; width: fit-content">Sudah Cetak</div>
                                    <?php else: ?>
                                        <div style="background-color: #ededed; padding: 7px; border-radius: 20px; font-size: 13px; padding-left: 10px; padding-right: 10px; width: fit-content">Belum Cetak</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header">
                                <div class="row">
                                    <div class="col-8" style="border-bottom: 2px solid #e3e3e3">
                                        <h5 class="text-dark mt-2"><strong>Keanggotaan</strong></h2>
                                    </div>
                                    <div class="col-4 text-right" style="border-bottom: 2px solid #e3e3e3; margin-top: 5px" onclick="modalRiwayatKTA()">
                                        <span style="font-size: 14px; color: #8f8f8f"><strong style="font-size: 14px">Riwayat KTA</strong></span>
                                        <img src="assets/img/chevron.svg">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Nomor KTA</small>
                                            <div><b><?= substr($ktaData['NO_ANGGOTA'] ,0,3).' '.substr($ktaData['NO_ANGGOTA'] ,3,3).' '.substr($ktaData['NO_ANGGOTA'] ,6,4) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Status Keanggotaan</small>
                                            <div><b><?= $ktaData['EMAIL'] ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Aktif Sampai</small>
                                            <div><b><?= $newDate = date("d M Y", strtotime('+1 year', strtotime($ktaData['CREATED_DATE']))) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Tanggal Transaksi</small>
                                            <div><b><?= $newDate = date("d M Y", strtotime($ktaData['CREATED_DATE'])) ?></b></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Produk KTA</small>

                                            <?php if ($ktaData['STATUS_ANGGOTA'] == 0): ?>
                                                <div><b>Mobility</b></div>
                                            <?php elseif($ktaData['STATUS_ANGGOTA'] == 1): ?>
                                                <div><b>Pro</b></div>
                                            <?php endif; ?>
                                            
                                        </div>
                                        <div class="mt-2">
                                            <small>Tanggal Daftar</small>
                                            <div><b><?= $newDate = date("d M Y", strtotime($ktaData['CREATED_DATE'])) ?></b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Pembaruan</small>
                                            <div><b>-</b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>ID Transaksi</small>

                                            <?php if ($ktaData['PAYMENT_ID']): ?>
                                                <div><b><?= $ktaData['PAYMENT_ID'] ?></b></div>
                                            <?php else: ?>
                                                <div><b>Transaction data not found.</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header mt-5">
                                <div class="row">
                                    <div class="col-8" style="border-bottom: 2px solid #e3e3e3">
                                        <h5 class="text-dark mt-2"><strong>Klub</strong></h2>
                                    </div>
                                    <div class="col-4 text-right" style="border-bottom: 2px solid #e3e3e3; margin-top: 5px" onclick="modalRiwayatKlub()">
                                        <span style="font-size: 14px; color: #8f8f8f"><strong style="font-size: 14px">Riwayat Pindah Klub</strong></span>
                                        <img src="assets/img/chevron.svg">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Nama Klub</small>

                                            <?php if (isset($tktData)): ?>
                                                <div><b style="color: #4747c8"><?= $tktData['CLUB_NAME'] ?></b></div>
                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>
                                            
                                        </div>
                                        <div class="mt-2">
                                            <small>Status Klub</small>

                                            <?php 

                                            $dateTimestamp1 = strtotime($tktData['EXPIRE_DATE']);
                                            $dateTimestamp2 = strtotime(date('Y-m-d'));

                                            ?>

                                            <?php if (isset($tktData)): ?>

                                                <?php if ($dateTimestamp1 > $dateTimestamp2): ?>
                                                    <div><b>Aktif</b></div>
                                                <?php else: ?>
                                                    <div><b>Tidak Aktif</b></div>
                                                <?php endif; ?>

                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>

                                        </div>
                                        <div class="mt-2">
                                            <small>Provinsi</small>

                                            <?php if (isset($tktData)): ?>
                                                <div><b><?= ucwords(strtolower($tktData['PROV_NAME'])) ?></b></div>
                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>

                                        </div>
                                        <div class="mt-2">
                                            <small>Posisi</small>
                                            <div><b>-</b></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <small>Level Klub</small>
                                            <div><b>-</b></div>
                                        </div>
                                        <div class="mt-2">
                                            <small>Aktif Sampai</small>

                                            <?php if (isset($tktData)): ?>
                                                <div><b><?= $newDate = date("d M Y", strtotime($tktData['EXPIRE_DATE'])) ?></b></div>
                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>

                                        </div>
                                        <div class="mt-2">
                                            <small>Kota/Kabupaten</small>

                                            <?php if (isset($tktData)): ?>
                                                <div><b><?= ucwords(strtolower($tktData['CITY_NAME'])) ?></b></div>
                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>

                                        </div>
                                        <div class="mt-2">
                                            <small>Admin Club</small>

                                            <?php if (isset($tktData)): ?>
                                                <div><b><?= $tktData['ADMIN'] ?></b></div>
                                            <?php else: ?>
                                                <div><b>-</b></div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mb-3 header mt-5">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-dark mt-2"><strong>Riwayat KIS</strong></h2>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <table id="tableKIS" class="table table-striped">
                                        <thead class="table-secondary">
                                            <th>Tipe KIS</th>
                                            <th>Waktu Transaksi</th>
                                            <th>Metode Pembayaran</th>
                                        </thead>
                                        <tbody>

                                            <?php if(mysqli_num_rows($kisData) > 0): ?>
                                                <?php foreach ($kisData as $index => $kis) {

                                                    $mil = $kis['DATE'];
                                                    $seconds = $mil / 1000;
                                                    $date = date("d-m-Y, H:i:s", $seconds);

                                                    $category = explode("|",$kis['KATEGORI']);

                                                    foreach ($category as $c): ?>
                                                    <tr>
                                                        <td><?= $c ?></td>
                                                        <td><?= $date ?></td>
                                                        <td><?= $kis['METHOD'] ?></td>
                                                    </tr>

                                                <?php endforeach;
                                                }; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" style="font-size: 12px;" class="text-center">Member ini belum mempunyai Lisensi Balap.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Gaspol! @2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-dark" onclick="logout()">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFotoProfile" tabindex="-1" aria-labelledby="modalFotoProfile" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" style="font-size: 14px" id="exampleModalLabel">Foto Profile<br />
                        <span id="foto-profile-text" class="modal-title" style="font-size: 16px; color: #2d2d2d" id="exampleModalLabel"></span>
                    </p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <img id="foto-profile-img" src="" style="width: 100%; height: 100%; object-fit: cover; object-position: center">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEKTP" tabindex="-1" aria-labelledby="modalEKTP" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" style="font-size: 14px" id="exampleModalLabel">Foto Identitas<br />
                        <span id="foto-ektp-text" class="modal-title" style="font-size: 16px; color: #2d2d2d" id="exampleModalLabel"></span>
                    </p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <img id="foto-ektp-img" src="" style="width: 100%; height: 100%; object-fit: cover; object-position: center">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFotoKTA" tabindex="-1" aria-labelledby="modalFotoKTA" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" style="font-size: 14px" id="exampleModalLabel">Foto KTA<br />
                        <span id="foto-kta-text" class="modal-title" style="font-size: 16px; color: #2d2d2d" id="exampleModalLabel"></span>
                    </p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <img id="foto-kta-img" src="" style="width: 100%; height: 100%; object-fit: cover; object-position: center">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKartuKTA" tabindex="-1" aria-labelledby="modalKartuKTA" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" style="font-size: 14px" id="exampleModalLabel">Kartu KTA<br />
                        <span id="foto-kartu-text" class="modal-title" style="font-size: 16px; color: #2d2d2d" id="exampleModalLabel">kta-card-<?= $ktaData['NO_ANGGOTA'] ?></span>
                    </p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body" style="margin: 90px; margin-left: 68px">
                    <div id="card-structure-modal" style="margin-top: -55px; -moz-transform: scale(1.30, 1.30); -webkit-transform: scale(1.30, 1.30); -o-transform: scale(1.30, 1.30); -ms-transform: scale(1.30, 1.30); transform: scale(1.30, 1.30); color: white; height: 215px; border-radius: 10px; background-color: black; padding-left: 10px; padding-right: 10px; width: 326px">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center" style="margin-left: -11px; height: 350px; margin-top: -51px; width: 350px">
                                <img src="../../output-kta-mobility-4.png" alt="" style="border-radius: 15px;height: 90%; margin-left: 9px">
                                <div class="row gx-0">
                                    <div class="col-12 d-flex justify-content-center" style="margin-top: fit-content; margin-left: -159px">
                                        <p id="no-anggota" class="data-person" style="margin-left: 31px; margin-top: -15px; font-size: 11px; font-weight:700"><?= $ktaData['NO_ANGGOTA'] ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="profile-picture col-3 d-flex justify-content-center">
                                        <img id="image-anggota" src="/gaspol_web/images/<?= $ktaData['PROFILE_IMAGE'] ?>" alt="" style="width: 66px; height: 76px; margin-top: 130px; margin-left: -509px; max-width: none; object-fit: cover; object-position: center">
                                    </div>
                                    <div class="col-6 justify-content-center" style="position: absolute; margin-left: -234px; margin-top: 155px">
                                        <div class="row gx-0">
                                            <div class="col-12" style="margin-left: 22px">
                                                <p id="nama-anggota" class="data-person" style="margin-top: -25px; font-size: 11px; font-weight:700"><?= $ktaData['NAME'] ?></p>
                                            </div>
                                            <br>
                                            <div class="col-12" style="margin-left: 22px">
                                                <p id="alamat" class="data-person" style="width: 145px; font-size: 11px; font-weight:700; margin-top: -30px; height: 30px"><?= $ktaData['ADDRESS'] ?></p>
                                            </div>
                                        </div>
                                        <div class="row gx-0" style="margin-top: -44px">
                                            <div class="col-12" style="position: absolute; margin-top: 40px; margin-left: 22px">
                                                <p id="status-membership" class="data-person"></p>
                                            </div>
                                            <div class="row gx-0" style="margin-top: 20px; margin-left: -5px">
                                                <div class="col-3 justify-content-center" style="margin-left: -50px; margin-top: 30px; position: absolute">
                                                    <p class="data-person" style="width: 50px; font-size: 9px; font-weight:700">Gol. Darah &nbsp; <span id="gol-darah" style="position: absolute" class="ms-2"><?= $ktaData['BLOODTYPE'] ?></span></p>
                                                </div>
                                                <div class="col-3 justify-content-center" style="margin-top: 30px; margin-left: 25px">
                                                    <p class="data-person" style="width: 180px; font-size: 9px; font-weight:700">Jatuh tempo &nbsp; <span id="jatuh-tempo"><?= date("d-m-Y", $todate) ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center" style="position: absolute; margin-top: 137px; margin-left: -40px; width: 60px; height: 60px">
                                        <img id="barcode" onclick="modalQR()" src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $no_member ?>&amp;size=100x100" alt="" width="120" height="z0" style="color: green; z-index: 999;/* margin-top: -10px; */">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-3" style="margin-top: -30px !important">
                    <div class="col-6 d-flex justify-content-start">
                        <div style="background-color: #ededed; padding: 7px; border-radius: 20px; font-size: 13px; padding-left: 10px; padding-right: 10px; width: fit-content">Belum Cetak</div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div onclick="cetakKTA()" style="background-color:#FF6B00; padding: 5px; border-radius: 20px; color: white !important; padding-left: 25px; padding-right: 25px; width: fit-content; height: 35px; font-weight: bold; cursor: pointer">Cetak KTA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalRiwayatKTA" tabindex="-1" aria-labelledby="modalRiwayatKTA" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="exampleModalLabel" style="font-weight: bold">Riwayat KTA</p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <h5 style="font-size: 16px; margin-top: 20px; margin-bottom: 20px">Transaksi</h5>
                    <table id="tableRiwayatKTA" class="table table-striped">
                        <thead class="table-secondary" style="font-size: 14px; color: black">
                            <th>Waktu Transaksi</th>
                            <th>Metode</th>
                            <th>Waktu Proses</th>
                            <th>Status</th>
                        </thead>
                        <tbody>

                        <?php if(mysqli_num_rows($registration) > 0): ?>

                            <?php foreach ($registration as $rg): ?>
                                <tr>
                                    <td style="font-size: 14px"><?= $rg['DATE'] ?></td>
                                    <td style="font-size: 14px"><?= $rg['METHOD'] ?></td>
                                    <td style="font-size: 14px"><?= $rg['DATE'] ?></td>
                                    <td style="font-size: 14px">Berhasil</td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <td colspan="4" style="font-size: 14px">Belum ada history transaksi.</td>
                            <td style="display: none">-</td>
                            <td style="display: none">-</td>
                            <td style="display: none">-</td>

                        <?php endif; ?>

                        </tbody>
                    </table>
                    <h5 style="font-size: 16px; margin-top: 40px; margin-bottom: 20px">Cetak KTA</h5>
                    <table id="tableCetakKTA" class="table table-striped" style="margin-bottom: 20px">
                        <thead class="table-secondary" style="font-size: 14px; color: black">
                            <th>Waktu Transaksi</th>
                            <th>Provinsi</th>
                            <th>Klub</th>
                            <th>PIC</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" style="font-size: 14px">Belum ada history cetak KTA.</td>
                                <td style="display: none">-</td>
                                <td style="display: none">-</td>
                                <td style="display: none">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRiwayatKlub" tabindex="-1" aria-labelledby="modalRiwayatKlub" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="exampleModalLabel" style="font-weight: bold">Riwayat Pindah Klub</p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <table id="tableRiwayatClub" class="table table-striped" style="margin-top: 20px; margin-bottom: 20px">
                        <thead class="table-secondary" style="font-size: 14px; color: black">
                            <th>Klub Lama</th>
                            <th>Waktu Pindah</th>
                            <th>Klub Baru</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" style="font-size: 14px">Belum ada history perpindahan klub.</td>
                                <td style="display: none">-</td>
                                <td style="display: none">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="exampleModalLabel">Edit Informasi Pribadi</p>
                    <div type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <small>Nama</small>
                            <input id="name" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $ktaData['NAME'] ?>">
                        </div>
                        <div class="col-6">
                            <small>NIK</small>
                            <input id="ektp" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $ktaData['EKTP'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <small>Email</small>
                            <input id="email" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $ktaData['EMAIL'] ?>">
                        </div>
                        <div class="col-6">
                            <small>Tanggal Lahir</small>
                            <input type="date" id="datebirth" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $ktaData['DATEBIRTH'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <small>Tempat Lahir</small>
                           
                            <select id="countries-select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">

                                <?php foreach($countries as $c): ?>
                                    <option <?php if ($ktaData['BIRTHPLACE'] == $c['ID']): ?> selected <?php endif; ?> value="<?= $c['ID'] ?>"><?= $c['COUNTRY_NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>

                        </div>
                        <div class="col-6">
                            <small>Gol. Darah</small>
                            <select id="bloodtype-select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option <?php if ($ktaData['BLOODTYPE'] == 'A'): ?> selected <?php endif; ?> value="A">A</option>
                                <option <?php if ($ktaData['BLOODTYPE'] == 'B'): ?> selected <?php endif; ?> value="B">B</option>
                                <option <?php if ($ktaData['BLOODTYPE'] == 'AB'): ?> selected <?php endif; ?> value="AB">AB</option>
                                <option <?php if ($ktaData['BLOODTYPE'] == 'O'): ?> selected <?php endif; ?> value="O">O</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <small>Jenis Kelamin</small>
                            <select id="gender-select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option <?php if ($ktaData['GENDER'] == 1): ?> selected <?php endif; ?> value="1">Pria</option>
                                <option <?php if ($ktaData['GENDER'] == 2): ?> selected <?php endif; ?> value="2">Wanita</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <small>Hobi</small>
                            <select id="hobby-select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">

                                <?php foreach($hobby as $h): ?>
                                    <option <?php if ($ktaData['HOBBY'] == $h['ID']): ?> selected <?php endif; ?> value="<?= $h['ID'] ?>"><?= $h['NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <small>Warga Negara</small>

                            <select id="countries-2-select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">

                                <?php foreach($countries as $c): ?>
                                    <option <?php if ($ktaData['NATIONALITY'] == $c['ID']): ?> selected <?php endif; ?> value="<?= $c['ID'] ?>"><?= $c['COUNTRY_NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                           
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: flex-start; border-top: none">
                    <div onclick="simpanEdit()" style="background-color:#FF6B00; padding: 5px; border-radius: 20px; color: white; padding-left: 25px; padding-right: 25px; width: fit-content; height: 35px">Simpan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOR CETAK CARD  -->

    <div id="card-structure-big" style="margin-top: -600px; position: absolute; z-index: -9999 !important; margin-left: 300px; color: white; height: 215px; border-radius: 10px; background-color: black; padding-left: 10px; padding-right: 10px; width: 326px">
        <div class="row">
            <div class="col-12 d-flex justify-content-center" style="margin-left: -11px; height: 350px; margin-top: -51px; width: 350px">
                <img src="../../output-kta-mobility-4.png" alt="" style="border-radius: 15px;height: 90%; margin-left: 9px">
                <div class="row gx-0">
                    <div class="col-12 d-flex justify-content-center" style="margin-top: fit-content; margin-left: -159px">
                        <p id="no-anggota" class="data-person" style="margin-left: 31px; margin-top: -15px; font-size: 11px; font-weight:700"><?= $ktaData['NO_ANGGOTA'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="profile-picture col-3 d-flex justify-content-center">
                        <img id="image-anggota" src="/gaspol_web/images/<?= $ktaData['PROFILE_IMAGE'] ?>" alt="" style="width: 66px; height: 76px; margin-top: 130px; margin-left: -509px; max-width: none; object-fit: cover; object-position: center">
                    </div>
                    <div class="col-6 justify-content-center" style="position: absolute; margin-left: -234px; margin-top: 155px">
                        <div class="row gx-0">
                            <div class="col-12" style="margin-left: 22px">
                                <p id="nama-anggota" class="data-person" style="margin-top: -25px; font-size: 11px; font-weight:700"><?= $ktaData['NAME'] ?></p>
                            </div>
                            <br>
                            <div class="col-12" style="margin-left: 22px">
                                <p id="alamat" class="data-person" style="width: 145px; font-size: 11px; font-weight:700; margin-top: -30px; height: 30px"><?= $ktaData['ADDRESS'] ?></p>
                            </div>
                        </div>
                        <div class="row gx-0" style="margin-top: -44px">
                            <div class="col-12" style="position: absolute; margin-top: 40px; margin-left: 22px">
                                <p id="status-membership" class="data-person"></p>
                            </div>
                            <div class="row gx-0" style="margin-top: 20px; margin-left: -5px">
                                <div class="col-3 justify-content-center" style="margin-left: -50px; margin-top: 30px; position: absolute">
                                    <p class="data-person" style="width: 50px; font-size: 9px; font-weight:700">Gol. Darah &nbsp; <span id="gol-darah" style="position: absolute" class="ms-2"><?= $ktaData['BLOODTYPE'] ?></span></p>
                                </div>
                                <div class="col-3 justify-content-center" style="margin-top: 30px; margin-left: 25px">
                                    <p class="data-person" style="width: 180px; font-size: 9px; font-weight:700">Jatuh tempo &nbsp; <span id="jatuh-tempo"><?= date("d-m-Y", $todate) ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex justify-content-center" style="position: absolute; margin-top: 137px; margin-left: -40px; width: 60px; height: 60px">
                        <img id="barcode" onclick="modalQR()" src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $no_member ?>&amp;size=100x100" alt="" width="120" height="z0" style="color: green; z-index: 999;/* margin-top: -10px; */">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

    <?php
        // require_once('gaspol_fb.php');
    ?>  

</body>

</html>

<script>

    $(document).ready(function(e) {

        $('#tableRiwayatKTA').DataTable({
            "info": false,
            "lengthChange": false,
            "paging": false,
            "searching": false
        });

        $('#tableCetakKTA').DataTable({
            "info": false,
            "lengthChange": false,
            "paging": false,
            "searching": false
        });

        $('#tableRiwayatClub').DataTable({
            "info": false,
            "lengthChange": false,
            "paging": false,
            "searching": false
        });

    });

    $('#gender-select').selectize();
    $('#bloodtype-select').selectize();
    $('#hobby-select').selectize();
    $('#countries-select').selectize();
    $('#countries-2-select').selectize();

    function modalFotoProfile(link){

        $('#foto-profile-img').attr('src','http://108.136.138.242/filepalio/image/'+link);
        $('#foto-profile-text').text(link);
        $('#modalFotoProfile').modal('show');

    }

    function modalEKTP(link){

        $('#foto-ektp-img').attr('src','/gaspol_web/images/'+link);
        $('#foto-ektp-text').text(link);
        $('#modalEKTP').modal('show');

    }

    function modalFotoKTA(link){

        $('#foto-kta-img').attr('src','/gaspol_web/images/'+link);
        $('#foto-kta-text').text(link);
        $('#modalFotoKTA').modal('show');

    }

    function modalRiwayatKTA(){

        $('#modalRiwayatKTA').modal('show');

    }

    function modalRiwayatKlub(){

        $('#modalRiwayatKlub').modal('show');

    }

    function modalKartuKTA(){

        $('#modalKartuKTA').modal('show');

    }

    function modalEdit(){

        $('#modalEdit').modal('show');

    }

    function cetakKTA(){

        var w = document.getElementById("card-structure-big").offsetWidth;
        var h = document.getElementById("card-structure-big").offsetHeight;
        html2canvas(document.querySelector("#card-structure-big"), {
            useCORS: true,
            dpi: 600,
            scale: 5,
            quality: 4,
        }).then(canvas => {
            console.log(canvas);
            var img = canvas.toDataURL("image/png", 1);
            var doc = new jsPDF('L', 'pt', [w, h]);
            doc.addImage(img, 'PNG', 10, 10, w * 0.90, h * 0.90);
            var uri = doc.output('datauristring')
            console.log(uri);
            var anchor = document.createElement('a');
            anchor.setAttribute('download', '<?= $no_anggota ?>-KTA-card.pdf');
            anchor.setAttribute('href', uri);
            anchor.click();

        });

    }

    function simpanEdit(){

        var name = $('#name').val();
        var email = $('#email').val();
        var ektp = $('#ektp').val();
        var bloodtype = $('#bloodtype-select').val();
        var birthplace = $('#countries-select').val();
        var gender = $('#gender-select').val();
        var hobby = $('#hobby-select').val();
        var nationality = $('#countries-2-select').val();
        var datebirth = $('#datebirth').val();

        var f_pin = '<?= $ktaData['KF_PIN'] ?>';

        console.log(name);
        console.log(email);
        console.log(ektp);
        console.log(bloodtype);
        console.log(birthplace);
        console.log(gender);
        console.log(hobby);
        console.log(nationality);
        console.log(datebirth);

        var formData = new FormData();
        formData.append('f_pin', f_pin);
        formData.append('name', name);
        formData.append('ektp', ektp);
        formData.append('email', email);
        formData.append('bloodtype', bloodtype);
        formData.append('birthplace', birthplace);
        formData.append('gender', gender);
        formData.append('hobby', hobby);
        formData.append('nationality', nationality);
        formData.append('datebirth', datebirth);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                if(result == 1){
                    $('#modalEdit').modal('hide');

                    $('#name-text').text(name);
                    $('#email-text').text(email);
                    $('#ektp-text').text(ektp);
                    $('#bloodtype-text').text(bloodtype);

                    $('#nationality-text').text($('#countries-2-select').text());
                    $('#birthplace-text').text($('#countries-select').text());
                    $('#gender-text').text($('#gender-select').text());

                    const today = new Date($('#datebirth').val());
                    const yyyy = today.getFullYear();
                    let mm = today.getMonth() + 1; // Months start at 0!
                    let dd = today.getDate();

                    if (dd < 10) dd = '0' + dd;
                    if (mm < 10) mm = '0' + mm;

                    const formattedToday = dd + ' ' + mm + ' ' + yyyy;

                    console.log(formattedToday);
                    $('#datebirth-text').text(formattedToday);

                }

            }
        }
        xmlHttp.open("post", "logics/edit_kta_admin");
        xmlHttp.send(formData);

    }
   
    function logout(){

        var f_pin = '<?= $_SESSION['f_pin'] ?>';

        var formData = new FormData();
        formData.append('f_pin', f_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                if(result == 1){
                    window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/";
                }

            }
        }
        xmlHttp.open("post", "logics/logout");
        xmlHttp.send(formData);

    }

</script>