<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/logics/dashboard_session_check.php');
session_start();

// print_r($_SESSION['web_login']);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Report

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

// GET KTA
$query = $dbconn->prepare("SELECT 
    kta.F_PIN, 
    kta.NAME,
    kta.EKTP,
    kta.NO_ANGGOTA,
    kta.STATUS_ANGGOTA,
    kta.EMAIL, 
    tkt.CLUB_NAME,
    pr.PROV_NAME,
    ct.CITY_NAME,
    kta.CREATED_DATE,
    kta.CREATED_DATE AS ACTIVE_DATE,
    MAX(rp.DATE) AS TRX_DATE,
    rp.PAYMENT_ID,
    rp.DATE,
    0 AS RENEWAL,
    0 AS CARD_STATUS
    FROM KTA kta
    LEFT JOIN PROVINCE pr ON kta.PROVINCE = pr.PROV_ID
    LEFT JOIN CITIES ct ON kta.CITY = ct.CITY_ID
    LEFT JOIN REGISTRATION_PAYMENT rp ON rp.REF_ID = kta.NO_ANGGOTA
    LEFT JOIN CLUB_MEMBERSHIP cm ON kta.F_PIN = cm.F_PIN
    LEFT JOIN TKT tkt ON tkt.ID = cm.CLUB_CHOICE
    GROUP BY kta.F_PIN");
$query->execute();
$kta = $query->get_result();
$query->close();

// GET KTA MOBILITY
$query = $dbconn->prepare("SELECT * FROM KTA WHERE STATUS_ANGGOTA = 0");
$query->execute();
$ktaMob = $query->get_result();
$query->close();

// GET KTA PRO
$query = $dbconn->prepare("SELECT * FROM KTA WHERE STATUS_ANGGOTA = 1");
$query->execute();
$ktaPro = $query->get_result();
$query->close();

// GET TKT
$query = $dbconn->prepare("SELECT * FROM TKT");
$query->execute();
$tkt = $query->get_result();
$query->close();

// GET PROVINCE
$query = $dbconn->prepare("SELECT * FROM PROVINCE");
$query->execute();
$province = $query->get_result();
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

if ($user['PROVINCE_ID'] == 0) {
    $admin_type = "( Admin Pusat )";
} else {
    $admin_type = "( Admin Provinsi )";
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

    <style>

        .text-black{
            color: black;
            margin-left: 10px;
            margin-right: 20px;
        }

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

        .header .nav.nav-pills {
            border-bottom: 1px solid #e3e3e3;
        }

        .header .nav.nav-pills .nav-link,
        .header .nav.nav-pills .nav-link.active {
            background-color: transparent;
            border-radius: 0;
        }

        .header .nav.nav-pills .nav-link {
            color: #aaa;
        }

        .header .nav.nav-pills .nav-link.active {
            color: black;
            border-bottom: 2px solid #ff6b00;
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

        .text-green {
            color: #12A200 !important;
        }

        .text-red {
            color: #F01010!important;
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

        #modal-download {
            color: black;
        }

        #modal-download .modal-dialog {
            max-width: 650px;
        }

        #modal-download .modal-header,
        #modal-download .modal-footer {
            border: 0;
        }

        #modal-download .modal-footer {
            justify-content: flex-start;
        }

        .modal-header {
            display: inline;
            margin-bottom: -30px;
        }

        #modal-download .modal-body label.report-type,
        label.report-period,
        label.report-format {
            font-size: 12px;
        }

        label.report-period {
            width:100%;
        }

        label.radio-inline {
            font-size: 15px;
        }

        label.radio-inline input {
            margin-right: .75rem;
        }

        .form-select,
        .selectize-input {
            display: block;
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            -moz-padding-start: calc(0.75rem - 3px);
            font-size: 13px;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            /* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); */
            background-image: url('assets/img/nav_chevron.png');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 22px;
            border: 0 !important;
            border-bottom: 1px solid #ced4da !important;
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
            padding: 0.5rem;
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

        #tableKTA_filter {
            background-color: white;
            border-radius: 5px;
            padding: 0.25rem 0.75rem;
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

        input[type="radio"]{
            accent-color: #f66701;
        }

        #tableKTA tbody tr {
            cursor: pointer;
        }

        .tooltip-inner{
            max-width: 200px;
            padding: 0.25rem 0.5rem;
            color: grey;
            text-align: center;
            background-color: #f6f6f6 !important;
            border-radius: 10px;
            box-shadow: 0px 0px 3px #666666;
            font-family: "Poppins", sans-serif;
        }

        .tooltip .arrow{
            display: none;
        }

    </style>

</head>

<body id="page-top">

    <?php include_once("wrapper_sidebar.php") ?>

    <div class="container-fluid mb-4 header mt-4">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active">
                            <h6 class="text-dark"><strong>Manajemen KTA</strong></h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <h6 class="text-darkk"><strong>Klaim Keanggotaan</strong></h6>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <!-- <div class="row filter-download-button">
            <a><img src="../assets/img/table-download.png"></a>
            <a id="toggle-filter"><img src="../assets/img/table-filter.png"></a>
        </div> -->
        <table id="tableKTA" class="table table-striped">
            <thead class="table-secondary">
                <th>Nama /NIK</th>
                <th>Email</th>
                <th>No. KTA/Tgl Daftar</th>
                <th>Tipe KTA</th>
                <th>Status KTA</th>
                <th>Provinsi</th>
                <th>Klub</th>
                <!-- <th>Reg. Date</th> -->
                <th>Transaksi</th>
                <th>Pembaruan</th>
                <th>Cetak</th>
                <th></th>
                <!-- <th>Action</th> -->
            </thead>
            <tbody>
                <?php foreach ($kta as $index => $kt) {

                    $created_date = strtotime($kt["CREATED_DATE"]);
                    $created_date = date("d M Y", $created_date);

                    $expiredTime = strtotime("+1 year", strtotime($kt["CREATED_DATE"]));
                    $expiredDate = date("d M Y", $expiredTime);

                    $trx_date = $kt['TRX_DATE'] != null ? date("d M Y", floor($kt['TRX_DATE'] / 1000)) : "";
                    $reg_date = floor($kt["DATE"] / 1000);

                    $current = time();

                ?>


                    <tr onclick="openDetailKTA('<?= $kt['NO_ANGGOTA'] ?>')">
                        <td>
                            <div class="kta-name"><?= $kt['NAME'] ?></div>
                            <div class="ektp"><?= $kt['EKTP'] ?></div>
                        </td>
                        <td><?= $kt['EMAIL'] ?></td>
                        <td>
                            <div class="kta-id"><?= $kt['NO_ANGGOTA'] ?></div>
                            <div class="kta-club"><?= $created_date ?></div>
                        </td>
                        <?php if ($kt['STATUS_ANGGOTA'] == 1) : ?>
                            <td><b>Pro</b></td>
                        <?php else : ?>
                            <td><b>Mobility</b></td>
                        <?php endif; ?>
                        <td>
                            
                            <?php if ($current < strtotime("+1 year", $reg_date)): ?>
                                <div class="text-green">Active</div>
                                <div class="kta-club">s/d <?= $expiredDate ?></div>
                            <?php elseif($current > strtotime("+1 year", $reg_date) && $reg_date != 0): ?>
                                <div class="text-danger">Expired</div>
                                <div class="kta-club">s/d <?= $expiredDate ?></div>
                            <?php else: ?>
                                <div class="text-green">Active</div>
                                <div class="kta-club">s/d <?= $expiredDate ?></div>
                            <?php endif; ?>

                        </td>
                        <td>
                            <div><?= ucwords(strtolower($kt['PROV_NAME'])) ?></div>
                            <div class="kta-club"><?= ucwords(strtolower($kt['CITY_NAME'])) ?></div>
                        </td>
                        <td>
                            <div><?= ucwords(strtolower($kt['CLUB_NAME'])) ?></div>
                            <?php if($kt['CLUB_NAME']): ?>
                                <div>★★★★</div>
                            <?php else: ?>
                                <div>-</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= $kt['PAYMENT_ID'] ?></div>
                            <div class="kta-club"><?= $created_date ?></div>
                        </td>
                        <!-- <td>
                            <div class="kta-trxdate"><?= $trx_date ?></div>
                            <div class="kta-trxid"><?= $kt['PAYMENT_ID'] ?></div>
                        </td> -->
                        <td><?= $kt["RENEWAL"] ?></td>
                        <td><?= $kt["CARD_STATUS"] == 0 ? "Belum" : "Cetak" ?></td>
                        <td><img src="assets/img/print.svg" style="width:25px; height: 25px"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- TABLE HIDDEN FOR EXPORT DOWNLOAD -->

        <table id="tableHidden" class="table table-striped d-none">
            <thead class="table-secondary">
                <th>Nama/NIK</th>
                <th>Email</th>
                <th>No. KTA/Tgl Daftar</th>
                <th>Tipe KTA</th>
                <th>Status KTA</th>
                <th>Provinsi</th>
                <th>Klub</th>
                <!-- <th>Reg. Date</th> -->
                <th>Transaksi</th>
                <th>Pembaruan</th>
                <th>Cetak</th>
                <th></th>
                <!-- <th>Action</th> -->
            </thead>
            <tbody>
                <?php foreach ($kta as $index => $kt) {

                    $created_date = strtotime($kt["CREATED_DATE"]);
                    $created_date = date("d M Y", $created_date);

                    $trx_date = $kt['TRX_DATE'] != null ? date("d M Y", floor($kt['TRX_DATE'] / 1000)) : "";

                ?>


                    <tr onclick="openDetailKTA('<?= $kt['NO_ANGGOTA'] ?>')">
                        <td>
                            <div class="kta-name"><?= $kt['NAME'] ?></div>
                            <div class="ektp"><?= $kt['EKTP'] ?></div>
                        </td>
                        <td><?= $kt['EMAIL'] ?></td>
                        <td>
                            <div class="kta-id"><?= $kt['NO_ANGGOTA'] ?></div>
                            <div class="kta-club"><?= $created_date ?></div>
                        </td>
                        <?php if ($kt['STATUS_ANGGOTA'] == 1) : ?>
                            <td><b>Pro</b></td>
                        <?php else : ?>
                            <td><b>Mobility</b></td>
                        <?php endif; ?>
                        <td class="text-green">Active</td>
                        <td>
                            <div><?= ucwords(strtolower($kt['PROV_NAME'])) ?></div>
                            <div class="kta-club"><?= ucwords(strtolower($kt['CITY_NAME'])) ?></div>
                        </td>
                        <td>
                            <div><?= ucwords(strtolower($kt['CLUB_NAME'])) ?></div>
                            <?php if($kt['CLUB_NAME']): ?>
                                <div>★★★★</div>
                            <?php else: ?>
                                <div>-</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= $kt['PAYMENT_ID'] ?></div>
                            <div class="kta-club"><?= $created_date ?></div>
                        </td>
                        <!-- <td>
                            <div class="kta-trxdate"><?= $trx_date ?></div>
                            <div class="kta-trxid"><?= $kt['PAYMENT_ID'] ?></div>
                        </td> -->
                        <td><?= $kt["RENEWAL"] ?></td>
                        <td><?= $kt["CARD_STATUS"] == 0 ? "Belum" : "Cetak" ?></td>
                        <td><img src="assets/img/print.svg" style="width:25px; height: 25px"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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

    <!-- Filter modal -->
    <div class="modal" id="modal-download" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row w-100 mx-auto">
                        <div class="col-6">
                            <h5 class="modal-title"><strong>Unduh Laporan</strong></h5>
                        </div>
                        <div class="col-6 text-right">
                            <a data-dismiss="modal" aria-label="Close" style="cursor:pointer;">
                                <img src="../assets/img/filter-close.png" style="width:30px; height:30px;">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row px-3 mt-3">
                        <div class="col-12">
                            <label class="report-type" for="report-type">Report Type</label>
                            <div id="report-type" class="row">
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-trx" value="trx" type="radio" checked/>Transaction
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-date" value="date" type="radio" />Register Date
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-print" value="print" type="radio" />Printing
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-3 mt-3">
                        <div class="col-12">
                            <label class="report-period" for="report-period">Report Period
                                <input type="text" name="period-range" id="period-select" class="form-select" aria-label=".form-select-sm example">
                            </label>
                        </div>
                    </div>
                    <div class="row px-3 mt-3">
                        <div class="col-12">
                            <label class="report-format" for="report-format">Report Format</label>
                            <div id="report-type" class="row">
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-format-csv" value="csv" type="radio" checked/>CSV
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-format-pdf" value="pdf" type="radio" />PDF
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-type-xls" value="xls" type="radio" />Printing
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnDownload" class="btn btn-orange">Download File</button>
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

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

</body>

<style>

    /* CUSTOM PAGINATION STYLE */

    .dataTables_wrapper .dataTables_paginate .paginate_button, .dataTables_wrapper .dataTables_paginate .paginate_button:hover, .dataTables_wrapper .dataTables_paginate .paginate_button:active  {
        background: none;
        color: black!important;
        border: none !important;
    }

    #tableKTA_paginate{
        /* width: 220px !important; */
    }

</style>

</html>

<script>
    var table;
    var table_hidden;
    var min, max, tableDate;

    $(document).ready(function(e) {

        $('#province').selectize();
        table = $('#tableKTA').DataTable({
            "pagingType": "simple",
            "language": {
                "paginate": {
                    "next": '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                    "previous": '<i class="fa fa-angle-left" aria-hidden="true"></i>'
                }
            },
            scrollX: true,
            dom: `
            <'row mb-3 align-items-center'
                <'col-3 search-table'f>
                <'col-7 table-pagination filter-buttons d-flex align-items-center justify-content-end'>
                <'col-2' p>
            >
            <'row my-3 filters d-none'>
            <'row't>
            <'row mt-2'
                <'col-6'>
                <'col-6 d-flex justify-content-end align-items-center table-pagination'p>
            >`
        });

        // FOR CHANGE PAGINATION SECTION

        var info = table.page.info();
        $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Anggota</span>').insertAfter($('.previous'));

        $('#tableKTA').on( 'page.dt', function () {
            
           var info = table.page.info();
           console.log((info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Anggota');

           setTimeout( function(){ 
                $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Anggota</span>').insertAfter($('.previous'));
            },1);
                        
        } );

        // FOR FILTER DATE TO EXPORT

        table_hidden = $('#tableHidden').DataTable();
        $('#tableHidden_wrapper').addClass('d-none');

        // $.fn.dataTable.ext.search.push(
        //     function (settings, data, dataIndex) {
        //         min = $("#period-select").data('daterangepicker').startDate.format('YYYY-MM-DD');
        //         max = $("#period-select").data('daterangepicker').endDate.format('YYYY-MM-DD');
        //         var date = new Date(data[6]);
        //         tableDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().substring(0, 10);

        //         console.log(tableDate);

        //         if (min == null && max == null) { return true; }
        //         if (min == null && tableDate <= max) { return true;}
        //         if (max == null && tableDate >= min) {return true;}
        //         if (tableDate <= max && tableDate >= min) { return true; }
        //         return false;
        //     }
        // );

        // $('.search-table label').text('')<input type="search" class="" placeholder="" aria-controls="tableKTA">

        // $('.search-table label').prepend(`<img src="assets/img/tab5/search-black.png" style="width:20px;height:20px">`);

        $('#tableKTA_filter').html(`
            <img src="assets/img/tab5/search-black.png" style="width:25px;height:25px">
            <input type="search" class="" placeholder="Cari Nama, No KTA atau ID Transaksi" aria-controls="tableKTA">
        `)

        $('#tableKTA_filter input').keyup(function() {
            table.search($(this).val()).draw();
        })

        $('.table-pagination.filter-buttons').prepend(
            `<div class="create-KTA-button mr-3">
                <a href="register_kta.php?m=1">
                    <button class="btn btn-orange">Buat KTA</button>
                </a>
            </div>
            <div class="filter-download-button">&nbsp;&nbsp;&nbsp;
                <a id="toggle-filter"><img src="../assets/img/table-filter.png">&nbsp;<b style="color: black">Filter</b>&nbsp;</a>&nbsp;&nbsp;&nbsp;
                <a id="toggle-download"><img src="../assets/img/table-download.png">&nbsp;<b style="color: black">Unduh</b>&nbsp;</a>&nbsp;&nbsp;&nbsp;
            </div>`
        );

        $('.row.filters').append(
            `<div class="col-2 px-1">
                <select id="kta-type-select" class="form-select" aria-label=".form-select-sm example">
                    <option style="display: none" selected value="">Tipe KTA</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-status-select" class="form-select" aria-label=".form-select-sm example">
                    <option style="display: none" selected value="">Status</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-province-select" aria-label=".form-select-sm example">
                    <option style="display: none" selected value="">Provinsi</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-renewal-select" class="form-select" aria-label=".form-select-sm example">
                    <option style="display: none" selected value="">Pembaruan</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-print-select" class="form-select" aria-label=".form-select-sm example">
                    <option style="display: none" selected value="">Cetak</option>
                </select>
            </div>
            <div class="col-2 text-center">
                <a class="mr-3" onclick="resetFilter();" style="cursor:pointer;" data-bs-toggle="tooltip" data-placement="bottom" title="Reset">
                    <img src="assets/img/reload.png" style="width:25px; height:25px">
                </a>
                <button class="btn btn-orange ml-3" onclick="saveFilter();"><strong>Apply</strong></button>
            </div>`
        );

        $('#toggle-filter').click(function(e) {
            $('.row.filters').toggleClass('d-none').toggleClass('d-flex align-items-center');

            $('.filters select').each(function() {
                $(this).prop('selectedIndex', 0);
            })

            if ($('.row.filters').hasClass('d-none')) {
                // $('.row.filters').removeClass('d-none');
                // $('.row.filters').addClass('d-flex align-items-center');
                $('#toggle-filter').css('background-color', 'transparent');
                $('#toggle-filter img').attr('src', '../assets/img/table-filter.png');
            } else {
                // $('.row.filters').addClass('d-none');
                // $('.row.filters').removeClass('d-flex align-items-center');
                $('#toggle-filter').css('background-color', 'white');
                $('#toggle-filter img').attr('src', 'assets/img/nav_chevron_up.png');
            }
        })

        $('#toggle-download').click(function(e) {
            $('#modal-download').modal('toggle');
        })

        var colTipeKTA = table.columns(3).data().eq(0).sort().unique().join('|');
        var colProvinceKTA = table.columns(5).data().eq(0).sort().unique().join('|');
        var colStatusKTA = table.columns(4).data().eq(0).sort().unique().join('|');
        var colRenewalKTA = table.columns(8).data().eq(0).sort().unique().join('|');
        var colPrintKTA = table.columns(9).data().eq(0).sort().unique().join('|');

        for (var i = 0; i < colTipeKTA.split("|").length; i++) {
            $('#kta-type-select').append('<option val="' + colTipeKTA.split("|")[i] + '">' + colTipeKTA.split("|")[i] + '</option>');
        }

        var temp_province;

        for (var i = 0; i < colProvinceKTA.split("|").length; i++) {

            if (temp_province != colProvinceKTA.split("|")[i].split("\n")[0]){

                temp_province = colProvinceKTA.split("|")[i].split("\n")[0];
                $('#kta-province-select').append('<option val="' + temp_province + '">' + temp_province + '</option>');

            }

        }

        var temp_status;

        for (var i = 0; i < colStatusKTA.split("|").length; i++) {

            if (temp_status != colStatusKTA.split("|")[i].split("\n")[0]){

                temp_status = colStatusKTA.split("|")[i].split("\n")[0];
                $('#kta-status-select').append("<option val='" + temp_status + "'>" + temp_status + "</option>");
           
            }

        }

        for (var i = 0; i < colRenewalKTA.split("|").length; i++) {
            $('#kta-renewal-select').append('<option val="' + colRenewalKTA.split("|")[i] + '">' + colRenewalKTA.split("|")[i] + '</option>');
        }

        for (var i = 0; i < colPrintKTA.split("|").length; i++) {
            $('#kta-print-select').append('<option val="' + colPrintKTA.split("|")[i] + '">' + colPrintKTA.split("|")[i] + '</option>');
        }

        $('input#period-select').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        var number_selected = parseInt($('#number-selected').text());
        var is_selected_type = 0;
        var is_selected_status = 0;
        var is_selected_province = 0;
        var is_selected_print = 0;
        var is_selected_renewal = 0;

        $("#kta-type-select").change(function() {

            $(this).css('font-weight', 'bold');

            if ($(this).val() != "" && is_selected_type == 0) {

                number_selected = number_selected + 1;
                $('#number-selected').text(number_selected);

                is_selected_type = 1;

            } else if ($(this).val() == "") {

                number_selected = number_selected - 1;
                $('#number-selected').text(number_selected);

                is_selected_type = 0;

            }

        });

        $("#kta-province-select").selectize({
            onChange: function(value) {
                if (value != "") {
                    filterCounter++;
                    if (filterCounter >= 4) {
                        filterCounter = 4;
                    }
                    $("#kta-province-select").siblings(".selectize-control").find('.selectize-input').css('font-weight', 'bold');
                    console.log(value);
                    $('#apply-filter').prop('disabled', false);
                } else {
                    filterCounter--;
                    if (filterCounter <= 0) {
                        filterCounter = 0;
                        $('#apply-filter').prop('disabled', true);
                    }
                }
                console.log('countfilter', filterCounter);
            }
        });

        $("#kta-status-select").change(function() {

            $(this).css('font-weight', 'bold');

            if ($(this).val() != "" && is_selected_status == 0) {

                number_selected = number_selected + 1;
                $('#number-selected').text(number_selected);

                is_selected_status = 1;

            } else if ($(this).val() == "") {

                number_selected = number_selected - 1;
                $('#number-selected').text(number_selected);

                is_selected_status = 0;

            }

        });

        $("#kta-renewal-select").change(function() {

            $(this).css('font-weight', 'bold');

            if ($(this).val() != "" && is_selected_renewal == 0) {

                number_selected = number_selected + 1;
                $('#number-selected').text(number_selected);

                is_selected_renewal = 1;

            } else if ($(this).val() == "") {

                number_selected = number_selected - 1;
                $('#number-selected').text(number_selected);

                is_selected_renewal = 0;

            }

        });

        $("#kta-print-select").change(function() {

            $(this).css('font-weight', 'bold');

            if ($(this).val() != "" && is_selected_print == 0) {

                number_selected = number_selected + 1;
                $('#number-selected').text(number_selected);

                is_selected_print = 1;

            } else if ($(this).val() == "") {

                number_selected = number_selected - 1;
                $('#number-selected').text(number_selected);

                is_selected_print = 0;

            }

        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

    });

    function saveFilter() {

        $('#modal-filter').modal('hide');

        var type = $('#kta-type-select').val();
        var province = $('#kta-province-select').val();
        var status = $('#kta-status-select').val();
        var renewal = $('#kta-renewal-select').val();
        var print = $('#kta-print-select').val();

        table.column(3).search(type).column(5).search(province).column(4).search(status).column(8).search(renewal).column(9).search(print).draw();

        var info = table.page.info();
        console.log((info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Anggota');

        setTimeout( function(){ 
            $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Anggota</span>').insertAfter($('.previous'));
        },1);

    }

    function resetFilter() {

        $('#kta-type-select').val("");
        $('#kta-status-select').val("");
        $('#kta-renewal-select').val("");
        $('#kta-print-select').val("");

        var $select = $(document.getElementById('kta-province-select'));
        var selectize = $select[0].selectize;

        selectize.setValue("");

        $('#kta-type-select').css('font-weight', 'normal');
        $('#kta-status-select').css('font-weight', 'normal');
        $('#kta-renewal-select').css('font-weight', 'normal');
        $('#kta-province-select').css('font-weight', 'normal');
        $('#kta-print-select').css('font-weight', 'normal');

        table.column(3).search("").column(5).search("").column(4).search("").column(8).search("").column(9).search("").draw();

    }

    $("#province").change(function() {

        var province = $(this).val();
        console.log(province);

        var formData = new FormData();
        formData.append('province', province);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText.split("|");

                $('#anggota-kta').text(result[2]);
                $('#anggota-kta-pro').text(result[3]);

            }
        }
        xmlHttp.open("post", "logics/get_data");
        xmlHttp.send(formData);

    });

    function logout() {

        var f_pin = '<?= $_SESSION['f_pin'] ?>';

        var formData = new FormData();
        formData.append('f_pin', f_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                if (result == 1) {
                    window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/";
                }

            }
        }
        xmlHttp.open("post", "logics/logout");
        xmlHttp.send(formData);

    }

    function openDetailKTA(no_anggota){

        window.location.href = "/gaspol_web/pages/gaspol-landing/dashboard/detail_kta?m=1&id="+no_anggota;

    }

    // FOR DOWNLOAD TABLE 

    $('#btnDownload').click(function() {

        table_hidden.draw();

        xport.toCSV('tableHidden');
    });

    var xport = {
        _fallbacktoCSV: true,
        toCSV: function(tableId, filename) {
            this._filename = (typeof filename === 'undefined') ? tableId : filename;
            // Generate our CSV string from out HTML Table
            var csv = this._tableToCSV(document.getElementById(tableId));
            // Create a CSV Blob
            var blob = new Blob([csv], {
                type: "text/csv"
            });

            // Determine which approach to take for the download
            if (navigator.msSaveOrOpenBlob) {
                // Works for Internet Explorer and Microsoft Edge
                navigator.msSaveOrOpenBlob(blob, this._filename + ".csv");
            } else {
                this._downloadAnchor(URL.createObjectURL(blob), 'csv');
            }
        },
        _getMsieVersion: function() {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf("MSIE ");
            if (msie > 0) {
                // IE 10 or older => return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
            }

            var trident = ua.indexOf("Trident/");
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf("rv:");
                return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
            }

            var edge = ua.indexOf("Edge/");
            if (edge > 0) {
                // Edge (IE 12+) => return version number
                return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
            }

            // other browser
            return false;
        },
        _isFirefox: function() {
            if (navigator.userAgent.indexOf("Firefox") > 0) {
                return 1;
            }

            return 0;
        },
        _downloadAnchor: function(content, ext) {
            var anchor = document.createElement("a");
            anchor.style = "display:none !important";
            anchor.id = "downloadanchor";
            document.body.appendChild(anchor);

            // If the [download] attribute is supported, try to use it

            if ("download" in anchor) {
                anchor.download = this._filename + "." + ext;
            }
            anchor.href = content;
            anchor.click();
            anchor.remove();
        },
        _tableToCSV: function(table) {
            // We'll be co-opting `slice` to create arrays
            var slice = Array.prototype.slice;

            return slice
                .call(table.rows)
                .map(function(row) {
                    return slice
                        .call(row.cells)
                        .map(function(cell) {
                            return '"t"'.replace("t", cell.textContent);
                        })
                        .join(",");
                })
                .join("\r\n");
        }
    };
    
</script>