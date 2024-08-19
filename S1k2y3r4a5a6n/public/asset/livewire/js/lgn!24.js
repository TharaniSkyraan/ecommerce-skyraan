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

$(document).on('click','.submitbutton', function()
{
    if (navigator.onLine) {
        $('.nointernet').html('');
    }else{
        $('.nointernet').html('You are offline. Please check your internet connection.');
    }
});