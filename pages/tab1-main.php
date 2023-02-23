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
  <title>Timeline</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../assets/css/tab3-style.css?v=<?php echo time(); ?>" />
  <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
  <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />

  <script src="../assets/js/xendit.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
  <script src="../assets/js/jquery.ba-throttle-debounce.js"></script>
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
      transition: top 0.4s ease-in-out;
    }

    #header-layout #header {
      padding-top: 0px;
    }

    #story-container {
      background: transparent;
    }

    form#searchFilterForm-a {
      border: 1px solid #c9c9c9;
      background-color: rgba(255, 255, 255, .55);
      width: 100%;
    }

    #searchFilter-a {
      margin-left: 0;
    }

    input#query {
      background-color: rgba(255, 255, 255, 0);
    }

    #modal-addtocart .modal-dialog {
      top: 0;
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
      z-index: 99;
    }

    a#to-new-post:hover {
      color: white;
    }

    .post-status.dropdown-toggle::after {
      display: none;
    }

    .dropdown ul li {
      /* padding: .25rem .75rem; */
    }

    #pbr-timeline {
      max-width: 100%;
      overflow-x: hidden;
    }

    .timeline-image .img-fluid {
      width: 100%;
      height: auto;
    }

    <?php

    $rand_pos = rand(0, 1);

    ?>#gif-container {
      position: fixed;
      z-index: 9999;
    }

    #gif-container.left {
      bottom: 150px;
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

    .modal#modal-addtocart {
      z-index: 99999;
    }

    .timeline-image .carousel-inner,
    .timeline-image .carousel-item.active,
    .timeline-image .carousel-item-wrap,
    .timeline-image .video-wrap {
      overflow:visible !important;
    }
  </style>
</head>

<body>
  <!-- <img
    class="demo-bg"
    alt=""
  > -->
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
  <div class="container-fluid">


    <div id="header-layout" class="sticky-top">
      <div id="story-container">
        <?php require('timeline_story_container.php'); ?>
      </div>
      <div id="header" class="row justify-content-between">
        <div class="col-10">
          <div id="searchFilter-a" class="col-12 d-flex align-items-center justify-content-center text-white">
            <form id="searchFilterForm-a" method=GET>
              <!-- <div class="d-flex align-items-center div-search"> -->
              <?php
              $query = "";
              if (isset($_REQUEST['query'])) {
                $query = $_REQUEST['query'];
              }
              ?>
              <input id="query" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
              <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).webp">
              <img id="mic" src="../assets/img/action_mic.webp" onclick="voiceSearch();">
              <!-- </div> -->
            </form>
          </div>
        </div>
        <div id="gear-div" class="col-2 d-flex align-items-center justify-content-center" style="padding-right: 9px; padding-left: 9px;">
          <!-- <a class="me-2 d-none" href="cart.php?v=<?= time(); ?>">
            <div class="position-relative">
              <img class="header-icon" src="../assets/img/icons/Shopping-Cart-(White).png">
              <span id='counter-here'></span>
            </div>
          </a>
          <a id="to-notifications" class="me-3 d-none" href="notifications.php">
            <div class="position-relative">
              <img class="header-icon mx-auto" src="../assets/img/icons/Shop Manager/App-Notification-(white).png">
              <span id='counter-notifs'></span>
            </div>
          </a> -->
          <a class="me-1" id="to-grid-layout">
            <div class="position-relative">
              <img class="header-icon" src="../assets/img/ic_grid.png">
              <!-- <span id='counter-here'></span> -->
            </div>
          </a>
          <a class="me-3" id="to-list-layout">
            <div class="position-relative">
              <img class="header-icon mx-auto" src="../assets/img/ic_list.png">
              <!-- <span id='counter-notifs'></span> -->
            </div>
          </a>
        </div>
      </div>
      <div id="category-tabs" class="ms-2 small-text d-none">
        <ul class="nav nav-tabs horizontal-slide" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="categoryFilter-all" data-bs-toggle="tab" role="tab">All</a>
          </li>
          <?php

          $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_category.php');

          for ($i = 0; $i < count($filters); $i++) {

            $idFilter = $filters[$i]["ID"];
            $nameFilter = $filters[$i]["CODE"];
            echo '<li class="nav-item">';

            echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" data-bs-toggle="tab" role="tab">' . $nameFilter . '</a>';
            echo '</li>';
          }

          ?>
        </ul>
      </div>
    </div>
    <div class="timeline" id="pbr-timeline">
      <?php //require('timeline_products.php'); 
      ?>
    </div>
    <div id="loader_message"></div>

    <a id="to-new-post" class="float">
      <i class="fa fa-plus my-float"></i>
    </a>
  </div>

  <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
    <div class="modal-dialog" role="document">


      <div class="modal-content animate-bottom">
        <div class="modal-back" data-bs-dismiss="modal">
          <img src="../assets/img/icons/Back-(White) - Copy.png" />
        </div>
        <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
        </div>
      </div>
    </div>
  </div>

  <!-- show product modal -->
  <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
        </div>
      </div>
    </div>
  </div>
  <!-- show product modal -->

  <!-- add to cart success modal -->
  <div class="modal fade" id="addtocart-success" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <h6>Product added to cart!</h6>
        </div>
        <div class="modal-footer">
          <button id="addtocart-success-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- add to cart success modal -->

  <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-body p-4" id="modal-add-body" style="position: relative;">
          <div class="row gx-0">
            <div class="col-12">
              <div class="col-12 mb-3 text-center">
                <h5 id="header-report-content">Why you want to report this content?</h5>
              </div>
              <div class="col-12" style="float: left; font-size: 16px">
                <ul>
                  <form action="/action_page.php">

                    <?php

                    $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                    $query->execute();
                    $category = $query->get_result();
                    $query->close();

                    foreach ($category as $c) : ?>

                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                          <?= $c['CATEGORY'] ?>
                        </label>
                      </div>


                    <?php endforeach;

                    ?>

                    <div class="row mt-3">
                      <div class="col-12 d-flex justify-content-center">
                        <button class="submit-report btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
                      </div>
                    </div>
                  </form>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-category2" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

          <div class="row gx-0">
            <div class="col-12">
              <div class="col-12 mb-3 text-center">
                <h5 id="header-report-user">Why you want to report this user?</h5>
              </div>
              <div class="col-12" style="float: left; font-size: 16px">
                <ul>
                  <form action="/action_page.php">

                    <?php

                    $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                    $query->execute();
                    $category = $query->get_result();
                    $query->close();

                    foreach ($category as $c) : ?>

                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                          <?= $c['CATEGORY'] ?>
                        </label>
                      </div>


                    <?php endforeach;

                    ?>

                    <!-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category1" value="0">
                                        <label class="form-check-label" for="report_category1">
                                        It's a scam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category2" value="1">
                                        <label class="form-check-label" for="report_category2">
                                        Nudity or sexual activity
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="2">
                                        <label class="form-check-label" for="report_category3">
                                        Hate speech or symbols
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="3">
                                        <label class="form-check-label" for="report_category3">
                                        Bullying or harassment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category4" value="4">
                                        <label class="form-check-label" for="report_category4">
                                        Violence or dangerous organization
                                        </label>
                                    </div> -->

                    <div class="row mt-3">
                      <div class="col-12 d-flex justify-content-center">
                        <button class="submit-report btn btn-dark" type="button" onclick="reportUserSubmit()">Submit</button>
                      </div>
                    </div>
                  </form>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-report-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-4 text-center">
          <p id="report-submitted" style="font-size: 16px">Report submited.</p>
          <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
              <button class="button-close btn btn-dark" type="button" onclick="reloadPages()">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-4 text-center">
          <p id="block-user" style="font-size: 16px">You blocked this user.</p>
          <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
              <button class="button-close btn btn-dark" type="button" onclick="reloadPages()">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- delete post -->
  <div class="modal fade" id="delete-post-info" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
        </div>
        <div class="modal-footer">
          <button id="delete-post-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($countGIF > 0) { ?>
    <div id="gif-container" class="<?php echo $rand_pos == 1 ? "right" : "left" ?>">

    </div>

  <?php } ?>

  <script>
    var BE_ID = <?php echo $be_id != null ? $be_id : "null" ?>;
  </script>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  <script src="../assets/js/tab5-collection.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/update_counter.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/profile-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/seamless-scroll-polyfill@latest/lib/bundle.min.js"></script>
  <script src="../assets/js/script-timeline.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
  <script>
    // $('#addtocart-success').on('hidden.bs.modal', function() {
    //   location.reload();
    // });

    if (localStorage.lang == 0) {
      $('input#query').attr('placeholder', 'Search');
      $('#story-all-posts').text("All Posts");
    } else {
      $('input#query').attr('placeholder', 'Pencarian');
      $('#story-all-posts').text("Semua Post");
      $('#header-report-content').text("Mengapa anda ingin melaporkan konten ini?");
      $('#header-report-user').text("Mengapa anda ingin melaporkan user ini?");
      $('.submit-report').text("Kirim");
      $('#report-submitted').text("Laporan telah dikirim.");
      $('#block-user').text("Anda telah berhasil memblokir user ini.");
      $('.button-close').text("Tutup");
    }

    function openNewPost(checkIOS = false) {
      if (window.Android) {
        if (typeof window.Android.checkFeatureAccess === "function" && window.Android.checkFeatureAccess("new_post") && window.Android.checkProfile()) {
          window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
        }
      } 
      else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
          param1: '',
          param2: 'newpost'
        });
        return;
      }
      else {
        let fpin = new URLSearchParams(window.location.search).get("f_pin");
        window.location = "tab5-new-post?f_pin=" + fpin;
      }
      localStorage.setItem("is_grid", "0");
    }

    $(document).ready(function() {
      $('#to-new-post').click(function() {
        openNewPost();

      })
      if(window.Android && typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")){
        $('#to-new-post').addClass('d-none');
      } else {
        $('#to-new-post').removeClass('d-none');
      }
    })
  </script>

  <script>
    // if (localStorage.lang == 1) {
    //   $('#header-report-content').text("Mengapa anda ingin melaporkan konten ini?");
    // }
  </script>



  <!-- <script src="../assets/js/paliopay-dictionary.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/paliopay.js?random=<?= time(); ?>"></script> -->
</body>

</html>