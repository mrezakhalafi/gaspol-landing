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

    // GET KIS
    $query = $dbconn->prepare("SELECT * FROM KIS LEFT JOIN PROVINCE ON PROVINCE.PROV_ID = KIS.PROVINCE ORDER BY KIS.ID DESC");
    $query->execute();
    $kis = $query->get_result();
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
    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

                <div class="container-fluid mb-5">
                    <h3 class="mb-5">Dashboard KIS</h3>
                    <!-- <b class="mt-2">Pilih Provinsi</b>
                    <div class="row mt-3">
                        <div class="col-12">
                            <select id="province">
                                <option value="" selected>Semua Provinsi</option>
                                <?php foreach($province as $prv): ?>
                                    <option value="<?= $prv['PROV_ID'] ?>"><?= $prv['PROV_NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div> -->
                    <!-- <div class="section-data mt-4">
                        <div class="row">
                            <div class="col-12 col-md-6 text-center">
                                <div class="card shadow" style="height: 100px">
                                    <div class="card-body">
                                      <b id="anggota-kta" style="font-size: 24px; color: darkorange">< mysqli_num_rows($ktaMob) ?></b>
                                      <p>Jumlah KIS Mobility</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-center">
                                <div class="card shadow" style="height: 100px">
                                    <div class="card-body">
                                      <b id="anggota-kta-pro"  style="font-size: 24px; color: darkorange">< mysqli_num_rows($ktaPro) ?></b>
                                      <p>Jumlah KIS Pro</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="container-fluid">
                    <h3 class="mb-5">List Anggota KIS</h3>
                    <table id="tableKTA" class="table table-striped table-hover">
                        <thead class="table-secondary">
                            <th>No.</th>
                            <th>Name</th>
                            <th>Nomor Kartu</th>
                            <th>Province</th>
                            <th>Address</th>
                            <th>Images</th>
                            <th>SIM A</th>
                            <th>No. SIM A</th>
                            <th>SIM C</th>
                            <th>No. SIM C</th>
                            <th>Foto Persetujuan</th>
                            <th style="width: 100px">Kategori</th>
                            <th>No. KK</th>
                            <th>No. KTA</th>
                        </thead>
                        <tbody>
                            <?php foreach($kis as $index => $kt): ?>
                                <tr> 
                                    <td><?= $index+1 ?></td>
                                    <td><?= $kt['NAME'] ?></td>
                                    <td><?= $kt['NOMOR_KARTU'] ?></td>

                                    <td><?= ucwords(strtolower($kt['PROV_NAME'])) ?></td>

                                    <td><?= $kt['DOMISILI'] ?></td>

                                    <?php if($kt['FOTO_PROFIL']): ?>
                                        <td><img src="http://108.136.138.242/gaspol_web/images/<?= $kt['FOTO_PROFIL'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php else: ?>
                                        <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php endif; ?>

                                    <?php if($kt['FOTO_SIM_A']): ?>
                                        <td><img src="http://108.136.138.242/gaspol_web/images/<?= $kt['FOTO_SIM_A'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php else: ?>
                                        <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php endif; ?>

                                    <?php if ($kt['NO_SIM_C']): ?>
                                        <td><?= $kt['NO_SIM_C'] ?></td>
                                    <?php else: ?>
                                        <td>-</td>
                                    <?php endif; ?>

                                    <?php if($kt['FOTO_SIM_C']): ?>
                                        <td><img src="http://108.136.138.242/gaspol_web/images/<?= $kt['FOTO_SIM_C'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php else: ?>
                                        <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php endif; ?>
                                    
                                    <?php if ($kt['NO_SIM_C']): ?>
                                        <td><?= $kt['NO_SIM_C'] ?></td>
                                    <?php else: ?>
                                        <td>-</td>
                                    <?php endif; ?>

                                    <?php if($kt['FOTO_PERSETUJUAN']): ?>
                                        <td><img src="http://108.136.138.242/gaspol_web/images/<?= $kt['FOTO_PERSETUJUAN'] ?>" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php else: ?>
                                        <td><img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" style="width: 50px; height: 50px; object-fit: cover; object-position: center; border-radius: 50px"></td>
                                    <?php endif; ?>

                                    <?php $kategori = explode("|",$kt['KATEGORI']) ?>

                                    <td>

                                        <?php foreach($kategori as $ktg): ?>

                                            <?php if ($ktg[0] == "A" || $ktg[0] == "B"): ?>

                                                <span style="background-color: #3853db; font-size: 11px; color: white; padding: 5px; border-radius: 50px"><?= $ktg ?></span>

                                            <?php elseif($ktg[0] == "C"): ?>

                                                <span style="background-color: #9035bf; font-size: 11px; color: white; padding: 5px; border-radius: 50px"><?= $ktg ?></span>

                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </td>

                                    <?php if ($kt['NO_KK']): ?>
                                        <td><?= $kt['NO_KK'] ?></td>
                                    <?php else: ?>
                                        <td>-</td>
                                    <?php endif; ?>
                                    <td><?= $kt['NO_KTA'] ?></td>
                                </tr>
                            <?php endforeach; ?>    
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

</body>
</html>

<script>

$(document).ready(function(e) {

    $('#province').selectize();
    $('#tableKTA').DataTable({
        scrollX: true,
    });

});

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
                window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/";
            }

        }
    }
    xmlHttp.open("post", "logics/logout");
    xmlHttp.send(formData);

}

</script>