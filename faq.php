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
        .privacy-area {
            font-size: 12px;
            line-height: 1;
            color: #888888;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 7px;
            margin-bottom: 3px;
        }

        #accordionExample .card {
            border: 1px solid rgba(0, 0, 0, .125);
        }

        #accordion ul {
            display: block;
            list-style-type: disc;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            padding-inline-start: 40px;
        }

        #accordion ol {
            margin-top: 0;
            margin-bottom: 1rem;
            list-style-type: decimal;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            padding-inline-start: 40px;
        }

        p {
            color:black;
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
                    <img class="logo-default" style="width:210px" src="assets/img/logo_gaspol.svg" alt="logo" />
                    <img class="logo-white" style="width:210px" src="assets/img/logo_gaspol.svg" alt="logo" />
                </a>
                <!-- /logo -->
                <button class="navbar-toggler" style="background-color: darkorange" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav ml-auto text-center">
                        <li class="nav-item dropdown ">
                            <a class="nav-link" href="index.php">
                                Homepage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About Gaspol</a>
                        </li>
                        <li class="nav-item " id="menu-membership">
                            <a class="nav-link" id="membership-link" href="membership.php">Menu Membership</a>
                        </li>
                        <li class="nav-item active" id="faq">
                            <a class="nav-link" id="faq-link" href="faq.php">FAQ</a>
                        </li>
                        <li class="nav-item" style="margin-top: -10px" id="menu-sign-in">
                            <a class="nav-link" href="https://qmera.io/chatcore/pages/login_page?env=2">
                                <div class="btn btn-main">SIGN IN</div>
                            </a>
                        </li>
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
                    <h2>Frequently Asked Questions (FAQ)</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="about-shot-info section-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-20">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0 d-inline">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Penyelesaian Masalah
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body" id="child1">
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#" data-toggle="collapse" data-target="#collapseOneA">Masalah pengiriman/penerimaan pesan</a>
                                        </div>
                                        <div class="card-body collapse" data-parent="#child1" id="collapseOneA">
                                            <p>
                                                Alasan paling umum mengapa Anda tidak dapat mengirim atau menerima pesan melalui Gaspol! adalah karena koneksi internet yang buruk. Jika Anda yakin ponsel Anda terhubung ke Internet, ada beberapa alasan mengapa pesan dari aplikasi Gaspol! tidak diproses:
                                            </p>
                                            <ol>
                                                <li>
                                                    Ponsel Anda mungkin mengalami masalah konektivitas. Periksa kembali apakah ponsel Anda memiliki koneksi internet aktif dengan sinyal yang kuat. Coba muat halaman web untuk memastikan.
                                                </li>
                                                <li>
                                                    Tanggal dan waktu ponsel Anda tidak disetel dengan benar. Jika tanggal Anda salah, Anda tidak akan dapat terhubung ke server Gaspol! untuk mengunduh media Anda.
                                                </li>
                                                <li>
                                                    Ukuran file, jika ada, terlalu besar. Ukuran file maksimum yang memungkinkan untuk dikirim melalui Gaspol! adalah 5 MB.
                                                </li>
                                                <li>
                                                    Jika Anda menemukan gambar buram atau tidak dapat memutar media apa pun, harap tunggu sebentar agar media menyelesaikan proses unduhannya. Tentu saja ini tergantung pada konektivitas jaringan Anda.
                                                </li>
                                                <li>
                                                    Anda belum masuk ke aplikasi Gaspol! selama lebih dari 24 jam. Jika ini terjadi, Anda harus memastikan untuk menindaklanjuti pengguna lain yang mungkin telah mengirimi Anda pesan.
                                                </li>
                                                <li>
                                                    Ada masalah dengan kartu SD Anda:
                                                    <ul>
                                                        <li>
                                                            Tidak cukup ruang pada kartu SD. Pastikan ada cukup ruang di kartu SD Anda. Jika kartu SD Anda penuh, Gaspol! tidak akan dapat menyimpan apa pun untuk itu. Buat lebih banyak ruang tersedia dengan menghapus file yang tidak diperlukan.
                                                        </li>
                                                        <li>
                                                            Kartu SD diatur ke mode hanya-baca.
                                                            Pastikan kartu SD Anda tidak disetel ke mode hanya baca. Coba simpan file ke kartu SD Anda yang bukan dari Gaspol!. Jika file disimpan, kartu Anda bukan hanya-baca, Gaspol! harus dapat menyimpan file ke dalamnya. Jika Anda tidak dapat menyimpan apa pun, kartu Anda kemungkinan diatur ke mode hanya baca. Anda perlu mengubah ini; silakan periksa manual ponsel Anda untuk instruksi.
                                                        </li>
                                                        <li>
                                                            Kartu SD rusak.
                                                            Jika poin di atas masih tidak dapat menyelesaikan masalah Anda, maka kartu SD Anda mungkin rusak. Dalam hal ini, Anda mungkin perlu memformat ulang kartu SD Anda. Ini berarti menghapus seluruh kartu SD dan mengatur ulangnya. Untuk memformat ulang kartu SD pada ponsel Android, buka Pengaturan> Penyimpanan. Jika ada, ketuk Lepaskan kartu penyimpanan> Ketuk Format kartu SD atau Hapus kartu SD> Boot ulang ponsel Anda. <strong>PENTING:</strong> tindakan ini akan menghapus SEMUA data di handheld Anda
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    Ada kemungkinan ponsel Anda termasuk salah satu yang membutuhkan konfigurasi khusus sebagai berikut:
                                                    <ul>
                                                        <li>
                                                            <strong>Samsung</strong>
                                                            <br>
                                                            Masuk ke Setting > Device Maintenance > Battery > Always Sleeping Apps > Lalu delete Gaspol!
                                                        </li>
                                                        <li>
                                                            <strong>Xiaomi</strong>
                                                            <br>
                                                            Buka Setting > Aplikasi terinstal > Pilih Gaspol! > Lalu tekan on pada "Izinkan untuk mulai otomatis" > Masuk ke Penghemat baterai > Ubah perizinan dengan "Tidak ada pembatasan"
                                                        </li>
                                                        <li>
                                                            <strong>Asus</strong>
                                                            <br>
                                                            Masuk ke Setting > Pilih Battery > Pilih Manager Pengaktifan Otomatis > Aktifkan pada "Manager Pengaktifan Otomatis"
                                                        </li>
                                                        <li>
                                                            <strong>Oppo</strong>
                                                            <br>
                                                            Masuk ke Setting > Pilih Battery > Pilih Energy Saver > Pilih Aplikasi Gaspol! > Lalu tekan off pada "Freeze when in Background"
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                    <!-- <div class="card">
                                        <div class="card-header">
                                            <a href="#" data-toggle="collapse" data-target="#collapseOneB">Child B</a>
                                        </div>
                                        <div class="card-body collapse" data-parent="#child1" id="collapseOneB">
                                            Another flipp runch wolf moon tempor, sunt aliqua put a bird.
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Collapsible #2
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird
                                    on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table,
                                    raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Collapsible #3
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird
                                    on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table,
                                    raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer id="footer" class="bg-one">
        <div class="footer-bottom">
            <h5>Copyright 2022. All rights reserved.</h5>
            <h6>Developed by <a href="">Gaspol!</a></h6>
            <hr>
            <hr>
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
    if (window.Android) {
        // $('#menu-membership').hide();
        // $('#menu-sign-in').hide();
        // $('#join-club-1').hide();
        // $('#join-club-2').hide();

        // $("#join-club-1").attr("href", "membership.php?f_pin="+window.Android.getFPin());
        // $("#join-club-2").attr("href", "membership.php?f_pin="+window.Android.getFPin());
        $("#membership-link").attr("href", "membership.php?f_pin=" + window.Android.getFPin());

    } else {
        // $('#join-club-3').show();
        // $('#join-club-4').show();
    }
</script>