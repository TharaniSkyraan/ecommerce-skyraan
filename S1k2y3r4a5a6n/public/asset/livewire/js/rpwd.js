
$('#newpassword').on('input', function() {

    $('.err_msg').html('');
    var password = $(this).val();
    passwordCheck(password);
    
});

function passwordCheck(password){
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

    return passwordStrength;
}

function validateForm(){
    $('.err_msg').html('');
    var password = $('#newpassword').val();
    var password_confirmation = $('#password_confirmation').val();
    console.log(passwordCheck(password));
    if(passwordCheck(password)!=3){
        $('.password').html("Please Enter password with given criteria.");
        return false;
    }else if(password!=password_confirmation){
        $('.password_confirmation').html("Passwords didn’t match. Try again");
        return false;
    }else{
        return true;
    }
}
