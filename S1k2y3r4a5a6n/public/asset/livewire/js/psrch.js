$(document).ready(function() {
    var blurTimer1; 
    $(document).on('focus', '#productsearch', function () { 
        Livewire.emit('productsuggestion');
    });
    $(document).on('blur', '#productsearch', function () { 
        blurTimer1 = setTimeout(function() {            
            Livewire.emit('unsetproductsuggestion');
        }, 500);
    });
    
    // Cancel blur timer if the input is focused again before it triggers
    $(document).on('focus', '#productsearch', function () { 
        clearTimeout(blurTimer1);
    });
});