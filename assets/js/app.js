$(function () {

  $('.toggle-button').on('click', function () {
    $('.toggle-brand').toggleClass('open');
    $('.toggle-navbar').toggleClass('open');
  })

  





  /* our blog slider */
  $('.blog-slider').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: $('.our-arrow-l'),
    nextArrow: $('.our-arrow-r'),
    dots: false,
    speed: 300,
    autoplay: true,
    fade: false,
    centerMode: true,
    centerPadding: '0px',
    responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    
                }
    },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
    },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
    }
  ]
  });
  /* our blog slider */


  /* banner slider */

  $('.banner-slider').slick({
    prevArrow: '<p class="banner-arrow-l"><span class="banner-icons "><a ><i class="fa fa-chevron-left prv-arrow"></i></a></span></p>',
    nextArrow: '<p class="banner-arrow-r"><span class="banner-icons "><a ><i class="fa fa-chevron-right nxt-arrow"></i></a></span></p>',
    dots: false,
    autoplay: true,
    speed: 1000,
    fade: true,



  })

  /* banner slider */

  /* service slider */
  $('.service-slider').slick({
    infinite: true,
    slidesToShow: 3,
    autoplay: true,
    slidesToScroll: 1,
    arrows: false,
    responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]

  });
  /* service slider */



  /* portfolio slider */
  $('.portfolio-slider').slick({
    infinite: true,
    slidesToShow: 4,
    autoplay: true,
    slidesToScroll: 1,
    prevArrow: $('.portfolio-arrow-l'),
    nextArrow: $('.portfolio-arrow-r'),
    responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,

        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
  /* portfolio slider */

  $('.portfolio-slider2').slick({
    infinite: true,
    slidesToShow: 1,
    autoplay: true,
    slidesToScroll: 1,
    arrows:false,
    

    
  
    responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,

        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
  /* testimonial slider */
  $('.testimonial-info-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.testimonial-img-slider'
  });
  $('.testimonial-img-slider').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.testimonial-info-slider',
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    centerPadding: '0px',
    prevArrow: $('.testimonial-arrow-l'),
    nextArrow: $('.testimonial-arrow-r')
  });
  /* testimonial slider */

  /* brand slider */
  $('.brand-slider').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    centerPadding: '0px',
    prevArrow: $('.brand-arrow-l'),
    nextArrow: $('.brand-arrow-r'),
    responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,

        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
  /* brand slider */

  /* our team slider */
  $('.team-slider').slick({
    infinite: true,
    slidesToShow: 3,
    autoplay: true,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: '0px',
    prevArrow: $('.our-arrow-l'),
    nextArrow: $('.our-arrow-r'),
    speed: 300,
    responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                   
                }
    },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
    },
            {
                breakpoint: 650,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
    }
  ]

  });
  /* our team slider */

  /* mobile menu js starts here */
  
  $('.toggle-btn-mobile').on('click', function () {
    $('.mobile-menu').toggleClass('active');
  }); 
  /* mobile menu js ends here */

  





/* counter up jus*/
	$('.counter_number').counterUp({
		time: 3000
	});
  /* counter up js */
 

  

  $('.buycollection').slick({
  centerMode: true,
  centerPadding: '60px',
  slidesToShow: 8,
  dots:true,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});


   
    






})





            /*fixed menu*/

  $(window).on('scroll', function(){
       if ($(window).scrollTop() > $('#bottomheader').height()){
           $('#bottomheader').addClass('fixed-menu')
       }else {
           $('#bottomheader').removeClass('fixed-menu')
       }
   }) 

   /* fixed menu */
   /* comming soon js */
    
    $('.comming-main').countdown('2023/01/02', function(event) {
		var $this = $(this);
		$this.find('#day').html(event.strftime('<span>%D</span>'));
		$this.find('#hour').html(event.strftime('<span>%H</span>'));
		$this.find('#month').html(event.strftime('<span>%M</span>'));
		$this.find('#second').html(event.strftime('<span>%S</span>'));
	});
  /* comming soon js */

   
      