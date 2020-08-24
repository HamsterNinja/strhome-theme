$(document).ready(function () {

  $('.who-we-are_short-links a[href^="#"]').click(function () {
    var scroll_el = $(this).attr('href');
    if ($(scroll_el).length != 0) {
      $('html, body').animate({scrollTop: $(scroll_el).offset().top}, 1000);
    }
    return false;
  });

  $('.foundation-prices-slick').slick({
    slidesToShow: 4,
    responsive: [
            {
                breakpoint: 1280,
                settings: {
                    arrows: false,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 940,
                settings: {
                    arrows: true,
                    centerMode: false,
                     slidesToShow: 2
                }
            },
            {
                breakpoint: 640,
                settings: {
                    arrows: true,
                    centerMode: false,
                     slidesToShow: 1
                }
            }
        ]
  });
  $('.question-block__button').click(function (event) {
    let text = $('.question-block__textarea').val()
    let url = `https://api.whatsapp.com/send?phone=79065590807&text=${text}`
    window.open(url, '_blank');
  });
})
