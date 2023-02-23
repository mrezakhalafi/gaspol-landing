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

// GET KTA
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

if($user['PROVINCE_ID'] == 0){
    $admin_type = "( Admin Pusat )";
}else{
    $admin_type = "( Admin Provinsi )";
}

// GET ADMIN TABLE
$query = $dbconn->prepare("SELECT USER_LIST.*, ADMIN_PROVINCE.PROVINCE_ID, PROVINCE.PROV_NAME FROM USER_LIST LEFT JOIN ADMIN_PROVINCE ON USER_LIST.F_PIN = ADMIN_PROVINCE.F_PIN LEFT JOIN PROVINCE ON ADMIN_PROVINCE.PROVINCE_ID = PROVINCE.PROV_ID WHERE USER_LIST.BE = 282 AND IS_CHANGED_PROFILE = 1 GROUP BY USER_LIST.F_PIN ORDER BY USER_LIST.ID DESC");
$query->execute();
$adminData = $query->get_result();
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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .bg-gradient-dark {
            background-color: #000000;
            background-image: url('assets/img/side-pane.jpg');
            background-size: 125% 125%;
        }

        .filter-download-button img {
            width: 25px;
            height: 25px;
            margin: 0 5px;
            float: right;
        }

        .header .col-12 {
            border-bottom: 2px solid #e3e3e3;
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

        .modal-header{
            display: inline;
            margin-bottom: -30px;
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

        #toggle-filter {
            cursor: pointer;
        }

        .table-pagination {
            font-size: 13px;
        }

        .table {
            color: black;
            font-size: .8rem;
            width: 100% !important;
            background-color: white;
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

        .table-dark {
            color: black;
            background-color: #E7E7E7;
        }

        #admin-body {
            background-color: white;
        }

        .odd {
            color: black;
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

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

    <div class="container-fluid mb-3 header">
        <div class="row">
            <div class="col-12">
                <h5 class="text-dark"><strong>List Member Gaspol</strong></h2>
            </div>
        </div>
    </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <div class="container-fluid">

                        <!-- <div class="filter-download-button">
                            <a><img src="../assets/img/table-download.png"></a>
                            <a data-toggle="modal" data-target="#modal-filter"><img src="../assets/img/table-filter.png"></a>
                        </div> -->
                        <table id="tableKTA" class="table table-striped table-hover">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Images</th>
                                <th>Status</th>
                                <th>Created Date</th>

                                <?php if($admin == 0): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                                
                            </thead>
                            <tbody id="admin-body">
                                <?php foreach($adminData as $index => $ad): ?>
                                    <tr> 
                                        <td><?= $index+1 ?></td>
                                        <td><?= $ad['FIRST_NAME']." ".$ad['LAST_NAME'] ?></td>

                                        <?php if ($ad['IMAGE']): ?>
                                            <td><img src="http://108.136.138.242/filepalio/image/<?= $kt['PROFILE_IMAGE'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                        <?php else: ?>
                                            <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                        <?php endif; ?>

                                        <?php if(isset($ad['PROVINCE_ID'])): ?>
                                            <?php if ($ad['PROVINCE_ID'] == 0): ?>
                                                <td class="text-success"><b>Admin Pusat</b></td>
                                            <?php else: ?>
                                                <td class="text-success"><b>Admin Provinsi (<?= ucfirst(strtolower($ad['PROV_NAME'])) ?>)</b></td>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <td class="text-danger"><b>Not Admin</b></td>
                                        <?php endif; ?>

                                        <?php $date = date_create($ad['CREATED_DATE']); ?>
                                        <td style="color: grey"><?= date_format($date,"d/m/Y, H:i:s") ?></td>

                                        <?php if($admin == 0): ?>
                                            <?php if(isset($ad['PROVINCE_ID'])): ?>
                                                <td><button class="btn btn-sm mt-2 btn-danger" onclick="removeAdmin('<?= $ad['F_PIN'] ?>')">Remove</button></td>
                                            <?php else: ?>
                                                <td><button class="btn btn-sm mt-2 btn-success" onclick="openModal('<?= $ad['FIRST_NAME'] . ' ' . $ad['LAST_NAME'] ?>','<?= $ad['F_PIN'] ?>')">Make Admin</button></td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>    
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.container-fluid -->

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

    <div id="modal-admin" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true" class="modal fade show" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Admin</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <b>Nama</b>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="admin_name" placeholder="" disabled>
                                <input type="hidden" class="form-control" id="admin_f_pin" placeholder="" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <b>Jenis Admin</b>
                            <select id="admin_type">
                                <option value="">Pilih Jenis Admin</option>    
                                <option value="0">Admin Pusat</option>
                                <option value="1">Admin Provinsi</option>
                            </select>
                            <span id="admin_type_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <b>Province</b>
                            <select id="province">
                                    <option value="">Pilih Provinsi</option>    
                                <?php foreach($province as $pv): ?>
                                    <option value="<?= $pv['PROV_ID'] ?>"><?= $pv['PROV_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span id="province_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
                    <button type="button" class="btn btn-dark" style="background-color: #f66701; color: #FFFFFF; border: 1px solid #f66701" onclick="saveAdmin()">Submit</button>
                </div>
            </div>
        </div>
    </div>

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

    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Add Admin Success!</h1>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="form_admin.php"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSuccess-2" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Remove Admin Success!</h1>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="form_admin.php"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
                        </div>
                    </div>
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <?php
        // require_once('gaspol_fb.php');
    ?>  

</body>

</html>

<script>

    var table;

    $(document).ready(function(e) {

        $('#province').selectize();
        $('#province')[0].selectize.disable();
        $('#admin_type').selectize();
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
            <input type="search" class="" placeholder="Search name, and status admin" aria-controls="tableKTA">
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
                <select id="admin-status-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="">Admin Status</option>
                </select>
            </div>
            <div class="col-2">

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

        var colStatusAdmin = table.columns(3).data().eq(0).sort().unique().join('|');

        for (var i=0; i<colStatusAdmin.split("|").length; i++){
            $('#admin-status-select').append('<option val="'+colStatusAdmin.split("|")[i]+'">'+colStatusAdmin.split("|")[i]+'</option>');
        }

        $('input#period-select').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

    });

    var number_selected = parseInt($('#number-selected').text());
    var is_selected_status = 0;

    $("#admin-status-select").change(function() {

        if ($(this).val() != "" && is_selected_status == 0){

            number_selected = number_selected + 1;
            $('#number-selected').text(number_selected);

            is_selected_status = 1;

        }else if($(this).val() == ""){

            number_selected = number_selected - 1;
            $('#number-selected').text(number_selected);

            is_selected_status = 0;

        }

    });

    function saveFilter(){

        var status = $('#admin-status-select').val();

        table.column(3).search(status).draw();

    }

    function resetFilter(){

        $('#admin-status-select').val("");

        table.column(3).search("").draw();

    }

    function openModal(name,f_pin){

        $('#modal-admin').modal({backdrop: 'static', keyboard: false});
        $('#admin_name').val(name);
        $('#admin_f_pin').val(f_pin);

    }

    $("#admin_type").bind("change paste keyup", function() {

        $('#admin_type_error').text("");

        var admin_type = $('#admin_type').val();

        if(admin_type!=0){
            $('#province')[0].selectize.enable();
        }else{
            $('#province')[0].selectize.disable();
            $('#province').val("");
            $('#province')[0].selectize.setValue('', false);
        }

    });

    $("#province").bind("change paste keyup", function() {

        $('#province_error').text("");

    });

    function saveAdmin(){

        var f_pin =  $('#admin_f_pin').val();
        var admin_type = $('#admin_type').val();
        var province = $('#province').val();

        if (admin_type != ""){

            $('#admin_type_error').text("");

            if(admin_type == 0){
                var formData = new FormData();
                formData.append('f_pin', f_pin);
                formData.append('admin_type', admin_type);
                formData.append('province', province);

                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
                        console.log(xmlHttp.responseText);
                        $('#modal-admin').modal('hide');
                        $('#modalSuccess').modal({backdrop: 'static', keyboard: false});

                    }
                }
                xmlHttp.open("post", "logics/set_admin");
                xmlHttp.send(formData);

            }else{

                if (province != ""){

                    $('#province_error').text("");

                    var formData = new FormData();
                    formData.append('f_pin', f_pin);
                    formData.append('admin_type', admin_type);
                    formData.append('province', province);

                    let xmlHttp = new XMLHttpRequest();
                    xmlHttp.onreadystatechange = function(){
                        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                            
                            console.log(xmlHttp.responseText);
                            $('#modal-admin').modal('hide');
                            $('#modalSuccess').modal({backdrop: 'static', keyboard: false});

                        }
                    }
                    xmlHttp.open("post", "logics/set_admin");
                    xmlHttp.send(formData);
                }else{
                    $('#province_error').text("Please fill this required field.");
                }
            }
        }else{
            $('#admin_type_error').text("Please fill this required field.");
        }
    }

    function removeAdmin(f_pin){

        var formData = new FormData();
        formData.append('f_pin', f_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                console.log(xmlHttp.responseText);
                $('#modal-admin').modal('hide');
                $('#modalSuccess-2').modal({backdrop: 'static', keyboard: false});

            }
        }
        xmlHttp.open("post", "logics/remove_admin");
        xmlHttp.send(formData);
    }

    function closeModal(){

        $('#modal-admin').modal('hide');

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