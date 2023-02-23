let activeFilter = '';
let activeClass = '';
if (localStorage.getItem('active_content_category') != null) {
  activeFilter = localStorage.getItem('active_content_category');
}
if (localStorage.getItem('active_content_classification') != null) {
  activeClass = localStorage.getItem('active_content_classification');
}

function postData(actionUrl, method, data) {
  var mapForm = $('<form id="mapform" action="' + actionUrl + '" method="' + method.toLowerCase() + '"></form>');
  for (var key in data) {
    if (data.hasOwnProperty(key)) {
      mapForm.append('<input type="hidden" name="' + key + '" id="' + key + '" value="' + data[key] + '" />');
    }
  }
  $('body').append(mapForm);
  mapForm.submit();
}

//var ua = window.navigator.userAgent;
//var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
//var webkit = !!ua.match(/WebKit/i);
//var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
//var palioBrowser = !!ua.match(/PalioBrowser/i);
//var isChrome = !!ua.match(/Chrome/i);

// $('.carousel').carousel({
//   pause: true,
//   interval: false
// });

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 3;
var navbarHeight = $('#header-layout').outerHeight();
var topPosition = 0;
var STORE_ID = "";
var FILTERS = "";

function hasScrolled() {
  var st = $(this).scrollTop();

  // Make sure they scroll more than delta
  if (Math.abs(lastScrollTop - st) <= delta)
    return;

  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > lastScrollTop && st > navbarHeight) {
    // Scroll Down
    $('#header-layout').css('top', -navbarHeight + 'px');
  } else {
    // Scroll Up
    if (st + $(window).height() < $(document).height()) {
      $('#header-layout').css('top', '0px');
    }
  }

  lastScrollTop = st;
}

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    $('.dropdown-toggle').dropdown('hide')
    didScroll = false;
  }
}, 10);

function headerOut() {
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

function headerOutAndReset() {
  $("#mic").attr("src", "../assets/img/action_mic.png");
  $('#query').val('');
  $('#switchAll').prop('checked', checked);
  setFilterCheckedAll(true);
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

// $('#header').click(function () {
//   $(document).scrollTop(0);
//   if ($('#searchFilter').hasClass('d-none')) {
//     $('#searchFilter').removeClass('d-none');
//     isSearchHidden = false;
//   } else {
//     $('#searchFilter').addClass('d-none');
//     isSearchHidden = true;
//     const query = $('#query').val();

//     if (!isFilterCheckedAny()) {
//       resetFilter();
//     } else
//     if (isFilterCheckedAny() || query != "") {
//       searchFilter();
//     } else if (query == "") {
//       var url_string = window.location.href;
//       var url = new URL(url_string);
//       var paramValue = url.searchParams.get("query");
//       if (paramValue != null) {
//         searchFilter();
//       }
//     }
//   }
//   navbarHeight = $('#header-layout').outerHeight();
//   $('#header-layout').css('top', '0px');
//   $('#gear').rotate({
//     angle: 0,
//     animateTo: 180
//   });
// });


let countVideoPlaying = 0;

let carouselIsPaused = true;

function checkVideoViewport() {
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    return;
  }
  let videoWrapElements = document.querySelectorAll('.timeline-image .video-wrap>video, .timeline-image .carousel-item.active .video-wrap>video');
  let videoWrapArr = [].slice.call(videoWrapElements);
  let carouselElements = document.querySelectorAll('.timeline-image .carousel');
  let carouselArr = [].slice.call(carouselElements);

  let allElementsArr = videoWrapArr.concat(carouselArr);
  // console.log('ele', allElementsArr);
  let observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      // console.log(entry.target);
      // console.log('ho', countVideoPlaying)
      if (entry.intersectionRatio >= 0.8 && $('#modal-addtocart').not('.show') && countVideoPlaying === 0) {
        playElement(entry.target);

      } else if (entry.intersectionRatio < 0.8) {
        pauseElement(entry.target);
      }
    });
  }, {
    threshold: 1
  });



  async function playElement(el) {

    if (el.id.includes('video') && el.paused) {
      let vidPromise = el.play();
      countVideoPlaying = 1;
    }
  }

  function pauseElement(el) {

    if (el.id.includes('video') && !el.paused) {
      el.pause();
      countVideoPlaying = 0;
    }
  }

  function pauseCarousel(cr) {
    cr.pause();
  }

  function startCarousel(cr) {
    cr.cycle();
  }

  allElementsArr.forEach((elements) => {
    observer.observe(elements);
  });

  // [].forEach.call(carouselElements, (carousel) => {
  //   // console.log('loop');

  //   observer.observe(carousel);
  // })
  videoReplayOnEnd();
  playVid();
  // }
}


document.addEventListener('focusin', function () {
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).pause();
    })
  }
}, true);

function checkVideoCarousel() {
  // play video when active in carousel
  // if (palioBrowser && isChrome) {
  $(".timeline-image .carousel").on("slid.bs.carousel", function (e) {
    if ($(this).find("video").length) {
      let isPaused = $(this).find("video").get(0).paused;
      let $videoPlayButton = $(this).find(".video-play");
      if ($(this).find(".carousel-item").hasClass("active") && isPaused) {
        $(this).find("video").get(0).play();
        $videoPlayButton.addClass("d-none");
      } else {
        $(this).find("video").get(0).pause();
        $videoPlayButton.removeClass("d-none");
      }
    }
  });
  videoReplayOnEnd();
  playVid();
  // }
}

// function carouselNext() {
//   // $('.product-row .timeline-image .carousel a.carousel-control-next').click(function(e) {
//   //   e.stopPropagation();

//   //   var myCarousel = document.querySelector('#myCarousel');
//   //   var carousel = new bootstrap.Carousel(myCarousel);


//   // })
//   // let carouselNext = document.querySelectorAll('.timeline-image .carousel');

//   // [].forEach.call(carouselNext, function(cn) {
//   //   let myCarousel = cn;
//   //   let carousel = new bootstrap.Carousel(myCarousel);

//   //   // let nextButton = cn.querySelector('a.carousel-control-next');


//   // })
//   $('.timeline-image .carousel').each(function () {
//     let myCarousel = $(this);
//     let carousel = new bootstrap.Carousel(myCarousel);

//     $(this).find('a.carousel-control-next').click(function (e) {

//     })
//   })
// }

function carouselPrev() {

}

function onVideoStop(vid) {
  $(vid).parent().find(".video-play").removeClass("d-none");
}

function onVideoPlay(vid) {
  $(vid).parent().find(".video-play").addClass("d-none");
}

// document.querySelectorAll("video.myvid").forEach(vid => {
//   vid.addEventListener("stop", function () {
//     onVideoStop(vid);
//   }, false);
//   vid.addEventListener("ended", function () {
//     onVideoStop(vid);
//   }, false);
//   vid.addEventListener("pause", function () {
//     onVideoStop(vid);
//   }, false);
//   vid.addEventListener("play", function () {
//     onVideoPlay(vid);
//   }, false);
// })

var visibleCarousel = new Set();

function checkcarousel() {

  // $('.timeline-image .carousel').each(function () {
  //   $(this).carousel('cycle');
  //   // var myCarousel = document.querySelector($(this).attr('id'))
  //   // var carousel = new bootstrap.Carousel(myCarousel, {
  //   //   interval: 5000,
  //   //   wrap: false
  //   // })
  //   // carousel.cycle();
  //   if ($(this).is(":in-viewport")) {
  //     if (!visibleCarousel.has($(this).attr('id'))) {
  //       visibleCarousel.add($(this).attr('id'));
  //       $(this).carousel('cycle');
  //       // carousel.cycle();
  //     }
  //   } else {
  //     if (visibleCarousel.has($(this).attr('id'))) {
  //       visibleCarousel.delete($(this).attr('id'));
  //       $(this).carousel('pause');
  //       // carousel.pause();
  //     }
  //   }
  // });
}

var scrollTimer = null;
$(function () {
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;

    // play video when is in view
    checkVideoViewport();
    checkVideoCarousel();
    // checkcarousel();

    // console.log('scroll');
    // if (window.Android) {
    //   window.Android.tabShowHide(false);
    // }
    // if (scrollTimer !== null) {
    //   clearTimeout(scrollTimer);
    // }
    // scrollTimer = setTimeout(function () {
    //   // do something
    //   console.log('finish scroll');
    //   if (window.Android) {
    //     window.Android.tabShowHide(true);
    //   }
    // }, 1500);
  });
});

function scrollFunction() {
  if ($(document).scrollTop() > navbarHeight) {
    if (!isSearchHidden)
      headerOut();
    $("#scroll-top").css('display', 'block');
    // setTimeout(function () {
    //   $("#scroll-top").css('display', 'none');
    // }, 5000);
  } else {
    $("#scroll-top").css('display', 'none');
  }
}

function topFunction(animate) {
  if (animate) {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  } else {
    window.scrollTo({
      top: 0
    });
  }
}

var productData = [];

var storeMap = new Map();
var storeIdMap = new Map();

function fetchStores() {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var params = location.search
    .substr(1)
    .split("&");
  var fpin = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('f_pin=')) {
      tmp = params[i].split("=")[1];
      fpin = tmp;
    }
  }

  if (!fpin && window.Android) {
    try {
      fpin = window.Android.getFPin();
    } catch (error) {}
  }

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      data.forEach(storeEntry => {
        storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
        storeIdMap.set("" + storeEntry.ID, storeEntry.CODE);
      });
    }
  }
  if (fpin != "") {
    xmlHttp.open("get", "/gaspol_web/logics/fetch_stores?f_pin=" + fpin);
  } else {
    xmlHttp.open("get", "/gaspol_web/logics/fetch_stores");
  }
  xmlHttp.send();
}

function openStore($store_code, $store_link) {
  if (window.Android) {
    if (storeMap.has($store_code)) {
      var storeOpen = storeMap.get($store_code);
      window.Android.openStore(storeOpen);
    }
  } else {
    if ($store_link != "") {
      window.location.href = $store_link;
    } else {
      window.location.href = "/gaspol_web/pages/tab3-profile.php?store_id=" + $store_code + "&f_pin=02b3c7f2db";
    }
  }
}

var likedPost = [];

function getLikedProducts() {
  var f_pin = ""
  if (window.Android) {
    f_pin = window.Android.getFPin();

  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  if (f_pin != "") {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        console.log(xmlHttp.responseText);
        let likeData = JSON.parse(xmlHttp.responseText);
        likeData.forEach(product => {
          var productCode = product.PRODUCT_CODE;
          likedPost.push(productCode);
          $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.webp");
        });
        console.log('get likes', likedPost);
      }
    }
    xmlHttp.open("get", "/gaspol_web/logics/fetch_products_liked?f_pin=" + f_pin);
    xmlHttp.send();
  }
}

function updateCartCounter() {
  let counter_badge = 0;
  if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
  } else {
    var cart = [];
  }
  cart.forEach(item => {
    item.items.forEach(item => {
      counter_badge += parseInt(item.itemQuantity);
    })
  })
  if (counter_badge != 0) {
    $('#cart-counter').removeClass('d-none');
    $('#cart-counter').html(counter_badge);
  } else {
    $('#cart-counter').addClass('d-none');
  }
}
// likeProduct(218931931, false)
function likeProduct($productCode, checkIOS = false) {
  if (window.Android) {
    if (!window.Android.checkProfile()) {
      return;
    }
  }

  if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: $productCode,
      param2: 'like'
    });
    return;
  }

  var score = parseInt($('#like-counter-' + $productCode).text());
  var isLiked = false;
  if (likedPost.includes($productCode)) {
    likedPost = likedPost.filter(p => p !== $productCode);
    $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.webp");
    if (score > 0) {
      $('#like-counter-' + $productCode).text(score - 1);
    }
    isLiked = false;
  } else {
    likedPost.push($productCode);
    $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.webp");
    $('#like-counter-' + $productCode).text(score + 1);
    isLiked = true;
  }

  let f_pin = '';

  //TODO send like to backend
  if (window.Android) {
    f_pin = window.Android.getFPin();


  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  var curTime = (new Date()).getTime();
  var formData = new FormData();

  formData.append('product_code', $productCode);
  formData.append('f_pin', f_pin);
  formData.append('last_update', curTime);
  formData.append('flag_like', (isLiked ? 1 : 0));

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // console.log(xmlHttp.responseText);
      // updateScore($productCode);
    }
  }
  xmlHttp.open("post", "/gaspol_web/logics/like_product");
  xmlHttp.send(formData);
}

var followedStore = [];

function getFollowedStores() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  // if (f_pin) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let followData = JSON.parse(xmlHttp.responseText);
      followData.forEach(store => {
        var storeCode = store.STORE_CODE;
        followedStore.push(storeCode);
        $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
      });
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_stores_followed?f_pin=" + f_pin);
  xmlHttp.send();
  // }
}
// }

function followStore($productCode, $storeCode) {
  var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0, -9));
  var isFollowed = false;
  if (followedStore.includes($storeCode)) {
    followedStore = followedStore.filter(p => p !== $storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist.png");
    if (score > 0) {
      $('.follow-counter-store-' + $storeCode).text((score - 1) + " pengikut");
    }
    isFollowed = false;
  } else {
    followedStore.push($storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
    $('.follow-counter-store-' + $storeCode).text((score + 1) + " pengikut");
    isFollowed = true;
  }

  //TODO send like to backend
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  var curTime = (new Date()).getTime();

  var formData = new FormData();

  formData.append('store_code', $storeCode);
  formData.append('f_pin', f_pin);
  formData.append('last_update', curTime);
  formData.append('flag_follow', (isFollowed ? 1 : 0));

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // console.log(xmlHttp.responseText);
      updateScoreShop($storeCode);
    }
  }
  xmlHttp.open("post", "/gaspol_web/logics/follow_store");
  xmlHttp.send(formData);
}

var commentedProducts = [];

function getCommentedProducts() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      //   // console.log("GETCOMMENTED");
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          let likeData = JSON.parse(xmlHttp.responseText);
          likeData.forEach(product => {
            var productCode = product.PRODUCT_CODE;
            commentedProducts.push(productCode);
            $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments.webp");
          });
        }
      }
      xmlHttp.open("get", "/gaspol_web/logics/fetch_products_commented?f_pin=" + f_pin);
      xmlHttp.send();
    }
  }
}

$('#switchAll').click(function () {
  setFilterCheckedAll($('#switchAll').is(':checked'));
});

function checkSwitch(checked) {
  $('#switchAll').prop('checked', checked);
}

$('.checkbox-filter-cat').click(function () {
  if (!$(this).is(':checked')) {
    checkSwitch(false);
  } else if (isFilterCheckedAll()) {
    checkSwitch(true);
  }
});

function fillFilter() {
  var url_string = window.location.href;
  var url = new URL(url_string);
  var searchValue = url.searchParams.get("query");
  if (searchValue != null) {
    $('#query').val(searchValue);
  }
  var filterValue = url.searchParams.get("filter");
  if (filterValue != null) {
    filterArr = filterValue.split("-");
    filterArr.forEach(filterId => {
      $('#checkboxFilter-' + filterId).prop('checked', true);
    });
  }
  // var filterGear = document.getElementById("gear");
  if (filterValue || searchValue) {
    // filterGear.classList.add("filter-yellow");
  } else {
    // filterGear.classList.remove("filter-yellow");
  }
}

function resetFilter() {
  $('#query').val('');
  $('#switchAll').prop('checked', true);
  setFilterCheckedAll(true);
  if (!isSearchHidden) {
    headerOut();
  }
  searchFilter();
}

function onClickHasStory() {
  $(".has-story").click(function (e) {
    e.preventDefault();
    busy = true;
    if (this.id == "all-store") {
      STORE_ID = "";
      let activeCategories = localStorage.getItem('active_content_category');
      let bTheme = '';
      if (activeCategories !== null && activeCategories.split('-').length === 1) {
        bTheme = activeCategories.split('-')[0];
      }
      buttonTheme(bTheme);
      // searchFilter();
    } else {
      let prev_STORE_ID = STORE_ID;
      STORE_ID = this.id.split("-")[1];
      // buttonTheme(STORE_ID);
      // fetchProductCount(STORE_ID, prev_STORE_ID);
    }
    searchFilter();
  });
}

function fetchProductCount(store_id, prev_STORE_ID) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      if ((data != null && data.PRODUCT_COUNT > 0) || store_id == '02fc4da57e' || store_id == "02072b68ec") {
        // buttonTheme(data.CATEGORY);
        searchFilter();
      } else {
        var f_pin = "";
        try {
          f_pin = window.Android.getFPin();
        } catch (error) {}

        if (storeIdMap.has(store_id)) {
          var $store_code = storeIdMap.get(store_id);
          if (data != null && data == -1) {
            openStore($store_code, "");
          } else {
            if (f_pin != "") {
              window.location.href = "tab3-profile.php?f_pin=" + f_pin + "&store_id=" + $store_code;
            } else {
              window.location.href = "tab3-profile.php?store_id=" + $store_code;
            }
          }
        } else {
          if (data != null && data == -1) {
            openStore(store_id, "");
          } else {
            if (f_pin != "") {
              window.location.href = "tab3-profile.php?f_pin=" + f_pin + "&store_id=" + store_id;
            } else {
              window.location.href = "tab3-profile.php?store_id=" + store_id;
            }
          }
        }
        STORE_ID = prev_STORE_ID;
      }
      // // console.log(data);
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_store_product_count?store_id=" + store_id);
  xmlHttp.send();
}

function highlightStore() {

  if (STORE_ID != "") {
    selected_id = "#store-" + STORE_ID;
    // todo: kalo store ga ada
  } else {
    selected_id = '#all-store';
  }
  $('.has-story').removeClass('selected');
  $(selected_id).addClass("selected");
  horizontalScrollPos(STORE_ID);
}

function selectCategoryFilter() {
  let selected = [];
  $('#categoryFilter-body input:checked').each(function () {
    selected.push($(this).attr('id'));
  });

  if (selected.length > 0) {
    activeFilter = selected.join('-');
  } else {
    activeFilter = '';
  }

  $('#modal-categoryFilter').modal('toggle');

  if (window.Android) {
    try {
      window.Android.selectCategory(activeFilter);
    } catch (e) {
      console.log('select cat', e);
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.selectCategory) {
    window.webkit.messageHandlers.selectCategory.postMessage({
      param1: activeFilter
    });
  }

  searchFilter();
}

function changeBg(category) {
  let imgBg = document.querySelector('.demo-bg');

  if (category == '313') { //soccer
    imgBg.src = "../assets/img/nxsport_bg/bg3.png";
  } else if (category == '314') { //basketball
    imgBg.src = "../assets/img/nxsport_bg/bg2.png";
  } else if (category == '315') { //boxing
    imgBg.src = "../assets/img/nxsport_bg/bg4.png";
  } else if (category == '316') { //tennis
    imgBg.src = "../assets/img/nxsport_bg/bg5.png";
  } else if (category == '317') { // racing
    imgBg.src = "../assets/img/nxsport_bg/bg6.png";
  } else {
    imgBg.src = "../assets/img/nxsport_bg/bg1.png";
  }
}

async function searchFilter() {
  var selected_id = "";
  $('.has-story').removeClass("selected");
  var dest = window.location.href;
  var product_dest = "timeline_products.php";
  var filter_dest = "timeline_story_container.php";
  var params = "";
  const query = $('#query').val();
  var filter = activeFilter;
  localStorage.setItem('activeQuery', query);
  localStorage.setItem('activeFilter', filter);
  localStorage.setItem('activeStoreId', STORE_ID);
  console.log('active filter: ' + filter);
  if (dest.includes('#')) {
    dest = dest.split('#')[0]
  }
  if (dest.includes('?')) {
    dest = dest.split('?')[0];
  }
  if (STORE_ID != "") {
    params = params + "?store_id=" + STORE_ID;
  }
  if (query != "" || filter != "") {
    if (!params.includes("?")) {
      params = params + "?";
    } else {
      params = params + "&";
    }
  }
  if (query != "") {
    let urlEncodedQuery = encodeURIComponent(query);
    params = params + "query=" + urlEncodedQuery;
    if (filter != "") {
      params = params + "&";
    }
  }
  if (filter != "") {
    let urlEncodedFilter = encodeURIComponent(filter);
    params = params + "filter=" + urlEncodedFilter;
  }

  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      if (!params.includes("?")) {
        params = params + "?f_pin=" + f_pin;
      } else {
        params = params + "&f_pin=" + f_pin;
      }
    }
  } else {
    var f_pin = new URLSearchParams(window.location.search).get("f_pin");
    if (f_pin) {
      if (!params.includes("?")) {
        params = params + "?f_pin=" + f_pin;
      } else {
        params = params + "&f_pin=" + f_pin;
      }
    }
  }
  // // console.log("params " + params);
  dest = dest + params;
  product_dest = product_dest + params;
  filter_dest = filter_dest + params;
  // window.location.href = dest;
  // // console.log("filter " + filter + " x " + FILTERS);
  // if (filter != FILTERS) {
  console.log(filter_dest);
  $.get(filter_dest, function (data) {
    $('#story-container').html(data);
    // highlightStore();
    onClickHasStory();
  });
  // }
  //  else {
  //   highlightStore();
  // }
  offset = 0;
  startPause = 0;
  $('#pbr-timeline').html('');
  // console.log('busy: ' + busy);
  isCalled = false;
  countVideoPlaying = 0;
  await displayRecords(params, 10, offset);
  redrawLikeFollowComment();
  window.history.replaceState(null, "", dest);
  reinitCarousel();
  hideProdDesc();
  toggleProdDesc();
  setCurrentStore(STORE_ID);
  // checkVideoViewport();
  // checkcarousel();
  // toggleVideoMute();
  fetchProductMap(params);
  addToCartModal();
  changeLayout();
}



function voiceSearch() {
  if (window.Android) {
    $isVoice = window.Android.toggleVoiceSearch();
    toggleVoiceButton($isVoice);
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.toggleVoiceSearch) {
    window.webkit.messageHandlers.toggleVoiceSearch.postMessage({
      param1: ""
    });
  }
}

function showHideNewPost(boolean) {
  if (!boolean) {
    $('#to-new-post').addClass('d-none'); // hide
  } else {
    $('#to-new-post').removeClass('d-none'); // show
  }
}

function submitVoiceSearch($searchQuery) {
  // // console.log("submitVoiceSearch " + $searchQuery);
  $('#query').val($searchQuery);
  $('#delete-query').removeClass('d-none');
  searchFilter();
}

function toggleVoiceButton($isActive) {
  if ($isActive) {
    $("#mic").attr("src", "../assets/img/action_mic_blue.png");
  } else {
    $("#mic").attr("src", "../assets/img/action_mic.png");
  }
}

$('#searchFilterForm-a').validate({
  rules: {
    'category[]': {
      required: true
    }
  },
  messages: {
    'category[]': {
      required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
    },
  },
  submitHandler: function (form) {
    searchFilter();
  },
  errorClass: 'help-block',
  errorPlacement: function (error, element) {
    if (element.attr('name') == 'category[]') {

      error.insertAfter('#checkboxGroup');
    }
  }

});

function hasStoreId() {
  var tmp = "";
  var params = location.search
    .substr(1)
    .split("&");
  var id = "#all-store";
  var filter = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('store_id=')) {
      tmp = params[i].split("=")[1];
      STORE_ID = tmp;
    }
    if (params[i].includes('filter=')) {
      tmp = params[i].split("=")[1];
      FILTERS = tmp;
    }
  }
  highlightStore();
  const scrollLeft = $(id).position()['left'];
  $("#story-container ul").scrollLeft(scrollLeft);
  if (location.href.includes('#product')) {
    var product_id = '#' + location.href.split('#')[1]
    $(product_id)[0].scrollIntoView();
  }
}


onClickHasStory();

if (performance.navigation.type == 2) {
  location.reload(true);
}

function redrawLikeFollowComment() {
  likedPost.forEach(productCode => {
    $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.webp");
  });
  followedStore.forEach(storeCode => {
    $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
  });
  commentedProducts.forEach(productCode => {
    $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments.webp");
  });
}

function reinitCarousel() {
  $('.carousel').each(function () {
    $(this).carousel();
  });
}

function horizontalScrollPos(selected) {
  let selectedPos = 0;
  try {
    selectedPos = document.querySelector('.has-story#store-' + selected).offsetLeft;
  } catch (e) {

  }

  $('#story-container ul').animate({
    scrollLeft: selectedPos
  })
}

function setCurrentStore($store_id) {
  if (storeIdMap.has($store_id)) {
    var $store_code = storeIdMap.get($store_id);
    if (storeMap.has($store_code) && window.Android) {
      var storeOpen = JSON.parse(storeMap.get($store_code));
      if (storeOpen.IS_VERIFIED == 1 && !storeOpen.LINK) {
        window.Android.setCurrentStore($store_code, storeOpen.BE_ID);
      } else {
        window.Android.setCurrentStore('', '');
      }
    }
  }
}

function openComment(code, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = "comment.php?product_code=" + code + "&f_pin=" + f_pin;
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: code,
      param2: 'comment'
    });
    return;

  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "comment.php?product_code=" + code + "&f_pin=" + f_pin;
  }


}

function hideProdDesc() {
  $(".prod-desc").each(function () {
    if ($(this).text().length > 100 && $(this).siblings('.truncate-read-more').length == 0) {
      $(this).toggleClass("truncate");
      let toggleText = document.createElement("span");
      toggleText.innerHTML = "Selengkapnya...";
      toggleText.classList.add("truncate-read-more");
      $(this).parent().append(toggleText);
    }
  });
}

function editPost(code) {
  if (window.Android) {
    let f_pin = window.Android.getFPin();

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
  }
}

function deletePost(post_id) {
  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      if (xmlHttp.responseText == "Success") {
        console.log(post_id + ' deleted');
        if (localStorage.lang == 0) {
          $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
          $('#delete-post-close').text('Close');
        } else {
          $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
          $('#delete-post-close').text('Tutup');
        }
        $('#delete-post-info .modal-footer #delete-post-close').click(function () {
          console.log('row', $('.product-row').length);
          if ($('.product-row').length === 1) {
            let f_pin = new URLSearchParams(window.location.search).get('f_pin');
            let activeCategories = localStorage.getItem('active_content_category');

            window.location.href = 'tab1-main.php?f_pin=' + f_pin + '&filter=' + activeCategories;
          } else if ($('.product-row').length > 1) {
            // let activeCategories = localStorage.getItem('active_content_category');
            // let bTheme = '';
            // if (activeCategories !== null && activeCategories.split('-').length === 1) {
            //     bTheme = activeCategories.split('-')[0];
            // }
            window.location.reload();
          }
        });
        $('#delete-post-info').modal('toggle');
      } else {
        if (localStorage.lang == 0) {
          $('#delete-post-info .modal-body').html('<h6>An error occured while deleting post. Please refresh and try again.</h6>');
          $('#delete-post-close').text('Close');
        } else {
          $('#delete-post-info .modal-body').html('<h6>Error saat menghapus post. Silahkan muat ulang dan coba lagi.</h6>');
          $('#delete-post-close').text('Tutup');
        }
        // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
        //   window.location.reload();
        // });
        $('#delete-post-info').modal('toggle');
      }
    }
  }
  xmlHttp.open("POST", "/gaspol_web/logics/delete_post");
  xmlHttp.send(formData);
}

function toggleProdDesc() {
  $(".truncate-read-more").each(function () {
    $(this).click(function () {
      // console.log("read more");
      $(this).parent().find(".prod-desc").toggleClass("truncate");
      if ($(this).text() == "Selengkapnya...") {
        $(this).text(" Sembunyikan");
      } else {
        $(this).text("Selengkapnya...");
      }
    });
  });
}

// function toggleVideoMute() {
//   $(".video-sound").each(function () {
//     $(this).click(function (e) {
//       e.stopPropagation();
//       let $videoElement = $(this).parent().find("video.myvid");
//       if ($videoElement.prop("muted")) {
//         $videoElement.prop("muted", false);
//         $(this).find("img").attr("src", "../assets/img/video_unmute.png");
//       } else {
//         $videoElement.prop("muted", true);
//         $(this).find("img").attr("src", "../assets/img/video_mute.png");
//       }
//     });
//   });
// }

function toggleVideoMute(code) {
  console.log(code);
  let videoWrap = document.getElementById(code);
  let videoElement = videoWrap.querySelector('video');
  // console.log(videoElement);

  console.log('#' + code + ' .video-sound img');
  let muteIcon = document.querySelector('#' + code + ' .video-sound img');

  if (videoElement.muted) {
    videoElement.muted = false;
    muteIcon.src = "../assets/img/video_unmute.png";
  } else {
    videoElement.muted = true;
    muteIcon.src = "../assets/img/video_mute.png";
  }

  console.log(code + ' ' + videoElement.muted);
}

function videoMuteAll() {
  $(".video-sound").each(function () {
    let $videoElement = $(this).parent().find("video.myvid");
    $videoElement.prop("muted", true);
    $(this).find("img").attr("src", "../assets/img/video_mute.png");
  });
}

var productMap = new Map();

function fetchProductMap(str) {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var params = "";
  if (str == "") {
    params = location.search;
  } else {
    params = str;
  }

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      data.forEach(productEntry => {
        productMap.set(productEntry.CODE, JSON.stringify(productEntry));
      });
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_products_json" + params);
  xmlHttp.send();
}

function openProductMenu($productCode) {
  if (window.Android) {
    if (productMap.has($productCode)) {
      var productOpen = productMap.get($productCode);
      window.Android.openProductMenu(productOpen);
    }
  }
}

function videoReplayOnEnd() {
  $(".myvid").each(function (i, obj) {
    $(this).on('ended', function () {
      // // console.log("video ended");
      let $videoPlayButton = $(this).parent().find(".video-play");
      $videoPlayButton.removeClass("d-none");
    })
  })
}

function playVid() {
  $("div.video-play").each(function () {
    $(this).click(function (e) {
      e.stopPropagation();
      $(this).parent().find("video.myvid").get(0).play();
      $(this).addClass("d-none");
    })
  })
}

let startPause = 0;

function refreshClean() {
  let f_pin = "";
  let p = "";
  let url = 'tab1-main.php';
  if (window.Android) {
    f_pin = window.Android.getFPin();
    url = url + '?f_pin=' + f_pin;
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
    if (f_pin != null) {
      url = url + '?f_pin=' + f_pin;
    }
  }
  window.location.reload();
}

function pauseAll() {
  $('.carousel-item video, .timeline-image video').each(function () {
    $(this).get(0).pause();
  })
  visibleCarousel.clear();
  $('.carousel').each(function () {
    $(this).carousel('pause');
  })
  startPause = new Date().getTime();
  console.log(startPause);
  hideCategoryModal();
}

function resumeAll() {
  console.log('resume');
  countVideoPlaying = 0;
  let curTime = new Date().getTime();
  if (window.Android && typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")) {
    $('#to-new-post').addClass('d-none');
  } else {
    $('#to-new-post').removeClass('d-none');
  }
  console.log('startpause', startPause);
  if (startPause > 0) {
    console.log('time', curTime - startPause);
    if (curTime - startPause >= 180000) {
      refreshClean();
    }
  }
  checkVideoViewport();
  checkVideoCarousel();
  // checkcarousel();
  // // updatecounter();
  fetchNotifCount();

}

function visitStore($store_code, $f_pin, $is_entering) {
  var formData = new FormData();

  formData.append('store_code', $store_code);
  formData.append('f_pin', $f_pin);
  formData.append('flag_visit', ($is_entering ? 1 : 0));

  if ($store_code && $f_pin) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // console.log(xmlHttp.responseText);
      }
    }
    xmlHttp.open("post", "/gaspol_web/logics/visit_store");
    xmlHttp.send(formData);
  }
}

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');

  if (activeParam == null) {
    activeParam = "all";
    activeFilter = "";
  } else {
    activeFilter = activeParam;
  }

  if (activeParam !== "all") {
    let filters = activeParam.split('-');

    filters.forEach(fi => {
      $('#modal-categoryFilter input#' + fi).prop('checked', true);
    })
  } else {
    console.log('all active')
    $('#modal-categoryFilter ul li input').each(function () {
      $(this).prop('checked', true);
    })
  }
}

function checkCategoryCheckbox() {
  $('#modal-categoryFilter input').on('change', function () {
    var len = $('#modal-categoryFilter input:checked').length;
    if (len === 0) {
      $(this).prop('checked', true);
      console.log('You must select at least 1 checkbox');
    }
  });
}

function ext(url) {
  return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."))
}

function goBack() {
  if (window.Android) {
    window.Android.closeView();
  } else {
    window.history.back();
  }
}

function numberWithCommas(x) {
  // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
  return x.toLocaleString();
}

function openDetailProduct(pr) {
  let getPr = JSON.parse(productMap.get(pr));

  $('#modal-addtocart .addcart-img-container').html('');
  $('#modal-addtocart .product-name').html('');
  $('#modal-addtocart .product-price').html('');
  $('#modal-addtocart .prod-details .col-11').html('');

  let product_imgs = getPr.THUMB_ID.split('|');
  let product_name = getPr.NAME;
  let product_price = numberWithCommas(getPr.PRICE);
  // let product_price = getPr.PRICE;
  let product_desc = getPr.DESCRIPTION;

  let product_showcase = "";

  // if (product_imgs.length == 1) {
  let extension = ext(product_imgs[0]);
  if (extension == ".jpg" || extension == ".png" || extension == ".webp") {
    product_showcase = `<img class="product-img" src="${product_imgs[0]}">`;
  } else if (extension == ".mp4" || extension == ".webm") {
    let poster = product_imgs[0].replace(extension, ".webp");
    product_showcase = `
      <div class="video-wrap"><video playsinline muted="" class="myvid" preload="metadata"
              poster="${poster}">
              <source src="${product_imgs[0]}" type="video/mp4"></video>
      </div>
      `;
  }

  let followSrc = "../assets/img/icons/Wishlist-(White).png";
  if (followedStore.includes(getPr.SHOP_CODE)) {
    followSrc = "../assets/img/icons/Wishlist-fill.png";
  }

  product_showcase += `
  <hr id="drag-this">
  <img id="btn-wishlist" class="addcart-wishlist follow-icon-${getPr.SHOP_CODE}" onclick="followStore('${getPr.CODE}','${getPr.SHOP_CODE}')" src="${followSrc}">`;

  $('#modal-addtocart .addcart-img-container').html(product_showcase);
  $('#modal-addtocart .product-name').html(product_name);
  $('#modal-addtocart .product-price').html('Rp ' + product_price);
  $('#modal-addtocart .prod-details .col-11').html(product_desc);
}

function hideAddToCart() {
  console.log('hideadadad')
  $('#modal-addtocart').modal('hide');
  // if (window.Android) {
  //   window.Android.setIsProductModalOpen(false);
  // }
  // if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
  //   window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
  //     param1: false
  //   });
  // }
}

function pauseAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("stop pause ended");
    $(this).on("stop pause ended", function (e) {
      $(this).closest(".carousel").carousel();
    });
    if (!isPaused) {
      $(this).get(0).pause();
    }
  });
}

function playAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    // pause carousel when video is playing
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
}

function playModalVideo() {
  $('#modal-addtocart .addcart-img-container video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    if (isPaused) {
      $(this).get(0).play();
      let $videoPlayButton = $(this).parent().find(".video-play");
      $videoPlayButton.addClass("d-none");
    }
  })
}

function changeItemQuantity(id, mod) {
  if (mod == "add") {
    document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
  } else {
    if (document.getElementById(id).value > 1) {
      document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
    }
  }
}

let product_id = "";

function checkButtonPos() {
  let elem = document.querySelector('.prod-addtocart');
  let bounding = elem.getBoundingClientRect();

  if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
    console.log('out')
    elem.style.bottom = elem.offsetHeight + 20 + 'px';
  } else {
    elem.style.bottom = '25px';
  }
}

function addToCartModal() {
  /* start handle detail product popup */
  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));

  // let product_id = "";

  let init = parseInt(fixedPos.replace('px', ''));

  // $('#modal-addtocart .modal-dialog').draggable({
  //   handle: ".modal-content",
  //   containment: "body",
  //   drag: function (event, ui) {

  //     // Keep the left edge of the element
  //     // at least 100 pixels from the container  

  //     if (ui.position.top < init) {
  //       ui.position.top = init;
  //     }

  //     let dialog = ui.position.top + window.innerHeight;
  //     if (dialog - window.innerHeight > 150) {
  //       $('#modal-addtocart').modal('hide');
  //     }
  //   }
  // })


  $('#modal-addtocart').on('shown.bs.modal', function () {
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    // checkButtonPos();
    pauseAllVideo();
    playModalVideo();
    $('.timeline-image .carousel').each(function () {
      $(this).carousel('pause');
      console.log('pause');
    })

    // if (window.Android) {
    //   window.Android.setIsProductModalOpen(true);
    // }

    // if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
    //   window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
    //     param1: true
    //   });
    // }
  })

  $('.product-row .timeline-main').click(function () {
    // console.log('init: ' + init);
    $('#modal-addtocart .modal-dialog').css('top', '55px');
    $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#modal-addtocart').on('hide.bs.modal', function () {
    $('#modal-addtocart #modal-add-body').html('');
    $('#modal-addtocart').removeClass('d-flex justify-content-center');
  })

  $('#modal-addtocart').on('hidden.bs.modal', function () {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    countVideoPlaying = 0;
    // let modalVideo = $('#modal-addtocart').find('video');

    // if (modalVideo.length > 0) {

    //   $('#modal-addtocart .modal-body video').get(0).pause();
    // }
    checkVideoViewport();

    // if (window.Android) {
    //   window.Android.setIsProductModalOpen(false);
    // }
    // if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
    //   window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
    //     param1: false
    //   });
    // }
  })

  /* end handle detail product popup */
}

function eraseQuery() {
  $("#delete-query").click(function () {
    $('#searchFilterForm-a input#query').val('');
    $('#delete-query').addClass('d-none');
    searchFilter();
  })

  $('#searchFilterForm-a input#query').keyup(function () {

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })
}

function resetSearch() {
  $('#searchFilterForm-a input#query').val('');
}

let busy = false;
let limit = 10;
let offset = 0;
let time = new Date().getTime();
let seed = JSON.parse(JSON.stringify(time));
let isCalled = false;

function getMaxProducts(param) {
  isCalled = true;
  return new Promise(function (resolve, reject) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // console.log(xmlHttp.responseText);
        resolve(xmlHttp.responseText);
      }
    }
    xmlHttp.open("get", "/gaspol_web/logics/get_max_products" + param);
    xmlHttp.send();
  });
}

function checkDupes() {
  var nodes = document.querySelectorAll('#pbr-timeline>*');
  var ids = {};
  var totalNodes = nodes.length;

  for (var i = 0; i < totalNodes; i++) {
    var currentId = nodes[i].id ? nodes[i].id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }
}

async function displayRecords(par, lim, off) {
  // let queryStr = window.location.search;

  let params = '';

  if (par.length > 0) {
    let searchQuery = par.substr(1).split("&");

    let limitIdx = searchQuery.findIndex(x => x.includes('limit'));
    let offsetIdx = searchQuery.findIndex(x => x.includes('offset'));
    if (limitIdx > -1) {
      searchQuery[limitIdx] = 'limit=' + lim;
    } else {
      searchQuery.push('limit=' + lim);
    }
    if (offsetIdx > -1) {
      searchQuery[offsetIdx] = 'offset=' + off;
    } else {
      searchQuery.push('offset=' + off);
    }
    params = searchQuery.join('&');
  } else {
    params = 'limit=' + lim + '&offset=' + off;
  }

  let url = 'timeline_products.php';

  params += '&seed=' + seed;

  // console.log('scroll:' + url);
  $.ajax({
    type: "GET",
    url: url,
    data: params,
    beforeSend: function () {
      $("#loader_message").html("").hide();
      $('#loader_image').show();
    },
    success: function (html) {
      if (off > 0 && html.includes('Tidak ada produk')) {

      } else {
        $("#pbr-timeline")
          .append(html)
          .ready(function () {
            hasStoreId();
            checkVideoViewport();
            checkVideoCarousel();
            hideProdDesc();
            toggleProdDesc();
            // checkcarousel();
            // toggleVideoMute();
            videoReplayOnEnd();
            playVid();
            addToCartModal();
          });
      }
      $('#loader_image').hide();
      if (html == "") {
        $("#loader_message").html('').show();
      } else {
        $("#loader_message").html('').show();
      }
      if ($('.product-row').length > 0) {
        $('#product-null').addClass('d-none');
      }
      busy = false;
      // console.log('busy: ' + busy);
    }
  });
}

function openNotifs() {
  let f_pin = '';

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  window.location.href = 'notifications.php?f_pin=' + f_pin;
}

// SHOW PRODUCT FUNCTIONS
function getProductThumbs(product_code, is_product) {
  let formData = new FormData();
  formData.append("product_id", product_code);
  formData.append("is_product", is_product);

  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/gaspol_web/logics/get_product_thumbs");

    xhr.onload = function () {
      if (this.status >= 200 && this.status < 300) {
        resolve({
          thumb_id: JSON.parse(xhr.response).THUMB_ID,
          name: JSON.parse(xhr.response).NAME,
          description: JSON.parse(xhr.response).DESCRIPTION
        });
      } else {
        reject({
          status: this.status,
          statusText: xhr.statusText
        });
      }
    };
    xhr.onerror = function () {
      reject({
        status: this.status,
        statusText: xhr.statusText
      });
    };

    xhr.send(formData);
  });
}

let video_arr = ['webm', 'mp4'];
let img_arr = ['png', 'jpg', 'webp', 'gif'];

// class ShowProduct {

//   constructor(async_result) {

//     let thumbs = async_result.thumb_id.split('|');

//     let content = '';

//     if (thumbs.length == 1) {
//       // let type = ext(thumbs[0]);

//       let ph1 = thumbs[0].substr(1 + thumbs[0].lastIndexOf("/")).split('?')[0];
//       let ph2 = ph1.split('#')[0].substr(ph1.lastIndexOf(".") + 1);

//       if (video_arr.includes(ph2)) {
//         content = `
//                   <video class="d-block w-100" autoplay muted>
//                   <source src="${thumbs[0]}" type="video/${type}">
//                   </video>
//               `;
//       } else if (img_arr.includes(ph2)) {
//         content = `
//                   <img src="${thumbs[0]}" class="d-block w-100">
//               `;
//       }
//     } else {
//       content = `
//           <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
//           <div class="carousel-inner">
//           `;

//       thumbs.forEach((th, idx) => {
//         content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

//         // let type = ext(th);

//         let ph1 = th.substr(1 + th.lastIndexOf("/")).split('?')[0];
//         let ph2 = ph1.split('#')[0].substr(ph1.lastIndexOf(".") + 1);

//         if (video_arr.includes(ph2)) {
//           content += `
//                   <video class="d-block w-100" autoplay muted>
//                   <source src="${th.substr(0,4) == "http" ? th : '/gaspol_web/images/' + th}" type="video/${type}">
//                   </video>
//               `;
//         } else if (img_arr.includes(ph2)) {
//           content += `
//                   <img src="${th.substr(0,4) == "http" ? th : '/gaspol_web/images/' + th}" class="d-block w-100">
//               `;
//         }

//         content += `</div>`;
//       })

//       content += `
//           </div>
//           <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
//                   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//                   <span class="visually-hidden">Previous</span>
//               </button>
//               <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
//                   <span class="carousel-control-next-icon" aria-hidden="true"></span>
//                   <span class="visually-hidden">Next</span>
//               </button>
//           </div>
//           `;
//     }

//     // codes below wil only run after getProductThumbs done executing
//     this.html = content;

//     this.parent = document.body;
//     this.modal = document.querySelector('#modal-product .modal-body');
//     this.modal.innerHTML = " ";



//     this._createModal();
//   }

//   static async build(product_code, is_product, category) {
//     let async_result = await getProductThumbs(product_code, is_product);
//     if (window.Android) {
//       window.Android.setButtonTheme(category);
//     }    
//     return new ShowProduct(async_result);
//   }

//   question() {

//   }

//   _createModal() {

//     // Main text
//     this.modal.innerHTML = this.html;

//     // Let's rock
//     $('#modal-product').modal('show');
//   }

//   _destroyModal() {
//     $('#modal-product').modal('hide');
//     if (window.Android) {
//       window.Android.setButtonTheme('');
//     }  
//   }
// }

$('#modal-product').on('shown.bs.modal', function () {
  checkVideoCarousel();
  pauseAll();
})

$('#modal-product').on('hidden.bs.modal', function () {
  checkVideoCarousel();
  resumeAll();
})

// async function showProductModal(product_code, is_product, category) {

//   event.preventDefault();

//   let add = await ShowProduct.build(product_code, is_product, category);
//   // let response = await add.question();

// }


let gif_arr = [];
let gif_pos = [0, 1];

function getGIFs() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      gif_arr = JSON.parse(xmlHttp.responseText);
      let new_arr = [gif_arr[0], gif_arr[2], gif_arr[1], gif_arr[3]];
      console.log('all', new_arr);
      // let noPPOB = gif_arr.filter(gif => gif.BE_ID !== 0);
      // console.log('no ppob', noPPOB);
      drawGIFs(new_arr);
    }
  }
  xmlHttp.open("get", "/gaspol_web/logics/fetch_gifs?f_pin=" + f_pin);
  xmlHttp.send();

}

function drawGIFs(arr) {
  let lastAd = parseInt(localStorage.getItem('last_ad'));

  if (lastAd == null) {
    lastAd = 0;
  }

  let currentAd = 0;
  if (lastAd + 1 <= arr.length - 1) {
    currentAd = lastAd + 1;
  } else {
    currentAd = 0;
  }

  console.log('current ad', currentAd);

  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  localStorage.setItem('last_ad', currentAd);
  let pickGif = arr[currentAd];
  console.log(currentAd);
  let div = `
      <div id="gifs-${currentAd}" class="gifs">
        <a onclick="event.preventDefault(); goToURL('${pickGif.URL + '?f_pin=' + f_pin}');">
          <img src="/gaspol_web/assets/img/gif/${pickGif.FILENAME}">
        </a>
      </div>
    `;
  document.getElementById('gif-container').innerHTML = div;
  // randomAd(arr);
  animateAd(currentAd);
}

function goToURL(url, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = url;
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: url,
      param2: 'gif'
    });
    return;

  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = url;
  }
}

function animateAd(which) {
  console.log('which', which);
  if (which === 3 || which === 0) { // move horizontal
    if ($('#gif-container').hasClass('left')) {
      var windowHeight = $(window).width();
      var lineHeight = $('#gifs-' + which).width();
      var desiredBottom = 20;
      var newPosition = windowHeight - (lineHeight + desiredBottom);
      console.log('lh', lineHeight);
      console.log('db', desiredBottom);
      console.log('np', newPosition);
      $('#gif-container').animate({
        left: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: desiredBottom + 'px',
          left: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else if ($('#gif-container').hasClass('right')) {
      $('#gif-container').animate({
        left: '20px',
      }, 5000, function () {
        $('#gif-container').css({
          right: 'auto',
          left: '20px'
        });
        $('#gif-container').fadeOut();
      });
    }
  } else if (which === 2 || which === 1) {

    $('#gif-container').animate({
      top: '30px',
    }, 5000, function () {
      $('#gif-container').css({
        bottom: 'auto',
        top: '30px'
      });
      $('#gif-container').fadeOut();
    });
  }
}

function repositionIOS() {
  // let iOSSafari = true;
  if (iOSSafari) {
    $('#to-new-post').css('bottom', '25px');
    $('#gif-container.right').css('bottom', '100px');
    $('#gif-container.left').css('bottom', '100px');
  } else {
    $('#to-new-post').css('bottom', '75px');
    $('#gif-container.right').css('bottom', '150px');
    $('#gif-container.left').css('bottom', '150px');
  }
}

function changeLayout() {
  console.log('2131')
  let pickGrid = document.getElementById('to-grid-layout');
  let pickList = document.getElementById('to-list-layout');

  let f_pin = "";

  let urlParams = new URLSearchParams(window.location.search);

  let query = localStorage.getItem('activeQuery') !== '' && localStorage.getItem('activeQuery') !== null ? "&query=" + localStorage.getItem('activeQuery') : "";
  let filter = localStorage.getItem('activeFilter') !== '' && localStorage.getItem('activeFilter') !== null ? "&filter=" + localStorage.getItem('activeFilter') : "";
  let store_id = localStorage.getItem('activeStoreId') !== '' && localStorage.getItem('activeStoreId') !== null ? "&store_id=" + localStorage.getItem('activeStoreId') : "";

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = urlParams.get('f_pin');
  }

  function clickList() {
    if (window.Android) {
      // window.Android.isGrid("0");
    }
    console.log('query', query);
    console.log('filter', filter);
    console.log('store_id', store_id);
    localStorage.setItem("is_grid", "0");
    window.location = 'tab1-main.php?f_pin=' + f_pin + query + filter + store_id;
  }

  function clickGrid() {
    if (window.Android) {
      // window.Android.isGrid("1");
    }
    console.log('query', query);
    console.log('filter', filter);
    console.log('store_id', store_id);
    localStorage.setItem("is_grid", "1");
    window.location = 'tab3-main.php?f_pin=' + f_pin + query + filter + store_id;
  }

  if (pickGrid && pickList) {
    $('#to-grid-layout').off('click');
    $('#to-list-layout').off('click');
    $('#to-grid-layout').click(clickGrid);
    $('#to-list-layout').click(clickList);
  }
}

$(function () {


});

window.onload = (event) => {
  try {
    if (document.getElementById('gif-container') != null) {
      getGIFs();
    }
    // repositionIOS();
    displayRecords(window.location.search, limit, offset);

    getLikedProducts();
    getFollowedStores();
    getCommentedProducts();
    fetchStores();
    activeCategoryTab();
    fetchProductMap("");
    eraseQuery();
    // selectCategoryFilter();
    // updatecounter();
    changeLayout();
    if (STORE_ID != "") {
      setCurrentStore(STORE_ID);
    }

    checkCategoryCheckbox();

    $('#add-to-cart').click(function () {
      let itemQty = parseInt($('#modal-item-qty').val());
      addToCart(product_id, itemQty);
    })

    $('#modal-back').click(hideAddToCart);

    $(window).scroll(async function () {
      // make sure u give the container id of the data to be loaded in.
      if ($(window).scrollTop() + $(window).height() > $("#pbr-timeline").height() && !busy && !isCalled) {
        let maxProducts = await getMaxProducts(window.location.search);
        if (offset < maxProducts) {
          isCalled = false;
          busy = true;
          offset = limit + offset;
          $('#loader-image').removeClass('d-none');
          displayRecords(window.location.search, limit, offset);
        }
        // console.log(offset);
      }
    });

    $('#addtocart-success').on('hide.bs.modal', function () {
      // updatecounter();
    })
    // horizontalScrollPos();
    $('#toggle-filter').click(function () {
      $('#modal-categoryFilter').modal('toggle');
    })
    $('#submitCategory').click(function () {
      selectCategoryFilter();
    })
  } catch {}
};

$(window).on('unload', function () {
  $(window).scrollTop(0);
});
window.onunload = function () {
  window.scrollTo(0, 0);
}
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

function hideCategoryModal() {
  $('#modal-categoryFilter').modal('hide');
}

function buttonTheme(category, checkIOS = false) {
  console.log('cat: ' + category);
  if (window.Android) {
    window.Android.setButtonTheme(category);
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.setButtonTheme.postMessage({
      param1: category
    });
    // return;
  }
}

const pinchZoom = (imageElement) => {
  // console.log('element', imageElement);
  let imageElementScale = 1;

  let start = {};

  // Calculate distance between two fingers
  const distance = (event) => {
    return Math.hypot(event.touches[0].pageX - event.touches[1].pageX, event.touches[0].pageY - event.touches[1].pageY);
  };

  imageElement.addEventListener('touchstart', (event) => {
    // console.log('touchstart', event);
    if (event.touches.length === 2) {
      event.preventDefault(); // Prevent page scroll

      // Calculate where the fingers have started on the X and Y axis
      start.x = (event.touches[0].pageX + event.touches[1].pageX) / 2;
      start.y = (event.touches[0].pageY + event.touches[1].pageY) / 2;
      start.distance = distance(event);
    }
  });

  imageElement.addEventListener('touchmove', (event) => {
    // console.log('touchmove', event);
    if (event.touches.length === 2) {
      event.preventDefault(); // Prevent page scroll

      // Safari provides event.scale as two fingers move on the screen
      // For other browsers just calculate the scale manually
      let scale;
      if (event.scale) {
        scale = event.scale;
      } else {
        const deltaDistance = distance(event);
        scale = deltaDistance / start.distance;
      }
      imageElementScale = Math.min(Math.max(1, scale), 4);

      // Calculate how much the fingers have moved on the X and Y axis
      const deltaX = (((event.touches[0].pageX + event.touches[1].pageX) / 2) - start.x) * 2; // x2 for accelarated movement
      const deltaY = (((event.touches[0].pageY + event.touches[1].pageY) / 2) - start.y) * 2; // x2 for accelarated movement

      // Transform the image to make it grow and move with fingers
      const transform = `translate3d(${deltaX}px, ${deltaY}px, 0) scale(${imageElementScale})`;
      imageElement.style.transform = transform;
      imageElement.style.WebkitTransform = transform;
      imageElement.style.zIndex = "99999";

      let parent = imageElement.closest(".timeline-image");
      parent.style.zIndex = "99999"
    }
  });

  imageElement.addEventListener('touchend', (event) => {
    // console.log('touchend', event);
    // Reset image to it's original format
    imageElement.style.transform = "";
    imageElement.style.WebkitTransform = "";
    imageElement.style.zIndex = "";

    let parent = imageElement.closest(".timeline-image");
    parent.style.zIndex = ""
  });
}