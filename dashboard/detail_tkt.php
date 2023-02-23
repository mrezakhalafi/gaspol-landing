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

if (isset($_GET['tkt_id'])) {
    $tkt_id = $_GET['tkt_id'];
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

if ($user['PROVINCE_ID'] == 0) {
    $admin_type = "( Admin Pusat )";
} else {
    $admin_type = "( Admin Provinsi )";
}

$query = $dbconn->prepare("SELECT
tkt.ID,
tkt.F_PIN,
tkt.CLUB_NAME,
tkt.CLUB_CATEGORY,
taac.CATEGORY,
rp.DATE,
0 AS RENEWAL,
tkt.PROFILE_IMAGE,
tkt.CLUB_LINK,
tkt.CLUB_DESC, 
tkt.ADDRESS,
tkt.RTRW,
tkt.AD_ART,
tkt.CERTIFICATE,
taab.BANK,
tkt.BANK_NAME,
tkt.BANK_NUMBER,
tkt.CITY,
tkt.DISTRICT,
tkt.SUBDISTRICT,
tkt.POSTCODE,
tkt.PROVINCE,
tkt.EXPIRE_DATE
FROM 
TKT tkt
LEFT JOIN TAA_CATEGORY taac ON taac.ID = tkt.CLUB_CATEGORY
LEFT JOIN TAA_BANK taab ON tkt.BANK = taab.ID
LEFT JOIN REGISTRATION_PAYMENT rp ON rp.REF_ID = tkt.ID AND rp.REG_TYPE = 6
WHERE tkt.ID = $tkt_id");
$query->execute();
$tktdata = $query->get_result()->fetch_assoc();
$query->close();

// get province
$query = $dbconn->prepare("SELECT * FROM TAA_CATEGORY");
$query->execute();
$taac = $query->get_result();
$query->close();

$taa_category = array();
while ($category = $taac->fetch_assoc()) {
    $taa_category[] = $category;
}

$provId = $tktdata['PROVINCE'];
$cityId = $tktdata["CITY"];
$districtId = $tktdata["DISTRICT"];
$subdisId = $tktdata["SUBDISTRICT"];

// echo $provId;

// get province
$query = $dbconn->prepare("SELECT PROV_NAME FROM PROVINCE WHERE PROV_ID = $provId");
$query->execute();
$prov = $query->get_result()->fetch_assoc();
$query->close();

// get city
$query = $dbconn->prepare("SELECT CITY_NAME FROM CITY WHERE CITY_ID = $cityId");
$query->execute();
$city = $query->get_result()->fetch_assoc();
$query->close();

// get district
$query = $dbconn->prepare("SELECT DIS_NAME FROM DISTRICT WHERE DIS_ID = $districtId");
$query->execute();
$district = $query->get_result()->fetch_assoc();
$query->close();

// get subdistrict
$query = $dbconn->prepare("SELECT SUBDIS_NAME FROM SUBDISTRICT WHERE SUBDIS_ID = $subdisId");
$query->execute();
$subdistrict = $query->get_result()->fetch_assoc();
$query->close();

// get payment history
$query = $dbconn->prepare("SELECT rp.*
FROM TKT tkt
LEFT JOIN REGISTRATION_PAYMENT rp ON rp.REF_ID = tkt.ID AND rp.REG_TYPE = 6
WHERE tkt.ID = $tkt_id");
$query->execute();
$payment = $query->get_result();
$query->close();

$payment_history = array();
while ($pay = $payment->fetch_assoc()) {
    $payment_history[] = $pay;
}

// get club members
$query = $dbconn->prepare("SELECT 
CONCAT(ul.FIRST_NAME,' ',ul.LAST_NAME) AS `NAME`,
ul.EMAIL,
kta.NO_ANGGOTA, 
kta.STATUS_ANGGOTA,
cm.MANAGER,
DATE_ADD(kta.CREATED_DATE, INTERVAL 1 YEAR) AS EXPIRED_DATE
FROM CLUB_MEMBERSHIP cm
LEFT JOIN KTA kta ON cm.F_PIN = kta.F_PIN
LEFT JOIN USER_LIST ul ON cm.F_PIN = ul.F_PIN
WHERE cm.CLUB_CHOICE = $tkt_id");
$query->execute();
$members = $query->get_result();
$query->close();

$club_members = array();
while ($member = $members->fetch_assoc()) {
    $club_members[] = $member;
}

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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">

    <style>
        .bg-gradient-dark {
            background-color: #000000;
            background-image: url('assets/img/side-pane.jpg');
            background-size: 125% 125%;
        }

        nav.static-top,
        div.content-wrapper,
        footer {
            background-color: #f5f5f5 !important;
        }

        .header .col-12 {
            border-bottom: 2px solid #e3e3e3;
        }

        .table {
            color: black;
            font-size: .8rem;
            width: 100% !important;
            background-color: white;
        }

        .ektp,
        .kta-club,
        .kta-trxid {
            color: #858796;
        }

        .dataTables_scrollHeadInner {
            margin: 0 auto;
        }

        .filter-download-button img {
            width: 25px;
            height: 25px;
            margin: 0 5px;
            /* float: right; */
        }

        .text-green {
            color: #12A200;
        }

        .text-red {
            color: #F01010;
        }

        .filter-download-button a {
            border: 0;
            cursor: pointer;
        }

        .dataTables_wrapper {
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25em 0.75em;
        }

        .dataTables_wrapper .dataTables_scroll {
            width: 100%;
        }

        .dataTables_scrollHeadInner {
            width: 100%;
        }

        .filters {
            font-size: 13px;
            background-color: white;
            border-radius: 7px;
        }

        p#selected-filters {
            color: #1A73E8;
        }

        #modalEdit {
            color: black;
        }

        #modalEdit .modal-dialog {
            max-width: 650px;
        }

        #modalEdit .modal-header,
        #modalEdit .modal-footer {
            border: 0;
        }

        #modalEdit .modal-footer {
            justify-content: flex-start;
        }

        .modal-header {
            display: inline;
            margin-bottom: -30px;
        }

        /* #modalEdit .modal-body label.report-type,
        label.report-period,
        label.report-format {
            font-size: 12px;
        }

        label.report-period {
            width: 100%;
        }

        label.radio-inline {
            font-size: 15px;
        }

        label.radio-inline input {
            margin-right: .75rem;
        } */

        .form-select {
            display: block;
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            -moz-padding-start: calc(0.75rem - 3px);
            font-size: 13px;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            border: 0;
            border-bottom: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        #toggle-filter {
            cursor: pointer;
        }

        .table-pagination {
            font-size: 13px;
        }

        .btn {
            font-size: 13px;
            font-weight: bold;
        }

        .btn-orange:hover {
            color: white;
        }

        .btn-orange {
            border: 0;
            background-color: #ff6b00;
            color: white;
            border-radius: 3rem;
            padding: .45rem 2rem .35rem 2rem;
        }

        #tableTKT_filter,
        #tableMembers_filter {
            background-color: white;
            border-radius: 5px;
            padding: 0.25rem 0;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            margin-left: 3px;
            border: 0 !important;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter input:focus-visible {
            border: 0;
        }

        #tableTKT tbody tr {
            /* cursor: pointer; */
        }

        .row#tkt-detail {
            background-color: white;
        }

        b {
            font-weight: bold !important;
        }

        .search-table {
            border-bottom: 1px solid lightgray;
        }

        .text-black{
            color: black;
            margin-left: 10px;
            margin-right: 20px;
        }
        
    </style>

</head>

<body id="page-top">

    <?php include_once("wrapper_sidebar.php") ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-6">
                <img src="../../../assets/img/membership-back.png" style="width: 30px; height: 30px; margin-left: 10px; margin-right: 20px"><strong style="font-size: 18px" class="text-black">Detail Klub</strong>
            </div>
            <div class="col-6 text-right">
                <button class="btn btn-orange">Cetak Sertifikat</button>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="row m-3">
            <div class="col-6">
                <div id="section-detail-left" class="p-2" style="width: 99%; background-color: white; border-radius: 20px">
                    <div class="row p-3">
                        <div class="col-2">
                            <img src="http://108.136.138.242/gaspol_web/images/<?= $tktdata['PROFILE_IMAGE'] ?>" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                        </div>
                        <div class="col-4 align-items-center">
                            <p class="mb-0" style="font-size: 14px">Foto profil Klub</p>
                            <p><b><?= $tktdata['PROFILE_IMAGE'] ?></b></p>
                        </div>
                        <div class="col-2">
                            <img src="/gaspol_web/assets/img/pdf.svg" style="width: 120px; height: 120px; object-fit: cover; object-position: center; border-radius: 5px">
                        </div>
                        <div class="col-4 align-items-center">
                            <p class="mb-0" style="font-size: 14px">Sertifikat</p>
                            <p><b><?= $tktdata['CERTIFICATE'] ?></b></p>
                        </div>
                    </div>
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-8" style="border-bottom: 2px solid #e3e3e3">
                                <h5 class="text-dark mt-2"><strong>Informasi Pribadi</strong></h2>
                            </div>
                            <div class="col-4 text-right" style="border-bottom: 2px solid #e3e3e3; margin-top: 8px; cursor:pointer;" onclick="toggleEdit();">
                                <img src="assets/img/edit.svg">
                                <span style="font-size: 14px; color: #555555"><strong>Edit</strong></span>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>Nama</small>
                                    <div><b id="club-name"><?= $tktdata['CLUB_NAME'] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Jenis Kendaraan</small>
                                    <div><b>Mobil</b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Tanggal Daftar</small>
                                    <div><b>
                                            <?php

                                            $regDateMillis = floor($tktdata["DATE"] / 1000);
                                            $regDate = date("d M Y", strtotime($regDateMillis));
                                            echo $regDate;

                                            ?>
                                        </b>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small>Kategori Klub</small>
                                    <div><b id="club-category"><?= $tktdata["CATEGORY"] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Link Klub</small>
                                    <div><b id="club-link"><?= $tktdata["CLUB_LINK"] != "" ? $tktdata["CLUB_LINK"] : "-" ?></b></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>Status Klub</small>
                                    <div><b>
                                            <?php

                                            if ($tktdata["EXPIRE_DATE"] != null) {
                                                $expDate = strtotime($tktdata["EXPIRE_DATE"]);
                                                echo "bruh";
                                            } else {
                                                $regDate = floor($tktdata["DATE"] / 1000);
                                                $expDate = strtotime("+1 year", date("d M Y", $regDate));
                                                // echo "hurb";
                                                // echo $regDate;
                                            }
                                            if (time() < $expDate) {
                                                echo "<span class='text-green'>Aktif</span>";
                                            } else {
                                                echo "<span class='text-red'>Kadaluarsa</span>";
                                            }

                                            ?>
                                        </b>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small>No. Sertifikat</small>
                                    <div><b><?= pathinfo($tktdata["CERTIFICATE"], PATHINFO_BASENAME) ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Aktif Sampai</small>
                                    <div><b><?= $tktdata['EXPIRE_DATE'] != "" ? $tktdata['EXPIRE_DATE'] : "-" ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Pembaruan</small>
                                    <div><b><?= $tktdata['RENEWAL'] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Deskripsi</small>
                                    <div><b id="club-desc"><?= $tktdata['CLUB_DESC'] ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-dark mt-2"><strong>Alamat</strong></h2>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>Alamat Lengkap</small>
                                    <div><b><?= $tktdata['ADDRESS'] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Kecamatan</small>
                                    <div><b><?= ucwords(strtolower($district['DIS_NAME'])) ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Provinsi</small>
                                    <div><b><?= ucwords(strtolower($prov['PROV_NAME'])) ?></b></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>Desa</small>
                                    <div><b><?= ucwords(strtolower($subdistrict['SUBDIS_NAME'])) ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Kota/Kabupaten</small>
                                    <div><b><?= ucwords(strtolower($city['CITY_NAME'])) ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Kodepos</small>
                                    <div><b><?= $tktdata['POSTCODE'] ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-dark mt-2"><strong>Dokumen</strong></h2>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>AD/ART</small>
                                    <div><b><?= $tktdata["AD_ART"] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Dokumen Sertifikat</small>
                                    <div><b>-</b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Tanggal Cetak</small>
                                    <div><b>-</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="section-bank-left" class="p-2 mt-4" style="width: 99%; background-color: white; border-radius: 20px">
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-dark mt-2"><strong>Informasi Bank</strong></h2>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>Nama Bank</small>
                                    <div><b><?= $tktdata["BANK"] ?></b></div>
                                </div>
                                <div class="mt-2">
                                    <small>Nama Akun</small>
                                    <div><b><?= $tktdata["BANK_NAME"] ?></b></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-2">
                                    <small>No Rekening</small>
                                    <div><b><?= $tktdata["BANK_NUMBER"] ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div id="section-history" class="p-2" style="width: 99%; background-color: white; border-radius: 20px">
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-dark mt-2"><strong>Riwayat Transaksi</strong></h2>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table id="tableTKT" class="table">
                                    <thead class="table-secondary">
                                        <th>Nama/Email</th>
                                        <th>No KTA</th>
                                        <th>Status</th>
                                        <th>Jabatan</th>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($payment_history as $payment) {

                                            $mil = $payment['DATE'];
                                            $seconds = floor($mil / 1000);
                                            $date = date("d M Y", $seconds);
                                            $time = date("H:i", $seconds);
                                        ?>
                                            <tr>
                                                <td>
                                                    <div><?= $date ?></div>
                                                    <div><?= $time ?></div>
                                                </td>
                                                <td><?= $payment["METHOD"] ?></td>
                                                <td>
                                                    <div><?= $date ?></div>
                                                    <div><?= $time ?></div>
                                                </td>
                                                <td><?= $payment["STATUS"] == 1 ? "Berhasil" : "Gagal" ?></td>
                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="section-members" class="p-2 mt-4" style="width: 99%; background-color: white; border-radius: 20px">
                    <div class="container-fluid mb-3 header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-dark mt-2"><strong>Daftar Anggota</strong></h2>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table id="tableMembers" class="table">
                                    <thead class="table-secondary">
                                        <th>Nama/Email</th>
                                        <th>No KTA</th>
                                        <th>Status</th>
                                        <th>Jabatan</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($club_members as $member) {

                                            $seconds = strtotime($member["EXPIRED_DATE"]);
                                            $current = time();
                                            $date_only = date("d M Y", $seconds);
                                            // echo $seconds;
                                        ?>
                                            <tr>
                                                <td>
                                                    <div><?= $member["NAME"] ?></div>
                                                    <div><?= $member["EMAIL"] ?></div>
                                                </td>
                                                <td><?= $member["NO_ANGGOTA"] ?></td>
                                                <td>
                                                    <div><?= time() < $seconds ? "<span class='text-green'>Aktif</span>" : "<span class=''Kadaluarsa" ?></div>
                                                    <div>s/d <?= $date_only ?></div>
                                                </td>
                                                <td><?= $member["MANAGER"] == null ? "Anggota" : $member["MANAGER"]?></td>
                                            </tr>

                                        <?php } ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEdit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="modal-title" id="exampleModalLabel" style="color:black;"><strong>Edit Informasi Klub</strong></h5>
                            </div>
                            <div class="col-6">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img style="width: 25px" src="/gaspol_web/assets/img/action_close.png"></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body mt-3">
                    </div>
                    <div class="modal-footer" style="justify-content: flex-start; border-top: none">
                        <div onclick="simpanEdit()" style="cursor:pointer; background-color:#FF6B00; padding: 5px; border-radius: 20px; color: white; padding-left: 25px; padding-right: 25px; width: 100px; height: 35px">Simpan</div>
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

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script> -->


        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            var table;
            var table_members;

            $(document).ready(function() {
                table = $('#tableTKT').DataTable({
                    scrollX: true,
                    dom: `
                    <'row mb-3 align-items-center'
                        <'col-4 px-0 search-table'f>
                        <'col-8 table-pagination filter-buttons d-flex align-items-center justify-content-end'p>
                    >
                    <'row my-3 filters d-none'>
                    <'row't>`
                })

                $('#tableTKT_filter').html(`
                    <img src="assets/img/tab5/search-black.png" style="width:25px;height:25px">
                    <input type="search" class="" placeholder="Cari metode atau status" aria-controls="tableKTA">
                `)

                table_members = $('#tableMembers').DataTable({
                    scrollX: true,
                    dom: `
                    <'row mb-3 align-items-center'
                        <'col-4 px-0 search-table'f>
                        <'col-8 table-pagination filter-buttons d-flex align-items-center justify-content-end'p>
                    >
                    <'row my-3 filters d-none'>
                    <'row't>`
                })

                $('#tableMembers_filter').html(`
                    <img src="assets/img/tab5/search-black.png" style="width:25px;height:25px">
                    <input type="search" class="" placeholder="Cari metode atau status" aria-controls="tableKTA">
                `)

                $('.table-pagination.filter-buttons').prepend(
                    `<div class="filter-download-button">
                        <a id="toggle-download"><img src="../assets/img/table-download.png"></a>
                    </div>`
                );
            })

            function toggleEdit() {
                let editModalContent = `
                <div class="row">
                    <div class="col-6">
                        <small>Nama</small>
                        <input id="tkt-name" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $tktdata['CLUB_NAME'] ?>">
                    </div>
                    <div class="col-6">
                        <small>Jenis Kendaraan</small>
                        <input id="tkt-type" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <small>Kategori Klub</small>
                        <select id="tkt-category" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px">
                            <?php foreach ($taa_category as $cat) { ?>
                                <option <?= $cat["ID"] == $tktdata["CLUB_CATEGORY"] ? "selected" : "" ?> value="<?= $cat["ID"] ?>"><?= $cat["CATEGORY"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <small>Link Klub</small>
                        <input id="tkt-link" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $tktdata["CLUB_LINK"] ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <small>Deskripsi</small>
                        <input id="tkt-desc" class="form-control" style="border:none; border-bottom: 1px solid #d0d0d0; color: black; padding-left: 0px" value="<?= $tktdata['CLUB_DESC'] ?>">
                    </div>
                </div>
                <input type="hidden" id="tkt-id" value="<?= $tkt_id ?>" />
                `;

                $('#modalEdit .modal-body').html(editModalContent);
                $('#modalEdit').modal('show');
            }

            function simpanEdit() {

                var name = $('#tkt-name').val();
                var desc = $('#tkt-desc').val();
                var link = $('#tkt-link').val();
                var type = $('#tkt-type').val();
                var categoryId = $('#tkt-category').val();
                var categoryName = $('#tkt-category option:selected').text();
                var tktId = $('#tkt-id').val();

                var formData = new FormData();
                formData.append('desc', desc);
                formData.append('name', name);
                formData.append('link', link);
                formData.append('category', categoryId);
                // formData.append('type', type);
                formData.append('id', tktId);

                for (var pair of formData.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }
                console.log('categoryname', categoryName);

                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                        console.log(xmlHttp.responseText);

                        var result = xmlHttp.responseText;

                        if (result == 1) {
                            $('#modalEdit').modal('hide');

                            $('#club-name').text(name);
                            $('#club-desc').text(desc);
                            $('#club-category').text(categoryName);
                            $('#club-link').text(link);
                        }

                    }
                }
                xmlHttp.open("post", "logics/edit_tkt_admin");
                xmlHttp.send(formData);

            }
        </script>

        <?php
        // require_once('gaspol_fb.php');
        ?>

</body>

</html>