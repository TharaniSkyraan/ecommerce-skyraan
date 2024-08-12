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

document.getElementById('menuIcon').addEventListener('click', function() {
    var navbar = document.getElementById('top-menu-res');
    navbar.classList.remove('fixed-top');
  });

  // Optionally, re-add the 'fixed-top' class when the offcanvas is closed
document.getElementById('menuopen').addEventListener('hidden.bs.offcanvas', function () {
    var navbar = document.getElementById('top-menu-res');
    navbar.classList.add('fixed-top');
});

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.static-icons');
    navbar.classList.add('fixed-bottom');
});


$(document).ready(function() {
    Livewire.emit('MyCart',JSON.parse(localStorage.getItem('cart'))??{});
    cartProductQuantity();
    updateRelatedCaurosel();

    $(document).on('click', '.qty-dropdown .card', function(e) {
        e.stopPropagation(); // Prevent the event from bubbling up
        $(this).closest('.qty-dropdown').find(".card-bodys").toggle();
    });

    $(document).on('click', '.qty-dropdown .qty-option', function(e) {
        e.stopPropagation(); // Prevent the event from bubbling up
        var qty = $(this).data("qty");
        $(this).closest('.qty-dropdown').find(".input-qty").text(qty);
        $(this).closest('.qty-dropdown').find(".qty-option").removeClass("selected");
        $(this).addClass("selected");
        $(this).closest('.qty-dropdown').find(".card-bodys").hide();
        
        if ($(this).closest(".cartList").html() != undefined) {
            var productsArray = JSON.parse(localStorage.getItem('cart')) ?? {};
            var product_id = $(this).closest('.PrdRow').data('id');
            var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
            var index = product_id + '-' + variant_id;
            productsArray[index].quantity = parseInt(qty);
            var newProductsArray = {};
            newProductsArray[index] = productsArray[index];
            for (var key in productsArray) {
                if (key !== index) {
                    newProductsArray[key] = productsArray[key];
                }
            }
            localStorage.setItem('cart', JSON.stringify(newProductsArray));

            cartProductQuantity();

        }
    });

    // Close .card-bodys when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.qty-dropdown').length) {
            $('.card-bodys').hide();
        }
    });
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
            // productsArray[index].quantity = (parseInt(productsArray[index].quantity) + parseInt(qty));
            productsArray[index].quantity = parseInt(qty);
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
    
    if($('.checkoutpage').hasClass("check_out_li")){
        $(this).closest('.PrdRow').remove();
        Livewire.emit('cartList');
    }else if($(".product-list").hasClass('cartpage')){
        if($(".cartList.price-list").length==1){
            $('#cartpage').hide();
            $('.product-list').removeClass('d-none');
            Livewire.emit('cartList');
        }
        $(this).closest('.PrdRow').remove();
        var price = 0;
        $(".cartList.price-list").each(function() {
            product_price = $(this).find('.product-price').html();
            quantity = $(this).find('.input-qty').html();
            price = price + (parseFloat(product_price)*parseFloat(quantity));
        });
        $('.sub-total').html(price);
    }else{
        $(this).closest('.PrdRow').remove();
        Livewire.emit('MyCart',productsArray);
        updateRelatedCaurosel()
        cartProductQuantity();
    }
});

function cartProductQuantity(){
    
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    var quantity = 0;
    $.each(productsArray, function(key, value){
        quantity = quantity + productsArray[key].quantity;
    });
    $('.cartCount').html(quantity);
    
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    if($(".product-list").hasClass('cartpage')){
        var price = 0;
        $(".cartList.price-list").each(function() {
            product_price = $(this).find('.product-price').html();
            quantity = $(this).find('.input-qty').html();
            product_subtotal_price = parseFloat(product_price)*parseFloat(quantity);
            $(this).find('.product_subtotal_price').html(product_subtotal_price);
            price = price + product_subtotal_price;
        });
        $('.sub-total').html(price);
        Livewire.emit('addCartinUserCart',productsArray,'cartpage');
    }else{
        var price = 0;
        $(".cartList").each(function() {
            product_price = $(this).find('.product-price').html();
            quantity = $(this).find('.input-qty').html();
            product_subtotal_price = parseFloat(product_price)*parseFloat(quantity);
            $(this).find('.product_subtotal_price').html(product_subtotal_price);
            price = price + product_subtotal_price;
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
    if($(this).hasClass('other-attribute')){
        Livewire.emit('updateAttributesetId', id);
    }if($(this).hasClass('other-attributes')){
        Livewire.emit('updateAttributeSetId', id);
    }
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
    $("#for-drpt2").click(function(){
        $(".fortr-drpt2").toggle();
        $("#for-drpt2 img:first-child").toggle();
        $("#for-drpt2 img:last-child").toggle();
    });
});

// location

$(".explore_loc").on('click', function(){
    window.location.href  = 'https://www.google.com/maps?ll=11.055055,76.995125&z=14&t=m&hl=en&gl=IN&mapclient=embed&cid=7794558616518029306';
});
  
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

$(document).ready(function() {
    // Toggle dropdown on click
    $(".filter-select").click(function(e) {
        e.stopPropagation(); // Prevent the click event from bubbling up
        $(".filter-dropdown").toggle();
    });

    // Close the dropdown if clicking outside of it
    $(document).click(function(e) {
        if (!$(e.target).closest('.filter-select').length && !$(e.target).closest('.filter-dropdown').length) {
            $(".filter-dropdown").hide();
        }
    });
});

$(document).on( 'click', '.prdDet', function(e) {
    var slug = $(this).find('.PrdRow').data('slug');
    var pid = $(this).find('.PrdRow').data('id');
    var pvid = $(this).find('.PrdRow').data('variant-id');
    var pref = $(this).find('.PrdRow').data('prdref');    
    if(slug != ''&&pref != ''){
        url = '/product/'+ slug +'?product_variant='+ pvid +"&prdRef="+ pref;
        openInNewTabWithNoopener(url)
    }
});

function openInNewTabWithNoopener(val) {
    const aTag = document.createElement('a');
    aTag.rel = 'noopener';
    // aTag.target = "_blank";
    aTag.href = val;
    aTag.click();
}

$(document).on('click','.like_img, .add-to-cart', function(e)
{
    e.stopPropagation();
    
});
  
// $(document).ready(function(){
// const isiOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
//     // Remove list styles for summary elements on iOS
//     if (isiOS) {
//         document.addEventListener('DOMContentLoaded', function () {
//             const summaries = document.querySelectorAll('summary');
//             summaries.forEach(function (summary) {
//                 summary.style.listStyle = 'none';
//             });
//         });
//     }
// });

// document.addEventListener('touchmove', function (event) {
//     if (event.scale !== 1) {
//         event.preventDefault();
//     }
// }, { passive: false });


// body full loader

$(document).ready(function () {
    setTimeout(function () {
        $(".loader-overlay").fadeOut("fast");
        $("#overlayer").fadeOut("fast", function () {
            $('body').removeClass('overlayScroll');
        });
    }, 1000);
});

