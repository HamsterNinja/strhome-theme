webpackHotUpdate(0,[
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$('.main-slick').slick({
  slidesToShow: 1,
  autoplay: false,
  autoplaySpeed: 3000,
  arrows: true,
  dots: true,
  swipeToSlide: true
});
$('.projects-slick').slick({
  slidesToShow: 1,
  centerMode: true,
  centerPadding: '500px',
  autoplay: false,
  autoplaySpeed: 3000,
  arrows: true,
  dots: false,
  swipeToSlide: true,
  responsive: [{
    breakpoint: 1600,
    settings: {
      arrows: true,
      centerMode: true,
      centerPadding: '350px'
    }
  }, {
    breakpoint: 1280,
    settings: {
      arrows: false,
      centerMode: true,
      centerPadding: '200px',
      slidesToShow: 1
    }
  }]
});
$('.product-slick-for').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: true,
  asNavFor: '.product-slick-nav'
});
$('.product-slick-nav').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  asNavFor: '.product-slick-for',
  focusOnSelect: true
});

/***/ })
])