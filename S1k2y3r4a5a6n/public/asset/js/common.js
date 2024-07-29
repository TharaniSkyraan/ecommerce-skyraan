let product_id;
let product_variant_id;
$(document).ready(function() {
    $('#top_nav_carousel').owlCarousel({
        loop : true,
        autoplay : false,
        nav : true,
        dots:false,
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
});
$(document).ready(function(){
    $('#closeIcon').click(function(){
        $('#menuopen').offcanvas('hide');
    });
    $('#closeIcons').click(function(){
        $('#filter-menu').offcanvas('hide');
    });
    $('#menuIcon').click(function() {
        $('body').css('padding-right', '0 !important');
    });
});

$(document).ready(function() {

    cartProductQuantity();

    // $(document).on('click','.qty-btn-plus', function()
    // {
    //     var $input = $(this).closest(".qty-container").find(".input-qty");
    //     var qty = parseInt($input.text()) + 1;
    //     if($(".cartList").hasClass('price-list')){
    //     }else{
    //         $input.text(qty);
    //     }
    //     if($(this).closest(".cartList").html()!=undefined){
    //         var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    //         var product_id = $(this).closest('.PrdRow').data('id');
    //         var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
    //         var index = product_id+'-'+variant_id;
    //         productsArray[index].quantity = parseInt(qty);
            
    //         var newProductsArray = {};
    //         newProductsArray[index] = productsArray[index];
    //         for (var key in productsArray) {
    //             if (key !== index) {
    //                 newProductsArray[key] = productsArray[key];
    //             }
    //         }
    //         localStorage.setItem('cart',JSON.stringify(newProductsArray));
    //         cartProductQuantity();
    //     }
    // });

    // $(document).on('click','.qty-btn-minus', function()
    // {
    //     var $input = $(this).closest(".qty-container").find(".input-qty");
    //     var newqty = parseInt($input.text()) - 1;
    //     var qty = parseInt($input.text());
    //     if (qty > 1) {
    //         var qty = newqty;
    //         if($(".cartList").hasClass('price-list')){
    //         }else{
    //             $input.text(qty);
    //         }
    //     }

    //     if($(this).closest(".cartList").html()!=undefined){

    //         var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    //         var product_id = $(this).closest('.PrdRow').data('id');
    //         var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
    //         var index = product_id+'-'+variant_id;
    //         productsArray[index].quantity = parseInt(qty);
    //         // console.log(productsArray[index]);
    //         var newProductsArray = {};
    //         newProductsArray[index] = productsArray[index];
    //         for (var key in productsArray) {
    //             if (key !== index) {
    //                 newProductsArray[key] = productsArray[key];
    //             }
    //         }
    //         localStorage.setItem('cart',JSON.stringify(newProductsArray));
    //         cartProductQuantity();
    //     }
    // });

});

// navbar sticky
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    const scrollPosition = window.scrollY;

    if (scrollPosition > 50) {
        navbar.classList.add('fixed-top');
    } else {
        navbar.classList.remove('fixed-top');
    }
});

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('#top-menu-res');
    const scrollPosition = window.scrollY;

    if (scrollPosition > 50) {
        navbar.classList.add('fixed-top');
    } else {
        navbar.classList.remove('fixed-top');
    }
});

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.static-icons');
    navbar.classList.add('fixed-bottom');
});

$(document).on('click','.AddCart', function()
{
    if (navigator.onLine) {
        $('.nointernet').html('');
        $('.dffe, #offcanvasRight').removeClass('d-none');
        var productsArray = JSON.parse(localStorage.getItem('cart'))??{};    
        var product_id = $(this).closest('.PrdRow').attr('data-id');
        var qty = $(this).closest('.PrdRow').find('.input-qty').html();
        
        if($(this).closest('.PrdRow').find('.input-qty').html()==undefined){
            var qty = $(this).closest('.PrdRow').data('quantity');
        }
        if($(this).closest('.PrdRow').find('.variant_id').html()!=undefined){
            var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
            $('.close-btn').trigger('click');
        }else{
            var variant_id = $(this).closest('.PrdRow').data('variant-id');
        }
        var index = product_id+'-'+variant_id;
        if (productsArray.hasOwnProperty(index)) {
            productsArray[index].quantity = (parseInt(productsArray[index].quantity) + parseInt(qty));
        }else{
            productsArray[index] = {product_id: product_id, product_variant_id: variant_id, quantity: parseInt(qty)};
        }
        var newProductsArray = {};
        newProductsArray[index] = productsArray[index];
        for (var key in productsArray) {
            if (key !== index) {
                newProductsArray[key] = productsArray[key];
            }
        }
        localStorage.setItem('cart',JSON.stringify(newProductsArray));
        Livewire.emit('MyCart',newProductsArray);
        updateRelatedCaurosel();
        cartProductQuantity();

    } else {
        $('.dffe, #offcanvasRight').addClass('d-none');
        $('#offcanvasRight').find('.btn-close').trigger('click');
        $('.nointernet').html('You are offline. Please check your internet connection.');
        return false;
    }
});
    
$(document).on('click','.deleteCart', function()
{
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    var product_id = $(this).closest('.PrdRow').data('id');
    var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
    var index = product_id+'-'+variant_id;
    delete productsArray[index];    
    localStorage.setItem('cart',JSON.stringify(productsArray));
    Livewire.emit('RemoveProductFromCart',index);
    Livewire.emit('MyCart',productsArray);
    updateRelatedCaurosel()
    cartProductQuantity();
    if(!($(".product-list").hasClass('cartpage'))){
        $(this).closest('.PrdRow').remove();
    }
});

function cartProductQuantity(){
    
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    var quantity = 0;
    $.each(productsArray, function(key, value){
        quantity = quantity + productsArray[key].quantity;
    });
    $('.cartCount').html(quantity);
    
    Livewire.emit('cartQuantityUpdate',quantity);
    
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    
    if($(".product-list").hasClass('cartpage')){
        Livewire.emit('addCartinUserCart',productsArray,'cartpage');
    }else{
        var price = 0;
        $(".cartList").each(function() {
            product_price = $(this).find('.product-price').html();
            quantity = $(this).find('.input-qty').html();
            price = price + (parseFloat(product_price)*parseFloat(quantity));
        });
        $('.sub-total').html(price);
        Livewire.emit('addCartinUserCart',productsArray);
    }
}

$(document).on('click','.QuickShop', function()
{
    if (navigator.onLine) {
        $('.nointernet').html('');
        $('.dffe').removeClass('d-none');
        var variant_id = $(this).closest('.PrdRow').data('id');
        variant_id = $(this).closest('.PrdRow').data('variant-id');
    
        if(variant_id==undefined){
            variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
        }
        if(variant_id!=product_variant_id)
        {
            product_variant_id = variant_id;
            product_id = $(this).closest('.PrdRow').data('id');
            Livewire.emit('quickShop',product_id,product_variant_id);
        }
    } else {
        $('.dffe').addClass('d-none');
        $('.nointernet').html('You are offline. Please check your internet connection.');
    }

});

$(document).on('click', '.attribute-label', function () { 
    var id = $(this).attr('id');
    var previous_id = $(this).closest('.price').find('.active').attr('id');
    $(this).closest('.price').find('.attribute-label').removeClass('active');
    $(this).addClass('active');
    $(this).closest('.price').find('#selected_attributes_set_ids'+previous_id).trigger('click');
    $(this).closest('.price').find('#selected_attributes_set_ids'+id).trigger('click');
});
$(document).on('click','.cartGo', function()
{
    
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};

    Livewire.emit('MyCart',productsArray);

    updateRelatedCaurosel();
    
});

function updateRelatedCaurosel(){

    $('#related-items-cart').trigger("destroy.owl.carousel");

    setTimeout(function(){ 
        $('#related-items-cart').owlCarousel({
            loop:true,
            nav:true,
            dots:false,
            responsive:{
                0:{
                    items:3
                },
                600:{
                    items:3
                },
                1000:{
                    items:3
                }
            }
        });
    }, 500);
}

$(document).on('click','.NotifyMe', function()
{

    product_id = $(this).closest('.PrdRow').data('id');
    variant_id = $(this).closest('.PrdRow').data('variant-id');
    
    if(variant_id==undefined){
        variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
    }
    $('.modal').modal('hide');
    Livewire.emit('NotifyProduct',product_id,variant_id);
    $('#notifyMepopup').modal('show');
});
$(document).on('click','.view-password', function(){

    var data = $(this).attr('data-value');
    if(data=='show'){
        $(this).text('Hide').attr('data-value','hide');
        $('.password').attr('type','text');
    }else{
        $(this).text('Show').attr('data-value','show');
        $('.password').attr('type','password');
    }
});

// scroll to top
$(function(){
	var offset = 200;
	var duration = 100;
	$(window).scroll(function() {
		if ($(this).scrollTop() > offset) {
			$('#scroll-to-top').fadeIn(duration);
		} else {
			$('#scroll-to-top').fadeOut(duration);
		}
	});
	
	$('#scroll-to-top').click(function(event) {
		event.preventDefault();
		$('html, body').animate({scrollTop: 0}, duration);
		return false;
	});
});

$(document).ready(function(){
    $('.after-login').click(function(event){
        $('.box').toggle();
        event.stopPropagation();
    });
    $(document).click(function(event) {
        if (!$(event.target).closest('.box').length && !$(event.target).hasClass('after-login')) {
            $('.box').hide();
        }
    });
});

// toaster
$(document).ready(function() {
    $('#resetPasswordBtn').click(function() {
        // Assuming 'data' contains the response from the server with a 'message' property
        var data = {
            message: 'Password reset successfully!'
        };

        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": true,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }

        toastr['success'](data.message, {
            closeButton: true,
            positionClass: 'toast-top-right',
            progressBar: true,
            newestOnTop: true
        });
    });
});

// footer

$(document).ready(function(){
    $("#for-drpt1").click(function(){
        $(".fortr-drpt1").toggle();
        $("#for-drpt1 img:first-child").toggle();
        $("#for-drpt1 img:last-child").toggle();
    });
});

$(document).ready(function(){
    $("#for-drpt2").click(function(){
        $(".fortr-drpt2").toggle();
        $("#for-drpt2 img:first-child").toggle();
        $("#for-drpt2 img:last-child").toggle();
    });
});

$(document).ready(function() {
    var owl = $('#aboutus-carousal');

    owl.owlCarousel({
        loop: true,
        autoplay: false,
        nav: false, // Hide default navigation
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    $('#nextBtn').click(function() {
        owl.trigger('next.owl.carousel');
    });

    $('#prevBtn').click(function() {
        owl.trigger('prev.owl.carousel');
    });
});

$(document).ready(function() {
    var owl = $('#aboutus-carousals');

    owl.owlCarousel({
        loop: true,
        autoplay: false,
        nav: false, // Hide default navigation
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 1
            }
        }
    });

    $('#nextBtns').click(function() {
        owl.trigger('next.owl.carousel');
    });

    $('#prevBtns').click(function() {
        owl.trigger('prev.owl.carousel');
    });
});

// location

$(".explore_loc").on('click', function(){
    window.location.href  = 'https://www.google.com/maps?ll=11.055055,76.995125&z=14&t=m&hl=en&gl=IN&mapclient=embed&cid=7794558616518029306';
});
// about-us

$('#about-us').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    autoplay: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      1000: {
        items: 2
      }
    },
  })

  
// mobile screen category collapse

document.addEventListener('DOMContentLoaded', () => {
    const mainItems = document.querySelectorAll('.main-item');

    mainItems.forEach(mainItem => {
        const subList = mainItem.querySelector('.sub-list');
        const toggleSymbol = mainItem.querySelector('.toggle-symbol');

        mainItem.addEventListener('click', () => {
            if (subList.style.display === 'none' || subList.style.display === '') {
                subList.style.display = 'block';
                toggleSymbol.textContent = '-';
            } else {
                subList.style.display = 'none';
                toggleSymbol.textContent = '+';
            }
        });
    });
});

$(document).on('click','.addwishlist', function()
{
    if (navigator.onLine) {
        // $('.nointernet').html('');
    }else{
        alert('You are offline. Please check your internet connection.');
    }
});

$(document).ready(function(){
    $("#dropdown").hover(
        function() {
            $(".dropdown-menu").show();
        },
        function() {
            $(".dropdown-menu").hide();
        }
    );
});


$(document).ready(function(){
    $(".filter-select").click(function(){
        $(".filter-dropdown").toggle();
    });
});