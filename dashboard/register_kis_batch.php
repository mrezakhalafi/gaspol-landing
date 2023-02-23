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

        /* .bg-gradient-dark {
            background-color: #000000;
            background-image: url('assets/img/side-pane.jpg');
            background-size: 125% 125%;
        } */

        /* #wrapper #content-wrapper {
            background-color: #f8f9fc;
            width: 100%;
            overflow-x: inherit;
        } */

    </style>

</head>

<body id="page-top">

    <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/pages/gaspol-landing/dashboard/wrapper_sidebar.php');
    ?>

                <div class="container-fluid p-3" style="background-color: #F5F5F5">
                    <h3>Register KIS (Batch)</h3>
                  
                    <div class="form-submit text-center pb-5" style="height: 170px; padding-top: 20px">
                        <div class="single-upload-banner">
                            <div class="cover-upload" style="background-color: #F5F5F5">
                                <div class="table-place">
                                    <div class="col-12 text-center">
                                        <b>Upload File (.CSV) here :</b>
                                    </div>
                                </div>
                                <label id="plus-icon" for="file-cover">
                                    <img src="../assets/img/tab5/Add-(Grey).png" style="width: 300px">
                                </label>
                                <input id="file-cover" type="file" name="file" accept=".csv" style="visibility: hidden; position: absolute" onchange="uploadFile(event)"/>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <b id="file-name">No file chosen.</b>
                                        <b id="file-duplicate" class="text-danger"></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div class="form-check mb-4" style="margin-top: 50px">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            List di dalam file tersebut sudah mempunyai KTA (Only Register KIS)
                                        </label>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-submit d-flex justify-content-center" style="padding-top: 20px">
                                    <input class="btn btn-primary" type="submit" style="width: 300px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px; background-color: #f66701; color: #FFFFFF !important" name="submit" id="submit" class="submit" value="UPLOAD" onclick="uploadXLS()" disabled/>
                                </div>
                                <div class="form-submit d-flex justify-content-center" style="padding-top: 20px">
                                    <label for="file-cover" class="btn btn-primary d-none" type="submit" style="width: 300px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px; background-color: darkcyan; color: #FFFFFF !important" id="button-change" class="submit"><span>CHANGE FILE</span></label>
                                </div>
                                <div class="form-submit d-flex justify-content-center" style="padding-top: 20px">
                                    <input class="btn btn-secondary mx-2" type="submit" style="width: 300px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px" value="DOWNLOAD SAMPLE KIS .CSV FILE" onclick="downloadXLS()"/>
                                    <input class="btn btn-secondary mx-2" type="submit" style="width: 380px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px" value="DOWNLOAD SAMPLE KIS + KTA .CSV FILE" onclick="downloadXLS2()"/>
                                </div>
                                <div class="form-submit d-flex justify-content-center" style="padding-top: 20px">
                                    <label for="file-cover" class="btn btn-primary d-none" type="submit" style="width: 300px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px; background-color: darkcyan; color: #FFFFFF !important" id="button-change" class="submit"><span>CHANGE FILE</span></label>
                                </div>
                                <div class="form-submit d-flex justify-content-center" style="height: 170px; padding-top: 20px">
                                    <input class="btn btn-info" type="submit" style="width: 300px; border: 1px solid transparent; font-size: 16px; height: 50px; padding: 10px" value="DOWNLOAD CODE BOOK GASPOL" onclick="downloadBook()"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <div class="fixed-bottom">
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Gaspol! @2022</span>
                        </div>
                    </div>
                </footer>
            </div>
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

    <div class="modal fade" id="modalMembership" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="modalMembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center" id="modalMembership">
                    <img src="assets/img/success.png" style="width: 100px">
                    <h1 class="mt-3">KIS Mobility Registration Batch Success!</h1>
                    <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/register_kis_batch"><button type="button" class="btn btn-dark mt-3" style="background-color: #f66701; border: 1px solid #f66701">Reload</button></a>
                        </div>
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

    <!-- CSV -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/4.3.7/papaparse.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>

</body>
</html>

<script>

var uploadFile = function(event){

    var reader = new FileReader();
        reader.onload = function(){

        var files = event.target.files;
        var fileName = files[0].name;
      
        $('#file-name').text(fileName);
        $('#button-change').removeClass('d-none');
        $('#file-duplicate').text("");

        $('#submit').attr('disabled', false);
    };

    reader.readAsDataURL(event.target.files[0]);
};

var arrayKIS = [];
var arrayKTAKIS = [];

$("input[type=file]").change(function(){
	$("input[type=file]").parse({
		config: {
			delimiter: "",	// auto-detect
			newline: "",	// auto-detect
			quoteChar: '"',
			header: false,
			complete: function(results, file) {

                // Empty Table After Submit For New Submit
    
                arrayKTAKIS = [];

                for(var i=0; i<results.data.length; i++){

                    // console.log(results.data[i]);
                    arrayKIS.push(results.data[i]);
                    arrayKTAKIS.push(results.data[i]);

                }

                if ($('#flexCheckChecked').is(':checked')) {
                    makeTable();
                }else{
                    makeTableTwo();
                }

			}
		}
	});
});

function makeTable(){
    console.log(arrayKIS);

    var html = `
            <div id="example" style="">
            <h5>Preview Data</h5>
                <table id="tableKTA" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <th>No.</th>
                        <th>No. KTA</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Category</th>
                        <th>Expired Date</th>
                        <th>No. SIM C</th>
                        <th>No. SIM A</th>
                        <th>No. KK</th>
                        <th>Province</th>
                    </thead>
                    <tbody id="body-kta">

                    </tbody>
                </table>
                </div>`;

    $('#plus-icon').hide();
    $('.table-place').html(html);
    $('.sticky-footer').hide();

    for(var i=0; i<arrayKIS.length; i++){

        var rows = arrayKIS[i];

        var number = i+1;
        var kta = rows[0];
        var name = rows[1];
        var address = rows[2];
        var category = rows[3];
        var expired = rows[4];
        var no_simc = rows[5];
        var no_sima = rows[6];
        var no_kk = rows[7];
        var province = rows[8];

        var row = `<tr>
                        <td>`+number+`</td>
                        <td>`+kta+`</td>
                        <td>`+name+`</td>
                        <td>`+address+`</td>
                        <td>`+category+`</td>
                        <td>`+expired+`</td>
                        <td>`+no_simc+`</td>
                        <td>`+no_sima+`</td>
                        <td>`+no_kk+`</td>
                        <td>`+province+`</td>
                  </tr>`;
        $('#body-kta').append(row);

    }
    
    $('#tableKTA').DataTable({
        scrollX: true,
    });


    $("#wrapper").attr("style", "height: 1500px");

}

function makeTableTwo(){
    console.log(arrayKTAKIS);

    var html = `
            <div id="example">
            <h5>Preview Data</h5>
                <table id="tableKTA" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Birthplace</th>
                        <th>Date Birth</th>
                        <th>Gender</th>
                        <th>Bloodtype</th>
                        <th>Nationality</th>
                        <th>Hobby</th>
                        <th>Address</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Postcode</th>
                        <th>EKTP</th>
                        <th>Status</th>
                    </thead>
                    <tbody id="body-kta">

                    </tbody>
                </table>
                </div>`;

    $('#plus-icon').hide();
    $('.table-place').html(html);
    $('.sticky-footer').hide();

    var html2 = `
            <div id="example" style="display: contents">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <h5 style="margin-top: 75px">Preview Data</h5>
                    </div>
                </div>
                <table id="tableKIS" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Category</th>
                        <th>Expired Date</th>
                        <th>No. SIM C</th>
                        <th>No. SIM A</th>
                        <th>No. KK</th>
                        <th>Province</th>
                    </thead>
                    <tbody id="body-kis">

                    </tbody>
                </table>
                </div>`;

    $('.table-place').append(html2);

    for(var i=0; i<arrayKTAKIS.length; i++){

        var rows = arrayKTAKIS[i];

        var number = i+1;
        var name = rows[0];
        var email = rows[1];
        var birthplace = rows[2];
        var datebirth = rows[3];
        var gender = rows[4];
        var bloodtype = rows[5];
        var nationality = rows[6];
        var hobby = rows[7];
        var address = rows[8];
        var rt = rows[9];
        var rw = rows[10];
        // var province = rows[11];
        // var city = rows[12];
        // var district = rows[13];
        // var subdistrict = rows[14];
        var postcode = rows[11];
        var ektp = rows[12];
        var status = rows[13];

        var row = `<tr class="row-`+number+`">
                        <td>`+number+`</td>
                        <td>`+name+`</td>
                        <td>`+email+`</td>
                        <td>`+birthplace+`</td>
                        <td>`+datebirth+`</td>
                        <td>`+gender+`</td>
                        <td>`+bloodtype+`</td>
                        <td>`+nationality+`</td>
                        <td>`+hobby+`</td>
                        <td>`+address+`</td>
                        <td>`+rt+`</td>
                        <td>`+rw+`</td>
                        <td>`+postcode+`</td>
                        <td>`+ektp+`</td>
                        <td>`+status+`</td>
                  </tr>`;
        $('#body-kta').append(row);

        // CHECK DUPLICATE EMAIL DAN EKTP

        var formData = new FormData();
        formData.append('number', number);
        formData.append('email', email);
        formData.append('ektp', ektp);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                // console.log(xmlHttp.responseText);

                var result = xmlHttp.responseText.split("|");

                if (result[0] == 1){ // Email Duplicate

                    $('.row-'+result[1]).css('background-color','darkkhaki');
                    $('#file-duplicate').text("( Duplicate Email or NIK detected. Please change it first before uploading. )");
                    $('#button-change').removeClass('d-none');
                    $('#submit').attr('disabled', true);

                }else if(result[0] == 2){ // EKTP Duplicate

                    $('.row-'+result[1]).css('background-color','darkkhaki');
                    $('#file-duplicate').text("( Duplicate Email or NIK detected. Please change it first before uploading. )");
                    $('#button-change').removeClass('d-none');
                    $('#submit').attr('disabled', true);

                }else if(result[0] == 0){ // No Duplicate = Success

                    // $('#submit').attr('disabled', false);

                }
            }
        }
        xmlHttp.open("post", "logics/check_kta_batch");
        xmlHttp.send(formData);
    } 

    for(var i=0; i<arrayKTAKIS.length; i++){

        var rows = arrayKTAKIS[i];

        var number = i+1;
        var name = rows[15];
        var address = rows[16];
        var category = rows[17];
        var expired = rows[18];
        var no_simc = rows[19];
        var no_sima = rows[20];
        var no_kk = rows[21];
        var province = rows[22];

        var row = `<tr>
                        <td>`+number+`</td>
                        <td>`+name+`</td>
                        <td>`+address+`</td>
                        <td>`+category+`</td>
                        <td>`+expired+`</td>
                        <td>`+no_simc+`</td>
                        <td>`+no_sima+`</td>
                        <td>`+no_kk+`</td>
                        <td>`+province+`</td>
                </tr>`;
        $('#body-kis').append(row);

    }
    
    $('#tableKTA').DataTable({
        scrollX: true,
    });

    $('#tableKIS').DataTable({
        scrollX: true,
    });

    $("#wrapper").attr("style", "height: 2500px");

}

function uploadXLS(){

    if ($('#flexCheckChecked').is(':checked')) {

        console.log(arrayKIS);

        for(var i=0; i<arrayKIS.length; i++){

            var no_kta = arrayKIS[i][0];
            // var fotoprofil = arrayKIS[i][1];
            var name = arrayKIS[i][1];
            var domisili = arrayKIS[i][2];
            // var fotosimc = arrayKIS[i][4];
            // var fotopersetujuan = arrayKIS[i][5];
            var kategori = arrayKIS[i][3];
            var expireddate = arrayKIS[i][4];
            var nosimc = arrayKIS[i][5];
            // var fotosima = arrayKIS[i][9];
            var nosima = arrayKIS[i][6];
            var nokk = arrayKIS[i][7];
            var province = arrayKIS[i][8];
            // var isandroid = arrayKIS[i][13];

            console.log("No KTA : "+ no_kta);
            // console.log("Profile Image : "+ fotoprofil);
            console.log("Nama : "+ name);
            console.log("Domisili : "+ domisili);
            // console.log("Foto SIM C : "+ fotosimc);
            // console.log("Foto Persetujuan : "+ fotopersetujuan);
            console.log("Kategori : "+ kategori);
            console.log("Expired Date : "+ expireddate);
            console.log("No. SIM C : "+ nosimc);
            // console.log("Foto SIM A : "+ fotosima);
            console.log("No. SIM A : "+ nosima);
            console.log("No. KK : "+ nokk);
            console.log("Province : "+ province);
            // console.log("IS ANDROID : "+ isandroid);

            var formData = new FormData();
            formData.append('kta_number', no_kta);
            // formData.append('pasFoto', fotoprofil);
            formData.append('name', name);
            formData.append('domisili', domisili);
            // formData.append('fotoSIMC', fotosimc);
            // formData.append('fotoKK', fotopersetujuan);
            formData.append('kategoriKis', kategori);
            formData.append('expiry_date', expireddate);
            formData.append('sim-c', nosimc);
            // formData.append('fotoSIMA', fotosima);
            formData.append('sim-a', nosima);
            formData.append('kk', nokk);
            formData.append('province', province);
            // formData.append('is_android', isandroid);
            
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){

                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(xmlHttp.responseText);
                    var result = xmlHttp.responseText;

                    var no_kis = result.split("|")[0];
                    var price = result.split("|")[1];

                    insertRegisterPayment(no_kis,price);

                }
            }
            xmlHttp.open("post", "logics/register_new_kis_batch");
            xmlHttp.send(formData);

        }

        $('#modalMembership').modal({backdrop: 'static', keyboard: false});

    }else{

        // KIS AND KTA

        console.log(arrayKTAKIS);

        for(var i=0; i<arrayKTAKIS.length; i++){

            // KTA 

            var name = arrayKTAKIS[i][0];
            var email = arrayKTAKIS[i][1];
            var birthplace = arrayKTAKIS[i][2];
            var datebirth = arrayKTAKIS[i][3];
            var gender = arrayKTAKIS[i][4];
            var bloodtype = arrayKTAKIS[i][5];
            var nationality = arrayKTAKIS[i][6];
            var hobby = arrayKTAKIS[i][7];
            var address = arrayKTAKIS[i][8];
            var rt = arrayKTAKIS[i][9];
            var rw = arrayKTAKIS[i][10];
            // var province = arrayKTAKIS[i][11];
            // var city = arrayKTAKIS[i][12];
            // var district = arrayKTAKIS[i][13];
            // var subdistrict = arrayKTAKIS[i][14];
            var postcode = arrayKTAKIS[i][11];
            var ektp = arrayKTAKIS[i][12];
            // var profileimage = arrayKTAKIS[i][17];
            // var ektpimage = arrayKTAKIS[i][18];
            var statusanggota = arrayKTAKIS[i][13];
            // var noanggota = arrayKTAKIS[i][20];
            var hobbydesc = arrayKTAKIS[i][14];
            // var isandroid = arrayKTAKIS[i][22];

            // KIS

            // var no_kta = arrayKTAKIS[i][23];
            // var fotoprofil = arrayKTAKIS[i][23];
            var name = arrayKTAKIS[i][15];
            var domisili = arrayKTAKIS[i][16];
            // var fotosimc = arrayKTAKIS[i][26];
            // var fotopersetujuan = arrayKTAKIS[i][27];
            var kategori = arrayKTAKIS[i][17];
            var expireddate = arrayKTAKIS[i][18];
            var nosimc = arrayKTAKIS[i][19];
            // var fotosima = arrayKTAKIS[i][31];
            var nosima = arrayKTAKIS[i][20];
            var nokk = arrayKTAKIS[i][21];
            var province = arrayKTAKIS[i][22];
            // var isandroid = arrayKTAKIS[i][35];

            // KTA

            console.log("Name : "+ name);
            console.log("Email : "+ email);
            console.log("Birthplace : "+ birthplace);
            console.log("Date of Birth : "+ datebirth);
            console.log("Gender : "+ gender);
            console.log("Bloodtype : "+ bloodtype);
            console.log("Nationality : "+ nationality);
            console.log("Hobby : "+ hobby);
            console.log("Address : "+ address);
            console.log("RT : "+ rt);
            console.log("RW : "+ rw);
            // console.log("Province : "+ province);
            // console.log("City : "+ city);
            // console.log("District : "+ district);
            // console.log("Subdistrict : "+ subdistrict);
            console.log("Postcode : "+ postcode);
            console.log("E-KTP : "+ ektp);
            // console.log("Profile Image : "+ profileimage);
            // console.log("E-KTP Image : "+ ektpimage);
            console.log("Status Anggota : "+ statusanggota);
            // console.log("No. Anggota : "+ noanggota);
            console.log("Hobby : "+ hobbydesc);
            // console.log("Is Android : "+ isandroid);

            // KIS

            // console.log("No KTA : "+ no_kta);
            // console.log("Profile Image : "+ fotoprofil);
            console.log("Nama : "+ name);
            console.log("Domisili : "+ domisili);
            // console.log("Foto SIM C : "+ fotosimc);
            // console.log("Foto Persetujuan : "+ fotopersetujuan);
            console.log("Kategori : "+ kategori);
            console.log("Expired Date : "+ expireddate);
            console.log("No. SIM C : "+ nosimc);
            // console.log("Foto SIM A : "+ fotosima);
            console.log("No. SIM A : "+ nosima);
            console.log("No. KK : "+ nokk);
            console.log("Province : "+ province);
            // console.log("IS ANDROID : "+ isandroid);

            var formData = new FormData();

            // KTA

            formData.append('name', name);
            formData.append('email', email);
            formData.append('birthplace', birthplace);
            formData.append('date_birth', datebirth);
            formData.append('gender_radio', gender);
            formData.append('bloodtype', bloodtype);
            formData.append('nationality', nationality);
            formData.append('hobby', hobby);
            formData.append('address', address);
            formData.append('rt', rt);
            formData.append('rw', rw);
            // formData.append('province', province);
            // formData.append('city', city);
            // formData.append('district', district);
            // formData.append('subdistrict', subdistrict);
            formData.append('postcode', postcode);
            formData.append('ektp', ektp);
            // formData.append('pasFoto', profileimage);
            // formData.append('fotoEktp', ektpimage);
            formData.append('status_anggota', statusanggota);
            // formData.append('no_anggota', noanggota);
            formData.append('hobby_desc', hobbydesc);
            // formData.append('is_android', isandroid);

            // KIS

            // formData.append('kta_number', no_kta);
            // formData.append('pasFoto', fotoprofil);
            formData.append('name', name);
            formData.append('domisili', domisili);
            // formData.append('fotoSIMC', fotosimc);
            // formData.append('fotoKK', fotopersetujuan);
            formData.append('kategoriKis', kategori);
            formData.append('expiry_date', expireddate);
            formData.append('sim-c', nosimc);
            // formData.append('fotoSIMA', fotosima);
            formData.append('sim-a', nosima);
            formData.append('kk', nokk);
            formData.append('province', province);
            // formData.append('is_android', isandroid);
            
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){

                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(xmlHttp.responseText);

                    var result = xmlHttp.responseText;

                    var no_kta = result.split("|")[0];
                    var statusanggota = result.split("|")[1];

                    var no_kis = result.split("|")[2];
                    var price = result.split("|")[3];

                    insertRegisterPaymentKTA(no_kta, statusanggota);
                    insertRegisterPayment(no_kis, price);

                }
            }
            xmlHttp.open("post", "logics/register_new_kis_batch_2");
            xmlHttp.send(formData);

            sendInstruction(email);

        }

        $('#modalMembership').modal({backdrop: 'static', keyboard: false}); 

    }       
}

function sendInstruction(email) {

    var formData = new FormData();

    var email = email;
    // var f_pin = F_PIN;

    formData.append('email', email);
    // formData.append('f_pin', f_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = xmlHttp.responseText;

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "../../../logics/send_email_gmail_2");
    xmlHttp.send(formData);

}

function downloadXLS(){
    window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/csv/File KIS.csv";
}

function downloadXLS2(){
    window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/csv/File KTA KIS.csv";
}

function downloadBook(){
    window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/dashboard/csv/Code Book Gaspol.xlsx";
}

function insertRegisterPayment(no_kis,price){

    var formData = new FormData();

    var f_pin = '';
    var method = 'admin';
    var status = 1;
    var price = price; // Insert By Category
    var reg_type = 8;

    var datex = new Date();
    var date = datex.getTime();

    formData.append('f_pin', f_pin);
    formData.append('method', method);
    formData.append('status', status);
    formData.append('price', price);
    formData.append('reg_type', reg_type);
    formData.append('date', date);

    formData.append('no_batch', no_kis);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = xmlHttp.responseText;

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "logics/insert_membership_payment_mobility");
    xmlHttp.send(formData);

}

function insertRegisterPaymentKTA(no_kta, statusanggota){

    var formData = new FormData();

    var f_pin = '';
    var method = 'admin';
    var status = statusanggota;

    if (status == 0){

        var price = '<?= $upgradeFee + $adminFee ?>';
        var reg_type = 2;

    }else{

        var price = '<?= $upgradeFeePro + $adminFeePro ?>';
        var reg_type = 3;

    }

    var datex = new Date();
    var date = datex.getTime();

    formData.append('f_pin', f_pin);
    formData.append('method', method);
    formData.append('status', 1);
    formData.append('price', price);
    formData.append('reg_type', reg_type);
    formData.append('date', date);

    formData.append('no_batch', no_kta);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = xmlHttp.responseText;

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "logics/insert_membership_payment_mobility");
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