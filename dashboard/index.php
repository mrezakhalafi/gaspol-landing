<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
// include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/logics/dashboard_session_check.php');
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

// $f_pin_admin = '0275f69fe1';

// CHECK USER ID

if (!isset($admin)) {
    header("Location: http://108.136.138.242/gaspol_web/pages/gaspol-landing/");
}

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}

function cal_percentage($num_amount, $num_total) {
    $count1 = $num_amount / $num_total;
    $count2 = $count1 * 100;
    $count = number_format($count2, 0);
    return $count;
}

// GET GASPOL USER
$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE BE = 282");
$query->execute();
$userCount = $query->get_result();
$query->close();

// GET PERCENTAGE THIS MONTH OF GASPOL USER

$day = date('d');
$month = date('m');
$year = date ('Y');

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE CREATED_DATE LIKE '%".$year."-".$month."%'");
$query->execute();
$userMonthPercentage = $query->get_result();
$query->close();

$increaseUserPercentage = cal_percentage(mysqli_num_rows($userMonthPercentage),mysqli_num_rows($userCount));

// GET KTA
$query = $dbconn->prepare("SELECT * FROM KTA");
$query->execute();
$kta = $query->get_result();
$query->close();

// GET PERCENTAGE THIS MONTH OF KTA

$query = $dbconn->prepare("SELECT * FROM KTA WHERE CREATED_DATE LIKE '%".$year."-".$month."%'");
$query->execute();
$ktaThisMonth = $query->get_result();
$query->close();

$increaseKTAPercentage = cal_percentage(mysqli_num_rows($ktaThisMonth),mysqli_num_rows($kta));

// GET TKT
$query = $dbconn->prepare("SELECT * FROM TKT");
$query->execute();
$tkt = $query->get_result();
$query->close();

// GET KIS
$query = $dbconn->prepare("SELECT * FROM KIS");
$query->execute();
$kis = $query->get_result();
$query->close();

// GET DOWNLOAD KTA
$query = $dbconn->prepare("SELECT * FROM DOWNLOAD_CARD");
$query->execute();
$download = $query->get_result();
$query->close();

// GET TOTAL REVENUE
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT");
$query->execute();
$revenue = $query->get_result();
$query->close();

$totalRevenue = 0;

foreach ($revenue as $rv){
    $totalRevenue += $rv['PRICE'];
}

$totalRevenue = rupiah($totalRevenue);

// GET KTA MOBILITY REVENUE
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT WHERE REG_TYPE = 2");
$query->execute();
$revenueKTAM = $query->get_result();
$query->close();

$totalKTAMRevenue = 0;

foreach ($revenueKTAM as $rvktam){
    $totalKTAMRevenue += $rvktam['PRICE'];
}

// GET KTA PRO REVENUE
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT WHERE REG_TYPE = 3");
$query->execute();
$revenueKTAP = $query->get_result();
$query->close();

$totalKTAPRevenue = 0;

foreach ($revenueKTAP as $rvktap){
    $totalKTAPRevenue += $rvktap['PRICE'];
}

// GET REKAN REVENUE
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT WHERE REG_TYPE = 6");
$query->execute();
$revenueRekan = $query->get_result();
$query->close();

$totalRevenueRekan = 0;

foreach ($revenueRekan as $rvr){
    $totalRevenueRekan += $rvr['PRICE'];
}

// GET OTHERS REVENUE
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_PAYMENT WHERE REG_TYPE != 3 AND REG_TYPE != 2 AND REG_TYPE != 6");
$query->execute();
$revenueOthers = $query->get_result();
$query->close();

$totalRevenueOthers = 0;

foreach ($revenueOthers as $rvo){
    $totalRevenueOthers += $rvo['PRICE'];
}

// GET UPGRADE (JUST KIS)
$query = $dbconn->prepare("SELECT * FROM KIS");
$query->execute();
$upgradeKIS = $query->get_result();
$query->close();

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

// KIS CHART
$query = $dbconn->prepare("SELECT * FROM KIS WHERE KATEGORI LIKE '%A1%'");
$query->execute();
$kisA1 = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM KIS WHERE KATEGORI LIKE '%B1%'");
$query->execute();
$kisB1 = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM KIS WHERE KATEGORI LIKE '%C1%'");
$query->execute();
$kisC1 = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM KIS WHERE KATEGORI LIKE '%D1%'");
$query->execute();
$kisD1 = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM KIS WHERE KATEGORI LIKE '%D2%'");
$query->execute();
$kisD2 = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM KIS");
$query->execute();
$kisOthers = $query->get_result();
$query->close();

$totalKisOthers = 0;
$totalKis = 0;

foreach($kisOthers as $ko){

    $arrayCat = $ko['KATEGORI'];

    $categoryAll = explode("|", $arrayCat);
    $category = explode("|", $arrayCat);

    $index = array_search('A1',$category);
    $index2 = array_search('B1',$category);
    $index3 = array_search('C1',$category);
    $index4 = array_search('D1',$category);
    $index5 = array_search('D2',$category);

    if($index !== FALSE){
        unset($category[$index]);
    }

    if($index2 !== FALSE){
        unset($category[$index2]);
    }

    if($index3 !== FALSE){
        unset($category[$index3]);
    }

    if($index4 !== FALSE){
        unset($category[$index4]);
    }

    if($index5 !== FALSE){
        unset($category[$index5]);
    }

    $totalKisOthers = $totalKisOthers + count($category);
    $totalKis = $totalKis + count($categoryAll);

}

// TKT CHART 

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = 12");
$query->execute();
$tktJB = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = 13");
$query->execute();
$tktJTE = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = 15");
$query->execute();
$tktJTI = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = 6");
$query->execute();
$tktSS = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE = 17");
$query->execute();
$tktB = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TKT WHERE PROVINCE != 17 AND PROVINCE != 6 AND PROVINCE != 15 AND PROVINCE != 13 AND PROVINCE != 12");
$query->execute();
$tktOthers = $query->get_result();
$query->close();

$query = $dbconn->prepare("SELECT * FROM TAA");
$query->execute();
$taa = $query->get_result();
$query->close();

$countUserGaspol = mysqli_num_rows($userCount);
$countKtaMonth = mysqli_num_rows($ktaThisMonth);
$countUpKis = mysqli_num_rows($upgradeKIS);
$countTAA = mysqli_num_rows($taa);
$formatCount = number_format($countUserGaspol, 0, ',', '.');

// GET PERCENTAGE THIS MONTH OF KTA

// $query = $dbconn->prepare("SELECT * FROM TAA WHERE CREATED_DATE LIKE '%".$year."-".$month."%'");
// $query->execute();
// $taaThisMonth = $query->get_result();
// $query->close();

// $increaseTAAPercentage = cal_percentage(mysqli_num_rows($taaThisMonth),mysqli_num_rows($taa));

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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous"> -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/jquery-ui.css?v=4">
    <link rel="stylesheet" href="css/paliobutton.css?v=910928953">
    <link rel="stylesheet" href="css/paliopay.css?v=4">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <style>

        .text-black{
            color: black;
            margin-left: 10px;
            margin-right: 20px;
        }

        .bg-gradient-dark {
            background-color: white !important;
            /* background-image: url('assets/img/side-pane.jpg'); */
            background-size: 125% 125%;
        }

        .navbar-list {
            color: #000000
        }

        .sidebar-divider {
            background-color: #F5F5F5
        }

        select:focus {
            outline:none;
        }

        ul.horizontal-slide {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            display: block;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        ul.horizontal-slide::-webkit-scrollbar {
            display: none;
        }

        ul.horizontal-slide li {
            display: inline-block;
            width: 400px;
            vertical-align: bottom;
        }

        .selectize-input{
            border: none !important;
        }

        .selectize-dropdown{
            width: 150px !important;
            padding: 10px !important;
            margin-left: -50px !important;
            font-weight: bold !important;
        }

        .option{
            padding-top: 15px !important;
            padding-bottom: 15px !important;
            /* border-bottom: 1px solid #e6e6e6; */
        }

        .selectize-control{
            width: 120px !important;
        }

        .item{
            padding-right: 20px !important;
        }

        .selectize-input.focus {
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }

        [data-value="1"] {
            border-bottom: 1px solid #e6e6e6;
            border-top: 1px solid #e6e6e6;
        }

    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row mt-5">
                        <!-- <div class="col-12">
                            <h5 style="font-size: 25px; color: black; font-weight: 800">Selamat Datang</h5>
                        </div> -->
                    </div>

                    <div id="section-one">
                        <div class="row">
                            <ul id="summary-horizontal" class="nav nav-tabs horizontal-slide" role="tablist" style="border-bottom: none">
                                <li class="nav-item">
                                    <div class="ml-4 mr-4 mt-2">
                                        <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <img src="assets/img/logo-gaspol.svg" style="padding-top: 8px; width: 70px; height: auto">
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-1 mb-1" style="font-size: 12px; color: #454545">Pengguna Gaspol</div>
                                                    <div class="mt-1 mb-1" style="font-size: 24px; font-weight: 700; color: black"><?= $formatCount ?></div>
                                                    <div class="mt-1 mb-1" style="font-size: 11px"><img src="assets/img/arrow.svg?v=2" style="width: 20px; height: 20px; margin-right: 10px; margin-top: -2px"><?= $increaseUserPercentage ?>% of this month</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <img src="assets/img/chevron.svg" style="width: 30px; margin-top: 27px; height: auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="mr-4 mt-2">
                                        <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <img src="assets/img/switch.svg" style="padding-top: 8px; width: 70px; height: auto">
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-1 mb-1" style="font-size: 12px; color: #454545">Pengguna Aktif Bulanan</div>
                                                    <div class="mt-1 mb-1" style="font-size: 24px; font-weight: 700; color: black"><?= $formatCount ?></div>
                                                    <div class="mt-1 mb-1" style="font-size: 11px"><?= $increaseUserPercentage ?>% from total user</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <img src="assets/img/chevron.svg" style="width: 30px; margin-top: 27px; height: auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="mr-4 mt-2">
                                        <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <img src="assets/img/group user.svg" style="padding-top: 8px; width: 70px; height: auto">
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-1 mb-1" style="font-size: 12px; color: #454545">Pendaftar Baru KTA</div>
                                                    <div class="mt-1 mb-1" style="font-size: 24px; font-weight: 700; color: black"><?= number_format($countKtaMonth, 0, ',', '.') ?></div>
                                                    <div class="mt-1 mb-1" style="font-size: 11px"><img src="assets/img/arrow.svg?v=2" style="width: 20px; height: 20px; margin-right: 10px; margin-top: -2px"><?= $increaseKTAPercentage ?>% this month</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <img src="assets/img/chevron.svg" style="width: 30px; margin-top: 27px; height: auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="mr-4 mt-2">
                                        <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <img src="assets/img/followed.svg" style="padding-top: 8px; width: 70px; height: auto">
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-1 mb-1" style="font-size: 12px; color: #454545">Upgrade/Downgrade</div>
                                                    <div class="mt-1 mb-1" style="font-size: 24px; font-weight: 700; color: black"><?= number_format($countUpKis, 0, ',', '.') ?></div>
                                                    <div class="mt-1 mb-1" style="font-size: 11px"><img src="assets/img/calendar.svg" style="width: 20px; height: 20px; margin-right: 10px; margin-top: -2px">This month</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <img src="assets/img/chevron.svg" style="width: 30px; margin-top: 27px; height: auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="mr-4 mt-2">
                                        <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <img src="assets/img/imi.svg" style="padding-top: 8px; width: 70px; height: auto">
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-1 mb-1" style="font-size: 12px; color: #454545">Total TAA</div>
                                                    <div class="mt-1 mb-1" style="font-size: 24px; font-weight: 700; color: black"><?= number_format($countTAA, 0, ',', '.') ?></div>
                                                    <div class="mt-1 mb-1" style="font-size: 11px"><img src="assets/img/arrow.svg?v=2" style="width: 20px; height: 20px; margin-right: 10px; margin-top: -2px"><?= $increaseUserPercentage ?>% of this month</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <img src="assets/img/chevron.svg" style="width: 30px; margin-top: 27px; height: auto">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="section-two" class="mt-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                    <div class="row">
                                        <div class="col-8">
                                            <div style="font-size: 15px">Total Pendapatan</div>
                                            <p class="mt-1" style="font-size: 22px; font-weight: 700; color: black"><?= $totalRevenue ?></p>
                                        </div>
                                        <div class="col-4 d-flex justify-content-end">
                                            <span style="margin-top: 25px; font-size: 14px; margin-right: 10px">Lihat rincian</span>
                                            <img src="assets/img/chevron.svg" style="width: 30px; height: auto">
                                        </div>
                                    </div>
                                    <canvas id="chartBar"></canvas>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                    <div class="row">
                                        <div class="col-4">
                                            <div style="font-size: 15px">Total Anggota KTA</div>
                                            <p class="mt-1" style="font-size: 22px; font-weight: 700; color: black"><?= mysqli_num_rows($kta) ?></p>
                                        </div>
                                        <div class="col-5">
                                            <div class="row" style="font-size: 14px; margin-top: 25px">
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div style="width: 10px; height: 10px; border-radius: 100px; margin-top: 5px; margin-right: 10px; background-color: #27099D"></div><span>Mobility</span><span id="mobility-count-show" style="font-weight: bold; margin-left: 20px; color: black">0</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div style="width: 10px; height: 10px; border-radius: 100px; margin-top: 5px; margin-right: 10px; background-color: #1A73E8"></div><span>Pro</span><span id="pro-count-show" style="font-weight: bold; margin-left: 20px; color: black">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 d-flex justify-content-end pt-3">
                                            <select id="ktaMenu" aria-label="Default select example" style="margin-top: -50px; border: none; background-color: transparent; color: grey">
        
                                                <option value="2">Minggu</option>    
                                                <option value="1">Bulan</option>
                                                <option value="0" selected>Tahun</option>

                                            </select>
                                        </div>
                                    </div>
                                    <canvas id="chartLine"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="section-three" class="mt-4 mb-5">
                        <div class="row">
                            <div class="col-8">
                                <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                    <div class="row">
                                        <div class="col-6">
                                            <div style="font-size: 15px">Total TKT (Klub)</div>
                                            <p class="mt-1" style="font-size: 22px; font-weight: 700; color: black"><?= mysqli_num_rows($tkt) ?></p>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <span style="margin-top: 25px; font-size: 14px; margin-right: 10px">Lihat rincian</span>
                                            <img src="assets/img/chevron.svg" style="width: 30px; height: auto">
                                        </div>
                                    </div>
                                    <canvas id="chartBar2"></canvas>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="single-card" style="background-color: white; border-radius: 20px; padding: 15px">
                                    <div class="row">
                                        <div class="col-6">
                                            <div style="font-size: 15px">Total Penjualan KIS</div>
                                            <p class="mt-1" style="font-size: 22px; font-weight: 700; color: black"><?= $totalKis ?></p>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <span style="margin-top: 25px; font-size: 14px; margin-right: 10px">Lihat rincian</span>
                                            <img src="assets/img/chevron.svg" style="width: 30px; height: auto">
                                        </div>
                                    </div>
                                    <canvas id="chartDoughnut"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
        // require_once('gaspol_fb.php');
    ?>  

</body>

</html>

<script>

    $('#ktaMenu').selectize({
        onInitialize: function() {
            this.$control_input.attr('readonly', true);
        }
    });

    var dataKTAMYear = [];
    var dataKTAPYear = [];

    var dataKTAMMonth = [];
    var dataKTAPMonth = [];

    var dataKTAMWeek = [];
    var dataKTAPWeek = [];

    const labelsBar = [
        'KTA Pro',
        'KTA Mobility',
        'Rekan',
        'Lainnya'
    ];

    const labelsBar2 = [
        ''
    ];

    const labelsLineYear = [];
    var arrayLineYear = [];
    var arrayLineMonth = [];

    var monthName = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    var monthNum = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
    var now = new Date();

    const summaryContainer = document.querySelector("#summary-horizontal");

    summaryContainer.addEventListener("wheel", (evt) => {
        evt.preventDefault();
        summaryContainer.scrollLeft += evt.deltaY;
    });

    for (var i = 0; i < 12; i++) {
        var future = new Date(now.getFullYear(), now.getMonth() - i, 1);
        var month = monthName[future.getMonth()];
        var numberMonth = monthNum[future.getMonth()];
        var year = future.getFullYear();
        var year_num = year.toString().substr(2,4);

        labelsLineYear.push(month+" "+year_num);
        arrayLineMonth.push(numberMonth);
        arrayLineYear.push(year);

        if (labelsLineYear.length == 12){

            getKTAYear(arrayLineMonth, arrayLineYear, 0);

        }
    }

    const dataBar = {
        labels: labelsBar,
        datasets: [{
        backgroundColor: ['#27099D','#1A73E8','#1AC3E8','#777777'],
        borderColor: 'rgb(255, 99, 132)',
        borderRadius: 20,
        barThickness: 30,
        borderSkipped: false,
        borderDash: [10,10],
        data: [<?= $totalKTAPRevenue ?>, <?= $totalKTAMRevenue ?>, <?= $totalRevenueRekan ?>, <?= $totalRevenueOthers ?>],
        }]
    };

    const dataBar2 = {
        labels: ['','','','','','','',''],
        datasets: [
            {
                label: ['Jawa Barat'],
                backgroundColor: ["#000080"],
                data: [null,<?= mysqli_num_rows($tktJB) ?>,null,null,null,null,null,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            },
            {
                label: ['Jawa Tengah'],
                backgroundColor: ['#1A73E8'],
                data: [null,null,<?= mysqli_num_rows($tktJTE) ?>,null,null,null,null,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            },
            {
                label: ['Jawa Timur'],
                backgroundColor: ['#1AC3E8'],
                data: [null,null,null,<?= mysqli_num_rows($tktJTI) ?>,null,null,null,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            },
            {
                label: ['Sumatera Selatan'],
                backgroundColor: ['#12A200'],
                data: [null,null,null,null,<?= mysqli_num_rows($tktSS) ?>,null,null,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            },
            {
                label: ['Bali'],
                backgroundColor: ['#B9D83E'],
                data: [null,null,null,null,null,<?= mysqli_num_rows($tktB) ?>,null,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            },
            {
                label: ['Lainnya'],
                backgroundColor: ['#777777'],
                data: [null,null,null,null,null,null,<?= mysqli_num_rows($tktOthers) ?>,null],
                borderRadius: 20,
                barThickness: 30,
                borderSkipped: false
            }
        ]
    };

    const dataDoughnut = {
    labels: [
        'C1 - Motor',
        'A1 - Mobil',
        'B1 - Mobil',
        'D1 - Digital',
        'D2 - Digital',
        'Lainnya'
    ],
    datasets: [{
        data: [<?= mysqli_num_rows($kisC1) ?>,<?= mysqli_num_rows($kisA1) ?>,<?= mysqli_num_rows($kisB1) ?>,<?= mysqli_num_rows($kisD1) ?>,<?= mysqli_num_rows($kisD2) ?>,<?= $totalKisOthers ?>],
        backgroundColor: [
        '#27099D',
        '#1A73E8',
        '#1AC3E8',
        '#12A200',
        '#B9D83E',
        '#777777'
        ],
        hoverOffset: 4
    }]
    };

    const configBar = {
        type: 'bar',
        data: dataBar,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 100000,
                        callback: function(value) {

                            var ranges = [
                                { divider: 1e6, suffix: 'M' },
                                { divider: 1e3, suffix: 'k' }
                            ];

                            function formatNumber(n) {
                                for (var i = 0; i < ranges.length; i++) {
                                    if (n >= ranges[i].divider) {
                                        return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                    }
                                }
                                return n;
                            }
                            return formatNumber(value);
                        }
                    },
                    grid: {
                        borderColor:'white',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        drawOnChartArea:false,
                        borderColor:'white'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
        }
    };

    const configBar2 = {
        type: 'bar',
        data: dataBar2,
        options: {
            categoryPercentage: 0.2,
            barPercentage: 1,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderColor:'white',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    display: false,
                    grid: {
                        borderColor:'white'
                    }
                }
            },
            aspectRatio: 3.5,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 12,
                        boxHeight: 8,
                        padding: 23,
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    };

    const configDoughnut = {
        type: 'doughnut',
        data: dataDoughnut,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    display: false
                }
            },
            aspectRatio: 1.66,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 13,
                        boxHeight: 9,
                        padding: 23,
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    };

    const chartBar = new Chart(
        document.getElementById('chartBar'),
        configBar
    );

    const chartBar2 = new Chart(
        document.getElementById('chartBar2'),
        configBar2
    );

    const chartDoughnut = new Chart(
        document.getElementById('chartDoughnut'),
        configDoughnut
    );

    function getKTAYear(month,year,status){

        var month = month;
        var year = year;
        var status = status;

        var formData = new FormData();
        formData.append('month', JSON.stringify(month));
        formData.append('year', JSON.stringify(year));

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
     
                // console.log(xmlHttp.responseText);
                var result = xmlHttp.responseText.split("|");
                var ktam = JSON.parse(result[0]);
                var ktap = JSON.parse(result[1]);

                dataKTAMYear = ktam;
                dataKTAPYear = ktap;

                showKTAYear(dataKTAMYear, dataKTAPYear, status);

            }

        }
        xmlHttp.open("post", "logics/get_kta_year");
        xmlHttp.send(formData);

    }

    var labelLinesMonth = [];
    var labelLinesWeek = [];

    function getKTAMonth(day, numberMonth, year){

        var day = day;
        var numberMonth = numberMonth;
        var year = year;

        var formData = new FormData();
        formData.append('day', JSON.stringify(day));
        formData.append('month', JSON.stringify(numberMonth));
        formData.append('year', JSON.stringify(year));

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);
                var result = xmlHttp.responseText.split("|");
                var ktam = JSON.parse(result[0]);
                var ktap = JSON.parse(result[1]);

                dataKTAMMonth = ktam;
                dataKTAPMonth = ktap;

                showKTAMonth(labelLinesMonth, dataKTAMMonth, dataKTAPMonth);

            }

        }
        xmlHttp.open("post", "logics/get_kta_month");
        xmlHttp.send(formData);

    }

    function getKTAWeek(day, numberMonth, year){

        var day = day;
        var numberMonth = numberMonth;
        var year = year;

        var formData = new FormData();
        formData.append('day', JSON.stringify(day));
        formData.append('month', JSON.stringify(numberMonth));
        formData.append('year', JSON.stringify(year));

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                // console.log(xmlHttp.responseText);
                var result = xmlHttp.responseText.split("|");
                var ktam = JSON.parse(result[0]);
                var ktap = JSON.parse(result[1]);

                dataKTAMWeek = ktam;
                dataKTAPWeek = ktap;

                showKTAWeek(labelLinesWeek, dataKTAMWeek, dataKTAPWeek);

            }

        }
        xmlHttp.open("post", "logics/get_kta_week");
        xmlHttp.send(formData);

    }

    var chartLine;
    labelsLineYear.reverse();

    function showKTAYear(dataKTAMYear, dataKTAPYear, status){

        if (status == 1){
            chartLine.destroy();
        }

        console.log(dataKTAMYear);
        console.log(dataKTAPYear);

        dataKTAMYear.reverse();
        dataKTAPYear.reverse();

        const dataLine = {
            labels: labelsLineYear,
            datasets: [
                {
                    label: "Mobility",
                    backgroundColor: ['#27099D'],
                    borderColor: '#27099D',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAMYear
                },
                {
                    label: "Pro",
                    backgroundColor: ['#1A73E8'],
                    borderColor: '#1A73E8',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAPYear
                }
            ]
        };


        const configLine = {
            type: 'line',
            data: dataLine,
            options: {
                elements: {
                    point:{
                        // radius: 0
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            borderColor: 'white'
                        }
                    },
                    x: {
                        grid: {
                            borderColor: 'white',
                            drawOnChartArea: false,
                        }
                    }
                },
                aspectRatio: 4.2,
                plugins: {
                    legend: {
                        // position: 'top',
                        // labels: {
                        //     usePointStyle: true,
                        //     boxWidth: 12,
                        //     boxHeight: 8,
                        //     padding: 23,
                        //     font: {
                        //         size: 14
                        //     }
                        // }
                        display: false
                    }
                }
            }
        };

        chartLine = new Chart(
            document.getElementById('chartLine'),
            configLine
        );

        $('#mobility-count-show').text(dataKTAMYear.reduce((a, b) => a + b, 0));
        $('#pro-count-show').text(dataKTAPYear.reduce((a, b) => a + b, 0));

        dataKTAMYear = [];
        dataKTAPYear = [];

    }

    function showKTAMonth(labelLinesMonth, dataKTAMMonth, dataKTAPMonth){

        labelLinesMonth.reverse();
        dataKTAMMonth.reverse();
        dataKTAPMonth.reverse();

        console.log(dataKTAMMonth);
        console.log(dataKTAPMonth);
        console.log(labelLinesMonth);

        chartLine.destroy();

        const dataLine = {
            labels: labelLinesMonth,
            datasets: [
                {
                    label: "Mobility",
                    backgroundColor: ['#27099D'],
                    borderColor: '#27099D',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAMMonth
                },
                {
                    label: "Pro",
                    backgroundColor: ['#1A73E8'],
                    borderColor: '#1A73E8',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAPMonth
                }
            ]
        };


        const configLine = {
            type: 'line',
            data: dataLine,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                },
                aspectRatio: 4.2,
                plugins: {
                    legend: {
                        // position: 'top',
                        // labels: {
                        //     usePointStyle: true,
                        //     boxWidth: 12,
                        //     boxHeight: 8,
                        //     padding: 23,
                        //     font: {
                        //         size: 14
                        //     }
                        // }
                        display: false
                    }
                }
            }
        };

        chartLine = new Chart(
            document.getElementById('chartLine'),
            configLine
        );

        $('#mobility-count-show').text(dataKTAMMonth.reduce((a, b) => a + b, 0));
        $('#pro-count-show').text(dataKTAPMonth.reduce((a, b) => a + b, 0));

    }

    function showKTAWeek(labelLinesWeek, dataKTAMWeek, dataKTAPWeek){

        labelLinesWeek.reverse();
        dataKTAMWeek.reverse();
        dataKTAPWeek.reverse();

        console.log(dataKTAMWeek);
        console.log(dataKTAPWeek);
        console.log(labelLinesWeek);

        chartLine.destroy();

        const dataLine = {
            labels: labelLinesWeek,
            datasets: [
                {
                    label: "Mobility",
                    backgroundColor: ['#27099D'],
                    borderColor: '#27099D',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAMWeek
                },
                {
                    label: "Pro",
                    backgroundColor: ['#1A73E8'],
                    borderColor: '#1A73E8',
                    borderRadius: 20,
                    barThickness: 30,
                    data: dataKTAPWeek
                }
            ]
        };

        const configLine = {
            type: 'line',
            data: dataLine,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                    x: {
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                aspectRatio: 4.2,
                plugins: {
                    legend: {
                        // position: 'top',
                        // labels: {
                        //     usePointStyle: true,
                        //     boxWidth: 12,
                        //     boxHeight: 8,
                        //     padding: 23,
                        //     font: {
                        //         size: 14
                        //     }
                        // }
                        display: false
                    }
                }
            }
        };

        chartLine = new Chart(
            document.getElementById('chartLine'),
            configLine
        );

        $('#mobility-count-show').text(dataKTAMWeek.reduce((a, b) => a + b, 0));
        $('#pro-count-show').text(dataKTAPWeek.reduce((a, b) => a + b, 0));

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

    $(".selectize-input").click(function(){
        $('*[data-value=1]').css('border-top','1px solid #e6e6e6');
        $('*[data-value=1]').css('border-bottom','1px solid #e6e6e6');

        if ($('#ktaMenu').val() == 0){
            $('*[data-selectable]*[data-value=0]').html('<span>Tahun<img style="float: right" src="/gaspol_web/assets/img/social/Property 1=line.svg"></span>');
        }

    });

    $("#ktaMenu").change(function(){

        $('*[data-value=1]').css('border-top','none');
        $('*[data-value=1]').css('border-bottom','none');
        
        var menu = $(this).val();
        console.log(menu);

        const d = new Date()
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();

        var monthNum = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        var monthName = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        var now = new Date();

        if (menu == 0){

            $('*[data-selectable]*[data-value=0]').html('<span>Tahun<img style="float: right" src="/gaspol_web/assets/img/social/Property 1=line.svg"></span>');
            $('*[data-selectable]*[data-value=1]').html('<span>Bulan</span>');
            $('*[data-selectable]*[data-value=2]').html('<span>Minggu</span>');

            dataKTAMYear = [];
            dataKTAPYear = [];
            var arrayLineYear = [];
            var arrayLineMonth = [];

            for (var i = 0; i < 12; i++) {

                var future = new Date(now.getFullYear(), now.getMonth() - i, 1);
                var numberMonth = monthNum[future.getMonth()];
                var year = future.getFullYear();

                arrayLineYear.push(year);
                arrayLineMonth.push(numberMonth);

                if (arrayLineYear.length == 12){

                    getKTAYear(arrayLineMonth, arrayLineYear, 1);

                }
            }

        }else if(menu == 1){

            $('*[data-selectable]*[data-value=1]').html('<span>Bulan<img style="float: right" src="/gaspol_web/assets/img/social/Property 1=line.svg"></span>');
            $('*[data-selectable]*[data-value=0]').html('<span>Tahun</span>');
            $('*[data-selectable]*[data-value=2]').html('<span>Minggu</span>');

            labelLinesMonth = [];
            dataKTAMMonth = [];
            dataKTAPMonth = [];
            var arrayLineDay = [];
            var arrayLineMonth = [];
            var arrayLineYear = [];

            for (var i = 0; i < 30; i++) {

                var future = new Date(now.getFullYear(), now.getMonth(), now.getDate() - i, 1);
                var day = ("0" + future.getDate()).slice(-2);
                var numberMonth = monthNum[future.getMonth()];
                var nameMonth = monthName[future.getMonth()];
                var year = future.getFullYear();

                labelLinesMonth.push(day+" "+nameMonth);
                arrayLineDay.push(day);
                arrayLineMonth.push(numberMonth);
                arrayLineYear.push(year);

                if (labelLinesMonth.length == 30){
                    getKTAMonth(arrayLineDay, arrayLineMonth, arrayLineYear);
                }

            }

        }else if(menu == 2){

            $('*[data-selectable]*[data-value=2]').html('<span>Minggu<img style="float: right" src="/gaspol_web/assets/img/social/Property 1=line.svg"></span>');
            $('*[data-selectable]*[data-value=1]').html('<span>Bulan</span>');
            $('*[data-selectable]*[data-value=0]').html('<span>Tahun</span>');

            labelLinesWeek = [];
            dataKTAMWeek = [];
            dataKTAPWeek = [];
            var arrayLineDay = [];
            var arrayLineMonth = [];
            var arrayLineYear = [];

            for (var i = 0; i < 7; i++) {

                var future = new Date(now.getFullYear(), now.getMonth(), now.getDate() - i, 1);
                var day = ("0" + future.getDate()).slice(-2);
                var numberMonth = monthNum[future.getMonth()];
                var nameMonth = monthName[future.getMonth()];
                var year = future.getFullYear();

                labelLinesWeek.push(day+" "+nameMonth);
                arrayLineDay.push(day);
                arrayLineMonth.push(numberMonth);
                arrayLineYear.push(year);

                // console.log(future);

                if (labelLinesWeek.length == 7){
                    getKTAWeek(arrayLineDay, arrayLineMonth, arrayLineYear);
                }

            }

        }

    });

    function daysInMonth (month, year) {
        return new Date(year, month, 0).getDate();
    }
    

</script>