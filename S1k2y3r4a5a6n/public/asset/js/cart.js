let cart_id;
$(document).ready(function(){
    $(document).on('click','#changeAddress', function()
    {
        $('.multple-address').toggle();
        Livewire.emit('colapseAddressList');
    });
});

$(document).ready(function(){
    $("#order-list").click(function(){
        $(".down-ars").toggle();
        $(".up-ars").toggle();
        Livewire.emit('colapseSummaryShow');
    });
});

$(document).on('click','.EditQuickShop', function()
{
    cart_id = $(this).closest('.PrdRow').data('cid');
    Livewire.emit('quickShop',cart_id,'','replace');

});
$(document).on('click','.ReplaceCart', function()
{
    // remove previous
    var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
    var pvid = $(this).closest('.PrdRow').attr('data-pvid');
    product_id = $(this).closest('.PrdRow').attr('data-id');
    var index = product_id+'-'+pvid;
    delete productsArray[index]; 

    //replace
    var qty = $(this).closest('.PrdRow').find('.input-qty').html();
    var variant_id = $(this).closest('.PrdRow').find('.variant_id').html();
    var index = product_id+'-'+variant_id;
    productsArray[index] = {product_id: product_id, product_variant_id: variant_id, quantity: parseInt(qty)};
    localStorage.setItem('cart',JSON.stringify(productsArray));
    Livewire.emit('ReplaceItem',cart_id,variant_id,qty);
    $('.close-btn').trigger('click');
});

$(document).ready(function(){
    
    $(document).on('click','.plus, .minus', function()
    {
         $(this).closest('.moredetail').find('.plus').toggle();
        $(this).closest('.moredetail').find('.minus').toggle();
        $(this).closest('.moredetail').find('.more-detl').toggle();
        // $('.plus, .minus').toggle();
        // $('.more-detl').toggle();
    });
});

function isNumberKey(event) {
    const input = event.target;
    const value = input.value;

    // Allow empty input
    if (value === '') return;

    // Allow valid number input
    const validNumber = /^[0-9]*\.?[0-9]*$/.test(value);
    if (!validNumber) {
        input.value = value.slice(0, -1);
        return;
    }
}
