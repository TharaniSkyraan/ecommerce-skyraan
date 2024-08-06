document.addEventListener('livewire:load', function () {        
    Livewire.on('SignupComplete', message => {
        var productsArray = JSON.parse(localStorage.getItem('cart'))??{};
        Livewire.emit('addCartinUserCart',productsArray,'signup');
        location.reload();
    });
    Livewire.on('updateSignupCart', datas => {
        var productsArray = {};            
        $.each(datas, function(key, value){
        var index = value.product_id+'-'+value.product_variant_id;
            productsArray[index] = {product_id: value.product_id, product_variant_id: value.product_variant_id, quantity: parseInt(value.quantity)};
        });
        localStorage.setItem('cart',JSON.stringify(productsArray));
        location.reload();
    });
});
$('#password').on('input', function() {

    var password = $(this).val();
    
    // Regular expressions for various criteria
    var lengthPattern = /.{8,}/;
    var capitalLetterPattern = /[A-Z]/;
    var specialCharacterPattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    
    // Check if password meets all criteria
    var isLengthValid = lengthPattern.test(password);
    var hasCapitalLetter = capitalLetterPattern.test(password);
    var hasSpecialCharacter = specialCharacterPattern.test(password);
    
    // Check password strength
    var passwordStrength = 0;
    if (isLengthValid) {
        $('.LengthValid').addClass('strong');
        passwordStrength++;
    }else{
        $('.LengthValid').removeClass('strong');
    }
    if (hasCapitalLetter) {
        $('.CapitalLetter').addClass('strong');
        passwordStrength++;
    }else{
        $('.CapitalLetter').removeClass('strong');
    }
    if (hasSpecialCharacter) {
        $('.SpecialCharacter').addClass('strong');
        passwordStrength++;
    }else{
        $('.SpecialCharacter').removeClass('strong');
    }
});

var remainingTime = '';
var interval = '';

function runTimer(time){
    // Set the initial time to 5 minutes
    remainingTime = time;
    $('.seconds-counter').show();
    $('#restnt').hide();
    interval = setInterval(otptimer, 1000); 
}
// Get the timer element
var timerEl = $('#seconds-counter');

function otptimer(){  
    // Calculate the minutes and seconds
    var minutes = Math.floor(remainingTime / 60);
    var seconds = remainingTime % 60;
    // Display the time with leading zeros
    timerEl.text(('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));

    // Subtract one second from the remaining time
    remainingTime--;

    // Stop the timer when it reaches 0
    if (remainingTime < 1) {
        // Stop the interval
        clearInterval(interval);
        timerEl.text('05:00');
        $('.seconds-counter').hide();
        $('#restnt').show();
    }
}