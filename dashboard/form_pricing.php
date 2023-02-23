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

// GET PRICE FORM
$query = $dbconn->prepare("SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = 2");
$query->execute();
$ktamPrice = $query->get_result()->fetch_assoc();
$query->close();

$query = $dbconn->prepare("SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = 3");
$query->execute();
$ktapPrice = $query->get_result()->fetch_assoc();
$query->close();

$query = $dbconn->prepare("SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = 7");
$query->execute();
$ktapuPrice = $query->get_result()->fetch_assoc();
$query->close();

$query = $dbconn->prepare("SELECT * FROM PROVINCE WHERE PROV_ID = '".$admin."'");
$query->execute();
$kisPrice = $query->get_result()->fetch_assoc();
$query->close();

$query = $dbconn->prepare("SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = 5");
$query->execute();
$taaPrice = $query->get_result()->fetch_assoc();
$query->close();

$query = $dbconn->prepare("SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = 6");
$query->execute();
$tktPrice = $query->get_result()->fetch_assoc();
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

        /* .bg-gradient-dark {
            background-color: #000000;
            background-image: url('assets/img/side-pane.jpg');
            background-size: 125% 125%;
        } */
    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

    <div class="container-fluid mb-3 header">
        <div class="row">
            <div class="col-12">
                <h5 class="text-dark"><strong>Set Form Pricing</strong></h2>
            </div>
        </div>
    </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <?php if($admin == 0): ?>

                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <b>KTA Mobility Price</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="ktam_price" class="form-control" placeholder="KTA Mobility Price" value="<?= $ktamPrice['REG_FEE'] ?>">
                                        <button class="btn btn-primary" onclick="setPriceKTAM()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <b>KTA Pro Price (New)</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="ktap_price" class="form-control" placeholder="KTA Pro Price" value="<?= $ktapPrice['REG_FEE'] ?>">
                                        <button class="btn btn-primary" onclick="setPriceKTAP()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <b>KTA Pro Upgrade Price (From Mobility)</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="ktapu_price" class="form-control" placeholder="KTA Pro Upgrade Price" value="<?= $ktapuPrice['REG_FEE'] ?>">
                                        <button class="btn btn-primary" onclick="setPriceKTAPU()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <b>Club IMI Price</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="tkt_price" class="form-control" placeholder="TKT Price" value="<?= $tktPrice['REG_FEE'] ?>">
                                        <button class="btn btn-primary" onclick="setPriceTKT()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <b>TAA Price</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="taa_price" class="form-control" placeholder="TAA Price" value="<?= $taaPrice['REG_FEE'] ?>">
                                        <button class="btn btn-primary" onclick="setPriceTAA()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php else: ?>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <b>KIS Price (Province)</b>
                                    <div class="input-group mb-3 mt-3">
                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                        <input type="number" id="kis_price" class="form-control" placeholder="KIS Price" value="<?= $kisPrice['KIS_PRICE'] ?>">
                                        <input type="hidden" id="province_id" class="form-control" value="<?= $admin ?>">
                                        <button class="btn btn-primary" onclick="setPriceKIS()">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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

    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modalSuccess">
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">Change Price Success!</h1>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <?php
        // require_once('gaspol_fb.php');
    ?>  

</body>

</html>

<script>
   
function setPriceKTAM(){

        var type = 2;
        var price = $('#ktam_price').val();

        var formData = new FormData();
        formData.append('type', type);
        formData.append('price', price);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                console.log(xmlHttp.responseText);
                $('#modalSuccess').modal('show');

            }
        }
        xmlHttp.open("post", "logics/change_form_pricing");
        xmlHttp.send(formData);

}

function setPriceKTAP(){

    var type = 3;
    var price = $('#ktap_price').val();

    var formData = new FormData();
    formData.append('type', type);
    formData.append('price', price);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);
            $('#modalSuccess').modal('show');

        }
    }
    xmlHttp.open("post", "logics/change_form_pricing");
    xmlHttp.send(formData);

}

function setPriceKTAPU(){

    var type = 7;
    var price = $('#ktapu_price').val();

    var formData = new FormData();
    formData.append('type', type);
    formData.append('price', price);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);
            $('#modalSuccess').modal('show');

        }
    }
    xmlHttp.open("post", "logics/change_form_pricing");
    xmlHttp.send(formData);

}

function setPriceTKT(){

    var type = 6;
    var price = $('#tkt_price').val();

    var formData = new FormData();
    formData.append('type', type);
    formData.append('price', price);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);
            $('#modalSuccess').modal('show');

        }
    }
    xmlHttp.open("post", "logics/change_form_pricing");
    xmlHttp.send(formData);

}

function setPriceTAA(){

    var type = 5;
    var price = $('#taa_price').val();

    var formData = new FormData();
    formData.append('type', type);
    formData.append('price', price);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);
            $('#modalSuccess').modal('show');

        }
    }
    xmlHttp.open("post", "logics/change_form_pricing");
    xmlHttp.send(formData);

}

function setPriceKIS(){

    var type = 1;
    var price = $('#kis_price').val();
    var prov_id = $('#province_id').val();

    var formData = new FormData();
    formData.append('type', type);
    formData.append('price', price);
    formData.append('prov_id', prov_id);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            
            console.log(xmlHttp.responseText);
            $('#modalSuccess').modal('show');

        }
    }
    xmlHttp.open("post", "logics/change_form_pricing");
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