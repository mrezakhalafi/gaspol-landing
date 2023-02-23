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

    // NATIONALITY

    $sqlData = "SELECT * FROM COUNTRIES";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $countries = $queDATA->get_result();
    $queDATA->close();

    // HOBBIES

    $sqlData = "SELECT * FROM KTA_HOBBY";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $hobby = $queDATA->get_result();
    $queDATA->close();

    // HOBBIES LAINNYA

    // $queDATAS = $dbconn->prepare("SELECT * FROM KTA_HOBBY");
    // $queDATAS->execute();
    // $hobbies = $queDATAS->get_result()->fetch_assoc();
    // $hobby_id = $hobbies["ID"];
    // $queDATAS->close();

    // PROVINCE

    $sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $province = $queDATA->get_result();
    $queDATA->close();

    // BIRTHPLACE / CITY

    $sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $birthplace = $queDATA->get_result();
    $queDATA->close();

    // POSTAL CODE

    // $sqlData = "SELECT * FROM POSTAL_CODE ";

    // $queDATA = $dbconn->prepare($sqlData);
    // $queDATA->execute();
    // $postal = $queDATA->get_result();
    // $queDATA->close();

    // PRICE

    $sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '2'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $price = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    $upgradeFee = $price['REG_FEE'];
    $adminFee = $price['ADMIN_FEE'];

    $sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '3'";

    $queDATA = $dbconn->prepare($sqlData);
    $queDATA->execute();
    $price = $queDATA->get_result()->fetch_assoc();
    $queDATA->close();

    $upgradeFeePro = $price['REG_FEE'];
    $adminFeePro = $price['ADMIN_FEE'];

    $ver = time();

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

    <script src="assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <!-- <link rel="stylesheet" href="assets/css/form-e-sim.css?v=<?php echo $ver; ?>"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>

        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
            margin: 0;
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        #name {
            /* position: absolute; */
        }

        .star {
            position: absolute;
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

        /* .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        } */

        .error{
            font-size: 16px !important;
            color: red;
            width: 100%;
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

        .selectize-control.single .selectize-input, .selectize-control.single .selectize-input input {
            cursor: pointer;
            border: none;
            border-bottom: 2px solid #ebebeb;
            border-radius: unset;
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
                    <div class="p-3">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <img src="../../../assets/img/membership-back.png" style="width: 40px; height: auto" onclick="history.back()">
                                <span style="font-size: 18px; font-weight: 700; color: #000000; margin-top: 6px" class="ms-3">Buat KTA</span>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button class="btn" style="color: #000000; background-color: transparent; border: 1px solid #000000; border-radius: 30px; font-weight: 700" onclick="ktaRombongan()"><img src="../../../assets/img/kta-batch.svg" alt=""> Buat KTA Rombongan</button>
                            </div>
                        </div>
                    </div>

                    <!-- START FORM   -->

                    <form method="POST" class="main-form" id="admin-kta-form" style="padding: 0px" action="/gaspol_web/logics/register_new_kta" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12">

                                        <div class="bg-white" style="border-radius: 20px">
                                            <div class="pt-2" style="font-size: 18px">
                                                <p class="ms-3 mt-2 mb-0" style="color: #000000; font-weight: 700">Dokumen Foto</p>
                                            </div>

                                            <div class="p-3">
                                                <div style="height: 1px; background-color: #E7E7E7">

                                                </div>
                                            </div>

                                            <div class="p-3">
                                                <!-- <p class="mb-2">Profile Picture &nbsp;<span class="starppimg text-danger">*</span> </p> -->
                                                <!-- <input type="file" accept="image/*,photo/*,ocr/*" name="fotoProfil" id="fotoProfil" class="photo" placeholder="Foto Profil" required /> -->
                                                <div class="row">
                                                    <div class="col-2 ps-0 pe-0 text-center">
                                                        <img id="imageProfile" src="../../../assets/img/pp-dummy.svg?v=2" style="width: 100px; height: 100px; border: 1px solid #eeeeee; object-fit: cover; object-position: center">
                                                    </div>
                                                    <div class="col-3 me-5">
                                                        <p class="mb-2">Foto Profil KTA &nbsp;<span class="starppimg text-danger">*</span> </p>
                                                        <div class="row w-75 mt-3">
                                                            <label for="fotoProfile" id="profileLabelBtn" style="color: #000000; border: 1px solid #000000; background-color: white; margin-right: 10px; margin-bottom: 10px; border-radius: 20px; font-weight: 700" class="btn">Upload</label>
                                                        </div>
                                                        <div class="row">
                                                            <!-- <p id="profileFileName" style="display: inline;">No file chosen</p> -->
                                                            <input type="file" style="display: none" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)"/>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="col-1"></div> -->
                                                
                                                    <div class="col-2 ps-0 pe-0 text-center">
                                                        <img id="imageKTP" src="../../../assets/img/pp.svg?v=2" style="width: 100px; height: 100px; border: 1px solid #eeeeee; object-fit: cover; object-position: center">
                                                    </div>
                                                    <div class="col-3">
                                                        <p class="mb-2">Foto Identitas &nbsp;<span class="starktp text-danger">*</span> </p>
                                                        <div class="row w-75 mt-3">
                                                            <label for="fotoEktp" id="ektpLabelBtn" style="padding: .375rem .75rem; color: #000000; border: 1px solid #000000; background-color: white; margin-right: 10px; margin-bottom: 10px; border-radius: 20px; font-weight: 700" class="btn">Upload</label>
                                                        </div>
                                                        <div class="row">
                                                            <!-- <p id="ektpFileName" style="display: inline;">No file chosen</p> -->
                                                            <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" required onchange="loadFile2(event)"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <span id="fotoProfile-error" class="error" style="color: red"></span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span id="fotoEktp-error" class="error" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-2" style="padding: 12px">

                                    <div class="bg-white" style="border-radius: 20px">
                                        <div class="row">
                                            <div class="pt-2" style="font-size: 18px">
                                                <p class="ms-3 mt-2 mb-0" style="color: #000000; font-weight: 700">Informasi Pribadi</p>
                                            </div>

                                            <div class="p-3">
                                                <div style="height: 1px; background-color: #E7E7E7">

                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <p class="mt-3 mb-1">Produk KTA</p>
                                                <div class="row">
                                                    <!-- <div class="col-6">
                                                        <input type="radio" id="radioMob" name="kta_type" class="radio" value="0" checked>
                                                        <label for="radioMob">&nbsp;&nbsp;KTA Mobility</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="radio" id="radioPro" name="kta_type" class="radio" value="1">
                                                        <label for="radioPro">&nbsp;&nbsp;KTA Pro</label><br>
                                                    </div> -->
                                                    <div class="col-12">
                                                        <select class="mt-3 mb-2" id="kta_type" name="kta_type" aria-label="" style="font-size: 16px">
                                                            <option style="margin-left: -12px" value="1" selected>Mobility</option>
                                                            <option style="margin-left: -12px" value="2">Pro</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row gx-0" style="margin-top: 15px">
                                                    <div class="col-12">
                                                        <input type="number" name="ektp" id="ektp" placeholder="NIK" style="padding-left: 12px" required />
                                                    </div>
                                                    <span class="starnoktp text-danger" style="position: absolute; margin-top: 11px; margin-left: 45px; width: 10px">*</span>
                                                    <label id="ktp-exist" class="text-danger"></label>
                                                    <label id="ktp-not-exist" class="text-success"></label>
                                                    <label id="ktp-16" class="text-danger"></label>
                                                </div>

                                                <div class="row gx-0">
                                                    <div class="col-12">
                                                        <input type="email" name="email" id="email" placeholder="Email" style="padding-left: 12px" required />
                                                    </div>
                                                    <span class="starmail text-danger" style="position: absolute; margin-top: 11px; margin-left: 64px; width: 10px">
                                                        *
                                                    </span>
                                                    <label id="username-exist" class="text-danger"></label>
                                                    <label id="username-not-exist" class="text-success"></label>
                                                </div>

                                                <!-- <p class="mt-3 mb-1">Date of Birth</p> -->
                                                <div class="row gx-0" style="margin-top: 8px">
                                                    <div class="col-12">
                                                        <input type="text" onfocus="(this.type='date')" name="date_birth" id="date_birth" placeholder="Date of Birth" required style="background-color: transparent; padding-left: 12px"/>
                                                    </div>
                                                    <span class="bdstar text-danger" style="position: absolute; margin-top: 10px; margin-left: 118px; width: 10px">*</span>
                                                </div>

                                                <div class="row" style="margin-top: 30px">
                                                    <select class="mt-3 mb-2" id="bloodtype" name="bloodtype" aria-label="" style="font-size: 16px">
                                                        <option value="" selected>Gol. Darah</option>
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="AB">AB</option>
                                                        <option value="O">O</option>
                                                    </select>
                                                </div>

                                                <span class="starbt text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 104px">*</span>
                                                <span id="bloodtype-error" class="error" style="color: red"></span>
                                            </div>

                                            <div class="col-6">
                                                <div class="row gx-0 mt-5">
                                                    <div class="col-12">
                                                        <input style="padding-left: 12px" type="text" name="name" id="name" placeholder="Nama" required />
                                                    </div>
                                                    <span class="fullname ps-3 text-danger" style="position: absolute; margin-top: 11px; margin-left: 57px; width: 10px">
                                                        *
                                                    </span>
                                                </div>

                                                <div class="row gx-0" style="margin-top: 37px">
                                                    <div class="col-12">
                                                        <select class="mb-2" style="margin-left: -5px" id="birthplace" name="birthplace" aria-label="" style="font-size: 16px">
                                                            <option value=""  selected>Tempat Lahir</option>

                                                            <?php foreach($birthplace as $b): ?>
                                                                <option value="<?= $b['CITY_ID'] ?>"><?= ucwords(strtolower($b['CITY_NAME'])) ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                        <span class="starbp text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 127px">*</span>
                                                        <span id="birthplace-error" class="error" style="color: red"></span>
                                                    </div>
                                                </div> 
                                                
                                                <!-- <p class="mt-3 mb-1">Jenis Kelamin</p> -->
                                                <div class="row" style="margin-top: 10px">
                                                    <!-- <div class="col-6">
                                                        <input type="radio" id="genderMale" name="gender_radio" class="radio" value="1" checked>
                                                        <label for="genderMale">&nbsp;&nbsp;Laki-laki</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="radio" id="genderFemale" name="gender_radio" class="radio" value="2">
                                                        <label for="genderFemale">&nbsp;&nbsp;Perempuan</label><br>
                                                    </div> -->
                                                    <div class="col-12">
                                                        <select class="mt-3 mb-2" id="gender" name="gender_radio" aria-label="" style="font-size: 16px">
                                                            <option value="" selected>Jenis Kelamin</option>
                                                            <option value="1">Laki-laki</option>
                                                            <option value="2">Perempuan</option>
                                                        </select>
                                                        <span class="stargender text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 134px">*</span>
                                                        <span id="gender-error" class="error" style="color: red"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="row" style="margin-top: 26px">
                                                    <select class="mb-2" name="nationality" id="nationality" aria-label="" style="font-size: 16px">
                                                        <option value="" selected>Warga Negara</option>

                                                        <?php foreach($countries as $c): ?>
                                                            <option value="<?= $c['ID'] ?>"><?= $c['COUNTRY_NAME'] ?></option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                </div>
                                                <span class="starnation text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 142px">*</span>
                                                <span id="nationality-error" class="error" style="color: red"></span>

                                                <div class="row" style="margin-top: 36px">
                                                    <select class="mb-2" id="hobby" name="hobby" aria-label="" style="font-size: 16px">
                                                        <option value="" selected>Hobby</option>

                                                        <?php foreach($hobby as $h): ?>
                                                            <option value="<?= $h['ID'] ?>"><?= $h['NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <span class="starhobby text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 75px">*</span>

                                                <div class="add-hobby row gx-0">
                                                    <div class="col-12">
                                                        <input type="text" name="hobby_desc" id="hobby_desc" placeholder="Hobby" required />
                                                    </div>
                                                    <span class="staraddhobby text-danger" style="position: absolute; margin-top: 10px; margin-left: 49px; width: 10px">*</span>
                                                </div>     
                                                <span id="hobby-error" class="error" style="color: red"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white" style="border-radius: 20px">

                                    <div class="row" style="padding: 12px">
                                        <div class="pt-2" style="font-size: 18px">
                                            <p class="ms-3 mt-2 mb-0" style="color: #000000; font-weight: 700">Alamat</p>
                                        </div>

                                        <div class="p-3">
                                            <div style="height: 1px; background-color: #E7E7E7">

                                            </div>
                                        </div>

                                        <div class="col-6">

                                            <div class="row gx-0">
                                                <div class="col-12">
                                                    <input type="text" name="address" id="address" placeholder="Alamat Lengkap" style="padding-left: 12px" required />
                                                </div>
                                                <span class="staraddress text-danger" style="position: absolute; margin-top: 10px; margin-left: 155px; width: 10px">*</span>
                                            </div>

                                            <div class="row" style="margin-top: 25px">
                                                <div class="col-6">
                                                    <input type="number" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" style="padding-left: 12px" required />
                                                </div>
                                                <span class="starrt text-danger" style="position: absolute; margin-top: 11px; margin-left: 27px; width: 10px">*</span>
                                                <div class="col-6">
                                                    <input type="number" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" style="padding-left: 12px" required />
                                                </div>
                                                <span class="starrw text-danger" style="position: absolute; margin-top: 9px; margin-left: 64%; width: 10px">*</span>
                                            </div>

                                            <div class="row" style="margin-top: 35px">
                                                <select class="mb-2" id="city" name="city" aria-label="" style="font-size: 16px">
                                                    <option value="" selected>Kota/Kabupaten</option>
                                                </select>
                                                
                                                <span class="starcity text-danger" style="position: absolute; z-index: 999; margin-top: 5px; margin-left: 156px">*</span>
                                                <span id="city-error" class="error" style="color: red"></span>
                                            </div>

                                            <div class="row" style="margin-top: 29px">
                                                <select class="mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px">
                                                    <option value="" selected>Desa</option>
                                                </select>

                                                <span class="starsubdist text-danger" style="position: absolute; z-index: 999; margin-top: 5px; margin-left: 65px">*</span>
                                                <span id="subdistrict-error" class="error" style="color: red"></span>
                                            </div>

                                            <!-- <div class="row">
                                                <div class="col-6">
                                                    <input type="number" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" required />
                                                </div>
                                                <span class="starrt text-danger" style="position: absolute; margin-top: 8px; margin-left: 26px; width: 10px">*</span>
                                                <div class="col-6">
                                                    <input type="number" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" required />
                                                </div>
                                                <span class="starrw text-danger" style="position: absolute; margin-top: 8px; margin-left: 53%; width: 10px">*</span>
                                            </div> -->

                                        </div>

                                        <div class="col-6">
                                            
                                            <div class="row" style="margin-top: 12px">
                                                <select class="mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                                                    <option value="" selected>Provinsi</option>

                                                    <?php foreach($province as $p): ?>
                                                        <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <span class="starprovince text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 87px">*</span>
                                            <span id="province-error" class="error" style="color: red"></span>

                                            <div class="row" style="margin-top: 27px">
                                                <select class="mb-2" id="district" name="district" aria-label="" style="font-size: 16px">
                                                    <option value="" selected>Kecamatan</option>
                                                </select>
                                            </div>

                                            <span class="stardist text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 116px">*</span>
                                            <span id="district-error" class="error" style="color: red"></span>
                                            
                                            <div class="row" style="margin-top: 25px">
                                                <select class="mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px">
                                                    <option value="" selected>Postcode</option>

                                                    <!-- <?php foreach($postal as $p): ?>
                                                        <option value="<?= $p['POSTAL_ID'] ?>"><?= $p['POSTAL_CODE'] ?></option>
                                                    <?php endforeach; ?> -->
                                                
                                                </select>
                                            </div>

                                            <span class="starpost text-danger" style="position: absolute; z-index: 999; margin-top: -44px; margin-left: 97px">*</span>
                                            <span id="postcode-error" class="error" style="color: red"></span>

                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="form-check mb-4" style="margin-top: 50px">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                    <label class="form-check-label" for="flexCheckChecked">
                                        I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!
                                    </label>
                                </div> -->
                        
                                <!-- <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px; padding-top: 20px">
                                    <input type="submit" style="width: 40%; font-size: 16px; height: 50px; padding: 10px; background-color: white; color: #000000 !important; border-radius: 30px; border: 1px solid #000000; font-weight: 700" name="submit" id="submit" class="submit" value="SUBMIT" onclick="selectizeValid()"/>
                                </div> -->

                            </div>
                        

                            <div class="col-4">
                                <div class="bg-white" style="border-radius: 20px">
                                    <div class="row p-4">
                                        <div class="col-2">
                                            <img id="validation-1" src="../../../assets/img/unvalid.svg" alt="" style="width: 25px; height: 25px">
                                        </div>
                                        <div class="col-10">
                                            <p id="validation-text-1" class="mb-0" style="color: #000000; font-weight: 600">Dokumen Foto</p>
                                        </div>
                                    </div>
                                    <div class="row p-4">
                                        <div class="col-2">
                                            <img id="validation-2" src="../../../assets/img/novalid.svg" alt="" style="width: 25px; height: 25px">
                                        </div>
                                        <div class="col-10">
                                            <p id="validation-text-2" class="mb-0" style="color: #AAAAAA">Informasi Pribadi</p>
                                        </div>
                                    </div>
                                    <div class="row p-4">
                                        <div class="col-2">
                                            <img id="validation-3" src="../../../assets/img/novalid.svg" alt="" style="width: 25px; height: 25px">
                                        </div>
                                        <div class="col-10">
                                            <p id="validation-text-3" class="mb-0" style="color: #AAAAAA">Alamat</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <select id="dropdownMenuSelectMethod" style="border: 1px solid #d7d7d7" onchange="selectMethod(this.value);">
                                        <option value="" selected>Select Payment Method</option>
                                        <option value="CARD">CARD</option>
                                        <!-- <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="LINKAJA">LINKAJA</option>
                                        <option value="SHOPEEPAY">SHOPEEPAY</option> -->
                                        <option value="QRIS">QRIS</option>
                                    </select>
                                    <span id="payment-error" class="error" style="color: red"></span>
                                </div>
                                
                                <div class="bg-white" style="border-radius: 20px">
                                    <div class="row mt-3">
                                        <div class="pt-2" style="font-size: 18px">
                                            <p class="ms-3 mt-2 mb-0" style="color: #000000; font-weight: 600">Total Bayar</p>
                                        </div>

                                        <div class="p-3">
                                            <div style="height: 1px; background-color: #E7E7E7">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-8">
                                                <p class="ms-4" style="color: #000000">Iuran Tahunan KTA Mobility</p>
                                            </div>
                                            <div class="col-4">
                                                <p class="ms-4" id="kta-price" style="color: #000000; font-weight: 600"></p>
                                            </div>
                                            <div class="col-8">
                                                <p class="ms-4" class="mb-0" style="color: #000000">Biaya lainnya</p>
                                            </div>
                                            <div class="col-4">
                                                <p class="ms-4" id="admin-price" class="mb-0" style="color: #000000; font-weight: 600"></p>
                                            </div>
                                        </div>

                                        <div class="p-3">
                                            <div style="height: 1px; background-color: #E7E7E7">

                                            </div>
                                        </div>

                                        <div class="p-1"></div>

                                        <div class="row">
                                            <div class="col-8">
                                                <p class="ms-4" style="color: #000000; font-weight: 700">Total Bayar</p>
                                            </div>
                                            <div class="col-4">
                                                <p class="ms-4" id="total-price" style="color: #000000; font-weight: 700"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-submit w-50 d-flex justify-content-center pb-5" style="height: 170px; padding-top: 20px">
                                    <input type="submit" style="font-size: 16px; height: 50px; background-color: #E7E7E7; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700" name="submit" id="submit" class="submit" value="Bayar & Buat KTA" onclick="selectizeValid()"/>
                                </div>
                            </div>
                        <!-- <div style="width: 100%; height: 100px; background-color: transparent"></div> -->
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

<!-- The Modal -->
                    <!-- <div id="modalProgress" class="modal"> -->

                        <!-- Modal content -->
                        <!-- <div class="modal-content">
                            <p>Upload in progress...</p>
                        </div>

                    </div> -->

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
                                    <h1 class="mt-3">KTA Mobility Registration Success!</h1>
                                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-center">
                                            <a href="card-kta-mobility.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">View Card</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- </div> -->

                    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="height: 90vh">
                                <div class="modal-body p-0" id="modal-payment-body">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="margin-top: 200px">
                            <div class="modal-content">
                                <div class="modal-body p-0 text-center" id="modal-error-body">
                                    <p id="error-modal-text"></p>
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

                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-otp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="margin-top: 50px">
                            <div class="modal-content">
                                <div class="modal-body p-0 text-center" id="modal-validation-body">
                                    <p>Kode OTP telah dikirim ke email <span id="email-place-otp" class="text-success"></span>, silahkan buka email anda untuk mendapatkan kode OTP.</p>
                                    <div class="input-group mb-3 mt-3">
                                        <input type="text" id="input-otp" class="form-control" placeholder="Kode OTP" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <small id="otp-not-correct" class="text-danger d-none">Kode OTP tidak sesuai.</small>
                                        </div>
                                    </div>
                                    <div class="btn btn-success" onclick="checkOTP()">Submit</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-3 text-center" id="modalMembership">
                                    <img src="assets/img/success.png" style="width: 100px">
                                    <h1 class="mt-3">KTA Registration Success!</h1>
                                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-center">
                                            <a href="http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/register_kta"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
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
                        var PRICE = "<?= $upgradeFee + $adminFee ?>";
                        var REG_TYPE = 2;
                        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
                    </script>

                    <script>
                        var selectMethod;
                        
                        var title = 'Mobility Upgrade Fee';
                        var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
                        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
                        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';

                        var is_takken;
                        var is_takken_ktp;
                    </script>

                    <!-- <script src="assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script> -->
                    <script src="assets/js/form-kta-mobility.js?v=<?php echo $ver; ?>"></script>
                    
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

                    <!-- Javascript -->
                    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
                    </body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>

    $(document).ready(function(e) {

        $('#birthplace').selectize({
            openOnFocus: false,

            onInitialize: function () {
                var that = this;

                this.$control.on("click", function () {
                    that.ignoreFocusOpen = true;
                    setTimeout(function () {
                        that.ignoreFocusOpen = false;
                    }, 50);
                });
            },

            onFocus: function () {
                if (!this.ignoreFocusOpen) {
                    this.open();
                }
            }
        });

        $('#kta_type').selectize();
        $('#gender').selectize();
        $('#nationality').selectize();
        $('#bloodtype').selectize();
        $('#hobby').selectize();
        $('#postcode').selectize();
        $('#kta-method').selectize();

        $('#province').selectize();
        $('#city').selectize();
        $('#district').selectize();
        $('#subdistrict').selectize();

        $('#dropdownMenuSelectMethod').selectize();

        $('#dropdownMenuSelectMethod-selectized').attr('readonly', true);

        $("#postcode-selectized").bind("change paste keyup", function() {
        
            var $select = $(document.getElementById('postcode'));
            var selectize = $select[0].selectize;

            var postcode = $(this).val();
            var formData = new FormData();

            formData.append('postcode', postcode);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                    
                    Object.keys(obj).forEach(function (item){

                        // console.log(obj[item]['POSTAL_CODE']);                    
                        selectize.addOption({value: obj[item]['POSTAL_ID'], text: obj[item]['POSTAL_CODE']});

                    });

                }
            }
            xmlHttp.open("post", "logics/get_postcode");
            xmlHttp.send(formData);
        });

        function capitalize(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }

        $("#postcode").bind("change", function() {

            var postcode = $(this).val();
            var formData = new FormData();

            formData.append('postcode', postcode);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    const address = JSON.parse(xmlHttp.responseText);

                    var city_id = address.CITY_ID;
                    var subdis_id = address.SUBDIS_ID;
                    var district_id = address.DIS_ID;
                    var province_id = address.PROV_ID;
                    
                    var city_name = capitalize(address.CITY_NAME);
                    var subdis_name = capitalize(address.SUBDIS_NAME);
                    var district_name = capitalize(address.DIS_NAME);
                    var province_name = capitalize(address.PROV_NAME);

                    var $select = $(document.getElementById('province'));
                    var selectize = $select[0].selectize;
                    selectize.addOption({value: province_id, text: province_name});
                    selectize.setValue(province_id);

                    var $select2 = $(document.getElementById('city'));
                    var selectize2 = $select2[0].selectize;
                    selectize2.addOption({value: city_id, text: city_name});
                    selectize2.setValue(city_id);

                    var $select3 = $(document.getElementById('district'));
                    var selectize3 = $select3[0].selectize;
                    selectize3.addOption({value: district_id, text: district_name});
                    selectize3.setValue(district_id);

                    var $select4 = $(document.getElementById('subdistrict'));
                    var selectize4 = $select4[0].selectize;
                    selectize4.addOption({value: subdis_id, text: subdis_name});
                    selectize4.setValue(subdis_id);

                    // console.log(city_id);
                    // console.log(subdis_id);
                    // console.log(district_id);
                    // console.log(province_id);
                    // console.log(city_name);
                    // console.log(subdis_name);
                    // console.log(district_name);
                    // console.log(province_name);

                    // $('#city').val(capitalize(city));
                    // $('#province').val(capitalize(province));
                    // $('#district').val(capitalize(district));
                    // $('#district_word').val(capitalize(subdis));

                }
            }
            xmlHttp.open("post", "logics/get_full_address");
            xmlHttp.send(formData);
        });

        $(".add-hobby").hide();

        $("#hobby").change(function() {
            var value = $(this).val();
            
            if(value == 6){
                $(".add-hobby").show();
            }

            else {
                $(".add-hobby").hide();
            }
        });

        // FOR ARROW COLLAPSE

        $('#collapsePersonal').on('shown.bs.collapse', function () {
            $('#collapse-img-1').attr('src','assets/img/arrow-up.png');
        });

        $('#collapsePersonal').on('hidden.bs.collapse', function () {
            $('#collapse-img-1').attr('src','assets/img/arrow-down.png');
        });

        $('#collapseAddress').on('shown.bs.collapse', function () {
            $('#collapse-img-2').attr('src','assets/img/arrow-up.png');
        });

        $('#collapseAddress').on('hidden.bs.collapse', function () {
            $('#collapse-img-2').attr('src','assets/img/arrow-down.png');
        });

        $('#collapseIdentification').on('shown.bs.collapse', function () {
            $('#collapse-img-3').attr('src','assets/img/arrow-up.png');
        });

        $('#collapseIdentification').on('hidden.bs.collapse', function () {
            $('#collapse-img-3').attr('src','assets/img/arrow-down.png');
        });

        // select[0].selectize.close();

        // PROVINCE TO CITY

        $("#province").bind("change", function() {

            var $select = $(document.getElementById('city'));
            var selectize = $select[0].selectize;

            // CLEAR CITY
            selectize.clear(); 
            selectize.clearOptions();
        
            // CLEAR DISTRICT
            var $select2 = $(document.getElementById('district'));
            var selectize2 = $select2[0].selectize;
            selectize2.clearOptions();
            selectize2.clear(); 

            // CLEAR SUBDISTRICT
            var $select3 = $(document.getElementById('subdistrict'));
            var selectize3 = $select3[0].selectize;
            selectize3.clearOptions();
            selectize3.clear(); 

            var province = $(this).val();
            localStorage.setItem("province", province);

            var formData = new FormData();

            formData.append('province', province);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);
                            
                            selectize.addOption({value: obj[item]['CITY_ID'], text: capitalize(obj[item]['CITY_NAME'])});

                        });
                }
            }
            xmlHttp.open("post", "logics/get_city");
            xmlHttp.send(formData);
        });

        // CITY TO DISTRICT

        $("#city").bind("change", function() {

            var $select = $(document.getElementById('district'));
            var selectize = $select[0].selectize;

            // CLEAR CITY
            selectize.clearOptions();
            selectize.clear(); 

            // CLEAR DISTRICT
            var $select2 = $(document.getElementById('district'));
            var selectize2 = $select2[0].selectize;
            selectize2.clearOptions();
            selectize2.clear(); 

            // CLEAR SUBDISTRICT
            var $select3 = $(document.getElementById('subdistrict'));
            var selectize3 = $select3[0].selectize;
            selectize3.clearOptions();
            selectize3.clear(); 

            var city = $(this).val();
            localStorage.setItem("city", city);

            var formData = new FormData();

            formData.append('city', city);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);
                        
                            selectize.addOption({value: obj[item]['DIS_ID'], text: capitalize(obj[item]['DIS_NAME'])});

                        });
                }
            }
            xmlHttp.open("post", "logics/get_district");
            xmlHttp.send(formData);
        });

        // DISTRICT TO SUBDIS

        $("#district").bind("change", function() {

            var $select = $(document.getElementById('subdistrict'));
            var selectize = $select[0].selectize;

            // CLEAR DISTRICT
            selectize.clearOptions();
            selectize.clear(); 

            // CLEAR SUBDISTRICT
            var $select3 = $(document.getElementById('subdistrict'));
            var selectize3 = $select3[0].selectize;
            selectize3.clearOptions();
            selectize3.clear(); 

            var district = $(this).val();
            localStorage.setItem("district", district);

            var formData = new FormData();

            formData.append('district', district);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    console.log(xmlHttp.responseText);

                    var obj = JSON.parse(xmlHttp.responseText);
                        
                        Object.keys(obj).forEach(function (item){

                            // console.log(obj[item]['POSTAL_CODE']);

                            selectize.addOption({value: obj[item]['SUBDIS_ID'], text: capitalize(obj[item]['SUBDIS_NAME'])});

                        });
                }
            }
            xmlHttp.open("post", "logics/get_subdistrict");
            xmlHttp.send(formData);
        });

        // SUBDIS TO ALL ADDRESS

        $("#subdistrict").bind("change", function() {

            var province = localStorage.getItem("province");
            var city = localStorage.getItem("city");
            var district = localStorage.getItem("district");
            var subdistrict = $(this).val();

            console.log("Province :"+province);
            console.log("City :"+city);
            console.log("District :"+district);
            console.log("Subdis :"+subdistrict);

            var formData = new FormData();

            formData.append('province', province);
            formData.append('city', city);
            formData.append('district', district);
            formData.append('subdistrict', subdistrict);
            formData.append('postcode', 0);

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(">>>"+xmlHttp.responseText);
                    const postcode = JSON.parse(xmlHttp.responseText);

                    var $select = $(document.getElementById('postcode'));
                    var selectize = $select[0].selectize;

                    // CLEAR POSTAL CODE
                    if ($('#postcode').val().trim() === '') {
                        selectize.clearOptions();
                        selectize.clear(); 

                        selectize.addOption({value: postcode.POSTAL_ID, text: postcode.POSTAL_CODE});
                        selectize.setValue(postcode.POSTAL_ID);  
                    }          
                    
                }
            }
            xmlHttp.open("post", "logics/get_postcode");
            xmlHttp.send(formData);
        });

        // FOR RED DOT IN SELECTIZE

        $("#birthplace-selectized").bind("change paste keyup", function() {

            if($('#birthplace-selectized').val()){
                $('.starbp').hide();
            }else{
                $('.starbp').show();
            }
        });
        
        // $('#birthplace').on('dropdown_close', checkBirthplace);

        // var checkBirthplace = function() {
        //     $('.starbp').show();
        // }

        $("#bloodtype-selectized").bind("change paste keyup", function() {

            if($('#bloodtype-selectized').val()){
                $('.starbt').hide();
            }else{
                $('.starbt').show();
            }
        });

        $("#nationality-selectized").bind("change paste keyup", function() {

            if($('#nationality-selectized').val()){
                $('.starnation').hide();
            }else{
                $('.starnation').show();
            }
        });

        $("#postcode-selectized").bind("change paste keyup", function() {

            if($('#postcode-selectized').val()){
                $('.starpost').hide();
            }else{
                $('.starpost').show();
            }
        });

        $("#province-selectized").bind("change paste keyup", function() {

            if($('#province-selectized').val()){
                $('.starprovince').hide();
            }else{
                $('.starprovince').show();
            }
        });

        $("#city-selectized").bind("change paste keyup", function() {

            if($('#city-selectized').val()){
                $('.starcity').hide();
            }else{
                $('.starcity').show();
            }
        });

        $("#district-selectized").bind("change paste keyup", function() {

            if($('#district-selectized').val()){
                $('.stardist').hide();
            }else{
                $('.stardist').show();
            }
        });

        $("#subdistrict-selectized").bind("change paste keyup", function() {

            if($('#subdistrict-selectized').val()){
                $('.starsubdist').hide();
            }else{
                $('.starsubdist').show();
            }
        });

        $("#hobby-selectized").bind("change paste keyup", function() {

            if($('#hobby-selectized').val()){
                $('.starhobby').hide();
            }else{
                $('.starhobby').show();
            }
        });

    });

</script>

<script>

    var count_pp = 0;
    var count_ktp = 0;

    var loadFile = function(event) {

        var reader = new FileReader();
        reader.onload = function() {
        
        $('#fotoProfile-error').text("");
        $('#imageProfile').attr('src', reader.result);

        }
        reader.readAsDataURL(event.target.files[0]);

        count_pp = count_pp + 1;
        checkSessionOne();
        // checkAllSession();
    };

    var loadFile2 = function(event) {

        var reader = new FileReader();
        reader.onload = function() {
        
        $('#fotoEktp-error').text("");
        $('#imageKTP').attr('src', reader.result);
        // $('#imageKTP').show();

        }
        reader.readAsDataURL(event.target.files[0]);

        count_ktp = count_ktp + 1;
        checkSessionOne();
        // checkAllSession();
    };

    var $input_address = $('#address')
    $input_address.keyup(function(e) {
        var max = 60;
        if ($input_address.val().length > max) {
            $input_address.val($input_address.val().substr(0, max));
        }
    });

    var max = 1

    var $input = $('#ektp')
    $input.keyup(function(e) {
        var max = 18;
        if ($input.val().length > max) {
            $input.val($input.val().substr(0, max));
        }
    });

</script>

<script>
    $(".fullname").show();
    $(".starmail").show();
    $(".starbp").show();
    $(".bdstar").show();
    $(".starbt").show();
    $(".starnation").show();
    // $(".starhobby").show();
    // $(".staraddhobby").show();
    $(".staraddress").show();
    $(".starrt").show();
    $(".starrw").show();
    $(".starpost").show();
    $(".starprovince").show();
    $(".starcity").show();
    $(".stardist").show();
    $(".starsubdist").show();
    $(".starppimg").show();
    $(".starnoktp").show();
    $(".starktp").show();
    
    $("#name").bind("change paste keyup", function() {
        var namevalue = $(this).val();

        if (namevalue) {
            $(".fullname").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".fullname").show();
        }
    });

    $("#email").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starmail").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starmail").show();
        }
    });

    $("#birthplace").change(function() {
        var bpvalue = $(this).val();

        if (bpvalue) {
            $(".starbp").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starbp").show();
        }
    });

    $("#date_birth").bind("change paste keyup", function() {
        var bdvalue = $(this).val();

        if (bdvalue) {
            $(".bdstar").hide();
        }

        else {
            $(".bdstar").show();
        }

    });

    $("#bloodtype").change(function() {
        var btvalue = $(this).val();

        if (btvalue) {
            $(".starbt").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starbt").show();
        }
    });

    $("#nationality").change(function() {
        var nationalvalue = $(this).val();

        if (nationalvalue) {
            $(".starnation").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starnation").show();
        }
    });

    $("#hobby").change(function() {
        var hobbyvalue = $(this).val();

        if (hobbyvalue) {
            $(".starhobby").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starhobby").show();
        }
    });

    $("#hobby_desc").bind("change paste keyup", function() {
        var addhobbyvalue = $(this).val();

        if (addhobbyvalue) {
            $(".staraddhobby").hide();
            // checkSessionTwo();
            checkAllSession();
        }

        else {
            $(".staraddhobby").show();
        }

    });

    $("#address").bind("change paste keyup", function() {
        var addressvalue = $(this).val();

        if (addressvalue) {
            $(".staraddress").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".staraddress").show();
        }

    });

    $("#rt").bind("change paste keyup", function() {
        var rtvalue = $(this).val();

        if (rtvalue) {
            $(".starrt").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starrt").show();
        }

    });



    $("#rw").bind("change paste keyup", function() {
        var rwvalue = $(this).val();

        if (rwvalue) {
            $(".starrw").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starrw").show();
        }

    });

    $("#postcode").change(function() {
        var pcvalue = $(this).val();

        if (pcvalue) {
            $(".starpost").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starpost").show();
        }
    });

    $("#province").change(function() {
        var provincevalue = $(this).val();

        if (provincevalue) {
            $(".starprovince").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starprovince").show();
        }
    });

    $("#city").change(function() {
        var cityvalue = $(this).val();

        if (cityvalue) {
            $(".starcity").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starcity").show();
        }
    });

    $("#district").change(function() {
        var distvalue = $(this).val();

        if (distvalue) {
            $(".stardist").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".stardist").show();
        }
    });

    $("#subdistrict").change(function() {
        var subdistvalue = $(this).val();

        if (subdistvalue) {
            $(".starsubdist").hide();
            checkSessionThree();
            // checkAllSession();
        }

        else {
            $(".starsubdist").show();
        }
    });

    $("#fotoProfile").change(function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starppimg").hide();
        }

        else {
            $(".starppimg").show();
        }
    });

    $("#ektp").bind("change paste keyup", function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starnoktp").hide();
            checkSessionTwo();
            // checkAllSession();
        }

        else {
            $(".starnoktp").show();
        }
    });

    $("#fotoEktp").change(function() {
        var ppktp = $(this).val();

        if (ppktp) {
            $(".starktp").hide();
        }

        else {
            $(".starktp").show();
        }
    });

    $("#dropdownMenuSelectMethod").change(function() { 
        var paymentMethods = $(this).val();

        if (paymentMethods) {
            checkAllSession();
        }

        else {
            checkAllSession();
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

    $("#dropdownMenuSelectMethod").change(function() {
        $('#payment-error').text("");
    });

    function selectizeValid(){
        var birthplace = $('#birthplace').val();
        var bloodtype = $('#bloodtype').val();
        var gender = $('#gender').val();
        var nationality = $('#nationality').val();
        var hobby = $('#hobby').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        var fotoProfile = $('#fotoProfile').val();
        var fotoEktp = $('#fotoEktp').val();

        var payment = $("#dropdownMenuSelectMethod").val();

        // var type = document.querySelector('input[name="kta_type"]:checked').value;
        var type = $("#kta_type").val();

        if(type == 1){

            REG_TYPE = 2;
            PRICE = "<?= $upgradeFee + $adminFee ?>";;

        }else{

            REG_TYPE = 3;
            PRICE = "<?= $upgradeFeePro + $adminFeePro ?>";;

        }

        if(!birthplace){
            $('#birthplace-error').text("This field is required.");
        }else{
            $('#birthplace-error').text("");
        }

        if(!bloodtype){
            $('#bloodtype-error').text("This field is required.");
        }else{
            $('#bloodtype-error').text("");
        }

        if(!gender){
            $('#gender-error').text("This field is required.");
        }else{
            $('#gender-error').text("");
        }

        if(!nationality){
            $('#nationality-error').text("This field is required.");
        }else{
            $('#nationality-error').text("");
        }

        if(!hobby){
            $('#hobby-error').text("This field is required.");
        }else{
            $('#hobby-error').text("");
        }

        if(!postcode){
            $('#postcode-error').text("This field is required.");
        }else{
            $('#postcode-error').text("");
        }

        if(!province){
            $('#province-error').text("This field is required.");
        }else{
            $('#province-error').text("");
        }

        if(!city){
            $('#city-error').text("This field is required.");
        }else{
            $('#city-error').text("");
        }

        if(!district){
            $('#district-error').text("This field is required.");
        }else{
            $('#district-error').text("");
        }

        if(!subdistrict){
            $('#subdistrict-error').text("This field is required.");
        }else{
            $('#subdistrict-error').text("");
        }

        if(!fotoEktp){
            $('#fotoEktp-error').text("This field is required.");
        }else{
            $('#fotoEktp-error').text("");
        }

        if(!fotoProfile){
            $('#fotoProfile-error').text("This field is required.");
        }else{
            $('#fotoProfile-error').text("");
        }

        if (!payment || payment == '') {
            $("#payment-error").text("This field is required.");
        }
        else {
            $("#payment-error").text("");
        }
    }

    // CHECK EMAIL ALREADY TAKEN

    $("#email").bind("change paste keyup", function() {
        var email = $(this).val();

        // console.log(name);

        var formData = new FormData();

        formData.append('email', email);

        if (email != ""){

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(xmlHttp.responseText);

                    var result = xmlHttp.responseText;

                    if (result == "Ada"){
                        // console.log("Username Ada");
                        $('#username-not-exist').text("");
                        $('#username-exist').text("That email is taken, try another.");

                        is_takken = 0;
                    }else if(result == "Tidak ada"){
                        // console.log("Username Tidak Ada");
                        // $('#username-not-exist').text("That email is available.");
                        $('#username-exist').text("");

                        is_takken = 1;
                    }

                }
            }
            xmlHttp.open("post", "logics/check_kta_email");
            xmlHttp.send(formData);

        }else{
            $('#username-not-exist').text("");
            $('#username-exist').text("");
        }
    });

    // CHECK KTP

    $("#ektp").bind("change paste keyup", function() {
        var ektp = $(this).val();

        // console.log(name);

        var formData = new FormData();

        formData.append('ektp', ektp);

        if (ektp != ""){

            if(ektp.length < 16){
                $('#ktp-16').text("KTP Number must be 16 digits.");
                $('#ktp-exist').text("");
            } else {
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
                        // console.log(xmlHttp.responseText);

                        var result = xmlHttp.responseText;

                        if (result == 1){
                            console.log("KTP Ada");
                            $('#ktp-not-exist').text("");
                            $('#ktp-16').text("");
                            $('#ktp-exist').text("That KTP Number is taken, try another.");

                            is_takken_ktp = 0;
                        }else if(result == 0){
                            console.log("KTP Tidak Ada");
                            $('#ktp-16').text("");
                            $('#ktp-not-exist').text("That KTP Number is available.");
                            $('#ktp-exist').text("");

                            is_takken_ktp = 1;
                        }

                    }
                }
                xmlHttp.open("post", "logics/check_ktp");
                xmlHttp.send(formData);
            }
            
        }else{
            $('#ktp-not-exist').text("");
            $('#ktp-exist').text("");
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
        xmlHttp.open("post", "logics/logout");
        xmlHttp.send(formData);

    }

    function ktaRombongan() {
        window.location.href = "register_kta_batch.php?m=1";
    }

    function alphaOnly(event) {
       var value = String.fromCharCode(event.which);
       var pattern = new RegExp(/[a-zA-Z]/i);
       return pattern.test(value);
    }

    $('#name').bind('keypress', alphaOnly);

</script>

<script>

    // HIDE TAKE CAM WHILE ON WEB
    
    if (!window.Android) {
        $("#photo-method").hide();
        $("#id-photo-method").hide();
    }
    else {
        $("#photo-method").show();
        $("#id-photo-method").show();
    }

    // OCR WEB

    function uploadOCRWeb(file) {
        let formData = new FormData();
        formData.append('ektp', file);
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText;

                ktpOcr(result);

            }
        }
        xmlHttp.open("post", "logics/ocr_ktp_web");
        xmlHttp.send(formData);
    }

    $('#fotoEktp').change(function (e) {
        e.preventDefault();
        $('#ektpFileName').text(this.files[0].name)
        // upload for OCR web
        if (!window.Android) {
            uploadOCRWeb(this.files[0]);
        }
    });



</script>

<script>

    $("#kta-price").text("Rp. "+price);
    $("#admin-price").text("Rp. "+price_fee);
    $("#total-price").text("Rp. "+total_price);

    $("#kta_type").bind("change paste keyup", function () {

        var ktaType = $(this).val();

        if (ktaType == 1) {

            // console.log("0");

            var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
            var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
            var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';

            $("#kta-price").text("Rp. "+price);
            $("#admin-price").text("Rp. "+price_fee);
            $("#total-price").text("Rp. "+total_price);

        } else {

            // console.log("1");

            var price = '<?= number_format($upgradeFeePro, 0, '', '.') ?>';
            var price_fee = '<?= number_format($adminFeePro, 0, '', '.') ?>';
            var total_price = '<?= number_format($upgradeFeePro+$adminFeePro, 0, '', '.') ?>';

            $("#kta-price").text("Rp. "+price);
            $("#admin-price").text("Rp. "+price_fee);
            $("#total-price").text("Rp. "+total_price);

        }

    });

    function checkSessionOne(){

        if (count_pp > 0 && count_ktp > 0){
            $("#validation-1").attr("src", "../../../assets/img/valid.svg");
            $("#validation-2").attr("src", "../../../assets/img/unvalid.svg");
            $("#validation-text-2").attr("style", "color: #000000");
        }

        checkAllSession();

    }

    function checkSessionTwo() {
        
        var ektpVal = $("#ektp").val();
        var emailVal = $("#email").val();
        var bloodtypeVal = $("#bloodtype").val();
        var nameVal = $("#name").val();
        var bpVal = $("#birthplace").val();
        var nationalityVal = $("#nationality").val();
        var hobbyVal = $("#hobby").val();

        if (ektpVal && emailVal && bloodtypeVal && nameVal && bpVal && nationalityVal && hobbyVal) {
            $("#validation-2").attr("src", "../../../assets/img/valid.svg");
            $("#validation-text-2").attr("style", "color: #000000");
            $("#validation-3").attr("src", "../../../assets/img/unvalid.svg");
            $("#validation-text-3").attr("style", "color: #000000");
        }

        checkAllSession();

    }

    function checkSessionThree() {

        var addressVal = $("#address").val();
        var rtVal = $("#rt").val();
        var rwVal = $("#rw").val();
        var cityVal = $("#city").val();
        var subdistrictVal = $("#subdistrict").val();
        var provinceVal = $("#province").val();
        var districtVal = $("#district").val();
        var postcodeVal = $("#postcode").val();

        if (addressVal && rtVal && rwVal && cityVal && subdistrictVal && provinceVal && districtVal && postcodeVal) {
            $("#validation-3").attr("src", "../../../assets/img/valid.svg");
            $("#validation-text-3").attr("style", "color: #000000");
        }

        checkAllSession();

    }

    function checkAllSession() {

        var ektpVals = $("#ektp").val();
        var emailVals = $("#email").val();
        var bloodtypeVals = $("#bloodtype").val();
        var nameVals = $("#name").val();
        var bpVals = $("#birthplace").val();
        var nationalityVals = $("#nationality").val();
        var hobbyVals = $("#hobby").val();

        var addressVals = $("#address").val();
        var rtVals = $("#rt").val();
        var rwVals = $("#rw").val();
        var cityVals = $("#city").val();
        var subdistrictVals = $("#subdistrict").val();
        var provinceVals = $("#province").val();
        var districtVals = $("#district").val();
        var postcodeVals = $("#postcode").val();

        var paymentVals = $("#dropdownMenuSelectMethod").val();

        console.log("ektp: " + ektpVals);
        console.log("email: " + emailVals);
        console.log("bloodtype: " + bloodtypeVals);
        console.log("name: " + nameVals);
        console.log("bp: " + bpVals);
        console.log("nationality: " + nationalityVals);
        console.log("hobby: " + hobbyVals);

        console.log("address: " + addressVals);
        console.log("rt: " + rtVals);
        console.log("rw: " + rwVals);
        console.log("city: " + cityVals);
        console.log("subdistrict: " + subdistrictVals);
        console.log("province: " + provinceVals);
        console.log("district: " + districtVals);
        console.log("postcode: " + postcodeVals);

        console.log("payment: " + paymentVals);

        if (count_pp > 0 && count_ktp > 0 && ektpVals && emailVals && bloodtypeVals && nameVals && bpVals && nationalityVals && hobbyVals && addressVals && rtVals && rwVals && cityVals && subdistrictVals && provinceVals && districtVals && postcodeVals && paymentVals){
            console.log("Semua data ke isi.");
            $("#submit").attr("style", "font-size: 16px; height: 50px; background-color: #FF6B00; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700");
        }
        else {
            console.log("Data gagal ke isi");
            $("#submit").attr("style", "font-size: 16px; height: 50px; background-color: #E7E7E7; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700");
        }

    }
    
</script>