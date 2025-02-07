
$(document).ready(function() {
 
    $('#owl-example').owlCarousel({
        loop: true,
        slideSpeed: 500,
        nav: true,
        dots: false,
        singleItem: true,
        responsive: {
            0: {
                items: 1,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true
            },
            600: {
                items: 1,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true
            },
            1000: {
                items: 1
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
                items: 1,
                stagePadding: 0 
            },
            355:{
                items: 1,
                stagePadding: 30 
            },
            400:{
                items: 1,
                stagePadding: 50 
            },
            500:{
                items: 1,
                stagePadding: 80 
            },
            600:{
                items: 2,
                stagePadding: 0 
            },
            1000:{
                items: 3,
                stagePadding: 0 
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
                items: 2
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
        autoplay:false,
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

    $(document).on('click','.likedislike', function()
    {
        if($(this).attr('data-id')=='unlike'){
            $(this).attr('src', '../../asset/home/like.svg');
            $(this).attr('data-id','like');
        }else{
            $(this).attr('src', '../../asset/home/like-filled.svg');
            $(this).attr('data-id','unlike');
        }
        id = $(this).closest('.PrdRow').data('id');
        Livewire.emit('addremoveWish', id);
    });
});

    $(document).ready(function(){
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
    });
