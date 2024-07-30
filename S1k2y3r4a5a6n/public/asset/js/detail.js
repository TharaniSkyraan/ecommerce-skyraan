$(document).ready(function ($) {
    $("#detail-card-carousel").owlCarousel({
      loop: false,
      dots: false,
      nav: true,
      items: 4
    });
    var owl = $("#detail-card-carousel");
    owl.owlCarousel();
    $(".next-btn").click(function () {
      owl.trigger("next.owl.carousel");
    });
    $(".prev-btn").click(function () {
      owl.trigger("prev.owl.carousel");
    });
    $(".prev-btn").addClass("disabled");
    $(owl).on("translated.owl.carousel", function (event) {
      if ($(".owl-prev").hasClass("disabled")) {
        $(".prev-btn").addClass("disabled");
      } else {
        $(".prev-btn").removeClass("disabled");
      }
      if ($(".owl-next").hasClass("disabled")) {
        $(".next-btn").addClass("disabled");
      } else {
        $(".next-btn").removeClass("disabled");
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

$(document).ready(function() {
  $('.star').click(function() {
      var rating = $(this).data('rating');

      $('.star path').each(function(index) {
          if (index < rating) {
              $(this).addClass('active');
          } else {
              $(this).removeClass('active');
          }
      });
  });
});


var demoTrigger = document.querySelector('.demo-trigger');
var paneContainer = document.querySelector('.zoom-in');

// Check if the screen width is less than or equal to a certain value (e.g., 768 pixels for tablets)
if (window.innerWidth > 768) {
    new Drift(demoTrigger, {
        paneContainer: paneContainer,
        inlinePane: false,
    });
}

$('#related-images').owlCarousel({
  loop: false,
  margin: 10,
  nav: true,
  autoplay: false,
  responsive: {
    0:{
      items: 1,
      stagePadding: 55
    },
    400:{
        items: 1,
        stagePadding: 70 
    },
    500:{
        items: 1,
        stagePadding: 85 
    },
    600:{
        items: 3,
        stagePadding: 0 
    },
    1000:{
        items: 4,
        stagePadding: 0 
    }
  },
});


$('#frequent-images').owlCarousel({
  loop: false,
  margin: 10,
  nav: true,
  autoplay: false,
  responsive: {
    0:{
      items: 1,
      stagePadding: 55
    },
    400:{
        items: 1,
        stagePadding: 70 
    },
    500:{
        items: 1,
        stagePadding: 85 
    },
    600:{
        items: 3,
        stagePadding: 0 
    },
    1000:{
        items: 4,
        stagePadding: 0 
    }
  },
});

// hover img

$('.detail-img .demo-trigger').hover(function() {
  $('.zoom-in').addClass('hovered').css('height', '80%');
}, function() {
  $('.zoom-in').removeClass('hovered').css('height', '0%'); 
});


document.addEventListener('DOMContentLoaded', function() {
  const radioButtons = document.querySelectorAll('.form-check-input');

  radioButtons.forEach(function(radio) {
      radio.addEventListener('change', function() {
          document.querySelectorAll('.selectAddress').forEach(function(div) {
              div.classList.remove('selected');
          });

          if (radio.checked) {
              radio.closest('.selectAddress').classList.add('selected');
          }
      });
  });
});

  $(document).on('click','.hover-btn', function(){
      $(".location-card").toggle();
  });

function isNumberKey(event) {
  const input = event.target;
  const value = input.value;

  // Allow empty input
  if (value === '') return;

  // Allow valid number input
  const validNumber = /^[0-9]*\.?[0-9]*$/.test(value);
  if (!validNumber) {
      input.value = value.slice(0, -1);
      return;
  }
}
