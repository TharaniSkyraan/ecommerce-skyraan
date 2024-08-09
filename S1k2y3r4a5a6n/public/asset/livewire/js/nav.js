$(document).ready(function() {
    var blurTimer; 
    $(document).on('focus', '#query', function () { 
        Livewire.emit('suggestion');
    });
    $(document).on('blur', '#query', function () { 
        blurTimer = setTimeout(function() {            
            Livewire.emit('unsetsuggestion');
        }, 500);
    });
    
    // Cancel blur timer if the input is focused again before it triggers
    $(document).on('focus', '#query', function () { 
        clearTimeout(blurTimer);
    });
});

document.addEventListener('livewire:load', function () {    
    Livewire.on('updateCartQuantity', datas => {
         var quantity = 0;         
        $.each(datas, function(key, value){
            quantity = quantity + parseInt(value.quantity);
        });
        $('.cartCount').html(quantity);
    });
    Livewire.on('NotifySuccessToast', message => {    
        setTimeout(function(){ 
            $('#notifyMepopup').modal('hide')
        }, 1000);
    });

    Livewire.on('cartCount', (cartCount, price)  => {
        $('.sub-total').html(price);
        if(cartCount==0){$('.cardsfww').addClass('d-none');}else{$('.cardsfww').removeClass('d-none');}
    });
    
    Livewire.on('updateCart', (datas, reload)  => {
        var productsArray = {};   
        quantity = 0;         
        $.each(datas, function(key, value){
            var index = value.product_id+'-'+value.product_variant_id;
            quantity = quantity + parseInt(value.quantity);
            productsArray[index] = {product_id: value.product_id, product_variant_id: value.product_variant_id, quantity: parseInt(value.quantity)};
        });
        $('.cartCount').html(quantity);
        localStorage.setItem('cart',JSON.stringify(productsArray));
        if(reload=='login'){location.reload();}
        Livewire.emit('MyCart',productsArray);
    });
});
