$(document).on('click', '.detail-card .attribute-label', function () { 

    $('#detail-card-carousel').trigger("destroy.owl.carousel");

    setTimeout(function(){ 
        $("#detail-card-carousel").owlCarousel({
            loop: false,
            dots: false,
            nav: true,
            items: 5
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
    }, 400);
});

$(document).on('click', '.productItem', function () { 
    $('.detail_page_carousal').find('.card').removeClass('active');
    $(this).find('.card').addClass('active');
    var img_src = $(this).find('img').attr('src');
    $(".detail-img").find('img').attr("src", img_src);
    $(".detail-img").find('img').attr("data-zoom", img_src);
});