<?php

	// ini_set('display_errors', 1); 
	// ini_set('display_startup_errors', 1); 
	// error_reporting(E_ALL);

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
	$dbconn = paliolite();

	$query = $dbconn->prepare("SELECT * FROM GASPOL_NEWS LIMIT 3");
	$query->execute();
	$news = $query->get_result();
	$query->close();

  session_start();
  $_SESSION['web_login'] = null;
  $_SESSION['is_scanned'] = null;
  $_SESSION['f_pin'] = null;
  
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
          <li class="nav-item  active ">
            <a class="nav-link" href="about.php">About Gaspol</a>
          </li>
          <li class="nav-item " id="menu-membership">
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
				<h2>About Gaspol!</h2>
			</div>
		</div>
	</div>
</section>


<!-- <section class="about-shot-info section-sm">
	<div class="container">
		<div class="row">
			<div class="col-md-6 mt-20">
				<h2>Tentang IMI (Ikatan Motor Indonesia)</h2>
				<p>Ikatan Motor Indonesia yang disingkat IMI adalah organisasi induk dari olahraga bermotor baik mobil maupun sepeda motor di Indonesia. Olahraga yang berada dalam naungan IMI yaitu rally, time rally, sprint rally, adventure offroad, speed offroad, individual offroad, karting, slalom, balap mobil, drag race, drifting untuk kategori mobil dan supermoto, moto cross, power track, grass track, drag bike, indospeed race series, sidrap prix night race, balap motor/motor prix untuk kategori motor.</p>
			</div>
			<div class="col-md-6">
				<img class="img-fluid" style="border-radius: 20px" src="https://www.naikmotor.com/wp-content/uploads/2020/05/Sticker_IMI__Stiker_IMI___Ikatan_Motor_Indonesia_spesial_edi-e1590791958698.jpg" alt="">
			</div>
		</div>
	</div>
</section> -->

<section class="about-shot-info section-sm">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mt-20 text-center">
				<h2>Tentang Gaspol!</h2>
        <img style="width: 200px" class="mt-4 mb-4" src="assets/img/logo_gaspol.svg">
				<p>Aplikasi mobile resmi dari IMI, sebagai platform media digital untuk aktivitas sport-otomotif dan kebutuhan berkendara sehari-hari. Sekaligus menjadi wadah yang mewadahi segala aktivitas komunitas mobilitas IMI.</p>
			</div>
		</div>
	</div>
</section>



<section class="company-mission section-sm bg-gray">
	<div class="container">
		<div class="row">
			<div class="col-md-4 text-center">
				<h3 class="mt-4">Aktifitas Komunitas</h3>
				<p>Bergabung bersama para pengguna kendaraan bermotor di Indonesia. Bentuk komunitasmu dan kembangkan menjadi komunitas berskala national.</p>
				<img src="assets/img/imi-network.svg" alt="" class="img-fluid mt-30">
			</div>
			<div class="col-md-4 text-center">
				<h3 class="mt-4">Roadside Assistance</h3>
				<p>Bantuan instant untuk setiap kondisi darurat di jalan mulai dari ban bocor, jump -start, kehabisan bensin, kecelakaan lalulintas dan tindakan kriminal.</p>
				<img src="assets/img/passion.svg" alt="" class="img-fluid mt-30">
			</div>
      <div class="col-md-4 text-center">
				<h3 class="mt-4">Pasar Otomotif</h3>
				<p>Berbelanja di ratusan ribu merchant otomotif dan lifestyle di Indonesia, selalu temukan penawaran menarik untuk segala kebutuhan mobilitas Anda.</p>
				<img src="assets/img/automotive-event.svg" alt="" class="img-fluid mt-30">
			</div>
		</div>
	</div>
</section>

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

</script>