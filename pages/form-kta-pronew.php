<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$ver = time();

$sqlData = "SELECT COUNT(*) as exist
  FROM KTA
  WHERE F_PIN = '$f_pin'";

//   echo $sqlData;

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $resDATA = $queDATA->get_result()->fetch_assoc();
// $exist = $resDATA["exist"];
// $queDATA->close();

// if ($exist > 0) {
//     header("Location: /gaspol_web/pages/card-kta?f_pin=$f_pin");
//     die();
// }

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

// BIRTHPLACE / CITY

$sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$birthplace = $queDATA->get_result();
$queDATA->close();

// PROVINCES

$sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$provinces = $queDATA->get_result();
$queDATA->close();

// CLUB

$sqlData = "SELECT * FROM TKT ORDER BY CLUB_NAME ASC";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$club = $queDATA->get_result();
$queDATA->close();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '3'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

// LOAD DATA FROM KTA MOBILITY

$sqlData = "SELECT KTA.*, CITIES.CITY_NAME AS CNAME FROM KTA LEFT JOIN CITIES ON KTA.BIRTHPLACE = CITIES.CITY_ID WHERE F_PIN = '$f_pin'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$ktaData = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

if ($ktaData['STATUS_ANGGOTA'] == 1) {
    header("Location: /gaspol_web/pages/card-kta-pronew?f_pin=$f_pin");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form KTA Pro New</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

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

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="radio"]{
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }

        .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" id="kta-form" style="padding: 0px" action="/gaspol_web/logics/register_new_kta" enctype="multipart/form-data">
            <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid; background-image: url(../assets/img/lbackground_2.png)">
                <div class="col-1 d-flex justify-content-start">
                    <a onclick="history.back()"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                </div>
                <div class="col-11 d-flex justify-content-center">
                    <h2 style="margin-bottom: 0px">KTA Pro Registration</h2>
                </div>
            </div>
        
            <div class="container mx-auto pt-4">
                <h2 class="text-center"><span style="font-size: 22px">Formulir <span style="color: #f66701">Keanggotaan</span></span><br><span style="font-size: 16px; color: #626262">(Kartu Tanda Anggota)</span></h2>
            </div>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            
            <div class="container mx-auto mt-3">

                <?php if($ktaData): ?>
                    <b><p class="text-success mt-3">You upgrading from KTA Mobility.</p></b>
                    <p class="mb-3" style="color: grey">Most of your data will be generated from your KTA.</p>
                <?php else: ?>
                    <!-- <b><p class="text-danger mt-3 mb-2">KTA Data not found.</p></b> -->
                <?php endif; ?>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentification" aria-expanded="false" aria-controls="collapseIdentification">
                    <b>Identification<b>
                    <img id="collapse-img-4" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-3" id="collapseIdentification">

                    <p class="mb-2">Profile Picture &nbsp;<span class="starppimg text-danger" style="position: absolute">*</span> </p>
                    <!-- <input type="file" accept="image/*,photo/*,ocr/*" name="fotoProfil" id="fotoProfil" class="photo" placeholder="Foto Profil" required /> -->
                    <div class="row" style="margin-bottom: 5px">
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                            <label for="radioProfileFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                            <label for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?php if ($ktaData): ?>
                                <img src="../images/<?= $ktaData['PROFILE_IMAGE'] ?>" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center">
                            <?php else: ?>
                                <img id="imageProfile" src="../assets/img/tab5/create-post-black.png" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center">
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <div class="row w-75 mt-3">
                                <label for="fotoProfile" id="profileLabelBtn" style="padding: .375rem .75rem; color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                            </div>
                            <div class="row">
                                <?php if ($ktaData): ?>
                                    <p id="profileFileName" style="display: inline;"><?= $ktaData['PROFILE_IMAGE'] ?></p>
                                <?php else: ?>
                                    <p id="profileFileName" style="display: inline;">No file chosen</p>
                                <?php endif; ?>

                                <?php if($ktaData): ?>
                                    <input type="text" style="display:none;" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" value="<?= $ktaData['PROFILE_IMAGE'] ?>"/>
                                <?php else: ?>
                                    <input type="file" style="display:none;" accept="image/*,profile_file/*" name="fotoProfile" id="fotoProfile" class="photo" placeholder="Foto Profile" onchange="loadFile(event)"/>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!$ktaData): ?>
                        <span id="fotoProfile-error" class="error" style="color: red"></span>
                    <?php endif; ?>

                    <p class="mt-3 mb-2">ID Card &nbsp;<span class="starktp text-danger" style="position: absolute">*</span> </p>
                    <div class="row" style="margin-bottom:5px">
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioEktpFile" name="ektp_radio" class="radio" value="File" checked>
                            <label for="radioEktpFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input <?php if($ktaData): ?> disabled <?php endif; ?> type="radio" id="radioEktpOcr" name="ektp_radio" class="radio" value="OCR">
                            <label for="radioEktpOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?php if ($ktaData): ?>
                               <img src="../images/<?= $ktaData['EKTP_IMAGE'] ?>" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center">
                            <?php else: ?>  
                               <img id="imageKTP" src="../assets/img/tab5/create-post-black.png" style="width: 100px; height: 100px; border-radius: 100px; border: 1px solid #626262; object-fit: cover; object-position: center">
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <div class="row w-75 mt-3">
                                <label for="fotoEktp" id="ektpLabelBtn" style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                            </div>
                            <div class="row">
                                <?php if ($ktaData): ?>
                                    <p id="ektpFileName" style="display: inline;"><?= $ktaData['EKTP_IMAGE'] ?></p>
                                <?php else: ?>
                                    <p id="ektpFileName" style="display: inline;">No file chosen</p>
                                <?php endif; ?>

                                <?php if($ktaData): ?>
                                    <input type="text" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" value="<?= $ktaData['EKTP_IMAGE'] ?>"/>
                                <?php else: ?>
                                    <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" onchange="loadFile2(event)"/>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!$ktaData): ?>
                        <span id="fotoEktp-error" class="error" style="color: red"></span>
                    <?php endif; ?>

                    <div class="row gx-0 mt-3">
                        <div class="col-12">
                            <input type="number" name="ektp" id="ektp" placeholder="Identification Number" <?php if($ktaData): ?> disabled <?php endif; ?> value="<?= $ktaData['EKTP'] ?>" required/>
                        </div>
                        <span class="starnoktp text-danger" style="position: absolute; margin-top: 11px; margin-left: 158px; width: 10px">*</span>
                    </div>
                </div>

                <div data-bs-toggle="collapse" class="mt-3" style="font-size: 18px" data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                    <b>Personal Information<b>
                    <img id="collapse-img-1" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapsePersonal">
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="name" id="name" placeholder="Full Name" value="<?= $ktaData['NAME'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="fullname text-danger" style="position: absolute; margin-top: 9px; margin-left: 72px; width: 10px">*</span>
                    </div>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="email" id="email" placeholder="Email Address" value="<?= $ktaData['EMAIL'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="starmail text-danger" style="position: absolute; margin-top: 10px; margin-left: 100px; width: 10px">*</span>
                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>
                    
                    <div class="row gx-0">
                        <div class="col-12">
                            <select class="mt-3 mb-2" id="birthplace" name="birthplace" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                            
                                <?php if ($ktaData): ?>
                                    <option value="<?= $ktaData['BIRTHPLACE'] ?>" selected><?= $ktaData['CNAME'] ?></option>
                                <?php else: ?>
                                    <option value="" selected>Birthplace</option>

                                    <?php foreach($birthplace as $b): ?>
                                        
                                        <option value="<?= $b['CITY_ID'] ?>"><?= ucwords(strtolower($b['CITY_NAME'])) ?></option>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </select>
                            <span class="starbp text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 87px">*</span>
                            <span id="birthplace-error" class="error" style="color: red"></span>
                        </div>
                    </div>

                    <p class="mt-3 mb-1">Date of Birth <span class="bdstar text-danger" style="position: absolute; margin-left: 8px; margin-top: -5px">*</span> </p>
                    <input type="date" name="date_birth" id="date_birth" placeholder="Date Of Birth" <?php if($ktaData): ?> disabled <?php else: ?> value="1970-01-01" <?php endif; ?> style="background-color: white" value="<?= $ktaData['DATEBIRTH'] ?>" required/>

                    <p class="mt-3 mb-1">Gender</p>

                    <?php if ($ktaData): ?>

                        <div class="row">
                            <div class="col-6">
                                <input type="radio" id="genderMale" <?php if($ktaData): ?> disabled <?php endif; ?> name="gender_radio" class="radio" value="1" <?php if($ktaData['GENDER'] == 1): ?> checked <?php endif; ?> >
                                <label for="genderMale">&nbsp;&nbsp;Male</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" id="genderFemale" <?php if($ktaData): ?> disabled <?php endif; ?> name="gender_radio" class="radio" value="2" <?php if($ktaData['GENDER'] == 2): ?> checked <?php endif; ?>>
                                <label for="genderFemale">&nbsp;&nbsp;Female</label><br>
                            </div>
                        </div>
                    
                    <?php else: ?>

                        <div class="row">
                            <div class="col-6">
                                <input type="radio" id="genderMale" name="gender_radio" class="radio" value="1" checked>
                                <label for="genderMale">&nbsp;&nbsp;Male</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" id="genderFemale" name="gender_radio" class="radio" value="2">
                                <label for="genderFemale">&nbsp;&nbsp;Female</label><br>
                            </div>
                        </div>

                    <?php endif; ?>

                    <!-- <p class="mt-2 mb-1">Bloodtype</p> -->
                    <select class="mt-3 mb-2" id="bloodtype" name="bloodtype" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        <option value="" >Blood Type</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "A"): ?> selected <?php endif; ?> value="A">A</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "B"): ?> selected <?php endif; ?> value="B">B</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "AB"): ?> selected <?php endif; ?> value="AB">AB</option>
                        <option <?php if($ktaData['BLOODTYPE'] == "O"): ?> selected <?php endif; ?> value="O">O</option>
                    </select>
                    <span class="starbt text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 92px">*</span>
                    <span id="bloodtype-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="nationality" name="nationality" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        <option value="" selected>Nationality</option>

                        <?php foreach($countries as $c): ?>
                            <option value="<?= $c['ID'] ?>" <?php if($ktaData['NATIONALITY'] == $c['ID']): ?> selected <?php endif; ?>><?= $c['COUNTRY_NAME'] ?></option>
                        <?php endforeach; ?>

                    </select>
                    <span class="starnation text-danger" style="position: absolute; z-index: 999; margin-top: -47px; margin-left: 91px">*</span>
                    <span id="nationality-error" class="error" style="color: red"></span>

                    <select class="mt-3 mb-2" id="hobby" name="hobby" aria-label="" style="font-size: 16px" <?php if($ktaData): ?> disabled <?php endif; ?>>
                        
                        <option value="" selected>Hobby</option>

                        <?php foreach($hobby as $h): ?>
                            <option value="<?= $h['ID'] ?>" <?php if($ktaData['HOBBY'] == $h['ID']): ?> selected <?php endif; ?>><?= $h['NAME'] ?></option>
                        <?php endforeach; ?>

                    </select>
                    <span class="starhobby text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 62px">*</span>
                    <span id="hobby-error" class="error" style="color: red"></span>

                    <div class="add-hobby row gx-0">
                        <div class="col-12">
                            <input type="text" name="hobby_desc" id="hobby_desc" placeholder="Hobby" required />
                        </div>
                        <span class="staraddhobby text-danger" style="position: absolute; margin-top: 10px; margin-left: 49px; width: 10px">*</span>
                    </div> 

                </div>

                <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                    <b>Address<b>
                    <img id="collapse-img-2" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseAddress">
                    <div class="row gx-0">
                        <div class="col-12">
                            <input type="text" name="address" id="address" placeholder="Full Address" value="<?= $ktaData['ADDRESS'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="staraddress text-danger" style="position: absolute; margin-top: 10px; margin-left: 85px; width: 10px">*</span>
                    </div>
                    
                    <?php 

                    if ($ktaData):

                    $newRTRW = explode("/", $ktaData['RTRW']);

                    $rt = $newRTRW[0];
                    $rw = $newRTRW[1];

                    endif;

                    ?>
                    
                    <div class="row">
                        <div class="col-6">
                            <input type="number" name="rt" id="rt" placeholder="RT" onKeyPress="if (this.value.length == 3) return false;" value="<?= $rt ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="starrt text-danger" style="position: absolute; margin-top: 8px; margin-left: 20px; width: 10px">*</span>
                        <div class="col-6">
                            <input type="number" name="rw" id="rw" placeholder="RW" onKeyPress="if (this.value.length == 3) return false;" value="<?= $rw ?>" <?php if($ktaData): ?> disabled <?php endif; ?> required/>
                        </div>
                        <span class="starrw text-danger" style="position: absolute; margin-top: 8px; margin-left: 53%; width: 10px">*</span>
                    </div>          
                    
                    <?php if (!$ktaData): ?>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px">
                        <option value="" selected>Postcode</option>
                    </select>
                    <span class="starpost text-danger" style="position: absolute; z-index: 999; margin-top: -47px; margin-left: 80px">*</span>
                    <span id="postcode-error" class="error" style="color: red"></span>

                    <?php else: ?>

                    <select class="mt-3 mb-2" id="postcode" name="postcode" aria-label="" style="font-size: 16px" disabled>
                        <option value="<?= $ktaData['POSTCODE'] ?>" selected><?= $ktaData['POSTCODE'] ?></option>
                    </select>

                    <?php endif; ?>

                    <!-- <input type="text" name="province" id="province" placeholder="Province" value="<?= $ktaData['PROVINCE'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="city" id="city" placeholder="City" value="<?= $ktaData['CITY'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="district" id="district" placeholder="District" value="<?= $ktaData['DISTRICT'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/>
                    <input type="text" name="district_word" id="district_word" placeholder="District Word" value="<?= $ktaData['DISTRICT_WORD'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> readonly style="background-color: #ebebeb"/> -->
                    <!-- <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" name="postcode" id="postcode" placeholder="Postcode" value="<?= $ktaData['POSTCODE'] ?>" <?php if($ktaData): ?> disabled <?php endif; ?> /> -->

                    <?php if (!$ktaData): ?>

                        <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px">
                            <option value="" selected>Province</option>

                            <?php foreach($provinces as $p): ?>
                                <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <span class="starprovince text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 77px">*</span>
                        <span id="province-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px">
                            <option value="" selected>City</option>
                        </select>

                        <span class="starcity text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 47px">*</span>
                        <span id="city-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px">
                            <option value="" selected>District</option>
                        </select>

                        <span class="stardist text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 66px">*</span>
                        <span id="district-error" class="error" style="color: red"></span>

                        <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px">
                            <option value="" selected>District Word</option>
                        </select>

                        <span class="starsubdist text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 105px">*</span>
                        <span id="subdistrict-error" class="error" style="color: red"></span>

                        <?php else: ?>

                            <select class="mt-3 mb-2" id="province" name="province" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['PROVINCE'] ?>" selected><?= $ktaData['PROVINCE'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="city" name="city" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['CITY'] ?>" selected><?= $ktaData['CITY'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="district" name="district" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['DISTRICT'] ?>" selected><?= $ktaData['DISTRICT'] ?></option>
                            </select>

                            <select class="mt-3 mb-2" id="subdistrict" name="subdistrict" aria-label="" style="font-size: 16px" disabled>
                                <option value="<?= $ktaData['DISTRICT_WORD'] ?>" selected><?= $ktaData['DISTRICT_WORD'] ?></option>
                            </select>

                        <?php endif; ?>


                </div>

                <!-- <div class="mt-4" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseClub" aria-expanded="false" aria-controls="collapseClub">
                    <b>Club<b>
                    <img id="collapse-img-3" src="../assets/img/arrow-up.png" style="width:10px; height:6px; right: 0; margin-right: 20px; position: absolute; margin-top: 8px">
                </div>

                <div class="collapse show mt-2" id="collapseClub">
                    
                    <p class="mt-3 mb-1">Club Type</p>
                    <div class="row">
                        <div class="col-6">
                            <input type="radio" id="clubIMI" name="club_type" class="radio" value="1" checked>
                            <label for="clubIMI">&nbsp;&nbsp;IMI Club</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="clubPrivate" name="club_type" class="radio" value="2">
                            <label for="clubPrivate">&nbsp;&nbsp;Private</label><br>
                        </div>
                    </div>
                    <select class="mt-3 mb-2" id="club_location" name="club_location" aria-label="" style="font-size: 16px">
                        <option value=""  selected>Club Location</option>

                        <?php foreach($provinces as $p): ?>
                            <option value="<?= $p['PROV_ID'] ?>"><?= ucwords(strtolower($p['PROV_NAME'])) ?></option>
                        <?php endforeach; ?>

                    </select>

                    <span class="starclubl text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 115px">*</span>
                    <span id="clublocation-error" class="error" style="color: red"></span>
                    
                    <div class="club-bungkus">
                        <select class="mt-3 mb-2" id="club_choice" name="club_choice" aria-label="" style="font-size: 16px">
                            <option value=""  selected>Club Choice</option>

                            <?php foreach($club as $c): ?>
                                <option value="<?= $c['ID'] ?>"><?= ucwords(strtolower($c['CLUB_NAME'])) ?></option>
                            <?php endforeach; ?>

                        </select>

                        <span class="starclubc text-danger" style="position: absolute; z-index: 999; margin-top: -45px; margin-left: 106px">*</span>
                        <span id="clubchoice-error" class="error" style="color: red"></span>
                    </div>

                </div> -->

                <div class="form-check mb-4" style="margin-top: 50px">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        I here agree with the <span style="color: #f66701">Terms & Conditions</span> and <span style="color: #f66701">Privacy Policy</span> from Gaspol!
                    </label>
                </div>
            </div>
            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            <div class="container mx-auto">
                <div class="form-group-2 mt-4 mb-4">
                    <div class="row">
                        <div class="col-6" style="color: #626262">
                            Mobility Upgrade Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b style="font-size: 20px">Rp. <?= number_format($upgradeFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-6" style="color: #626262">
                            Administration Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b>Rp. <?= number_format($adminFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6" style="color: #626262">
                            Total Payment
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b style="font-size: 20px">Rp. <?= number_format($upgradeFee+$adminFee, 0, '', '.') ?></b>
                        </div>
                    </div> -->
                </div>
            </div>

            <?php if ($ktaData): ?>
                <input type="hidden" id="status_anggota" name="status_anggota" value="1">
            <?php else: ?>
                <input type="hidden" id="status_anggota" name="status_anggota" value="0">
            <?php endif; ?>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px; padding-top: 20px; background-image: url(../assets/img/lbackground_2.png)">
                <input type="submit" style="width: 40%; font-size: 16px; height: 50px; padding: 10px; background-color: #f66701; color: #FFFFFF" name="submit" id="submit" class="submit" value="SUBMIT" onclick="selectizeValid()" />
            </div>
            <!-- <div style="width: 100%; height: 100px; background-color: #fff"></div> -->
        </form>

        <!-- The Modal -->
        <!-- <div id="modalProgress" class="modal"> -->

            <!-- Modal content -->
            <!-- <div class="modal-content"> -->
                <!-- <p>Upload in progress...</p> -->
            <!-- </div> -->

        <!-- </div> -->

        <!-- <div id="modalSuccess" class="modal"> -->

            <!-- Modal content -->
            <!-- <div class="modal-content">
                <p>Successfully upload data</p>
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
                    <img src="../assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KTA Pro Registration Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="card-kta-pronew.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">View Card</button></a>
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
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-otp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 50px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p>Kode OTP telah dikirim ke email anda, silahkan buka email anda untuk mendapatkan kode OTP.</p>
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

    <input type="hidden" id="is_kta" name="is_kta" value="<?= $ktaData['ID'] ?>">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 3;
        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
    </script>

    <script>

        var price = '<?= number_format($upgradeFee, 0, '', '.') ?>';
        var title = 'Mobility Upgrade Fee';
        var price_fee = '<?= number_format($adminFee, 0, '', '.') ?>';
        var total_price = '<?= number_format($upgradeFee+$adminFee, 0, '', '.') ?>';

    </script>

    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kta-pronew.js?v=<?php echo $ver; ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

<script>

// $('input[type=radio][name=club_type]').change(function() {
//     if (this.value == '1') {
//         $('#club_choice').attr('disabled',false);
//         $('.club-bungkus').show();
//     }
//     else if (this.value == '2') {
//         $('#club_choice').attr('disabled',true);
//         $('.club-bungkus').hide();
//     }
// });

</script>

</html>

<script>

$(document).ready(function(e) {

    if ($('#is_kta').val() != ""){
        $('.fullname').hide();
        $(".starmail").hide();
        $(".starbp").hide();
        $(".bdstar").hide();
        $(".starbt").hide();
        $(".starnation").hide();
        $(".starhobby").hide();
        $(".staraddhobby").hide();
        $(".staraddress").hide();
        $(".starrt").hide();
        $(".starrw").hide();
        $(".starpost").hide();
        $(".starprovince").hide();
        $(".starcity").hide();
        $(".stardist").hide();
        $(".starsubdist").hide();
        // $(".starclubl").hide();
        // $(".starclubc").hide();
        $(".starppimg").hide();
        $(".starnoktp").hide();
        $(".starktp").hide();

        is_takken = 1;
    }

    $('#nationality').selectize();
    $('#bloodtype').selectize();
    $('#hobby').selectize();
    // $('#club_location').selectize();
    // $('#club_choice').selectize();
    $('#birthplace').selectize();
    $('#postcode').selectize();

    $('#province').selectize();
    $('#city').selectize();
    $('#district').selectize();
    $('#subdistrict').selectize();

    var $select = $(document.getElementById('postcode'));
    var selectize = $select[0].selectize;

    $("#postcode-selectized").bind("change paste keyup", function() {
       
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
        xmlHttp.open("post", "../logics/get_postcode");
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
                selectize.addOption({value: province_name, text: province_name});
                selectize.setValue(province_name);

                var $select2 = $(document.getElementById('city'));
                var selectize2 = $select2[0].selectize;
                selectize2.addOption({value: city_name, text: city_name});
                selectize2.setValue(city_name);

                var $select3 = $(document.getElementById('district'));
                var selectize3 = $select3[0].selectize;
                selectize3.addOption({value: district_name, text: district_name});
                selectize3.setValue(district_name);

                var $select4 = $(document.getElementById('subdistrict'));
                var selectize4 = $select4[0].selectize;
                selectize4.addOption({value: subdis_name, text: subdis_name});
                selectize4.setValue(subdis_name);

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
        xmlHttp.open("post", "../logics/get_full_address");
        xmlHttp.send(formData);

    });

    $(".add-hobby").hide();

    $("#hobby").change(function() {
        var value = $(this).val();

        if (value == 6) {
            $(".add-hobby").show();
        }
        else {
            $(".add-hobby").hide();
        }
    });

    // FOR ARROW COLLAPSE

    $('#collapsePersonal').on('shown.bs.collapse', function () {
        $('#collapse-img-1').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapsePersonal').on('hidden.bs.collapse', function () {
        $('#collapse-img-1').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseAddress').on('shown.bs.collapse', function () {
        $('#collapse-img-2').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseAddress').on('hidden.bs.collapse', function () {
        $('#collapse-img-2').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseClub').on('shown.bs.collapse', function () {
        $('#collapse-img-3').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseClub').on('hidden.bs.collapse', function () {
        $('#collapse-img-3').attr('src','../assets/img/arrow-down.png');
    });

    $('#collapseIdentification').on('shown.bs.collapse', function () {
        $('#collapse-img-4').attr('src','../assets/img/arrow-up.png');
    });

    $('#collapseIdentification').on('hidden.bs.collapse', function () {
        $('#collapse-img-4').attr('src','../assets/img/arrow-down.png');
    });


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
    xmlHttp.open("post", "../logics/get_city");
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
        xmlHttp.open("post", "../logics/get_district");
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
        xmlHttp.open("post", "../logics/get_subdistrict");
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
                selectize.clearOptions();
                selectize.clear(); 

                selectize.addOption({value: postcode.POSTAL_ID, text: postcode.POSTAL_CODE});
                selectize.setValue(postcode.POSTAL_ID);            
                
            }
        }
        xmlHttp.open("post", "../logics/get_postcode");
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

    // $("#club_location-selectized").bind("change paste keyup", function() {

    //     if($('#club_location-selectized').val()){
    //         $('.starclubl').hide();
    //     }else{
    //         $('.starclubl').show();
    //     }
    // });

    // $("#club_choice-selectized").bind("change paste keyup", function() {

    //     if($('#club_choice-selectized').val()){
    //         $('.starclubc').hide();
    //     }else{
    //         $('.starclubc').show();
    //     }
    // });

});

</script>

<script>

    // $('#imageProfile').hide();
    // $('#imageKTP').hide();

    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        
        $('#fotoProfile-error').text("");
        $('#imageProfile').attr('src', reader.result);
        // $('#imageProfile').show();

        }
        reader.readAsDataURL(event.target.files[0]);
    };

    var loadFile2 = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        
        $('#fotoEktp-error').text("");
        $('#imageKTP').attr('src', reader.result);
        // $('#imageKTP').show();

        }
        reader.readAsDataURL(event.target.files[0]);
    };

    var $input_address = $('#address')
    $input_address.keyup(function(e) {
        var max = 60;
        if ($input_address.val().length > max) {
            $input_address.val($input_address.val().substr(0, max));
        }
    });

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
    $(".starhobby").show();
    $(".staraddhobby").show();
    $(".staraddress").show();
    $(".starrt").show();
    $(".starrw").show();
    $(".starpost").show();
    $(".starprovince").show();
    $(".starcity").show();
    $(".stardist").show();
    $(".starsubdist").show();
    // $(".starclubl").show();
    // $(".starclubc").show();
    $(".starppimg").show();
    $(".starnoktp").show();
    $(".starktp").show();
    
    $("#name").bind("change paste keyup", function() {
        var namevalue = $(this).val();

        if (namevalue) {
            $(".fullname").hide();
        }

        else {
            $(".fullname").show();
        }
    });

    $("#email").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starmail").hide();
        }

        else {
            $(".starmail").show();
        }
    });

    $("#birthplace").change(function() {
        var bpvalue = $(this).val();

        if (bpvalue) {
            $(".starbp").hide();
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
        }

        else {
            $(".starbt").show();
        }
    });

    $("#nationality").change(function() {
        var nationalvalue = $(this).val();

        if (nationalvalue) {
            $(".starnation").hide();
        }
        
        else {
            $(".starnation").show();
        }
    });

    $("#hobby").change(function() {
        var hobbyvalue = $(this).val();

        if (hobbyvalue) {
            $(".starhobby").hide();
        }

        else {
            $(".starhobby").show();
        }
    });

    $("#hobby_desc").bind("change paste keyup", function() {
        var addhobbyvalue = $(this).val();

        if (addhobbyvalue) {
            $(".staraddhobby").hide();
        }

        else {
            $(".staraddhobby").show();
        }

    });

    $("#address").bind("change paste keyup", function() {
        var addressvalue = $(this).val();

        if (addressvalue) {
            $(".staraddress").hide();
        }

        else {
            $(".staraddress").show();
        }

    });

    $("#rt").bind("change paste keyup", function() {
        var rtvalue = $(this).val();

        if (rtvalue) {
            $(".starrt").hide();
        }

        else {
            $(".starrt").show();
        }

    });



    $("#rw").bind("change paste keyup", function() {
        var rwvalue = $(this).val();

        if (rwvalue) {
            $(".starrw").hide();
        }

        else {
            $(".starrw").show();
        }

    });

    $("#postcode").change(function() {
        var pcvalue = $(this).val();

        if (pcvalue) {
            $(".starpost").hide();
        }

        else {
            $(".starpost").show();
        }
    });

    $("#province").change(function() {
        var provincevalue = $(this).val();

        if (provincevalue) {
            $(".starprovince").hide();
        }

        else {
            $(".starprovince").show();
        }
    });

    $("#city").change(function() {
        var cityvalue = $(this).val();

        if (cityvalue) {
            $(".starcity").hide();
        }

        else {
            $(".starcity").show();
        }
    });

    $("#district").change(function() {
        var distvalue = $(this).val();

        if (distvalue) {
            $(".stardist").hide();
        }

        else {
            $(".stardist").show();
        }
    });

    $("#subdistrict").change(function() {
        var subdistvalue = $(this).val();

        if (subdistvalue) {
            $(".starsubdist").hide();
        }

        else {
            $(".starsubdist").show();
        }
    });

    // $("#club_location").change(function() {
    //     var clublvalue = $(this).val();

    //     if (clublvalue) {
    //         $(".starclubl").hide();
    //     }

    //     else {
    //         $(".starclubl").show();
    //     }
    // });

    // $("#club_choice").change(function() {
    //     var clubcvalue = $(this).val();

    //     if (clubcvalue) {
    //         $(".starclubc").hide();
    //     }

    //     else {
    //         $(".starclubc").show();
    //     }
    // });

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

// $("#club_location").change(function() {
//     $('#clublocation-error').text("");
// });

// $("#club_choice").change(function() {
//     $('#clubchoice-error').text("");
// });

function selectizeValid(){
    var birthplace = $('#birthplace').val();
    var bloodtype = $('#bloodtype').val();
    var nationality = $('#nationality').val();
    var hobby = $('#hobby').val();
    var postcode = $('#postcode').val();
    var province = $('#province').val();
    var city = $('#city').val();
    var district = $('#district').val();
    var subdistrict = $('#subdistrict').val();

    // var clublocation = $('#club_location').val();
    // var clubchoice = $('#club_choice').val();

    var fotoProfile = $('#fotoProfile').val();
    var fotoEktp = $('#fotoEktp').val();

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

    // if(!clubchoice){
    //     $('#clubchoice-error').text("This field is required.");
    // }else{
    //     $('#clubchoice-error').text("");
    // }

    // if(!clublocation){
    //     $('#clublocation-error').text("This field is required.");
    // }else{
    //     $('#clublocation-error').text("");
    // }
}

// CHECK EMAIL ALREADY TAKEN

$("#email").bind("change", function() {
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
                    console.log("Username Ada");
                    $('#username-not-exist').text("");
                    $('#username-exist').text("That email is taken, try another.");

                    is_takken = 0;
                }else if(result == "Tidak ada"){
                    console.log("Username Tidak Ada");
                    $('#username-not-exist').text("That email is available.");
                    $('#username-exist').text("");

                    is_takken = 1;
                }

            }
        }
        xmlHttp.open("post", "../logics/check_kta_email");
        xmlHttp.send(formData);

    }else{
        $('#username-not-exist').text("");
        $('#username-exist').text("");
    }
});

</script>