$('#dashboard-carousel').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    autoplay: false,
    dots:false,
    responsive: {
      0: {
        items: 2
      },
      600: {
        items: 3
      },
      1000: {
        items: 5
      }
    },
  })

$('#order-list').owlCarousel({
  loop: false,
  margin: 10,
  nav: true,
  autoplay: false,
  dots:false,
  responsive: {
    0: {
      items: 2
    },
    600: {
      items: 2
    },
    1000: {
      items: 3
    }
  },
})
// view more and less


$(document).ready(function() {
  $('.accordion-header').click(function() {
      var $accordionItem = $(this).closest('.accordion-item');
      var $accordionToggle = $(this).find('.text-center');

      if ($accordionItem.hasClass('active')) {
          $accordionItem.removeClass('active');
          $accordionToggle.text('View more');
      } else {
          $('.accordion-item').removeClass('active');
          $('.accordion-header .text-center').text('View more');
          $accordionItem.addClass('active');
          $accordionToggle.text('View less');
      }
  });
});

$(document).ready(function(){
  function handleClassBasedOnScreenSize() {
      if ($(window).width() >= 768 && $(window).width() <= 1024) {
          if ($('.adasd').hasClass('active')) {
              $('.inside-cd').removeClass('d-md-block');
          }
      } else {
          $('.inside-cd').addClass('d-md-block');
      }
  }

  handleClassBasedOnScreenSize();

  $(window).resize(function() {
      handleClassBasedOnScreenSize();
  });
});
