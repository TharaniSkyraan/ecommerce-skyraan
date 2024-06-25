document.addEventListener('livewire:load', function () {        
    Livewire.on('appliedCouponSuccessToast', message => {
        $('#coupenapplied').modal('show');            
        setTimeout(function(){ 
            $('#coupenapplied').modal('hide')
        }, 3000);
    });    
});
$(document).on('click','#availableCoupon', function()
{
    var price = $('#total_price').val();
    Livewire.emit('availableCoupon',price);
});