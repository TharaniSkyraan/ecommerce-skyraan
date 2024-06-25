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
    Livewire.on('updatedCart', datas => {
        var productsArray = {};   
        var quantity = 0;         
        $.each(datas, function(key, value){
            var index = value.product_id+'-'+value.product_variant_id;
            quantity = quantity + parseInt(value.quantity);
            productsArray[index] = {product_id: value.product_id, product_variant_id: value.product_variant_id, quantity: parseInt(value.quantity)};
        });
        $('.cartCount').html(quantity);
        localStorage.setItem('cart',JSON.stringify(productsArray));
    });
    Livewire.on('NotifySuccessToast', message => {    
        setTimeout(function(){ 
            $('#notifyMepopup').modal('hide')
        }, 1000);
    });

});
