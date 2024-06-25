
  $(document).ready(function() {
 
    $('#owl-example').owlCarousel({
    loop : true,
    autoplay : false,
    slideSpeed : 500,
    nav : true,
    dots:false,
    singleItem: true,
      responsive:{
        0:{
        items:1
        },
        600:{
        items:1
        },
        1000:{
        items:1
        }
      }
    });

    var owl = $('#home_first_carousel');
    owl.owlCarousel({
        loop: false,
        autoplay: false,
        nav: true,
        dots: true,
        responsive: {
            0:{
                items: 1
            },
            600:{
                items: 2
            },
            1000:{
                items: 3
            }
        }
    });
    
    var owl = $('#card_and_carousal');
    owl.owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        responsive: {
            0:{
                items: 1
            },
            600:{
                items: 2
            },
            1000:{
                items: 3
            }
        }
    });

    var owl = $('#why-choose');
    owl.owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        responsive: {
            0:{
                items: 1
            },
            600:{
                items: 1
            },
            1000:{
                items: 1
            }
        }
    });
        
    var owl = $('#abt_review');
    owl.owlCarousel({
        loop: true,
        nav: true,
        dots: true,
        autoplay:true,
        responsive: {
            0:{
                items: 1
            },
            700:{
                items: 2
            },

            500:{
                items: 1
            },
            1000:{
                items: 3
            }
        }
    });
    var owl = $('#why-choosnne');
    owl.owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        responsive: {
            0:{
                items: 1
            }
        }
    });

    $(document).on('click','.likedislike', function()
    {
        if($(this).attr('data-id')=='unlike'){
            $(this).attr('src', '../../asset/home/like.svg');
            $(this).attr('data-id','like');
        }else{
            $(this).attr('src', '../../asset/home/like-filled.svg');
            $(this).attr('data-id','unlike');
        }
    });
});

