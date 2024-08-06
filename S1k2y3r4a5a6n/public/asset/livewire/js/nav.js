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

});
