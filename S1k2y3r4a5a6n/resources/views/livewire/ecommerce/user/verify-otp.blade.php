
<div>
    <div class="text-center">
        <h4 class="price">{{ $siteSetting->site_name }}</h4>
    </div>
    <div class="text-center">
        <div class="loader p-4" wire:loading></div>
    </div>
    <div wire:loading.remove>
        <div class="text-center py-3">
            <h5 class="">Enter OTP</h5>
        </div>
        <div class="text-center">
            <h6 class="text-secondary fw-normal opacity-75 h-sms">Please enter OTP that sent to</h6>
        </div>
        <div class="d-flex justify-content-center align-items-center gap-2 py-2">
            <h6 class="h-sms">{{ $phone }}</h6>
            <input type="hidden" id="verified_status" value="{{$verified_status}}">
            <h6 class="text-danger h-sms fw-normal cursor changeNumber" data-bs-toggle="modal" data-bs-target="#signup">change</h6>
        </div>
        <div class="otp-container pb-3 digit-group" data-group-name="digits">
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp0" data-index="0" onkeypress="return /[0-9]/i.test(event.key)"/>
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp1" data-index="1" onkeypress="return /[0-9]/i.test(event.key)"/>
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp2" data-index="2" onkeypress="return /[0-9]/i.test(event.key)"/>
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp3" data-index="3" onkeypress="return /[0-9]/i.test(event.key)"/>
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp4" data-index="4" onkeypress="return /[0-9]/i.test(event.key)"/>
            <input type="text" class="otp-input border-0 rounded-0" maxlength="1" id="otp5" data-index="5" onkeypress="return /[0-9]/i.test(event.key)"/>
        </div>
        @if($error)<span class="error">{{$error}}</span>@endif
        <div class="d-flex justify-content-end align-items-center gap-2 py-2">
            <h6 class="text-secondary fw-normal opacity-75">Not received code yet?</h6>
            <span class="seconds-counter1">
                <span id="remainingTime" class="d-none">{{$remaining_time}}</span>
                <span class="restnt text-primary" id="restnt" style="display:none;"> <a href="javascript:;" wire:click="ReSendOtp">Resend</a></span>
                <h6 class="restnt seconds-counter" id="seconds-counter" style="display:none;">00:00</h6>
            </span>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).on('click', '.changeNumber', function () { 
    $('.otp-input').val('');
    $('#seconds-counter').text('00:00');
    clearInterval(interval);
});
$(document).on('click', '#restnt', function () { 
    $('.otp-input').val('');
    clearInterval(interval);
})
const inputs = document.querySelectorAll(".digit-group input");
let otp = '';

inputs.forEach((input, index) => {

    input.dataset.index = index;
    input.addEventListener("keyup", handleOtp);
    input.addEventListener("paste", handleOnPasteOtp);
});

function handleOtp(e) {

    let cotp = '';
    inputs.forEach((input) => {cotp += input.value;});
    /**
     * <input type="text" 👉 maxlength="1" />
     * 👉 NOTE: On mobile devices `maxlength` property isn't supported,
     * So we to write our own logic to make it work. 🙂
     */
    const input = e.target;
    let fieldIndex = input.dataset.index;
    let value = e.key;
    let value1 = input.value;
    let isValidInput = value.match(/^[0-9]*$/);
    let isValidInput1 = value1.match(/^[0-9]*$/);
    value = isValidInput ? value : (isValidInput1 ? input.value : "");
    // let isValidInput = true;
    $(this).val(value);
    if (fieldIndex < inputs.length - 1 && isValidInput) {
        input.nextElementSibling.focus();
    }
    
    if (e.key === "Backspace" && fieldIndex > 0 && (otp.length == cotp.length)) {
        input.previousElementSibling.focus();
    }

    otp = cotp; 
    if (otp.length == inputs.length) {
        @this.set('otp', otp);
        setTimeout(function(){ 
            if($('#verified_status').val()=='success'){
                $('.changeNumber').trigger('click');
            }
        }, 300);
    }
}

function handleOnPasteOtp(e) {
    const data = e.clipboardData.getData("text");
    const value = data.split("");
    let cotp = '';
    inputs.forEach((input, index) => (input.value = value[index]??''));
    inputs.forEach((input) => {cotp += input.value;});
    otp = cotp;
    if (value.length === inputs.length) {
        @this.set('otp', otp);
        setTimeout(function(){ 
            if($('#verified_status').val()=='success'){
                $('.changeNumber').trigger('click');
            }
        }, 300);
    }
}

document.addEventListener('livewire:load', function () {        
    Livewire.on('CalculateTimer', time => {
        runTimer(time);
    });
});

</script>
@endpush