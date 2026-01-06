$(document).ready(function(){
  $('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav'
  });

  $('.slider-nav').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: false,
    centerMode: true,
    focusOnSelect: true
  });

  $('.preowned').slick({
    centerMode: true,
    centerPadding: '60px',
    slidesToShow: 3,
    arrows: true,
    dots: false,
    responsive: [
        {
            breakpoint: 992,
            settings: {
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 576,
            settings: {
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 1
            }
        }
    ]
});

$('.brand-slider.autoplay').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 0,        // continuous animation
    speed: 9000,             // scroll speed
    cssEase: 'linear',       // smooth
    infinite: true,
    arrows: false,
    dots: false,
    pauseOnHover: false,
    swipeToSlide: true,
    responsive: [
        {
            breakpoint: 992,
            settings: { slidesToShow: 4 }
        },
        {
            breakpoint: 768,
            settings: { slidesToShow: 3 }
        },
        {
            breakpoint: 576,
            settings: { slidesToShow: 2 }
        }
    ]
});



});