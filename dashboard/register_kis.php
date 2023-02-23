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

    // PROVINCE

    $sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $province = $queDATA->get_result();
    $queDATA->close();

    echo '<script>';
    echo 'var prov_pricelist = [';
    foreach($province as $p):
        echo '{';
        echo 'prov_id: "'.$p['PROV_ID'].'",';
        echo 'prov_name: "'.$p['PROV_NAME'].'",';
        echo 'prov_price: "'.$p['KIS_PRICE'].'",';
        echo '},';
    endforeach;
    echo '];';
    echo '</script>';

    // KIS CATEGORY

    $sqlData = "SELECT * FROM KIS_CATEGORY WHERE TYPE = '1'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $mobcat = $queDATA->get_result();
    $queDATA->close();

    $sqlData = "SELECT * FROM KIS_CATEGORY WHERE TYPE = '2'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $motcat = $queDATA->get_result();
    $queDATA->close();

    // GET DATA FROM KTA

    $sqlData = "SELECT * FROM KTA WHERE F_PIN = '".$f_pin."'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $ktaData = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    // print_r($ktaData);

    // PRICE

    $sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '1'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $price = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    $upgradeFee = 0;
    $adminFee = $price['ADMIN_FEE'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gaspol! Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script src="assets/js/xendit.min.js"></script>

    <!-- Font Icon -->
    <link rel="stylesheet" href="assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <!-- <link rel="stylesheet" href="assets/css/form-e-sim.css?v=<?php echo $ver; ?>"> -->

    <style>

        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content {
            margin: 0;
            width: 100%;
        }

        html,
		body {
			max-width: 100%;
			/* overflow-x: hidden; */
		}

        input[type="radio"]{
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .form-check-input-car:checked {
            accent-color: #f66701;
        }

        .form-check-input-motor:checked {
            accent-color: #f66701;
        }

        /* .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        } */

        .error {
            font-size: 13px !important;
        }

        input, select {
            width: 100%;
            display: block;
            border: none;
            border-bottom: 2px solid #ebebeb;
            padding: 10px 0;
            color: #222;
            margin-bottom: 5px;
        }

        input[type=radio] {
            appearance: radio !important;
            -moz-appearance: radio !important;
            -webkit-appearance: radio !important;
            -o-appearance: radio !important;
            -ms-appearance: radio !important;
        }

        input.radio {
            width: fit-content;
            display: inline-block;
        }

        #content{
            background-color: white;
        }

        input{
            color: black !important;
            background-color: transparent;
        }

        .error{
            font-size: 13px !important;
            color: red;
            width: 100%;
        }

    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

                <!-- Begin Page Content -->
                <div class="container-fluid p-3" style="background-color: #F5F5F5">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Register KIS</h1>

                    <!-- START FORM   -->

                    <form method="POST" class="main-form" style="padding: 0" id="kis-form" action="/gaspol_web/logics/register_new_kis" enctype="multipart/form-data">

                        <div class="container mx-auto">

                        <div class="row gx-0 mt-3">
                            <div class="col-12">
                                <input type="number" name="no_kta" id="no_kta" placeholder="Nomor KTA" required />
                            </div>
                            <span class="starnokta text-danger" style="position: absolute; margin-top: 8px; margin-left: 100px; width: 10px">*</span>
                            <label id="kta-exist" class="text-danger" style="font-weight: bold; margin-left: 12px"></label>
                            <label id="kta-not-exist" class="text-success" style="font-weight: bold; margin-left: 12px"></label>
                        </div>

                                <!-- 
                            <?php if($ktaData): ?>
                                <b><p class="text-success mt-3">KTA Data found.</p></b>
                                <p style="color: grey">Name, address, bloodtype and profile picture will be generated from your KTA.</p>
                            <?php else: ?>
                                <b><p class="text-danger mt-3">KTA Data not found.</p></b>
                            <?php endif; ?> -->

                            <div class="mt-1" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseLicense" aria-expanded="false" aria-controls="collapseLicense">
                                <b>Racing License</b>
                                <img id="collapse-img-1" src="assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                            </div>

                            <div class="collapse show mt-2" id="collapseLicense">
                                <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                                    <option value="" selected>Province</option>

                                    <?php foreach($province as $p): ?>
                                        <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <span class="chooseprovince text-danger" style="position: absolute; margin-top: -39px; margin-left: 80px; z-index: 999">*</span>
                                <span id="province-error" class="error" style="color: red"></span>

                                <p style="margin-top: 20px; margin-bottom: 20px">Car Racing</p>

                                <span class="choosecr text-danger" style="position: absolute; margin-top: -44px; margin-left: 89px; z-index: 999">*</span>

                                <?php foreach($mobcat as $b): ?>
                                    <div class="form-check">
                                        <div class="row gx-0 mt-3">
                                            <div class="col-1">
                                                <input class="form-check-input-car" type="checkbox" value="" id="mobCar-<?= $b['ID'] ?>" data-id="<?= $b['CODE'] ?>" data-price="">
                                            </div>
                                            <div class="col-2" style="margin-top: -5px">
                                                <div style="width: 30px; height: 30px; background-color: #3853db; border-radius: 50px; padding-top: 5px">
                                                    <span style="color:white; padding-left: 7px; font-size: 13px"><?= $b['CODE'] ?></span>
                                                </div>
                                            </div>
                                            <div class="col-9" style="margin-top: -10px">
                                                <label class="form-check-label" for="mobCar-<?= $b['ID'] ?>">
                                                    <p><?= $b['NAME'] ?></p>
                                                    <p class="kis-price" style="color: grey"></p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <p style="margin-top: 19px; margin-bottom: 20px">Motorcycle Racing</p>

                                <span class="choosemr text-danger" style="position: absolute; margin-top: -44px; margin-left: 148px; z-index: 999">*</span>

                                <?php foreach($motcat as $t): ?>
                                    <div class="form-check">
                                        <div class="row gx-0 mt-3">
                                            <div class="col-1">
                                                <input class="form-check-input-motor" type="checkbox" value="" id="motCat-<?= $t['ID'] ?>" data-id="<?= $t['CODE'] ?>" data-price="">
                                            </div>
                                            <div class="col-2" style="margin-top: -5px">
                                                <div style="width: 30px; height: 30px; background-color: #9035bf; border-radius: 50px; padding-top: 5px">
                                                    <span style="color:white; padding-left: 7px; font-size: 13px"><?= $t['CODE'] ?></span>
                                                </div>
                                            </div>
                                            <div class="col-9" style="margin-top: -10px">
                                                <label class="form-check-label" for="motCat-<?= $t['ID'] ?>">
                                                    <p><?= $t['NAME'] ?></p>
                                                    <p class="kis-price" style="color: grey"></p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <span id="category-error" class="error" style="color: red"></span>
                            </div>

                            <div class="mt-3" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseDriving" aria-expanded="false" aria-controls="collapseDriving">
                                <b>Driving License</b>
                                <img id="collapse-img-2" src="assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                            </div>

                            <div class="collapse show mt-2" id="collapseDriving">
                                <p class="mt-3 mb-1">Status</p>
                                <div class="row">
                                    <div class="col-7">
                                        <input type="radio" id="ownLicense" name="driving_status" class="radio" value="1" checked>
                                        <label for="ownLicense">&nbsp;&nbsp;Own Driving License</label>
                                    </div>
                                    <div class="col-5">
                                        <input type="radio" id="parentLicense" name="driving_status" class="radio" value="2">
                                        <label for="parentLicense">&nbsp;&nbsp;Under 17 y.o.</label><br>
                                    </div>
                                </div>

                                <div id="license-section">
                                    <p class="mt-3 mb-1">Choose License Type</p>

                                    <span class="checklt text-danger" style="position: absolute; margin-top: -28px; margin-left: 165px; z-index: 999">*</span>

                                    <div class="row">
                                        <div class="col-7">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="licenseCar">
                                                <label class="form-check-label" for="licenseCar">
                                                    Car
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="2" id="licenseBike">
                                                <label class="form-check-label" for="licenseBike">
                                                    Motorcycle
                                                </label>
                                            </div>
                                        </div>
                                        <span id="licensetype-error" class="error" style="color: red"></span>
                                    </div>
                                </div>

                                <!-- <input type="hidden" name="category_array" id="category_array"> -->

                                <!-- SIM A SECTION -->
                                <div id="sim-a-section">
                                    <div class="row gx-0">
                                        <div class="col-12">
                                            <input type="number" name="sim-a" id="sim-a" placeholder="SIM A Card Number" required />
                                        </div>
                                        <span class="nosima text-danger" style="position: absolute; margin-top: 9px; margin-left: 164px; width: 10px">*</span>
                                    </div>

                                    <small style="color: grey">Valid and active driving license</small>
                                    <p class="mb-2 mt-3">SIM A Card Number</p>

                                    <span class="imgsima text-danger" style="position: absolute; margin-top: -32px; margin-left: 162px; width: 10px">*</span>

                                    <!-- <div class="row" style="margin-bottom: 5px">
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                            <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                            <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                                        </div>
                                    </div> -->
                                    <label for="fotoSIMA" id="profileLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                                    <p id="simAFileName" style="display: inline;">No file chosen</p>
                                    <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoSIMA" id="fotoSIMA" class="photo" placeholder="Foto Profile" required/>
                                    <span id="fotoSIMA-error" class="error" style="color: red"></span>
                                </div>

                                <!-- SIM C SECTION -->
                                <div id="sim-c-section">
                                    <div class="row gx-0">
                                        <div class="col-12">
                                            <input class="mt-2" type="number" name="sim-c" id="sim-c" placeholder="SIM C Card Number" required />
                                        </div>
                                        <span class="nosimc text-danger" style="position: absolute; margin-top: 16px; margin-left: 161px; width: 10px">*</span>
                                    </div>

                                    <small style="color: grey">Valid and active driving license</small>
                                    <p class="mb-2 mt-3">SIM C Card Number</p>

                                    <span class="imgsimc text-danger" style="position: absolute; margin-top: -32px; margin-left: 159px; width: 10px">*</span>

                                    <!-- <div class="row" style="margin-bottom: 5px">
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                            <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                            <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                                        </div>
                                    </div> -->
                                    <label for="fotoSIMC" id="profileLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                                    <p id="simCFileName" style="display: inline;">No file chosen</p>
                                    <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoSIMC" id="fotoSIMC" class="photo" placeholder="Foto Profile" required/>
                                    <span id="fotoSIMC-error" class="error" style="color: red"></span>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <span class="error" id="sim-validation" style="color: red; font-size: 13px"></span>
                                    </div>
                                </div>

                                <!-- KK SECTION -->
                                <div id="kk-section">
                                    <input class="mt-2" type="number" name="kk" id="kk" placeholder="KK Number" required />

                                    <span class="kknumber text-danger" style="position: absolute; margin-top: -43px; margin-left: 89px; z-index: 999">*</span>

                                    <small style="color: grey">Kartu Keluarga Document</small>
                                    <p class="mb-2 mt-3">Parents Permit Document</p>

                                    <span class="izinortu text-danger" style="position: absolute; margin-top: -32px; margin-left: 208px; width: 10px">*</span>
                                    <!-- <div class="row" style="margin-bottom: 5px">
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                                            <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                                            <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                                        </div>
                                    </div> -->
                                    <label for="fotoKK" id="profileLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                                    <p id="kkFileName" style="display: inline;">No file chosen</p>
                                    <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoKK" id="fotoKK" class="photo" placeholder="Foto Profile" required/>
                                    <span id="fotoKK-error" class="error" style="color: red"></span>
                                </div>
                            </div>

                            <input id="fotoKta" name="fotoKta" type="hidden" value="">
                            <input id="name" name="name" type="hidden" value="">
                            <input id="f_pin" name="f_pin" type="hidden" value="">
                            <input id="domisili" name="domisili" type="hidden" value="">
                                
                            <!-- <div id="notfound" class="form-group-2 mt-3" style="display:none;">
                                <p><b>Data KTA tidak ditemukan.<b></p>
                            </div>
                            <div class="form-group-2 mt-3">
                                <b style="font-size: 18px">Data Anggota</b>
                                <div class="fotoProfil mt-4">
                                    <p>Foto Profil</p>
                                    <img id="fotoProfilKta" class="photo d-none" style="max-width: 50vw; max-height: 50vw;" />
                                    <input type="hidden" id="fotoKta" name="fotoKta" value="">
                                </div>
                                <input type="text" name="name" id="name" placeholder="Nama Lengkap" required />
                                <input type="text" name="domisili" id="domisili" placeholder="Domisili/Provinsi (Sesuai KTP)" required />
                                <div class="fotoSim mt-3">
                                    <p class="fotoSim">Foto Fisik SIM (Optional)</p>
                                    <img id="fotoSimKtaImg" class="photo d-none" style="max-width: 50vw; max-height: 50vw;" />
                                    <input type="file" name="fotoSim" id="fotoSim" class="photo" accept="photo/*,ocr/*" placeholder="Foto Fisik SIM" />
                                    <input type="hidden" id="fotoSimKta" name="fotoSimKta" value="">

                                </div>
                                <div class="fotoPersetujuan mt-3">
                                    <p class="fotoPersetujuan">Formulir Persetujuan Orang Tua / Wali (Optional, jika pemohon tidak punya SIM)</p>
                                    <input type="file" name="fotoPersetujuan" id="fotoPersetujuan" accept="photo/*,ocr/*" class="photo" placeholder="Formulir Persetujuan Orang Tua / Wali" />
                                </div>
                                <div class="kategoriKis mt-3">
                                    <p class="kategoriKis">Kategori KIS</p>
                                    <select name="kategoriKis" id="kategoriKis" class="kategoriKis" required>
                                        <option value="">- Pilih -</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-12 container mx-auto">
                                    <div class="form-check mb-4" style="margin-top: 25px; margin-left: 10px">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px; padding-top: 20px">
                            <input type="submit" style="height: 50px; width: 40%; font-size: 16px; padding: 10px; background-color: #f66701; color: #FFFFFF !important" name="submit" id="submit" class="submit" value="SUBMIT" onclick="selectizeValid()"/>
                        </div>
                        
                    </form>

                    <!-- END FORM -->

                </div>
                <!-- /.container-fluid -->

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

</body>

</html>

        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="modalProgress">
                    <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div id="modalSuccess" class="modal"> -->

            <!-- Modal content -->
            <!-- <div class="modal-content">
                <p>Successfully upload data</p>
            </div>

        </div> -->

    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modalSuccess">
                    <img src="assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KIS Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="card-kis.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">View Card</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 90vh">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modal-validation-body">
                    <p class="mb-0" id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modalMembership">
                    <img src="assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KIS Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/register_kis"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 8;
        localStorage.setItem('grand-total', 60000);
    </script>

    <script>
    
        var title = 'KIS Registration Fee';
        var price = 0;
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';
        var REG_TYPE = 8;
        var is_takken_kta;

    </script>

    <!-- <script src="assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script> -->
    <script src="assets/js/form-kis-new.js?v=<?php echo $ver; ?>"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>

$(document).ready(function(e) {
    // console.log(prov_pricelist);

    $('#province').selectize();

    // FOR RED DOT IN SELECTIZE

    $("#province-selectized").bind("change paste keyup", function() {

        // console.log($('#province-selectized').val());

        if($('#province-selectized').val()){
            $('.chooseprovince').hide();
            
        }else{
            $('.chooseprovince').show();
        }
        
    });

    $('#province').on('change', function() {
        // console.log(prov_pricelist);
        let province_price = prov_pricelist.find(pr => pr.prov_id === $('#province').val());
        console.log(province_price)
        $('input.form-check-input-car').each(function() {
            $(this).attr('data-price', province_price.prov_price);
        })
        $('input.form-check-input-motor').each(function() {
            $(this).attr('data-price', province_price.prov_price);
        })
        $('p.kis-price').each(function() {
            $(this).text('Rp. ' + numberWithDots(province_price.prov_price));
        })
    })
});

</script>

<script>

var total_price = 0;
var array_checked = [];

var admin_fee = <?= $adminFee ?>

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).on("change",":checkbox", function(e){

    // array_checked = $('#category_array').val();
    $('#category-error').text("");

        if (this.checked){

            // console.log($(this).data('id'));
            // console.log($(this).data('price'));

            if ($(this).data('id')){

                $('.choosecr').hide();
                $('.choosemr').hide();

                array_checked.push($(this).data('id'));
                total_price = total_price + $(this).data('price');
                price = total_price;
                localStorage.setItem('grand-total', total_price+admin_fee);
            }

            console.log(array_checked);
            console.log(total_price);

            $('#upgrade-fee').text("Rp. "+numberWithDots(total_price));
            $('#total-payment').text("Rp. "+(numberWithDots(total_price + admin_fee)));

        }else{

            if ($(this).data('id')){
                array_checked = array_checked.filter(e => e !== $(this).data('id'));
                total_price = total_price - $(this).data('price');
                localStorage.setItem('grand-total', total_price+admin_fee);
                price = total_price;
            }

            console.log(array_checked);
            console.log(total_price);

            $('#upgrade-fee').text("Rp. "+numberWithDots(total_price));
            $('#total-payment').text("Rp. "+(numberWithDots(total_price + admin_fee)));
        

            if(array_checked.length == 0){
                $('.choosecr').show();
                $('.choosemr').show();
            }
        
        }

    });

</script>

<script>

    $('#kk-section').hide();
    $('#sim-a-section').hide();
    $('#sim-c-section').hide();

    $('#licenseCar').click(function(){
        if ($(this).is(':checked')){

            $('#licensetype-error').text("");
            $('#sim-a-section').show();
            $(".checklt").hide();

        } else {

            $('#sim-a-section').hide();

            if (!$('#licenseBike').is(':checked')){
                $(".checklt").show();
            }
            
        }
    });

    $('#licenseBike').click(function(){
        if($(this).is(':checked')){

            $('#licensetype-error').text("");
            $('#sim-c-section').show();
            $(".checklt").hide();

        } else {

            $('#sim-c-section').hide();

            if (!$('#licenseCar').is(':checked')){
                $(".checklt").show();
            }

        }
    });

    $('input[type=radio][name=driving_status]').change(function() {
    if (this.value == '1') {

        $('#license-section').show();
        $('#kk-section').hide();

        if($('#licenseCar').is(':checked')){
            $('#sim-a-section').show();
        } else {
            $('#sim-a-section').hide();
        }

        if($('#licenseBike').is(':checked')){
            $('#sim-c-section').show();
        } else {
            $('#sim-c-section').hide();
        }
    }
    else if (this.value == '2') {

        $('#sim-a-section').hide();
        $('#sim-c-section').hide();
        $('#kk-section').show();
        $('#license-section').hide();

    }
});

// FOR ARROW COLLAPSE

    $('#collapseLicense').on('shown.bs.collapse', function () {
        $('#collapse-img-1').attr('src','assets/img/arrow-up.png');
    });

    $('#collapseLicense').on('hidden.bs.collapse', function () {
        $('#collapse-img-1').attr('src','assets/img/arrow-down.png');
    });

    $('#collapseDriving').on('shown.bs.collapse', function () {
        $('#collapse-img-2').attr('src','assets/img/arrow-up.png');
    });

    $('#collapseDriving').on('hidden.bs.collapse', function () {
        $('#collapse-img-2').attr('src','assets/img/arrow-down.png');
    });

// FOR LIMIT NUMBER ID

var $input = $('#sim-a')
    $input.keyup(function(e) {
        var max = 18;
        if ($input.val().length > max) {
            $input.val($input.val().substr(0, max));
        }
});

var $input2 = $('#sim-c')
$input2.keyup(function(e) {
    var max = 18;
    if ($input2.val().length > max) {
        $input2.val($input2.val().substr(0, max));
    }
});

var $input3 = $('#kk')
$input3.keyup(function(e) {
    var max = 18;
    if ($input3.val().length > max) {
        $input3.val($input3.val().substr(0, max));
    }
});
    

</script>

<script>
    $(".chooseprovince").show();
    $(".kknumber").show();
    $(".izinortu").show();
    $(".nosima").show();
    $(".imgsima").show();
    $(".nosimc").show();
    $(".imgsimc").show();

    $("#province").change(function() {
        var valprovince = $(this).val();

        if (valprovince) {
            $(".chooseprovince").hide();
        }

        else {
            $(".chooseprovince").show();
        }
    });

    $("#kk").bind("change paste keyup", function() {
        var valkk = $(this).val();

        if (valkk) {
            $(".kknumber").hide();
        }

        else {
            $(".kknumber").show();
        }
    });

    $("#fotoKK").change(function() {
        var valfotokk = $(this).val();

        if (valfotokk) {
            $(".izinortu").hide();
        }

        else {
            $(".izinortu").show();
        }
    });

    $("#sim-a").bind("change paste keyup", function() {
        var nosima = $(this).val();

        if (nosima) {
            $(".nosima").hide();
        }

        else {
            $(".nosima").show();
        }
    });

    $("#fotoSIMA").change(function() {
        var imgsima = $(this).val();

        if (imgsima) {
            $(".imgsima").hide();
        }

        else {
            $(".imgsima").show();
        }
    });

    $("#sim-c").bind("change paste keyup", function() {
        var nosimc = $(this).val();

        if (nosimc) {
            $(".nosimc").hide();
        }

        else {
            $(".nosimc").show();
        }
    });

    $("#fotoSIMC").change(function() {
        var imgsimc = $(this).val();

        if (imgsimc) {
            $(".imgsimc").hide();
        }

        else {
            $(".imgsimc").show();
        }
    });

</script>

<script>

// FOR SELECTIZED VALIDATION

$("#birthplace").change(function() {
    $('#birthplace-error').text("");
});

$("#bloodtype").change(function() {
    $('#bloodtype-error').text("");
});

$("#nationality").change(function() {
    $('#nationality-error').text("");
});

$("#hobby").change(function() {
    $('#hobby-error').text("");
});

$("#postcode").change(function() {
    $('#postcode-error').text("");
});

$("#province").change(function() {
    $('#province-error').text("");
});

$("#city").change(function() {
    $('#city-error').text("");
});

$("#district").change(function() {
    $('#district-error').text("");
});

$("#subdistrict").change(function() {
    $('#subdistrict-error').text("");
});

$("#club_location").change(function() {
    $('#clublocation-error').text("");
});

$("#club_choice").change(function() {
    $('#clubchoice-error').text("");
});

function selectizeValid(){

    var province = $('#province').val();
    var fotoSIMA = $('#fotoSIMA').val();
    var fotoSIMC = $('#fotoSIMC').val();
    var inputsima = $("#sim-a").val();
    var inputsimc = $("#sim-c").val();
    var fotoKK = $('#fotoKK').val();
    var driving_status = document.querySelector('input[name="driving_status"]:checked').value;
    var payment = $("#dropdownMenuSelectMethod").val();

    $("#fotoSIMA").change(function() {
        $('#fotoSIMA-error').text("");
    });

    $("#fotoSIMC").change(function() {
        $('#fotoSIMC-error').text("");
    });

    $("#fotoKK").change(function() {
        $('#fotoKK-error').text("");
    });

    if (inputsima == inputsimc) {
        $("#sim-validation").text("SIM A and SIM C Card Number cannot be the same.");
    }
    else {
        $("#sim-validation").text("");
    }
    
    if(!province){
        $('#province-error').text("This field is required.");
    }else{
        $('#province-error').text("");
    }

    if(array_checked.length == 0){
        $('#category-error').text("This field is required.");
    }else{
        $('#category-error').text("");
    }
    
    if (!payment || payment == '') {
        $("#payment-error").text("This field is required.");
    }
    else {
        $("#payment-error").text("");
    }

    if(driving_status == 1){

        if(!$('#licenseCar').is(':checked') && !$('#licenseBike').is(':checked')){

            $('#licensetype-error').text("This field is required.");

        }else{

            $('#licensetype-error').text("");

            if($('#licenseCar').is(':checked') && $('#licenseBike').is(':checked')){

                if(!fotoSIMA && !fotoSIMC){
                    $('#fotoSIMA-error').text("This field is required.");
                    $('#fotoSIMC-error').text("This field is required.");
                }else if(!fotoSIMA){
                    $('#fotoSIMA-error').text("This field is required.");
                }else if(!fotoSIMC){
                    $('#fotoSIMC-error').text("This field is required.");
                }
            
            }else if($('#licenseCar').is(':checked')){
                
                if(!fotoSIMA){
                    $('#fotoSIMA-error').text("This field is required.");
                }

            }else if($('#licenseBike').is(':checked')){
                
                if(!fotoSIMC){
                    $('#fotoSIMC-error').text("This field is required.");
                }

            }

        }  
    }else if(driving_status == 2){

        if(!fotoKK){
            $('#fotoKK-error').text("This field is required.");
        }

    }
}

// CHECK KTA

$("#no_kta").bind("change paste keyup", function() {
        var kta_number = $(this).val();

        // console.log(name);

        var formData = new FormData();

        formData.append('no_anggota', kta_number);

        if (kta_number != ""){

            $('.starnokta').hide();

            if(!kta_number){
                $('#kta-exist').text("");
            } else {
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
                        // console.log(xmlHttp.responseText);

                        var result = JSON.parse(xmlHttp.responseText);

                        if (result == 0){
                            console.log("KTA Tidak Ada");
                            $('#kta-not-exist').text("");
                            $('#kta-exist').text("KTA Not Found.");

                            is_takken_kta = 0;
                            
                        }else{

                            console.log("KTA Ada");
                            console.log(result);

                            $('#kta-not-exist').text("KTA Found.");
                            $('#kta-exist').text("");

                            $('#fotoKta').val(result.PROFILE_IMAGE);
                            $('#name').val(result.NAME);
                            $('#domisili').val(result.ADDRESS);
                            $('#f_pin').val(result.F_PIN);

                            is_takken_kta = 1;

                        }

                    }
                }
                xmlHttp.open("post", "logics/check_kta");
                xmlHttp.send(formData);
            }
            
        }else{
            $('#ktp-not-exist').text("");
            $('#ktp-exist').text("");

            $('.starnokta').show();
        }
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
        xmlHttp.open("post", "ogics/logout");
        xmlHttp.send(formData);

    }

</script>
