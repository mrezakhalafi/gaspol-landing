<?php 

  include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
  session_start();

  // print_r($_SESSION['web_login']);

  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);

  $dbconn = paliolite();

  if (isset($_GET['f_pin'])){

    $f_pin = $_GET['f_pin'];
    
  }else{

    $f_pin = $_SESSION['f_pin'];

  }

  $query = $dbconn->prepare("SELECT * FROM KTA WHERE KTA.F_PIN = '$f_pin'");
  $query->execute();
  $ktainfo = $query->get_result()->fetch_assoc();
  $query->close();

  $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
  $query->execute();
  $user = $query->get_result()->fetch_assoc();
  $query->close();

?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="description" content="Bingo One page parallax responsive HTML Template ">
  
  <meta name="author" content="Themefisher.com">

  <title>Gaspol! | Official Website</title>

<!-- Mobile Specific Meta
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSS
  ================================================== -->
  <!-- Themefisher Icon font -->
  <link rel="stylesheet" href="plugins/themefisher-font/style.css">
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Lightbox.min css -->
  <link rel="stylesheet" href="plugins/lightbox2/dist/css/lightbox.min.css">
  <!-- animation css -->
  <link rel="stylesheet" href="plugins/animate/animate.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="css/style.css">  

  <style>

.privacy-area{
    font-size: 12px;
    line-height: 1;
    color: #888888;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-top: 7px;
    margin-bottom: 3px;
}

</style>

</head>

<body id="body">

<!--
Fixed Navigation
==================================== -->
<header class="navigation fixed-top">
  <div class="container">
    <!-- main nav -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <!-- logo -->
      <a class="navbar-brand logo" href="index.php">
        <img class="logo-default" style="width:210px" src="assets/img/logo_gaspol.svg" alt="logo"/>
        <img class="logo-white" style="width:210px" src="assets/img/logo_gaspol.svg" alt="logo"/>
      </a>
      <!-- /logo -->
      <button class="navbar-toggler" style="background-color: darkorange" type="button" data-toggle="collapse" data-target="#navigation"
        aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav ml-auto text-center">
          <li class="nav-item dropdown ">
            <a class="nav-link" href="index.php">
              Homepage
            </a>
            <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="index.html">Homepage</a>
              <a class="dropdown-item" href="onepage-slider.html">Onepage</a>
              <a class="dropdown-item" href="onepage-text.html">Onepage 2</a>
            </div> -->
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About Gaspol</a>
          </li>
          <li class="nav-item active" id="menu-membership">
            <a class="nav-link" id="membership-link" href="membership.php">Menu Membership</a>
          </li>
          <li class="nav-item " id="faq">
              <a class="nav-link" id="faq-link" href="faq.php">FAQ</a>
            </li>
		  <li class="nav-item" style="margin-top: -10px" id="menu-sign-in">
            <a class="nav-link" href="https://qmera.io/chatcore/pages/login_page?env=2"><div class="btn btn-main">SIGN IN</div></a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Pages
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="404.html">404 Page</a>
              <a class="dropdown-item" href="blog.html">Blog Page</a>
              <a class="dropdown-item" href="single-post.html">Blog Single Page</a>
            </div>
          </li> -->
        </ul>
      </div>
    </nav>
    <!-- /main nav -->
  </div>
</header>
<!--
End Fixed Navigation
==================================== -->

<section class="single-page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Menu Membership</h2>
			</div>
		</div>
	</div>
</section>

<section class="about-shot-info section-sm" style="background-color: #f9f9f9">
	<div class="container">
        <div class="row">

            <?php if(isset($user)): ?>
              <div class="col-12 text-center">
                <b class="small-text" style="margin-top: -10px; font-size: 20px">Hello <?= $user['FIRST_NAME']?>. <span class="text-danger" onclick="logout()">Logout</span></b>
              </div>

              <?php if(isset($ktainfo)): ?>
                <div class="col-12 text-center">
                  <b class="small-text" style="margin-top: -10px; font-size: 20px; color: darkgreen">Anda sudah memiliki KTA (No KTA : <?= $ktainfo['NO_ANGGOTA'] ?>)</b>
                </div>
              <?php else: ?>
                <div class="col-12 text-center">
                  <b class="small-text" style="margin-top: -10px; font-size: 20px; color: darkred">Anda belum memiliki KTA.</b>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <div class="col-12 text-center">
                <b class="small-text" style="margin-top: -10px; font-size: 20px">Pilih Layanan</b>
            </div>
        </div>
        <div class="row">

          <?php if(isset($ktainfo)): ?>

            <div id="kta-form-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="assets/img/undraw-1.png" style="max-width: 170px">
                        <p><b style="font-size: 16px; color: dimgrey">Pembuatan KTA Mobility</b></p>
                    </div>
                </div>
            </div>

            <?php else: ?>

              <div id="kta-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="assets/img/undraw-1.png" style="max-width: 170px">
                        <p><b style="font-size: 16px; color: dimgrey">Pembuatan KTA Mobility</b></p>
                    </div>
                </div>
            </div>

            <?php endif; ?>

            <?php if($ktainfo['STATUS_ANGGOTA'] == 1): ?>

              <div id="upgrade-kta-form-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-2.png" style="max-width: 170px; margin-top: 15px">
                          <p><b style="font-size: 16px; color: dimgrey">Pembuatan KTA Pro</b></p>
                      </div>
                  </div>
              </div>

            <?php else: ?>

              <div id="upgrade-kta-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-2.png" style="max-width: 170px; margin-top: 15px">
                          <p><b style="font-size: 16px; color: dimgrey">Pembuatan KTA Pro</b></p>
                      </div>
                  </div>
              </div>

            <?php endif; ?>

            <?php if(!isset($f_pin) || $f_pin == ''): ?>

              <div id="imi-join" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-5.png" style="max-width: 170px; margin-top: 10px">
                          <p><b style="font-size: 16px; color: red">Bergabung ke Klub IMI (Scan QR Required)</b></p>
                      </div>
                  </div>
              </div>

            <?php else: ?>

              <?php if(isset($ktainfo)): ?>

                <div id="imi-join-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-5.png" style="max-width: 170px; margin-top: 10px">
                          <p><b style="font-size: 16px; color: dimgrey">Bergabung ke Klub IMI</b></p>
                      </div>
                  </div>
                </div>

              <?php else: ?>

                <div id="imi-join-3" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-5.png" style="max-width: 170px; margin-top: 10px">
                          <p><b style="font-size: 16px; color: dimgrey">Bergabung ke Klub IMI</b></p>
                      </div>
                  </div>
                </div>


              <?php endif; ?>

            <?php endif; ?>

            <?php if(!isset($f_pin) || $f_pin == ''): ?>

            <div id="kis-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                    <div class="card-body text-center">
                        <img src="assets/img/undraw-3.png" style="max-width: 170px; margin-top: 5px; margin-bottom: 5px">
                        <p><b style="font-size: 16px; color: red">Pembuatan Kartu Ijin Start (Scan QR Required)</b></p>
                    </div>
                </div>
            </div>

            <?php else: ?>

              <?php if(isset($ktainfo)): ?>

                <div id="kis-form-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-3.png" style="max-width: 170px; margin-top: 5px; margin-bottom: 5px">
                          <p><b style="font-size: 16px; color: dimgrey">Pembuatan Kartu Ijin Start</b></p>
                      </div>
                  </div>
                </div>

              <?php else: ?>

                <div id="kis-form-3" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-3.png" style="max-width: 170px; margin-top: 5px; margin-bottom: 5px">
                          <p><b style="font-size: 16px; color: dimgrey">Pembuatan Kartu Ijin Start</b></p>
                      </div>
                  </div>
                </div>

              <?php endif; ?>

            <?php endif; ?>

            <?php
            if(!isset($f_pin) || $f_pin == '') {
              ?>
              <div id="tkt-imi-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-4.png" style="max-width: 170px">
                          <p><b style="font-size: 16px; color: red">Pendaftaran Klub IMI (TKT) (Scan QR Required)</b></p>
                      </div>
                  </div>
              </div>
              <?php
            }
            else {

              $stats_mobility = $ktainfo['STATUS_ANGGOTA'] == 0;
              $stats_pro = $ktainfo['STATUS_ANGGOTA'] == 1;

              if (isset($ktainfo) && ($stats_mobility || $stats_pro)) {
                ?>
                <div id="tkt-imi-form-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                    <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                        <div class="card-body text-center">
                            <img src="assets/img/undraw-4.png" style="max-width: 170px">
                            <p><b style="font-size: 16px; color: dimgrey">Pendaftaran Klub IMI (TKT)</b></p>
                        </div>
                    </div>
                </div>
                <?php
              }
              else {
                ?>
                <div id="tkt-imi-form-3" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                    <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                        <div class="card-body text-center">
                            <img src="assets/img/undraw-4.png" style="max-width: 170px">
                            <p><b style="font-size: 16px; color: dimgrey">Pendaftaran Klub IMI (TKT)</b></p>
                        </div>
                    </div>
                </div>
                <?php
              }
            }
            ?>

            <?php
            if(!isset($f_pin) || $f_pin == '') {
              ?>
              <div id="taa-form" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                  <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                      <div class="card-body text-center">
                          <img src="assets/img/undraw-6.png" style="max-width: 170px">
                          <p><b style="font-size: 16px; color: red">Pendaftaran Asosisasi (TAA) (Scan QR Required)</b></p>
                      </div>
                  </div>
              </div>
              <?php
            }
            else {

              $status_pro = $ktainfo['STATUS_ANGGOTA'] == 1;

              if (isset($ktainfo) && $status_pro) {
                ?>
                <div id="taa-form-2" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                    <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                        <div class="card-body text-center">
                            <img src="assets/img/undraw-6.png" style="max-width: 170px">
                            <p><b style="font-size: 16px; color: dimgrey">Pendaftaran Asosisasi (TAA)</b></p>
                        </div>
                    </div>
                </div>
                <?php
              }
              else {
                ?>
                <div id="taa-form-3" class="col-12 col-md-6 mt-5 d-flex justify-content-center">
                    <div class="card shadow" style="width: 80%; height: 200px; border-radius: 20px;">
                        <div class="card-body text-center">
                            <img src="assets/img/undraw-6.png" style="max-width: 170px">
                            <p><b style="font-size: 16px; color: dimgrey">Pendaftaran Asosisasi (TAA)</b></p>
                        </div>
                    </div>
                </div>
                <?php
              }
            }
            ?>
        </div>
	</div>
</section>

<div id="modal-barcode" class="modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p>Scan barcode di bawah dari aplikasi Gaspol! untuk melanjutkan ke fitur ini.</p>
        <img id="barcode">
      </div>
    </div>
  </div>
</div>

<div id="modal-error" class="modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p>Data KTA tidak ditemukan. Harap mendaftar KTA terlebih dahulu.</p>
      </div>
    </div>
  </div>
</div>

<div id="modal-error-pro" class="modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p>Anda belum terdaftar KTA Pro. Harap mendaftar KTA Pro terlebih dahulu.</p>
      </div>
    </div>
  </div>
</div>

<div id="modal-error-2" class="modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p>Anda sudah pernah registrasi KTA sebelumnya, harap login pada aplikasi Gaspol! untuk melihat kartu.</p>
      </div>
    </div>
  </div>
</div>

<footer id="footer" class="bg-one">
  <div class="footer-bottom">
    <h5>Copyright 2022. All rights reserved.</h5>
    <h6>Developed by <a href="">Gaspol!</a></h6>
    <hr><hr>
    <div class="privacy-area">
      <span>Privacy Policy</span> | <span>Terms of Services</span>
    </div>
  </div>
</footer> <!-- end footer -->

    <!-- end Footer Area
    ========================================== -->
    
    <!-- 
    Essential Scripts
    =====================================-->
    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap4 -->
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Parallax -->
    <script src="plugins/parallax/jquery.parallax-1.1.3.js"></script>
    <!-- lightbox -->
    <script src="plugins/lightbox2/dist/js/lightbox.min.js"></script>
    <!-- Owl Carousel -->
    <script src="plugins/slick/slick.min.js"></script>
    <!-- filter -->
    <script src="plugins/filterizr/jquery.filterizr.min.js"></script>
    <!-- Smooth Scroll js -->
    <script src="plugins/smooth-scroll/smooth-scroll.min.js"></script>
    
    <!-- Custom js -->
    <script src="js/script.js"></script>

    <script>
        
      var F_PIN = new URLSearchParams(window.location.search).get('f_pin');
      var global_barcode;
      var qr_menu;

      $("#kta-form").click(function (e) { 
        e.preventDefault();

        if (window.Android){
          window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kta-mobility?f_pin="+F_PIN;
        }else{
          window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kta-mobility";
        }

      });

      $("#upgrade-kta-form").click(function (e) { 
        e.preventDefault();

        if (window.Android){
          window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kta-pronew?f_pin="+F_PIN;
        }else{
          window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kta-pronew";
        }

      });

      $("#imi-join").click(function (e) { 
        
        var formData = new FormData();

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                var response = xmlHttp.responseText;
                console.log(response);
                qr_menu = 1;

                generateBarCode(response);
                global_barcode = response;
                $('#modal-barcode').modal('show');
                
            }
        }

        xmlHttp.open("post", "logics/insert_barcode");
        xmlHttp.send(formData);

      });

      $("#kis-form").click(function (e) { 
        
        var formData = new FormData();

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                var response = xmlHttp.responseText;
                console.log(response);
                qr_menu = 2;

                generateBarCode(response);
                global_barcode = response;
                $('#modal-barcode').modal('show');
                
            }
        }

        xmlHttp.open("post", "logics/insert_barcode");
        xmlHttp.send(formData);

      });

      $("#imi-join-2").click(function (e) { 
        e.preventDefault();
        window.location.href = "http://108.136.138.242/gaspol_web/pages/form-join-imi?f_pin="+F_PIN;
      });

      $("#kis-form-2").click(function (e) { 
        e.preventDefault();
        window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kis-new?f_pin="+F_PIN;
      });

      $("#imi-join-3").click(function (e) { 
        $('#modal-error').modal('show');
      });

      $("#kis-form-3").click(function (e) { 
        $('#modal-error').modal('show');
      });

      $("#kta-form-2").click(function (e) {
        
        if (window.Android){
          window.location.href = "http://108.136.138.242/gaspol_web/pages/card-kta-mobility?f_pin="+F_PIN;
        }else{
          $('#modal-error-2').modal('show');
        }

      });

      $("#upgrade-kta-form-2").click(function (e) {

        if (window.Android){ 
          window.location.href = "http://108.136.138.242/gaspol_web/pages/card-kta-pronew?f_pin="+F_PIN;
        }else{
          $('#modal-error-2').modal('show');
        }

      });

      // $("#kis-form-backup").click(function (e) { 
      //   e.preventDefault();

      //   <?php
      //   if ($ktainfo) {
      //     ?>
      //     window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kis-new";
      //     <?php  
      //   }

      //   else {
      //     ?>
      //     $("#kta-modal").modal('show');
      //     <?php
      //   }
      //   ?>

      // });

      $("#tkt-imi-form").click(function (e) { 
        // e.preventDefault();

        // if (window.Android){
        //   window.location.href = "http://108.136.138.242/gaspol_web/pages/tkt-imi-club?f_pin="+F_PIN;
        // }else{
        //   window.location.href = "http://108.136.138.242/gaspol_web/pages/tkt-imi-club";
        // }
        var formData = new FormData();

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                var response = xmlHttp.responseText;
                console.log(response);
                qr_menu = 3;

                generateBarCode(response);
                global_barcode = response;
                $('#modal-barcode').modal('show');
                
            }
        }

        xmlHttp.open("post", "logics/insert_barcode");
        xmlHttp.send(formData);

      });

      $("#tkt-imi-form-2").click(function (e) { 
        e.preventDefault();

        if (window.Android) {
          window.location.href = "http://108.136.138.242/gaspol_web/pages/tkt-imi-club?f_pin="+F_PIN;
        }
        else {
          window.location.href = "http://108.136.138.242/gaspol_web/pages/tkt-imi-club";
        }

      });

      $("#tkt-imi-form-3").click(function (e) { 
        $('#modal-error').modal('show');
      });

      $("#taa-form").click(function (e) { 
        // e.preventDefault();

        // if (window.Android){
        //   window.location.href = "http://108.136.138.242/gaspol_web/pages/taa-club?f_pin="+F_PIN;
        // }
        // else{
        //   window.location.href = "http://108.136.138.242/gaspol_web/pages/taa-club";
        // }

        var formData = new FormData();

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

                var response = xmlHttp.responseText;
                console.log(response);
                qr_menu = 4;

                generateBarCode(response);
                global_barcode = response;
                $('#modal-barcode').modal('show');
                
            }
        }

        xmlHttp.open("post", "logics/insert_barcode");
        xmlHttp.send(formData);
      });

      $("#taa-form-2").click(function (e) { 
        e.preventDefault();

        if (window.Android) {
          window.location.href = "http://108.136.138.242/gaspol_web/pages/taa-club?f_pin="+F_PIN;
        }
        else {
          window.location.href = "http://108.136.138.242/gaspol_web/pages/taa-club";
        }

      });

      $("#taa-form-3").click(function (e) { 
        $('#modal-error-pro').modal('show');
      });

    </script>

    <script> 

    setInterval(function () {
      fetch_user_status();
    }, 2000);

    let shown = 0;

    function fetch_user_status() {
      $.ajax({
          url: "logics/check_barcode?qr_code=" + global_barcode,
          method: "GET",
          success: function (data) {
              
          data = JSON.parse(data);
          console.log(data);

          if (data.STATUS != 0 ){
           directMenu(data.F_PIN);
          }

        }
      })
    }

    function directMenu(f_pin){

      var formData = new FormData();

        formData.append('f_pin', f_pin);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                
                console.log("Respon : "+xmlHttp.responseText);

                var response = JSON.parse(xmlHttp.responseText);

                if (response == 0 || response == null){

                  window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/membership";

                }else{
                  
                  if (qr_menu == 1){
                    window.location.href = "http://108.136.138.242/gaspol_web/pages/form-join-imi";
                  }else if(qr_menu == 2){
                    window.location.href = "http://108.136.138.242/gaspol_web/pages/form-kis-new";
                  }else if(qr_menu == 3){
                    if (response.STATUS_ANGGOTA == 0 || response.STATUS_ANGGOTA == 1) {
                      window.location.href = "http://108.136.138.242/gaspol_web/pages/tkt-imi-club";
                    }
                    else {
                      window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/membership";
                    }
                  }else if(qr_menu == 4){
                    if (response.STATUS_ANGGOTA == 0) {
                      window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/membership";
                    }
                    else if(response.STATUS_ANGGOTA == 1) {
                      window.location.href = "http://108.136.138.242/gaspol_web/pages/taa-club";
                    }
                  }
                }
            }
        }
        xmlHttp.open("post", "logics/check_is_kta");
        xmlHttp.send(formData);
    }

    </script>

  <script type="text/javascript">

    function generateBarCode(nric) {
      var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=50x50';
      $('#barcode').attr('src', url);
    }

  </script>

  </body>
  </html>

  <script>

  if (window.Android){
      // $('#menu-membership').hide();
      // $('#menu-sign-in').hide();
      // $('#join-club-1').hide();
      // $('#join-club-2').hide();

      // $("#join-club-1").attr("href", "membership.php?f_pin="+window.Android.getFPin());
      // $("#join-club-2").attr("href", "membership.php?f_pin="+window.Android.getFPin());
      $("#membership-link").attr("href", "membership.php?f_pin="+window.Android.getFPin());

  }else{
    // $('#join-club-3').show();
    // $('#join-club-4').show();
  }

  function logout(){

    var formData = new FormData();
    formData.append('type', 'logout');

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
          
        console.log("Respon : "+xmlHttp.responseText);
        var response = xmlHttp.responseText;

        if(response == 1){

          window.location.href = "http://108.136.138.242/gaspol_web/pages/gaspol-landing/membership";

        }
      }
    }
    xmlHttp.open("post", "logics/logout");
    xmlHttp.send(formData);
}

</script>