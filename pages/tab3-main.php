<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

$f_pin = $_GET['f_pin'];
$_SESSION['user_f_pin'] = $f_pin;

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$sqlGIF = "SELECT BE_ID, COUNT(BE_ID) AS COUNT_BE FROM XPORA_GIF WHERE BE_ID = 0 OR BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queGIF = $dbconn->prepare($sqlGIF);
$queGIF->execute();
$resGIF = $queGIF->get_result()->fetch_assoc();
$queGIF->close();

$countGIF = $resGIF["COUNT_BE"];
$be_id = $resGIF["BE_ID"];

?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab3-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js?v=<?= time(); ?>"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>

    <?php
    $rand_bg = rand(1, 12) . ".png";
    ?>

    <style>
        body {
            /* background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
            background-size: 100% auto;
            background-repeat: repeat-y; */
            background: transparent;
        }

        #header-layout {
            background: <?= $setting['COLOR_PALETTE']; ?>;
            z-index: 99;
        }

        form#searchFilterForm-a {
            border: 1px solid #c9c9c9;
            background-color: rgba(255, 255, 255, .55);
            width: 100%;
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 75px;
            right: 20px;
            background-color: rgba(0, 0, 0, .65);
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 999;
        }

        .my-float {
            /* margin-top: 22px; */
            z-index: 999;
        }

        <?php

        $rand_pos = rand(0, 1);

        ?>#gif-container {
            position: fixed;
            z-index: 9999;
        }

        #gif-container.left {
            bottom: 70px;
            left: 20px;
        }

        #gif-container.right {
            bottom: 150px;
            right: 20px;
        }

        .gifs img {
            height: 200px;
            width: auto;
        }

        * {
            -webkit-user-select: none !important;
            -webkit-touch-callout: none !important;
        }

        .header-icon {
            width: 32px;
            height: 32px;
        }
    </style>

</head>


<body class="tab3">
    <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
    <div class="container-fluid">
        <div class="row">
            <div id="header-layout" class="col-12 sticky-top">
                <div class="row" style="padding: 10px 0 10px 0;">
                    <div id="searchFilter-a" class="col-10 text-white">
                        <form id="searchFilterForm-a">
                            <!-- <div class="d-flex align-items-center div-search"> -->
                            <?php
                            $query = "";
                            if (isset($_REQUEST['query'])) {
                                $query = $_REQUEST['query'];
                            }
                            ?>
                            <input id="query" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
                            <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).webp">
                            <img id="voice-search" src="../assets/img/action_mic.webp" onclick="voiceSearch();">
                            <!-- </div> -->
                        </form>
                    </div>
                    <div class="col-2 d-flex justify-content-center ps-0">
                        <a class="mx-auto" id="to-grid-layout">
                            <img class="header-icon" src="../assets/img/ic_grid.png">
                        </a>
                        <a class="mx-auto" id="to-list-layout">
                            <img class="header-icon mx-auto" src="../assets/img/ic_list.png">
                        </a>
                    </div>
                    <!-- <div class="col-1 ps-0">
                        
                    </div> -->
                    <!-- <div class="col-2 ps-0 d-none">
                        <a href="notifications.php">
                            <div class="position-relative me-4">
                                <img src="../assets/img/icons/Shop Manager/App-Notification-(Grey).png" style="width:30px">
                                <span id='counter-notifs'></span>
                            </div>
                        </a>
                    </div>
                    <div class="col-2 ps-0 d-none">
                        <a class="me-2 d-none" href="cart.php?v=<?= time(); ?>">
                            <div class="position-relative">
                                <img class="header-icon" src="../assets/img/icons/Shopping-Cart-(White).png">
                                <span id='counter-here'></span>
                            </div>
                        </a>
                    </div> -->
                </div>
                <div id="category-tabs" class="row small-text d-none">
                    <ul class="nav nav-tabs horizontal-slide" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="categoryFilter-all" href="tab3-main.php">All</a>
                        </li>
                        <?php

                        $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_category.php');

                        for ($i = 0; $i < count($filters); $i++) {

                            $idFilter = $filters[$i]["ID"];
                            $nameFilter = $filters[$i]["CODE"];
                            echo '<li class="nav-item">';

                            echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" href="tab3-main.php?filter=' . $idFilter . '">' . $nameFilter . '</a>';
                            echo '</li>';
                        }

                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- <a id="to-new-post" class="float">
            <i class="fa fa-plus my-float"></i>
        </a> -->
    </div>
    <div class="box">
        <div id="container">
            <div id="loading" class="d-none">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center;">Sedang memuat. Tunggu sebentar...</h5>
                </div>
            </div>
            <div class="d-none" id="no-stores">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center;">There are no content that match the criteria</h5>
                </div>
            </div>
            <div id="content-grid" class="mt-5 grid-stack grid-stack-3" style="inset: -1px;">
                <div id="grid-overlay" class="overlay d-none"></div>
            </div>
        </div>
        <script>
            const search = <?php if (isset($_GET['query'])) {
                                echo '"' . $_GET['query'] . '"';
                            } else {
                                echo "null";
                            } ?>;
            const filter = <?php if (isset($_GET['filter'])) {
                                echo '"' . $_GET['filter'] . '"';
                            } else {
                                echo "null";
                            } ?>;
        </script>
    </div>
    <div class="bg-grey stack-top" style="display: none;" id="stack-top">
        <div class="container small-text">
            <div id="sort-store-popular" class="bg-white row py-3">
                <div class="col-6" style="font-weight:500;">Popular</div>
                <div class="col-6 check-mark">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-date" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Date Added (New to Old)</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-follower" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Followers</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

    <!-- show product modal -->
    <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body p-0"></div>
            </div>
        </div>
    </div>
    <!-- show product modal -->

    <?php if ($countGIF > 0) { ?>
        <div id="gif-container" class="<?php echo $rand_pos == 1 ? "right" : "left" ?>">

        </div>

    <?php } ?>

</body>

<!-- <script type="text/javascript" src="../assets/js/script-filter.js?random=<?= time(); ?>"></script> -->
<script src="../assets/js/update_counter.js?random=<?= time(); ?>"></script>
<script src="../assets/js/long-press-event.min.js?random=<?= time(); ?>"></script>
<script type="text/javascript" src="../assets/js/script-store_list.js?random=<?= time(); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    function myFunction() {
        var x = document.getElementById("stack-top");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#grid-overlay').removeClass('d-none');
        } else {
            x.style.display = "none";
            $('#grid-overlay').addClass('d-none');
        }
    }

    if (localStorage.lang == 0) {
        $('input#query').attr('placeholder', 'Search');
        $('#no-stores .prod-name').text('There are no content that match the criteria');
    } else {
        $('input#query').attr('placeholder', 'Pencarian');
        $('#no-stores .prod-name').text('Tidak ada konten yang sesuai dengan kriteria');
    }

    $(document).ready(function() {
        //   $('#to-new-post').click(function() {
        //     if (window.Android) {
        //       if (window.Android.checkProfile()) {
        //         window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
        //       }
        //     } else {
        //       let fpin = new URLSearchParams(window.location.search).get("f_pin");
        //       window.location = "tab5-new-post?f_pin=" + fpin;
        //     }
        //   })
    })
</script>

</html>