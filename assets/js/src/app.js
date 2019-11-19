$('.main-slick').slick({
    slidesToShow: 1,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    dots: true,
    swipeToSlide: true,
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
     responsive: [
    {
      breakpoint: 1600,
      settings: {
        arrows: true,
        centerMode: true,
        centerPadding: '350px',
      }
    },
    {
      breakpoint: 1280,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '200px',
        slidesToShow: 1
      }
    }
  ]
});