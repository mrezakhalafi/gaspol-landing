<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

<style>

    body{
        font-family: 'Poppins' !important;
    }

    .input-group-text {
        border-radius: unset;
    }

    .form-control {
        border-radius: unset;
    }

    .bg-gradient-dark {
        background-color: white !important;
        /* background-image: url('assets/img/side-pane.jpg'); */
        background-size: 125% 125%;
    }

    .navbar-list {
        color: #000000
    }

    .sidebar-divider {
        background-color: #F5F5F5
    }

    .active {
        background-color: #FF6B00;
    }

    .active-text {
        color: white;
    }

    .navbar-expand .navbar-nav {
        flex-direction: column;
    }
    
    a:hover{
        text-decoration: none;
    }

    .navbar-list{
        font-weight: bold;
    }

    .collapse-item{
        font-weight: bold;
    }

    @media (min-width: 768px){
        .sidebar {
            width: 12rem!important;
        }
    }

    .sidebar .nav-item .collapse .collapse-inner, .sidebar .nav-item .collapsing .collapse-inner {
        min-width: 5rem;
    }

    @media (min-width: 768px){
        .sidebar.toggled {
            overflow: visible;
            width: 7.6rem!important;
        }
    }

</style>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="d-flex align-items-center justify-content-center mt-3 mb-5" href="index.php" id="logo-gaspol">
            <!-- <div class="sidebar-brand-text mx-3">Gaspol</div> -->
            <!-- <img src="http://108.136.138.242/gaspol_web/pages/gaspol-landing/assets/img/logo_gaspol.svg" width="300"> -->
            <!-- <img src="assets/img/logo-gaspol.svg" width="50px" height="50px" style="margin-left: -15px">
            <img src="assets/img/close-icon.png" width="30px" height="30px" style="transform: rotate(45deg); margin-left: 15px">
            <img src="assets/img/logo-imi.svg" width="50px" height="50px" style="margin-left: 15px">
             -->
            <h3 style="color: black; text-transform: unset; font-family: poppins; font-weight: 700">Gas<span style="color: #FF6B00">board</span></h3>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- <li style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link" href="index.php">
                <php if($user['IMAGE']): ?>
                    <img style="width: 25px; height: 25px; margin-top: -2px" class="img-profile rounded-circle" src="http://108.136.138.242/filepalio/image/<?= $user['IMAGE'] ?>">
                <php else: ?>
                    <img style="width: 25px; height: 25px; margin-top: -2px" class="img-profile rounded-circle" src="img/undraw_profile.svg">
                <php endif; ?> &nbsp;
                <span class="navbar-list">Admin</span>
            </a>
        </li> -->

        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li id="dashboard" style="border-radius: 10px" class="nav-item mx-3 active" onclick="activeDashboard()">
            <a class="nav-link" href="index.php">
                <img id="img-dashboard" src="assets/img/dashboard-icons-white.svg" alt="" style="width: 22px; height: 22px; margin-top: -2px; margin-left: -4.5px"> &nbsp;
                <span id="text-dashboard" class="navbar-list active-text" style="margin-left: -4.5px">Ringkasan</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- <li id="reporting" style="border-radius: 10px" class="nav-item mx-3" onclick="activeReporting()">
            <a class="nav-link collapsed" href="#" aria-expanded="true" aria-controls="collapseThree">
                <img src="assets/img/reporting-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-reporting" class="navbar-list">Reporting</span>
            </a>
        </li> -->

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Pages Collapse Menu -->
        <li id="kta-management" style="border-radius: 10px" class="nav-item mx-3" onclick="activeKtaManagement()">
            <a id="link-kta-management" class="nav-link collapsed" href="kta_report.php?m=1" aria-expanded="true" aria-controls="collapseKTA">
                <img id="img-kta-management" src="assets/img/kta-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-kta-management" class="navbar-list">KTA</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="kis-management" style="border-radius: 10px" class="nav-item mx-3" onclick="activeKisManagement()">
            <a id="link-kis-management" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKIS" aria-expanded="true" aria-controls="collapseKIS">
                <img id="img-kis-management" src="assets/img/kis-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-kis-management" class="navbar-list">KIS</span>
            </a>
            <div id="collapseKIS" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="register_kis.php?m=2">Create Single</a>
                    <a class="collapse-item" href="register_kis_batch.php?m=2">Create Batch</a>
                    <a class="collapse-item" href="kis_report.php?m=2">KIS Member</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="tkt-management" style="border-radius: 10px" class="nav-item mx-3" onclick="activeTktManagement()">
            <a id="link-tkt-management" class="nav-link collapsed" href="tkt_report.php?m=3" aria-expanded="true" aria-controls="collapseTKT">
                <img id="img-tkt-management" src="assets/img/tkt-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-tkt-management" class="navbar-list">TKT</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="taa-management" style="border-radius: 10px" class="nav-item mx-3" onclick="activeTaaManagement()">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTAA" aria-expanded="true" aria-controls="collapseTAA">
                <img id="img-taa-management" src="assets/img/taa-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-taa-management" class="navbar-list">TAA</span>
            </a>
            <!-- <div id="collapseTAA" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="taa_report.php">TAA Club</a>
                </div>
            </div> -->
        </li>

        <hr class="sidebar-divider my-0">

        <li id="finance" style="border-radius: 10px" class="nav-item mx-3" onclick="activeFinance()">
            <a id="link-finance" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <img id="img-finance" src="assets/img/finance-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-finance" class="navbar-list">Finansial</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="finance_kta_m.php?m=5">KTA Mobility</a>
                    <a class="collapse-item" href="finance_kta_p.php?m=5">KTA Pro</a>
                    <a class="collapse-item" href="finance_kis.php?m=5">KIS</a>
                    <a class="collapse-item" href="finance_tkt.php?m=5">TKT IMI Club</a>
                    <a class="collapse-item" href="finance_taa.php?m=5">TAA</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="merchant" onclick="activeMerchant()" style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link collapsed" href="#">
                <img id="img-merchant" src="assets/img/merchant-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-merchant" class="navbar-list">Merchant</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="data-list" style="border-radius: 10px" class="nav-item mx-3" onclick="activeDataList()">
            <a id="link-data-list" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <img id="img-data-list" src="assets/img/kis-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-data-list" class="navbar-list">Data</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <a class="collapse-item" href="kta_report.php">KTA Member</a>
                    <a class="collapse-item" href="kis_report.php">KIS Member</a>
                    <a class="collapse-item" href="tkt_report.php">TKT IMI Club</a> -->
                    <a class="collapse-item" href="dashboard_card.php?m=7">Member Card</a>
                    <a class="collapse-item" href="club_report.php?m=7">History Club</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="admin" style="border-radius: 10px" class="nav-item mx-3" onclick="activeAdmin()">
            <a id="link-admin" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
                aria-expanded="true" aria-controls="collapseFive">
                <img id="img-admin" src="assets/img/admin-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-admin" class="navbar-list">Admin</span>
            </a>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="form_pricing.php?m=8">Form Pricing</a>
                    <a class="collapse-item" href="form_admin.php?m=8">Set Admin</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="gaspol-user" onclick="activeUser()" style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link collapsed" href="#">
                <img id="img-user" src="assets/img/admin-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-user" class="navbar-list">Akun Gaspol</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <li id="imi-partner" onclick="activePartner()" style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link collapsed" href="#">
                <img id="img-partner" src="assets/img/imi-partner-icon.svg" alt="" style="width: 15px; height: 15px; margin-top: -2px"> &nbsp;
                <span id="text-partner" class="navbar-list">Rekan IMI</span>
            </a>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- Heading -->
        <!-- <div class="sidebar-heading">
            CPAAS
        </div> -->

        <!-- <li style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
                aria-expanded="true" aria-controls="collapseFour">
                <i class="fas fa-fw fa-cog"></i>
                <span>CPAAS</span>
            </a>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="http://108.136.138.242/chatcore/pages/login_page?env=2">Go To Chat</a>
                </div>
            </div>
        </li> -->

        <!-- Nav Item - Charts -->
        <!-- <li style="border-radius: 10px" class="nav-item mx-3">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Charts</span></a>
        </li> -->

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button style="background-color: rgb(190 190 190)" class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content" style="background-color: #F5F5F5">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light topbar static-top" style="background-color: #f8f9fc">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <!-- <form
                    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> -->

                <!-- Topbar Navbar -->
                <ul class="navbar-nav" style="width: 100%">

                <div class="row">
                    <div class="col-6 d-flex justify-content-start">

                        <li style="border-radius: 10px" class="nav-item mx-3 dropdown no-arrow">
                            <a class="nav-link text-start" href="#" id="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p id="current-time" style="color: black" class="mb-0"></p>
                            </a>
                        </li>

                    </div>
                
                    <div class="col-6 d-flex justify-content-end">

                        <!-- <nav class="navbar navbar-light">
                            <form class="container-fluid mb-0">
                                <div class="input-group" style="border: 1px solid #f8f9fc">
                                    <span class="input-group-text" id="basic-addon1" style="background-color: white; border: none; border-radius: 10px">
                                        <img src="assets/img/search-icon.png" alt="" style="width: 24px; height: 24px">
                                        <input type="text" class="form-control" placeholder="Pencarian" aria-label="Username" aria-describedby="basic-addon1" style="border: none;">
                                    </span>
                                </div>
                            </form>
                        </nav> -->

                        <!-- <div class="form-group fg--search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                            <input type="text" class="input" placeholder="search">
                        </div> -->

                        <li style="border-radius: 10px" class="nav-item mx-3 dropdown no-arrow" style="margin-right: 15px">
                            <a class="nav-link" href="#" id="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile" src="assets/img/print-icon.svg" style="width: 30px; height: 30px">
                                <p class="mb-0 ms-3 text-black" style="font-size: 15px">Daftar Cetak</p>           
                            </a>
                        </li>
                
                        <li style="border-radius: 10px" class="nav-item mx-3 dropdown no-arrow" style="margin-right: 15px">
                            <a class="nav-link" href="#" id="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile" src="assets/img/notification-icon.svg">
                                <p class="mb-0 ms-3 text-black" style="font-size: 15px">0 Notifikasi</p>                           
                            </a>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li style="border-radius: 10px" class="nav-item mx-3 dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile" src="img/undraw_profile.svg">
                                <span style="color: black; font-size: 14px; margin-left: 12px; font-weight: bold">Administrator <span style="font-size: 12px; margin-left: 10px">▼</span></span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div> -->

                                <a class="dropdown-item pb-2 m-3" style="border-bottom: 1px solid #e6e6e6; width: auto; " href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item pt-2 pb-2 m-3" style="border-bottom: 1px solid #e6e6e6; width: auto; " href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fa fa-cog mr-2"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item pt-2 m-3" style="width: auto" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                        
                    </div>
                </div>

                </ul>

            </nav>
            <!-- End of Topbar -->

        <!-- </div>  -->
        <!-- End of Main Content 1 -->

    <!-- </div> -->
    <!-- End of Content and Page Wrapper 2 -->

<!-- </div> -->
<!-- 3 -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

    // function activeDashboard() {

    // }

    // function activeReporting() {
    //     $("#dashboard").attr("class", "nav-item mx-3");
    //     $("#text-dashboard").attr("class", "navbar-list");

    //     $("#reporting").attr("class", "nav-item mx-3 active");
    //     $("#text-reporting").attr("class", "navbar-list active-text");

    //     $("#kta-management").attr("class", "nav-item mx-3");
    //     $("#text-kta-management").attr("class", "navbar-list");
    //     $("#link-kta-management").attr("class", "nav-link collapsed");
    //     $("#link-kta-management").attr("aria-expanded", false);
    //     $("#collapseKTA").attr("class", "collapse");

    //     $("#kis-management").attr("class", "nav-item mx-3");
    //     $("#text-kis-management").attr("class", "navbar-list");
    //     $("#link-kis-management").attr("class", "nav-link collapsed");
    //     $("#link-kis-management").attr("aria-expanded", false);
    //     $("#collapseKIS").attr("class", "collapse");

    //     $("#tkt-management").attr("class", "nav-item mx-3");
    //     $("#text-tkt-management").attr("class", "navbar-list");
    //     $("#link-tkt-management").attr("class", "nav-link collapsed");
    //     $("#link-tkt-management").attr("aria-expanded", false);
    //     $("#collapseTKT").attr("class", "collapse");

    //     $("#taa-management").attr("class", "nav-item mx-3");
    //     $("#text-taa-management").attr("class", "navbar-list");

    //     $("#merchant").attr("class", "nav-item mx-3");
    //     $("#text-merchant").attr("class", "navbar-list");

    //     $("#data-list").attr("class", "nav-item mx-3");
    //     $("#text-data-list").attr("class", "navbar-list");
    //     $("#link-data-list").attr("class", "nav-link collapsed");
    //     $("#link-data-list").attr("aria-expanded", false);
    //     $("#collapseTwo").attr("class", "collapse");

    //     $("#finance").attr("class", "nav-item mx-3");
    //     $("#text-finance").attr("class", "navbar-list");
    //     $("#link-finance").attr("class", "nav-link collapsed");
    //     $("#link-finance").attr("aria-expanded", false);
    //     $("#collapseOne").attr("class", "collapse");

    //     $("#admin").attr("class", "nav-item mx-3");
    //     $("#text-admin").attr("class", "navbar-list");
    //     $("#link-admin").attr("class", "nav-link collapsed");
    //     $("#link-admin").attr("aria-expanded", false);
    //     $("#collapseFive").attr("class", "collapse");

    //     $("#gaspol-user").attr("class", "nav-item mx-3");
    //     $("#text-user").attr("class", "navbar-list");

    //     $("#imi-partner").attr("class", "nav-item mx-3");
    //     $("#text-partner").attr("class", "navbar-list");
    // }

    function activeKtaManagement() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3 active");
        $("#text-kta-management").attr("class", "navbar-list active-text");
        $("#img-kta-management").attr("src", "assets/img/kta-icon-white.svg");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeKisManagement() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");

        $("#kis-management").attr("class", "nav-item mx-3 active");
        $("#text-kis-management").attr("class", "navbar-list active-text");
        $("#img-kis-management").attr("src", "assets/img/kis-icon-white.svg");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeTktManagement() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");

        $("#tkt-management").attr("class", "nav-item mx-3 active");
        $("#text-tkt-management").attr("class", "navbar-list active-text");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon-white.svg");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeTaaManagement() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3 active");
        $("#text-taa-management").attr("class", "navbar-list active-text");
        $("#img-taa-management").attr("src", "assets/img/taa-icon-white.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeMerchant() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3 active");
        $("#text-merchant").attr("class", "navbar-list active-text");
        $("#img-merchant").attr("src", "assets/img/merchant-icon-white.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeDataList() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3 active");
        $("#text-data-list").attr("class", "navbar-list active-text");
        $("#img-data-list").attr("src", "assets/img/kis-icon-white.svg");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeFinance() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3 active");
        $("#text-finance").attr("class", "navbar-list active-text");
        $("#img-finance").attr("src", "assets/img/finance-icon-white.svg");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeAdmin() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3 active");
        $("#text-admin").attr("class", "navbar-list active-text");
        $("#img-admin").attr("src", "assets/img/admin-icon-white.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activeUser() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3 active");
        $("#text-user").attr("class", "navbar-list active-text");
        $("#img-user").attr("src", "assets/img/admin-icon-white.svg");

        $("#imi-partner").attr("class", "nav-item mx-3");
        $("#text-partner").attr("class", "navbar-list");
        $("#img-partner").attr("src", "assets/img/imi-partner-icon.svg");
    }

    function activePartner() {
        $("#dashboard").attr("class", "nav-item mx-3");
        $("#text-dashboard").attr("class", "navbar-list");
        $("#img-dashboard").attr("src", "assets/img/dashboard-icons.svg");

        $("#reporting").attr("class", "nav-item mx-3");
        $("#text-reporting").attr("class", "navbar-list");

        $("#kta-management").attr("class", "nav-item mx-3");
        $("#text-kta-management").attr("class", "navbar-list");
        $("#img-kta-management").attr("src", "assets/img/kta-icon.svg");
        $("#link-kta-management").attr("class", "nav-link collapsed");
        $("#link-kta-management").attr("aria-expanded", false);
        $("#collapseKTA").attr("class", "collapse");

        $("#kis-management").attr("class", "nav-item mx-3");
        $("#text-kis-management").attr("class", "navbar-list");
        $("#img-kis-management").attr("src", "assets/img/kis-icon.svg");
        $("#link-kis-management").attr("class", "nav-link collapsed");
        $("#link-kis-management").attr("aria-expanded", false);
        $("#collapseKIS").attr("class", "collapse");

        $("#tkt-management").attr("class", "nav-item mx-3");
        $("#text-tkt-management").attr("class", "navbar-list");
        $("#img-tkt-management").attr("src", "assets/img/tkt-icon.svg");
        $("#link-tkt-management").attr("class", "nav-link collapsed");
        $("#link-tkt-management").attr("aria-expanded", false);
        $("#collapseTKT").attr("class", "collapse");

        $("#taa-management").attr("class", "nav-item mx-3");
        $("#text-taa-management").attr("class", "navbar-list");
        $("#img-taa-management").attr("src", "assets/img/taa-icon.svg");

        $("#merchant").attr("class", "nav-item mx-3");
        $("#text-merchant").attr("class", "navbar-list");
        $("#img-merchant").attr("src", "assets/img/merchant-icon.svg");

        $("#data-list").attr("class", "nav-item mx-3");
        $("#text-data-list").attr("class", "navbar-list");
        $("#img-data-list").attr("src", "assets/img/kis-icon.svg");
        $("#link-data-list").attr("class", "nav-link collapsed");
        $("#link-data-list").attr("aria-expanded", false);
        $("#collapseTwo").attr("class", "collapse");

        $("#finance").attr("class", "nav-item mx-3");
        $("#text-finance").attr("class", "navbar-list");
        $("#img-finance").attr("src", "assets/img/finance-icon.svg");
        $("#link-finance").attr("class", "nav-link collapsed");
        $("#link-finance").attr("aria-expanded", false);
        $("#collapseOne").attr("class", "collapse");

        $("#admin").attr("class", "nav-item mx-3");
        $("#text-admin").attr("class", "navbar-list");
        $("#img-admin").attr("src", "assets/img/admin-icon.svg");
        $("#link-admin").attr("class", "nav-link collapsed");
        $("#link-admin").attr("aria-expanded", false);
        $("#collapseFive").attr("class", "collapse");

        $("#gaspol-user").attr("class", "nav-item mx-3");
        $("#text-user").attr("class", "navbar-list");
        $("#img-user").attr("src", "assets/img/admin-icon.svg");

        $("#imi-partner").attr("class", "nav-item mx-3 active");
        $("#text-partner").attr("class", "navbar-list active-text");
        $("#img-partner").attr("src", "assets/img/support-white.svg");
    }

    var today = new Date();
    var date = today.getDate();
    var month = today.getMonth();
    var year = today.getFullYear();
    var hours = (today.getHours()<10?'0':'') + today.getHours();
    var minutes = (today.getMinutes()<10?'0':'') + today.getMinutes();
    // var seconds = (today.getSeconds()<10?'0':'') + today.getSeconds();

    const monthNames = ["January", "February", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var month_name = monthNames[month];
    
    $("#current-time").text(date+" "+month_name+" "+year+" - "+hours+":"+minutes);

</script>

<script>

    // FOR MENU


    var menu = "<?= $_GET['m'] ?>";

    if (menu == 1){
        activeKtaManagement();
    }else if(menu == 2){
        activeKisManagement();
    }else if(menu == 3){
        activeTktManagement();
    }else if(menu == 4){
        
    }else if(menu == 5){
        activeFinance();
    }else if(menu == 6){
        
    }else if(menu == 7){
        activeDataList();
    }else if(menu == 8){
        activeAdmin();
    }

    // FOR SWITCH MAIN ICON 

    $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
  
      var smallIcon = `<img src="assets/img/logo-gaspol-2.svg">`;
      var largeIcon = `<h3 style="color: black; text-transform: unset; font-family: poppins; font-weight: 700">Gas<span style="color: #FF6B00">board</span></h3>`;
  
      if ($(".sidebar").hasClass("toggled")) {
  
        $('#logo-gaspol').html(largeIcon);      
        
      }else{
  
        $('#logo-gaspol').html(smallIcon);
  
      };
  
    });

</script>