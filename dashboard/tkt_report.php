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

// GET KTA
$query = $dbconn->prepare("SELECT * FROM KTA");
$query->execute();
$kta = $query->get_result();
$query->close();

// GET TKT
$query = $dbconn->prepare("SELECT 
tkt.ID,
tkt.CLUB_NAME, 
(
    SELECT COUNT(cm.F_PIN)
    FROM CLUB_MEMBERSHIP cm
    WHERE cm.CLUB_CHOICE = tkt.ID
) AS CLUB_MEMBERS,
tkt.EXPIRE_DATE,
pr.PROV_NAME, 
ct.CITY_NAME,
MAX(rp.DATE) AS TRX_DATE,
rp.PAYMENT_ID,
0 AS RENEWAL,
0 AS CERTIFICATE_STATUS
FROM 
TKT tkt
LEFT JOIN PROVINCE pr ON pr.PROV_ID = tkt.PROVINCE 
LEFT JOIN CITIES ct ON tkt.CITY = ct.CITY_ID
LEFT JOIN REGISTRATION_PAYMENT rp ON rp.F_PIN = tkt.F_PIN AND rp.REG_TYPE = 6
GROUP BY tkt.ID
ORDER BY 
tkt.ID DESC");
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
            width: 100%;
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

        .btn-orange:disabled {
            background-color: #E7E7E7;
        }

        #tableTKT_filter {
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

        .dataTables_wrapper .row.filters .col-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }

        .dataTables_wrapper .dataTables_filter input:focus-visible {
            border: 0;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button, .dataTables_wrapper .dataTables_paginate .paginate_button:hover, .dataTables_wrapper .dataTables_paginate .paginate_button:active  {
            background: none;
            color: black!important;
            border: none !important;
        }

        #tableTKT tbody tr {
            cursor: pointer;
        }

        .text-black{
            color: black;
        }
    </style>

</head>

<body id="page-top">

    <?php include_once("wrapper_sidebar.php") ?>

    <div class="container-fluid my-3 header">
        <div class="row">
            <div class="col-12 px-0">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active">
                            <h6 class="text-dark"><strong>Manajemen TKT</strong></h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <h6 class="text-darkk"><strong>Klaim Klub</strong></h6>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <table id="tableTKT" class="table table-striped table-hover">
            <thead class="table-secondary">
                <th>Nama Klub</th>
                <th>Anggota</th>
                <th>Bintang</th>
                <th>Status</th>
                <th>Provinsi</th>
                <th>Tgl. Daftar</th>
                <th>ID Transaksi</th>
                <th>Pembaruan</th>
                <th>Status Sertifikat</th>
                <th></th>
                <!-- <th>Expire Date</th> -->
            </thead>
            <tbody>
                <?php foreach ($tkt as $index => $tk) {

                    // $created_date = strtotime($kt["CREATED_DATE"]);
                    // $created_date = date("d M Y", $created_date);

                    $trx_date = $tk['TRX_DATE'] != null ? date("d M Y", floor($tk['TRX_DATE'] / 1000)) : "";

                    $reg_date = floor($tk['TRX_DATE'] / 1000);
                    $created_date = date("d M Y", strtotime($reg_date));
                    $exp_date = date("d M Y", strtotime("+1 year", $reg_date));

                    $current = time();
                    // print_r($created_date);

                ?>
                    <tr onclick="window.location.href='detail_tkt.php?m=3&tkt_id=<?= $tk['ID'] ?>';">
                        <td><?= $tk['CLUB_NAME'] ?></td>
                        <td><?= $tk['CLUB_MEMBERS'] ?></td>
                        <td>
                            <div>★★★★</div>
                            <div class="kta-club">-</div>
                        </td>
                        <td>
                            <?php if ($current < strtotime("+1 year", $reg_date)) { ?>
                                <span class="text-green">Aktif</span><br>S/d <?= $exp_date ?>
                            <?php } else { ?>
                                <span class="text-red">Kadaluarsa</span><br>S/d <?= $exp_date ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?= ucwords(strtolower($tk['PROV_NAME'])) ?>
                            <br>
                            <div class="kta-club"><?= ucwords(strtolower($tk['CITY_NAME'])) ?></div>
                        </td>
                        <td><?= $trx_date ?></td>
                        <td>
                            <div><?= $trx_date ?></div>
                            <div class="kta-club"><?= $tk['PAYMENT_ID'] ?></div>
                        </td>
                        <td><?= $tk['RENEWAL'] ?></td>
                        <td>
                            <span><?= $tk["CERTIFICATE_STATUS"] == 0 ? "Belum Cetak" : "Dicetak" ?></span>
                            <br>
                            <div class="kta-club">-</div>
                        </td>
                        <td><img src="assets/img/print.svg" style="width:25px; height: 25px"></td>
                        <!-- <td><?= $tk['EXPIRE_DATE'] ?></td> -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <table id="tableHidden" style="display:none;">
            <thead class="table-secondary">
                <th>Nama Klub</th>
                <th>Anggota</th>
                <th>Bintang</th>
                <th>Status</th>
                <th>Provinsi</th>
                <th>Tgl. Daftar</th>
                <th>ID Transaksi</th>
                <th>Pembaruan</th>
                <th>Status Sertifikat</th>
                <th></th>
                <!-- <th>Expire Date</th> -->
            </thead>
            <tbody>
                <?php foreach ($tkt as $index => $tk) {

                    // $created_date = strtotime($kt["CREATED_DATE"]);
                    // $created_date = date("d M Y", $created_date);

                    $trx_date = $tk['TRX_DATE'] != null ? date("d M Y", floor($tk['TRX_DATE'] / 1000)) : "";
                    $reg_date = floor($tk['TRX_DATE'] / 1000);
                    $created_date = date("d M Y", strtotime($reg_date));
                    $exp_date = date("d M Y", strtotime("+1 year", $reg_date));

                    $current = time();

                ?>
                    <tr onclick="window.location.href='detail_tkt.php?tkt_id=<?= $tk['ID'] ?>';">
                        <td><?= $tk['CLUB_NAME'] ?></td>
                        <td><?= $tk['CLUB_MEMBERS'] ?></td>
                        <td>★★★★</td>
                        <td>
                            <?php if ($current < strtotime("+1 year", $reg_date)) { ?>
                                <span class="text-green">Aktif</span><br /><?= $exp_date ?>
                            <?php } else { ?>
                                <span class="text-red">Kadaluarsa</span><br /><?= $exp_date ?>
                            <?php } ?>
                        </td>
                        <td><?= ucwords(strtolower($tk['PROV_NAME'])) ?></td>
                        <td><?= $trx_date ?></td>
                        <td>
                            <div><?= $created_date ?></div>
                            <div class="kta-club"><?= $tk['PAYMENT_ID'] ?></div>
                        </td>
                        <td><?= $tk['RENEWAL'] ?></td>
                        <td><?= $tk["CERTIFICATE_STATUS"] == 0 ? "Not-Printed" : "Printed" ?></td>
                        <td><img src="assets/img/print.svg" style="width:25px; height: 25px"></td>
                        <!-- <td><?= $tk['EXPIRE_DATE'] ?></td> -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white mt-5">
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
                            <label class="report-type" for="report-type">Jenis Laporan</label>
                            <div id="report-type" class="row">
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-trx" value="trx" type="radio" />Transaksi
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-date" value="date" type="radio" />Tanggal Daftar
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_type" id="report-type-print" value="print" type="radio" />Cetak Sertifikat
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-3 mt-3">
                        <div class="col-12">
                            <label class="report-period" for="report-period">Periode Laporan
                                <input type="text" name="period-range" id="period-select" class="form-select" aria-label=".form-select-sm example">
                            </label>
                        </div>
                    </div>
                    <div class="row px-3 mt-3">
                        <div class="col-12">
                            <label class="report-format" for="report-format">Format File</label>
                            <div id="report-type" class="row">
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-format-csv" value="csv" type="radio" />CSV
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-format-pdf" value="pdf" type="radio" />PDF
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="radio-inline">
                                        <input name="report_format" id="report-type-xls" value="xls" type="radio" />XLS
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-orange" id="btnDownload">Unduh File</button>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

</body>

</html>

<script>
    var table;
    var table_hidden;
    var min, max, tableDate;
    let filterCounter = 0;

    $(document).ready(function(e) {



        $('#province').selectize();
        table = $('#tableTKT').DataTable({
            "pagingType": "simple",
            "language": {
                "paginate": {
                    "next": '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                    "previous": '<i class="fa fa-angle-left" aria-hidden="true"></i>'
                }
            },
            scrollX: true,
            dom: `
            <'row my-4 align-items-center'
                <'col-3 pl-0 search-table'f>
                <'col-7 pr-0 table-pagination filter-buttons d-flex align-items-center justify-content-end'>
                <'col-2' p>
            >
            <'row my-3 filters d-none'>
            <'row't>
            <'row mt-2'
                <'col-6'>
                <'col-6 d-flex justify-content-end align-items-center table-pagination'p>
            >`
        });

        var info = table.page.info();
        $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Club</span>').insertAfter($('.previous'));

        $('#tableTKT').on( 'page.dt', function () {
            
           var info = table.page.info();
           console.log((info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Club');

           setTimeout( function(){ 
                $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Club</span>').insertAfter($('.previous'));
            },1);
                        
        } );

        $('#tableTKT_filter').html(`
            <img src="assets/img/tab5/search-black.png" style="width:25px;height:25px">
            <input type="search" class="" placeholder="Cari Nama Klub, Provinsi, atau ID Transaksi" aria-controls="tableKTA">
        `)

        $('#tableTKT_filter input').keyup(function() {
            table.search($(this).val()).draw();
        })

        $('.table-pagination.filter-buttons').prepend(
            `<div class="create-KTA-button mr-3">
                <a href="register_tkt.php?m=3">
                    <button class="btn btn-orange">Buat Klub</button>
                </a>
            </div>
            <div class="filter-download-button">&nbsp;&nbsp;&nbsp;
                <a id="toggle-filter" style="padding:.5rem;"><img src="../assets/img/table-filter.png">&nbsp;<b style="color: black">Filter</b>&nbsp;</a>&nbsp;&nbsp;&nbsp;
                <a id="toggle-download" style="padding:.5rem;"><img src="../assets/img/table-download.png">&nbsp;<b style="color: black">Unduh</b>&nbsp;</a>&nbsp;&nbsp;&nbsp;
            </div>`
        );

        $('.row.filters').append(
            `<div class="col-2 px-1">
                <select id="kta-type-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="" style="display: none">Bintang</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-status-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="" style="display: none">Status</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-province-select" aria-label=".form-select-sm example">
                    <option selected value="" style="display: none">Provinsi</option>
                </select>
            </div>
            <div class="col-2">
                <select id="kta-renewal-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="" style="display: none">Pembaruan</option>
                </select>
            </div>
            <div class="col-2 text-center">
                <a class="mr-3" onclick="resetFilter();" style="cursor:pointer;">
                    <img src="assets/img/reload.png" style="width:25px; height:25px">
                </a>
                <button disabled class="btn btn-orange ml-3" id="apply-filter" onclick="saveFilter();"><strong>Terapkan</strong></button>
            </div>`
        );

        table_hidden = $('#tableHidden').DataTable();
        $('#tableHidden_wrapper').addClass('d-none');

        // $.fn.dataTable.ext.search.push(
        //     function(settings, data, dataIndex) {
        //         min = $("#period-select").data('daterangepicker').startDate.format('YYYY-MM-DD');
        //         max = $("#period-select").data('daterangepicker').endDate.format('YYYY-MM-DD');
        //         var date = new Date(data[5]);
        //         tableDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().substring(0, 10);

        //         console.log(tableDate);

        //         if (min == null && max == null) {
        //             return true;
        //         }
        //         if (min == null && tableDate <= max) {
        //             return true;
        //         }
        //         if (max == null && tableDate >= min) {
        //             return true;
        //         }
        //         if (tableDate <= max && tableDate >= min) {
        //             return true;
        //         }
        //         return false;
        //     }
        // );

        $('#toggle-download').click(function(e) {
            $('#modal-download').modal('toggle');
        })

        var colTipeKTA = table.columns(2).data().eq(0).sort().unique().join('|');
        var colProvinceKTA = table.columns(4).data().eq(0).sort().unique().join('|');
        var colStatusKTA = table.columns(3).data().eq(0).sort().unique().join('|');
        var colRenewalKTA = table.columns(7).data().eq(0).sort().unique().join('|');

        for (var i = 0; i < colTipeKTA.split("|").length; i++) {
            $('#kta-type-select').append('<option val="' + colTipeKTA.split("|")[i].split("\n")[0] + '">' + colTipeKTA.split("|")[i].split("\n")[0] + '</option>');
        }

        let newProvArr = [];
        for (var i = 0; i < colProvinceKTA.split("|").length; i++) {
            newProvArr.push(colProvinceKTA.split("|")[i].split("<br>")[0]);
        }

        var uniqueProvArr = newProvArr.filter((v, i, a) => a.indexOf(v) === i);
        var uniqueProvArrClean = [];

        uniqueProvArr.forEach(a => {
            uniqueProvArrClean.push(a.split("<")[0]);
        })

        console.log(uniqueProvArrClean);

        for (var i = 0; i < uniqueProvArrClean.length; i++) {
            $('#kta-province-select').append('<option val="' + uniqueProvArrClean[i] + '">' + uniqueProvArrClean[i] + '</option>');
        }

        let newStsArr = [];
        for (var i = 0; i < colStatusKTA.split("|").length; i++) {
            newStsArr.push(colStatusKTA.split("|")[i].split(">")[1]);
        }

        var uniqueStsArr = newStsArr.filter((v, i, a) => a.indexOf(v) === i);
        var uniqueStsArrClean = [];

        uniqueStsArr.forEach(a => {
            uniqueStsArrClean.push(a.split("<")[0]);
        })


        for (var i = 0; i < uniqueStsArrClean.length; i++) {
            $('#kta-status-select').append('<option val="' + uniqueStsArrClean[i] + '">' + uniqueStsArrClean[i] + '</option>');
        }

        for (var i = 0; i < colRenewalKTA.split("|").length; i++) {
            $('#kta-renewal-select').append('<option val="' + colRenewalKTA.split("|")[i] + '">' + colRenewalKTA.split("|")[i] + '</option>');
        }

        $('input#period-select').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

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

        $("#kta-type-select").change(function() {

            if ($(this).prop('selectedIndex') != 0) {
                filterCounter++;
                if (filterCounter >= 4) {
                    filterCounter = 4;
                }
                $('#apply-filter').prop('disabled', false);
                $(this).css('font-weight', 'bold');
            } else {
                $(this).css('font-weight', 'normal');
                filterCounter--;
                if (filterCounter <= 0) {
                    filterCounter = 0;
                    $('#apply-filter').prop('disabled', true);
                }
            }
            console.log('countfilter', filterCounter);

        });

        // $("#kta-province-select").change(function() {

        //     if ($(this).prop('selectedIndex') != 0) {
        //         $('#apply-filter').prop('disabled', false);
        //         $(this).css('font-weight', 'bold');
        //     } else {
        //         $(this).css('font-weight', 'normal');
        //         filterCounter--;
        //         if (filterCounter <= 0) {
        //             filterCounter = 0;
        //             $('#apply-filter').prop('disabled', true);
        //         }
        //     }

        //     $(this).css('font-weight', 'bold');
        // });

        $("#kta-status-select").change(function() {

            if ($(this).prop('selectedIndex') != 0) {
                filterCounter++;
                if (filterCounter >= 4) {
                    filterCounter = 4;
                }
                $('#apply-filter').prop('disabled', false);
                $(this).css('font-weight', 'bold');
            } else {
                $(this).css('font-weight', 'normal');
                filterCounter--;
                if (filterCounter <= 0) {
                    filterCounter = 0;
                    $('#apply-filter').prop('disabled', true);
                }
            }
            console.log('countfilter', filterCounter);
        });

        $("#kta-renewal-select").change(function() {

            if ($(this).prop('selectedIndex') != 0) {
                filterCounter++;
                if (filterCounter >= 4) {
                    filterCounter = 4;
                }
                $('#apply-filter').prop('disabled', false);
                $(this).css('font-weight', 'bold');
            } else {
                $(this).css('font-weight', 'normal');
                filterCounter--;
                if (filterCounter <= 0) {
                    filterCounter = 0;
                    $('#apply-filter').prop('disabled', true);
                }
            }

            console.log('countfilter', filterCounter);
        });

    });

    // $("#province").change(function() {

    //     var province = $(this).val();
    //     console.log(province);

    //     var formData = new FormData();
    //     formData.append('province', province);

    //     let xmlHttp = new XMLHttpRequest();
    //     xmlHttp.onreadystatechange = function() {
    //         if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

    //             console.log(xmlHttp.responseText);

    //             var result = xmlHttp.responseText.split("|");

    //             $('#klub-imi').text(result[1]);

    //         }
    //     }
    //     xmlHttp.open("post", "logics/get_data");
    //     xmlHttp.send(formData);

    // });

    var number_selected = parseInt($('#number-selected').text());
    var is_selected_type = 0;
    var is_selected_status = 0;
    var is_selected_province = 0;
    var is_selected_print = 0;
    var is_selected_renewal = 0;



    function saveFilter() {

        $('#modal-filter').modal('hide');

        var type = $('#kta-type-select').val();
        var province = $('#kta-province-select').val();
        var status = $('#kta-status-select').val();
        var renewal = $('#kta-renewal-select').val();

        table.column(2).search(type).column(4).search(province).column(3).search(status).column(7).search(renewal).draw();

        var info = table.page.info();
        console.log((info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Club');

        setTimeout( function(){ 
            $('<span style="margin-left: 6px; margin-right: 6px">'+(info.start+1)+'-'+info.end+' dari '+info.recordsTotal+' Club</span>').insertAfter($('.previous'));
        },1);

    }

    function resetFilter() {

        $('#kta-type-select').val("");
        $('#kta-status-select').val("");
        $('#kta-renewal-select').val("");

        var $select = $(document.getElementById('kta-province-select'));
        var selectize = $select[0].selectize;

        selectize.setValue("");

        $('#kta-type-select').css('font-weight', 'normal');
        $('#kta-status-select').css('font-weight', 'normal');
        $('#kta-renewal-select').css('font-weight', 'normal');
        $('#kta-province-select').css('font-weight', 'normal');

        table.column(2).search("").column(3).search("").column(4).search("").column(7).search("").draw();

    }

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
    }
</script>