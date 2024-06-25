
document.addEventListener('livewire:load', function () {        
    Livewire.on('updateCart', message => {
        var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
        Livewire.emit('addCartinUserCart',productsArray,'login');
    });     
    Livewire.on('SigninComplete', datas => {
        var productsArray = {};            
        $.each(datas, function(key, value){
        var index = value.product_id+'-'+value.product_variant_id;
            productsArray[index] = {product_id: value.product_id, product_variant_id: value.product_variant_id, quantity: parseInt(value.quantity)};
        });
        localStorage.setItem('cart',JSON.stringify(productsArray));
        location.reload();
    });
});
$('#username').on('input', function() {
    var phoneNumber = $(this).val();
    var regex = /^\d+$/; // Regex for a 10-digit phone number (modify according to your requirements)
    if(regex.test(phoneNumber)) {
        if(!$('#username').hasClass('phonenumber_dial_code')){
            $('#username').addClass('phonenumber_dial_code');
            $('<span class="dial_code">+91</span>').insertBefore('#username');
        }
    } else {
        if($('#username').hasClass('phonenumber_dial_code')){
            $('#username').removeClass('phonenumber_dial_code');
            $('.dial_code').remove();
        }
    }
});