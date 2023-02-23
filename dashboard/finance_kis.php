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

    if($user['PROVINCE_ID'] == 0){
        $admin_type = "( Admin Pusat )";
    }else{
        $admin_type = "( Admin Provinsi )";
    }

    // print_r($transaction);
    // print_r($total_price);

    // CHECK ADMIN PUSAT OR PROVINCE

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = 1");
    $query->execute();
    $isPusat = $query->get_result()->fetch_assoc();
    $query->close();

    if($admin != 0){

        // GET KIS BY PROVINCE
        $query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU = REGISTRATION_PAYMENT.REF_ID WHERE REGISTRATION_PAYMENT.REG_TYPE = 8 AND KIS.NOMOR_KARTU IS NOT NULL AND KIS.PROVINCE = '".$admin."' ORDER BY REGISTRATION_PAYMENT.DATE DESC");
        $query->execute();
        $transaction = $query->get_result();
        $query->close();

    }else{

        // GET KIS ALL PROVINCE
        $query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN REGISTRATION_PAYMENT ON KIS.NOMOR_KARTU = REGISTRATION_PAYMENT.REF_ID WHERE REGISTRATION_PAYMENT.REG_TYPE = 8 AND KIS.NOMOR_KARTU IS NOT NULL ORDER BY REGISTRATION_PAYMENT.DATE DESC");
        $query->execute();
        $transaction = $query->get_result();
        $query->close();

    }

    $total_price = 0;

    foreach($transaction as $tr){
        $total_price = $total_price + $tr['PRICE'];
    }

    function rupiah($angka){
	
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
     
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

        nav.static-top,
        div.content-wrapper,
        footer {
            background-color: #f5f5f5 !important;
        }

        .table-dark {
            color: black;
            background-color: #E7E7E7;
        }

        #kta-body {
            background-color: white;
        }

        .odd {
            color: black;
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

        .header .col-12 {
            border-bottom: 2px solid #e3e3e3;
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

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

        <div class="container-fluid mb-3 header">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-dark"><strong>Finance Report KIS</strong></h2>
                </div>
            </div>
        </div>

                <div class="container-fluid">
                    <table id="tableKTA" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <th>No.</th>
                            <th>Images</th>
                            <th>Name</th>
                            <th>No. KIS</th>
                            <th>Price</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Transaction Date</th>
                        </thead>
                        <tbody id="kis-body">
                            <?php foreach($transaction as $index => $tr): ?>
                                <tr> 
                                    <td><?= $index+1 ?></td>
                                    <?php if($tr['FOTO_PROFIL']): ?>
                                        <td><img src="http://108.136.138.242/gaspol_web/images/<?= $tr['FOTO_PROFIL'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php else: ?>
                                        <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php endif; ?>
                                    <td><?= $tr['NAME'] ?></td>
                                    <td><?= $tr['NOMOR_KARTU'] ?></td>
                                    <td><?= rupiah($tr['PRICE']) ?></td>
                                    <td><?= $tr['METHOD'] ?></td>
                                    <?php if($tr['STATUS'] == 1): ?>
                                        <td class="text-success"><b>Paid</b></td>
                                    <?php else: ?>
                                        <td class="text-danger"><b>Not Paid</b></td>
                                    <?php endif; ?>

                                    <?php 
                                    $mil = $tr['DATE'];
                                    $seconds = $mil / 1000;
                                    ?>
                                    
                                    <td><?= date("d/m/Y, H:i:s", $seconds) ?></td>
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
            <input type="search" class="" placeholder="Search name, KIS no., and status payment" aria-controls="tableKTA">
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
                <select id="payment-method-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="">Payment Method</option>
                </select>
            </div>
            <div class="col-2">
                <select id="payment-status-select" class="form-select" aria-label=".form-select-sm example">
                    <option selected value="">Status</option>
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

        var colPaymentMethod = table.columns(5).data().eq(0).sort().unique().join('|');
        var colPaymentStatus = table.columns(6).data().eq(0).sort().unique().join('|');

        for (var i=0; i<colPaymentMethod.split("|").length; i++){

            $('#payment-method-select').append('<option val="'+colPaymentMethod.split("|")[i]+'">'+colPaymentMethod.split("|")[i]+'</option>');

        }

        for (var i=0; i<colPaymentStatus.split("|").length; i++){

            $('#payment-status-select').append('<option val="'+colPaymentStatus.split("|")[i]+'">'+colPaymentStatus.split("|")[i]+'</option>');

        }

        $('input#period-select').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        var counter = 1;

});


function saveFilter() {

    $('#modal-filter').modal('hide');

    var status = $('#payment-status-select').val();
    var method = $('#payment-method-select').val();

    table.column(5).search(method).column(6).search(status).draw();

}

function resetFilter() {

    $('#payment-status-select').val("");
    $('#payment-method-select').val("");

    table.column(5).search("").column(6).search("").draw();

}


$("#province").change(function() {

var province = $(this).val();
console.log(province);

var formData = new FormData();
formData.append('province', province);
formData.append('type', 1);

let xmlHttp = new XMLHttpRequest();
xmlHttp.onreadystatechange = function(){
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
        
        console.log(xmlHttp.responseText);
        var obj = JSON.parse(xmlHttp.responseText);
        var price = 0;

        $('#price-kta').text('Rp 0,00');          

        table.clear().draw();
        counter = 1;
            
        Object.keys(obj).forEach(function (item){

            if (obj[item]['STATUS'] == 1){
                var status = "<span class='text-success'><b>Paid</b></span>";
            }else{
                var status = "<span class='text-success'><b>Not Paid</b></span>";
            }

            var date = new Date(obj[item]['DATE']);
            
            var bilangan = obj[item]['PRICE'];

            var	number_string = bilangan.toString(),
                sisa 	= number_string.length % 3,
                rupiah 	= number_string.substr(0, sisa),
                ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                    
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            var position = parseInt(item)+1;

            table.row.add( [

                counter,
                '<img src="http://108.136.138.242/gaspol_web/images/'+obj[item]['FOTO_PROFIL']+'" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px""></td>',
                obj[item]['NAME'],
                obj[item]['NOMOR_KARTU'],
                'Rp '+rupiah+',00',
                obj[item]['METHOD'],
                status,
                date.toLocaleString('en-GB', {timeZone: 'UTC'})

            ] ).draw();

            counter++;
            price = price + obj[item]['PRICE'];
            
            var	number_string2 = price.toString(),
            sisa2 	= number_string2.length % 3,
            rupiah2 	= number_string2.substr(0, sisa2),
            ribuan2 	= number_string2.substr(sisa2).match(/\d{3}/g);
                
            if (ribuan2) {
                separator2 = sisa2 ? '.' : '';
                rupiah2 += separator2 + ribuan2.join('.');
            }

            $('#price-kta').text('Rp '+rupiah2+',00');              

        });

        $('#anggota-kta').text(obj.length);
        
    }
}
xmlHttp.open("post", "logics/finance_kis");
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
                window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/";
            }

        }
    }
    xmlHttp.open("post", "logics/logout");
    xmlHttp.send(formData);

}

</script>