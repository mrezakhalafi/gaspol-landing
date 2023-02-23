<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();
$dbNewNus = newnus();

if (!isset($_GET['product_code'])) {
  die();
}

// if (isset($_GET['is_post'])) {
//   $is_post = 0;
// } else {
//   $is_post = $_GET['is_post'];
// }

$product = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_spesific_post.php');

$shop_thumb_id = $product[0]["THUMB_ID"];

if ($shop_thumb_id == null || $shop_thumb_id == "") {
  $shop_thumb_id = "/gaspol_web/assets/img/ic_person_boy.png";
} else {
  $shop_thumb_id = 'https://qmera.io/filepalio/image/' . $product[0]["THUMB_ID"];
}

// if ($is_post == 0) {
//   $product_name = "Nexilis";
//   $shop_thumb_id = "/gaspol_web/assets/img/nxsport_icons/nx.png";
// } else {
$product_name = $product[0]["NAME"];
// }

$product_description = $product[0]["DESCRIPTION"];
$product_date = $product[0]["CREATED_DATE"];

// 
// $shop_thumb_id = $shop_thumb[0];
$seconds = intval(intval($product_date) / 1000);
// $printed_date = date("H:i", $seconds);
// $date_explode = explode(":", $printed_date);
// $hours = (int)$date_explode[0] + 7;
// if ($hours >= 24) {
//   $hours = $hours - 24;
//   $hours = "{$hours}";
//   if (strlen($hours) == 1) {
//     $hours = "0" . $hours;
//   }
// }
// $printed_date = $hours . ":" . $date_explode[1];

$date_diff = round((time() - $seconds) / (60 * 60 * 24));
if ($date_diff == 0) {
  $printed_date = "Hari ini";
} else if ($date_diff == 1) {
  $printed_date = "Kemarin";
} else if ($date_diff == 2) {
  $printed_date = "2 hari lalu";
} else if ($date_diff == 3) {
  $printed_date = "3 hari lalu";
} else if ($date_diff == 4) {
  $printed_date = "4 hari lalu";
} else if ($date_diff == 5) {
  $printed_date = "5 hari lalu";
} else if ($date_diff == 6) {
  $printed_date = "6 hari lalu";
} else if ($date_diff == 7) {
  $printed_date = "7 hari lalu";
} else if ($date_diff > 7 && $date_diff < 365) {
  $printed_date = date("j M Y", $seconds);
} else if ($date_diff >= 365) {
  $printed_date = date("j M Y", $seconds);
}

// if (!(substr($shop_thumb_id, 0, 4) === "http")) {
//   $shop_thumb_id = "/gaspol_web/images/" . $shop_thumb_id;
// }

if (isset($_GET['f_pin'])) {

  $f_pin = $_GET['f_pin'];
}

$bg_url = "";

// if (isset($f_pin) && $f_pin != "") {
//   $sqlBE = "SELECT be.COMPANY_ID
//     FROM BUSINESS_ENTITY be
//     LEFT JOIN USER_LIST ul ON ul.BE = be.ID
//     WHERE ul.F_PIN = '$f_pin'";
// } else if (isset($api_key) && $api_key != "") {
//   $sqlBE = "SELECT be.COMPANY_ID
//     FROM BUSINESS_ENTITY be
//     WHERE be.API = '$api_key'";
// }

// $queBE = $dbconn->prepare($sqlBE);
// $queBE->execute();
// $resBE = $queBE->get_result()->fetch_assoc();
// $c_id = $resBE["COMPANY_ID"];
// $queBE->close();

// // get BG
// $sqlBG = "SELECT APP_BG FROM WEBFORM WHERE COMPANY_ID = " . $c_id . " AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
// $queBG = $dbNewNus->prepare($sqlBG);
// $queBG->execute();
// $resBG = $queBG->get_result()->fetch_assoc();
// $bg_str = $resBG["APP_BG"];
// $queBG->close();

// // echo $bg_str;
// if ($bg_str != null && $bg_str != "") {
//   $bg_arr = explode(",", $bg_str);

//   // echo "<pre>";
//   // print_r($bg_arr);
//   // echo "</pre>";

//   $rand_bg = $bg_arr[array_rand($bg_arr, 1)];

//   $bg_url = "https://newuniverse.io/dashboardv2/uploads/background/" . $rand_bg;
// } else {
$rand_bg = rand(1, 12) . ".png";
$bg_url = "../assets/img/lbackground_" . $rand_bg;
// }

?>
<!doctype html>
<html lang="en">

<head>
  <title>Comment</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/roboto.css" />
  <link rel="stylesheet" href="../assets/css/style-comment.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />
  <script src="../assets/js/script-data-comment.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script type="module" src="../assets/js/translate.js?random=<?= time(); ?>"></script>

  <style>
    body::after {
      content: "";
      background-image: url('<?php echo $bg_url; ?>');
      /* background-image: url('/dashboardv2/uploads/background/<?php echo $rand_bg; ?>'); */
      background-size: 100% auto;
      background-repeat: repeat-y;
      opacity: .5;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      position: fixed;
      z-index: -1;
    }

    .cmt-reply {
      margin-left: 3rem !important;
      margin-right: 0 !important;
      margin-top: 0 !important;
      margin-bottom: 0 !important;
      width: 100%;
    }

    /* asdadasd */
  </style>
</head>

<body>
  <div id="header-layout" class="sticky-top">
    <div id="header" class="row justify-content-between">
      <div class="col-auto">
        <!-- <i class="fas fa-arrow-left" style="color: white;" onclick="goBack()"></i> -->
        <img src="../assets/img/tab5/Back-(White).png" class="navbar-back-black" onclick="goBack()" style="height:28px; width: auto;">
        &ensp;
        <span id="header-title" class="align-middle"></h4>

          <script>
            if (localStorage.lang == 0) {
              $('#header-title').text('Comment');
            } else if (localStorage.lang == 1) {
              $('#header-title').text('Komentar');
            }
          </script>
      </div>
    </div>
  </div>

  <div id="content-comment" style="margin-bottom: 10rem;">
    <div class="row mx-0">
      <div class="col-2">
        <img alt="Profile Photo" class="rounded-circle my-3" style="height:50px; width:50px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" id="display-pic" src="<?php echo $shop_thumb_id; ?>">
      </div>
      <div class="col-10 text-break">
        <div style="font-weight: bold;" class="mt-3 mb-1 mr-3">
          <img class="verified-icon-prod" src="../assets/img/icons/Verified-(Black).png">
          <?php echo $product_name; ?>
        </div>
        <div style="font-weight: 300;" class="my-1 mr-3">
          <!-- <div class="prod-desc"><?php //echo $product_description; 
                                      ?></div> -->
          <div class="prod-desc <?php
                                if (strlen($product_description) > 50) {
                                  echo "truncate mb-3";
                                }
                                ?>">
            <?php echo $product_description; ?>
          </div>
          <?php
          if (strlen($product_description) > 50) {
            echo '<span class="truncate-read-more" style="color:#999999;" data-translate="comment-4"></span>';
          ?>
            <script>
              if (localStorage.lang == 0) {
                $('.truncate-read-more').text('Read more...');
              } else if (localStorage.lang == 1) {
                $('.truncate-read-more').text('Selengkapnya...');
              }
            </script>
          <?php
          }
          ?>
        </div>
        <div style="font-weight: 300; color: grey;" class="my-1">
          <?php echo $printed_date; ?>
        </div>
      </div>
    </div>
    <hr style="border-top: 3px solid #bbb;" class="ml-3 mr-3">
    <?php


    $comments = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');

    function getReplies($reffs, $sub)
    {
      ${"j" . $sub} = 0;
      foreach ($reffs as $reff) {
        ${"comment_id_reff" . $sub} = $reff["COMMENT_ID"];
        ${"f_pin_reff" . $sub} = $reff["F_PIN"];
        ${"comment_text_reff" . $sub} = $reff["COMMENT"];
        ${"created_date_reff" . $sub} = $reff["CREATED_DATE"];

        ${"seconds_reff" . $sub} = intval(intval(${"created_date_reff" . $sub}) / 1000);
        $date_diff = round((time() - ${"seconds_reff" . $sub}) / (60 * 60 * 24));
        if ($date_diff == 0) {
          ${"printed_date_reff" . $sub} = "Hari ini";
        } else if ($date_diff == 1) {
          ${"printed_date_reff" . $sub} = "Kemarin";
        } else if ($date_diff == 2) {
          ${"printed_date_reff" . $sub} = "2 hari lalu";
        } else if ($date_diff == 3) {
          ${"printed_date_reff" . $sub} = "3 hari lalu";
        } else if ($date_diff == 4) {
          ${"printed_date_reff" . $sub} = "4 hari lalu";
        } else if ($date_diff == 5) {
          ${"printed_date_reff" . $sub} = "5 hari lalu";
        } else if ($date_diff == 6) {
          ${"printed_date_reff" . $sub} = "6 hari lalu";
        } else if ($date_diff == 7) {
          ${"printed_date_reff" . $sub} = "7 hari lalu";
        } else if ($date_diff > 7 && $date_diff < 365) {
          ${"printed_date_reff" . $sub} = date("j M Y", ${"seconds_reff" . $sub});
        } else if ($date_diff >= 365) {
          ${"printed_date_reff" . $sub} = date("j M Y", ${"seconds_reff" . $sub});
        }
        // ${"printed_date_reff" . $sub} = date("H:i", ${"seconds_reff" . $sub});
        // ${"date_explode_reff" . $sub} = explode(":", ${"printed_date_reff" . $sub});
        // ${"hours_reff" . $sub} = (int)${"date_explode_reff" . $sub}[0] + 7;
        // if (${"hours_reff" . $sub} >= 24) {
        //   ${"hours_reff" . $sub} = ${"hours_reff" . $sub} - 24;
        //   ${"hours_reff" . $sub} = "{" . ${"hours_reff" . $sub} . "}";
        //   if (strlen(${"hours_reff" . $sub}) == 1) {
        //     ${"hours_reff" . $sub} = "0" . ${"hours_reff" . $sub};
        //   }
        // }
        // ${"printed_date_reff" . $sub} = ${"hours_reff" . $sub} . ":" . ${"date_explode_reff" . $sub}[1];
        ${"parameter_reply_reff" . $sub} = "true," . "'user-name-reff-" . $sub . ${"j" . $sub} . "'," . "'" . ${'comment_id_reff' . $sub} . "'";
        ${"parameter_profile_reff" . $sub} = "'" . ${"f_pin_reff" . $sub} . "'";
        $is_delete = $reff["IS_DELETE"];

        $displayPic = $reff['IMAGE'];
        $displayName = $reff['USERNAME'];

        if ($displayPic == "") {
          $displayPic = "../assets/img/ic_person_boy.png";
        } else {
          $displayPic = "https://qmera.io/filepalio/image/" . $displayPic;
        }
        if ($is_delete == 0) {
          echo '<div class="row comments cmt-reply" id="' . ${'comment_id_reff' . $sub} . '">';
        } else {
          echo '<div class="row comments cmt-reply is-delete" id="' . ${'comment_id_reff' . $sub} . '">';
        }
        echo '<div class="commentId" style="display: none;">' . ${'comment_id_reff' . $sub} . '</div>';
        echo '<div class="fPin" style="display: none;">' . ${"f_pin_reff" . $sub} . '</div>';
        echo '<div class="col-2">';
        echo '<img onclick="showProfile(' . ${"parameter_profile_reff" . $sub} . ')" id="user-thumb-reff-' . $sub . ${"j" . $sub} . '" alt="Profile Photo" class="rounded-circle my-3" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" id="display-pic" src="' . $displayPic . '">';
        echo '</div>';
        echo '<div class="col-10 text-break">';
        if ($is_delete == 0) {
          echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span id="user-name-reff-' . $sub . ${"j" . $sub} . '">' . $displayName . '</span><span style="font-weight: 300;"> ' . ${"comment_text_reff" . $sub} . '</h4></div>';
          echo '<div style="font-weight: 100; color: grey;" class="my-1">' . ${"printed_date_reff" . $sub} . '&emsp;<span class="text-replied" data-translate="comment-2" style="font-weight: 300;" onclick="onReply(' . ${"parameter_reply_reff" . $sub} . ');"></span></div>';
        } else {
          echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span style="font-weight: 300;">Comment deleted.</h4></div>';
        }
    ?>

        <script>
          if (localStorage.lang == 0) {
            $('.text-replied').text('Reply');
          } else if (localStorage.lang == 1) {
            $('.text-replied').text('Balas');
          }
        </script>

      <?php
        echo '</div>';
        echo '</div>';

        echo ('<script>getDisplayNameReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
        echo ('<script>getThumbIdReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
        ${"reffs" . $sub} = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
        if (count(${"reffs" . $sub}) > 0) {
          getReplies(${"reffs" . $sub}, $sub + 1);
        }
        ${"j" . $sub}++;
      }
    }

    $i = 0;

    foreach ($comments as $comment) {
      $comment_id = $comment["COMMENT_ID"];
      // if ($is_post == 0) {
      //   $product_code = $comment["LINK_ID"];
      // } else {
      $product_code = $comment["POST_ID"];
      // }
      $f_pin = $comment["F_PIN"];
      $comment_text = $comment["COMMENT"];
      $created_date = $comment["CREATED_DATE"];
      $displayPic = $comment['IMAGE'];
      $displayName = $comment['USERNAME'];
      $is_delete = $comment['IS_DELETE'];

      if ($displayPic == "") {
        $displayPic = "../assets/img/ic_person_boy.png";
      } else {
        $displayPic = "https://qmera.io/filepalio/image/" . $displayPic;
      }

      $seconds = intval(intval($created_date) / 1000);
      $date_diff = round((time() - $seconds) / (60 * 60 * 24));
      if ($date_diff == 0) {
        $printed_date = "Hari ini";
      } else if ($date_diff == 1) {
        $printed_date = "Kemarin";
      } else if ($date_diff == 2) {
        $printed_date = "2 hari lalu";
      } else if ($date_diff == 3) {
        $printed_date = "3 hari lalu";
      } else if ($date_diff == 4) {
        $printed_date = "4 hari lalu";
      } else if ($date_diff == 5) {
        $printed_date = "5 hari lalu";
      } else if ($date_diff == 6) {
        $printed_date = "6 hari lalu";
      } else if ($date_diff == 7) {
        $printed_date = "7 hari lalu";
      } else if ($date_diff > 7 && $date_diff < 365) {
        $printed_date = date("j M Y", $seconds);
      } else if ($date_diff >= 365) {
        $printed_date = date("j M Y", $seconds);
      }
      // $printed_date = date("H:i", $seconds);
      // $date_explode = explode(":", $printed_date);
      // $hours = (int)$date_explode[0] + 7;
      // if ($hours >= 24) {
      //   $hours = $hours - 24;
      //   $hours = "{$hours}";
      //   if (strlen($hours) == 1) {
      //     $hours = "0" . $hours;
      //   }
      // }
      // $printed_date = $hours . ":" . $date_explode[1];
      $parameter_reply = "true," . "'user-name-" . $i . "'," . "'$comment_id'";
      $parameter_profile = "'" . $f_pin . "'";
      if ($is_delete == 0) {
        echo '<div class="row mx-0 comments" id="' . $comment_id . '">';
      } else {
        echo '<div class="row mx-0 comments is-deleted" id="' . $comment_id . '">';
      }
      echo '<div class="commentId" style="display: none;">' . $comment_id . '</div>';
      echo '<div class="fPin" style="display: none;">' . $f_pin . '</div>';
      echo '<div class="col-2">';
      echo '<img onclick="showProfile(' . $parameter_profile . ')" id="user-thumb-' . $i . '" alt="Profile Photo" class="rounded-circle my-3" style="height:50px; width:50px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" id="display-pic" src="' . $displayPic . '">';
      echo '</div>';

      if ($is_delete == 0) {
        echo '<div class="col-10 text-break first-comment">';
        echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span id="user-name-' . $i . '">' . $displayName . '</span><span style="font-weight: 300;"> ' . $comment_text . '</h4></div>';

        echo '<div style="font-weight: 100; color: grey;" class="my-1">' . $printed_date . '&emsp;<span class="text-replied" style="font-weight: 300;" data-translate="comment-2" onclick="onReply(' . $parameter_reply . ');"></span></div>';
      } else {
        echo '<div class="col-10 text-break first-comment is-deleted">';
        echo '<div style="font-weight: bold;" class="mt-3 mb-1 mr-3"><span style="font-weight: 300;">Comment deleted.</h4></div>';
      }
      ?>

      <script>
        if (localStorage.lang == 0) {
          $('.text-replied').text('Reply');
        } else if (localStorage.lang == 1) {
          $('.text-replied').text('Balas');
        }
      </script>

    <?php
      echo '</div>';
      $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
      if (count($reffs) > 0) {
        getReplies($reffs, 1);
      }
      echo '</div>';

      echo ('<script>getDisplayName("' . $f_pin . '","' . $i . '")</script>');
      echo ('<script>getThumbId("' . $f_pin . '","' . $i . '")</script>');
      // $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_comments.php');
      // if (count($reffs) > 0) {
      //   getReplies($reffs, 1);
      // }
      $i++;
    }
    ?>
  </div>

  <div class="row fixed-bottom py-2" style="background-color: transparent; margin-bottom: 85px;">
    <div style="width: 100%; height: 40px; background: #b0bec6;" class="d-none row mb-2 pt-2" id="reply-div">
      <div class="col-10" style="color: grey; font-weight: 300; padding-left: 40px;" id="content-reply">
      </div>
      <div class="col-2 text-right">
        <i class="fas fa-times" style="color: white;" onclick="onReply(false);"></i>
      </div>
    </div>
    <div class="col-10 pl-4">
      <input type="text" name="message" id="input" placeholder="Tulis Komentar" data-translate-placeholder="comment-3" onclick="onFocusInput()" class="border-0 px-3 py-2 rounded shadow-sm">
    </div>
    <div class="col-2 px-0">
      <div id="buttond_send" class="px-3 py-2" onclick="commentProduct('<?php echo $product_code; ?>')">
        <img src="../assets/img/arrow_send.png" id="triangle-right">
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/script-comment.js?random=<?= time(); ?>"></script>

  <script>
    if (localStorage.lang == 0) {
      $('#header-title').text('Comment');
      $('#input').attr('placeholder', 'Write a Comment');
    } else if (localStorage.lang == 1) {
      $('#header-title').text('Komentar');
      $('#input').attr('placeholder', 'Tulis Komentar');
    }
  </script>
</body>

</html>