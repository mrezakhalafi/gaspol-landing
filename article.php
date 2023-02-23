<?php

	// ini_set('display_errors', 1); 
	// ini_set('display_startup_errors', 1); 
	// error_reporting(E_ALL);

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
	$dbconn = paliolite();

  $id = $_GET['id'];

	$query = $dbconn->prepare("SELECT * FROM GASPOL_NEWS WHERE ID = $id");
	$query->execute();
	$news = $query->get_result()->fetch_assoc();
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
          <li class="nav-item">
            <a class="nav-link" href="about.php">About Gaspol</a>
          </li>
          <li class="nav-item" id="menu-membership">
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
				<h2>Article</h2>
			</div>
		</div>
	</div>
</section>

<section class="about-shot-info section-sm">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mt-20 text-center">
        <div class="row">

          <div class="col-1"></div>
          <div class="col-md-10 text-center">
            <h2><?= $news['TITLE'] ?></h2>
          </div>
          <div class="col-1"></div>

        </div>
        <img class="img-fluid mt-40" style="border-radius: 20px; width: 400px" src="<?= $news['IMAGES'] ?>" alt="">
			</div>

      <div class="col-1"></div>
			<div class="col-md-10 mt-40 text-center">
				<p><?= $news['NEWS'] ?></p>
			</div>
      <div class="col-1"></div>

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