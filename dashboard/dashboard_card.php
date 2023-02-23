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
        header("Location: https://qmera.io/gaspol_web/pages/gaspol-landing/");
    }

    // GET KTA
    $query = $dbconn->prepare("SELECT KTA.*, TKT.*, PROVINCE.*, DOWNLOAD_CARD.*, KTA.F_PIN AS K_PIN FROM KTA LEFT JOIN TKT ON KTA.F_PIN = TKT.F_PIN LEFT JOIN PROVINCE ON PROVINCE.PROV_ID = KTA.PROVINCE LEFT JOIN DOWNLOAD_CARD ON KTA.F_PIN = DOWNLOAD_CARD.F_PIN LEFT JOIN REGISTRATION_PAYMENT ON KTA.F_PIN = REGISTRATION_PAYMENT.F_PIN GROUP BY KTA.ID");
    $query->execute();
    $ktatkt = $query->get_result();
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>

        .bg-gradient-dark {
            background-color: #000000;
            background-image: url('assets/img/side-pane.jpg');
            background-size: 125% 125%;
        }

        .header .col-12 {
            border-bottom: 2px solid #e3e3e3;
        }

        .data-person {
            font-size: 9px;
            color: white
        }

        .filter-download-button img {
            width: 25px;
            height: 25px;
            margin: 0 5px;
            float: right;
        }

        .filter-download-button a {
            border: 0;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
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

        .btn-orange {
            border: 0;
            background-color:#ff6b00;
            color:white;
            border-radius: 3rem;
            padding: .375rem 3rem;
        }

        nav.static-top,
        div.content-wrapper,
        footer {
            background-color: #f5f5f5 !important;
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
            float: right;
        }

        .filter-download-button a {
            border: 0;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
        }

        .filters {
            font-size: 13px;
            background-color: white;
            border-radius: 7px;
        }

        p#selected-filters {
            color: #1A73E8;
        }

        #modal-filter {
            color:black;
        }

        #modal-filter .modal-header,
        #modal-filter .modal-footer {
            border: 0;  
        }

        #modal-filter .modal-footer {
            justify-content: flex-start;
        }

        #tableKTA_filter {
            background-color: white;
            border-radius: 5px;
            padding: 0.25rem 0.75rem;
            display: flex;
            align-items: center;
            width:100%;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            margin-left: 3px;
            border: 0 !important;
            width:100%;
        }

        .dataTables_wrapper .dataTables_filter input:focus-visible {
            border:0;
        }

    </style>

</head>

<body id="page-top">

    <?php include_once("wrapper_sidebar.php") ?>

    <div class="container-fluid mb-3 header">
        <div class="row">
            <div class="col-12">
                <h5 class="text-dark"><strong>Member Card List</strong></h2>
            </div>
        </div>
    </div>

                <div class="container-fluid">
                    <table id="tableKTA" class="table table-striped table-hover">
                        <thead class="table-secondary">
                            <th>No.</th>
                            <th>Name</th>
                            <th>Province</th>
                            <th>No. KTA</th>
                            <th>Joined IMI Club</th>
                            <th>Processed</th>
                            <th>Generated Time</th>
                        </thead>
                        <tbody>
                            <?php foreach($ktatkt as $index => $kt): ?>
                                <tr>
                                    <?php 
                                    
                                    $msdate = $kt["CREATED_DATE"];
                                    $todate = strtotime('+1 year',strtotime($msdate));
                                    $finaldate = date("d-m-Y", $todate);

                                    ?>

                                    <td><?= $index+1 ?></td>
                                    <td><?= $kt['NAME'] ?></td>
                                    <td><?= ucwords(strtolower($kt['PROV_NAME'])) ?></td>
                                    <td><?= $kt['NO_ANGGOTA'] ?></td>

                                    <?php if($kt['CLUB_NAME']): ?>
                                        <td><?= $kt['CLUB_NAME'] ?></td>
                                    <?php else: ?>
                                        <td>Not Joined Club</td>
                                    <?php endif; ?>

                                    <td><button class="btn btn-primary" onclick="cardModal('<?= $kt['PROFILE_IMAGE'] ?>', '<?= $kt['NO_ANGGOTA'] ?>', '<?= $kt['NAME'] ?>', '<?= $kt['ADDRESS'] ?>', '<?= $kt['STATUS_ANGGOTA'] ?>', '<?= $kt['BLOODTYPE'] ?>', '<?= $finaldate ?>', '<?= $kt['K_PIN'] ?>', '<?= $kt['TOTAL_DOWNLOAD'] ?>')">View Card</button></td>
                                    <td><?= $kt['CREATED_DATE'] ?>
                                        <br> 

                                        <?php
                                            if ($kt['TOTAL_DOWNLOAD']) {
                                                ?>
                                                <p style="color: rgb(0, 171, 85); font-weight: 700"><?= $kt['TOTAL_DOWNLOAD']." Download"; ?></p> 
                                                <?php
                                            }
                                            else {
                                                ?>
                                                <p style="color: rgb(255, 107, 0); font-weight: 700">0 Download</p> 
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    <!-- MODAL CARD -->
    <div class="modal fade" id="modal-card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">KTA Card</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="card-structure" class="col-12 d-flex justify-content-center" style="height: 215px; border-radius: 10px; background-color: black; padding-left: 10px; padding-right: 10px; width: 326px; margin-left: 55px">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center" style="margin-left: -11px; height: 350px; margin-top: -51px; width: 350px">
                                <img src="../../output-kta-mobility-4.png" alt="" style="border-radius: 15px;height: 90%; margin-left: 9px">
                                <div class="row gx-0">
                                    <div class="col-12 d-flex justify-content-center" style="margin-top: 110px; margin-left: -159px">
                                        <p id="no-anggota" class="data-person" style="margin-left: 31px; margin-top: -15px;"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="profile-picture col-3 d-flex justify-content-center">
                                        <img id="image-anggota" src="" alt="" style="width: 66px; height: 76px; margin-top: 130px; margin-left: -509px; max-width: none; object-fit: cover; object-position: center">
                                    </div>
                                    <div class="col-6 justify-content-center" style="position: absolute; margin-left: -234px; margin-top: 155px">
                                        <div class="row gx-0">
                                            <div class="col-12" style="margin-left: 22px">
                                                <p id="nama-anggota" class="data-person" style="margin-top: -25px;"></p>
                                            </div>
                                            <br>
                                            <div class="col-12" style="margin-left: 22px">
                                                <p id="alamat" class="data-person" style="width: 145px;margin-top: -30px;"></p>
                                            </div>
                                        </div>
                                        <div class="row gx-0" style="margin-top: -44px">
                                            <div class="col-12" style="position: absolute; margin-top: 40px; margin-left: 22px">
                                                <p id="status-membership" class="data-person"></p>
                                            </div>
                                            <div class="row gx-0">
                                                <div class="col-3 justify-content-center" style="margin-left: -40px; margin-top: 75px; position: absolute">
                                                    <p class="data-person" style="width: 50px">Gol. Darah &nbsp; <span id="gol-darah" style="position: absolute" class="ms-2"></span></p>
                                                </div>
                                                <div class="col-3 justify-content-center" style="margin-top: 75px; margin-left: 35px">
                                                    <p class="data-person" style="width: 130px">Jatuh tempo &nbsp; <span id="jatuh-tempo"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center" style="position: absolute; margin-top: 137px; margin-left: -60px; width: 100px; height: 77px">
                                        <img id="barcode" src="" alt="" width="120" height="z0" style="color: green; z-index: 999; width: 76px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" class="main-form" id="register-card" enctype="multipart/form-data">
                    <div class="modal-footer">
                        <button id="button_close" style="z-index: 999" class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <a id="button_download" style="z-index: 999" class="btn btn-primary" onclick="">Download</a>
                    </div>
                </form>
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
                                        <input name="report_type" id="report-type-trx" value="trx" type="radio" />Transaction
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
                                        <input name="report_format" id="report-type-xls" value="xls" type="radio" />Printing
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-orange">Download File</button>
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

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

</body>
</html>

<script>

var table;

$(document).ready(function(e) {

    $('#province').selectize();

    table = $('#tableKTA').DataTable({
            dom: `
            <'row mb-3 align-items-center'
                <'col-3 search-table'f>
                <'col-9 table-pagination filter-buttons d-flex align-items-center justify-content-end'p>
            >
            <'row my-3 filters d-none'>
            <'row't>
            <'row mt-2'
                <'col-6'>
                <'col-6 d-flex justify-content-end align-items-center table-pagination'p>
            >`
        });

        $('#tableKTA_filter').html(`
            <img src="assets/img/tab5/search-black.png" style="width:25px;height:25px">
            <input type="search" class="" placeholder="Search name, KTA no., province, and club joined" aria-controls="tableKTA">
        `)

        $('#tableKTA_filter input').keyup(function(){
            table.search($(this).val()).draw() ;
        })

        $('.table-pagination.filter-buttons').prepend(
            `<div class="filter-download-button">
                <a id="toggle-download"><img src="../assets/img/table-download.png"></a>
                <a id="toggle-filter"><img src="../assets/img/table-filter.png"></a>
            </div>`
        );

        $('.row.filters').append(
            `<div class="col-2 px-1">
                <select id="kta-province-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="">KTA Province</option>
                </select>
            </div>
            <div class="col-2">
                <select id="joined-club-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="">Joined Club</option>
                </select>
            </div>
            <div class="col-2">

            </div>
            <div class="col-2">

            </div>
            <div class="col-2">

            </div>
            <div class="col-2 text-center">
                <a class="mr-3" onclick="resetFilter();" style="cursor:pointer;">
                    <img src="assets/img/reload.png" style="width:25px; height:25px">
                </a>
                <button class="btn btn-orange ml-3" onclick="saveFilter();"><strong>Apply</strong></button>
            </div>`
        );

        $('#toggle-filter').click(function(e) {
            $('.row.filters').toggleClass('d-none').toggleClass('d-flex align-items-center');
        })

        $('#toggle-download').click(function(e) {
            $('#modal-download').modal('toggle');
        })

        var colProvinceKTA = table.columns(2).data().eq(0).sort().unique().join('|');
        var colJoinedClub = table.columns(4).data().eq(0).sort().unique().join('|');

        for (var i=0; i<colProvinceKTA.split("|").length; i++){

            $('#kta-province-select').append('<option val="'+colProvinceKTA.split("|")[i]+'">'+colProvinceKTA.split("|")[i]+'</option>');

        }

        for (var i=0; i<colJoinedClub.split("|").length; i++){

            $('#joined-club-select').append('<option val="'+colJoinedClub.split("|")[i]+'">'+colJoinedClub.split("|")[i]+'</option>');

        }

        $('input#period-select').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

});


    function saveFilter(){

        var province = $('#kta-province-select').val();
        var joined = $('#joined-club-select').val();

        table.column(2).search(province).column(4).search(joined).draw();

    }

    function resetFilter(){

        $('#kta-province-select').val("");
        $('#joined-club-select').val("");

        table.column(2).search("").column(4).search("").draw();

    }

$("#province").change(function() {

    var province = $(this).val();
    console.log(province);

    var formData = new FormData();
    formData.append('province', province);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);

            var result = xmlHttp.responseText.split("|");

            $('#anggota-kta').text(result[2]);
            $('#anggota-kta-pro').text(result[3]);
            
        }
    }
    xmlHttp.open("post", "logics/get_data");
    xmlHttp.send(formData);

});

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
                window.location.href = "https://qmera.io/gaspol_web/pages/gaspol-landing/";
            }

        }
    }
    xmlHttp.open("post", "logics/logout");
    xmlHttp.send(formData);

}

function cardModal(imgAnggota, noAnggota, namaAnggota, alamatAnggota, statusAnggota, darahAnggota, jatuhTempo, fPin, totalDownload) {
    $("#modal-card").modal('show');

    $("#image-anggota").attr("src", "../../../images/"+imgAnggota);
    $("#no-anggota").text(noAnggota);
    $("#nama-anggota").text(namaAnggota);
    $("#alamat").text(alamatAnggota);
    $("#status-membership").text(statusAnggota);
    $("#gol-darah").text(darahAnggota);
    $("#jatuh-tempo").text(jatuhTempo);
    $("#barcode").attr("src", "https://api.qrserver.com/v1/create-qr-code/?data="+noAnggota+"&amp;size=100x100");

    var jenisAnggota = $("#status-membership").text();

    if (jenisAnggota == 0) {
        $("#status-membership").text("Basic");
    }
    else {
        $("#status-membership").text("Full Membership");
    }

    $('#button_download').attr('onclick','insertData("'+fPin+'",'+noAnggota+','+totalDownload+')');
}

function insertData(fPin, noAnggota, totalDownload){

    var k_pin = fPin;
    var no_kta = noAnggota;
    var download = totalDownload;

    var formData = new FormData();

    formData.append('kta_fpin', k_pin);
    formData.append('no_kta', no_kta);
    formData.append('count_button', download);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            console.log(xmlHttp.responseText);

            var result = xmlHttp.responseText;

            if(result == "Koneksi Database Berhasil"){
                // alert("Data berhasil diupload");
                html2canvas(document.querySelector("#card-structure"), {
                    useCORS: true
                }).then(canvas => {
                    // document.body.appendChild(canvas);

                    var image = canvas.toDataURL("image/png");
                    var anchor = document.createElement('a');
                    anchor.setAttribute('download', 'my-KTA-card.jpg');
                    anchor.setAttribute('href', image);
                    anchor.click();
                });
            }
            else {
                alert("Upload file gagal");
            }

        }
    }
    xmlHttp.open("post", "logics/register-card");
    xmlHttp.send(formData);

}

</script>

<!-- KTA 
nama kta
provinsi kta (province)
join imi club (club_membership)

DOWNLOAD_CARD
id 
no_kta
processed
total_download
generated time -->
