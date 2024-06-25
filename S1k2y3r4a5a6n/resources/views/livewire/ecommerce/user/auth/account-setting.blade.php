<div>
    <section class="userdashboard">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 select-li ps-xl-3 ps-lg-3 ps-md-3 ps-sm-0 ps-0 pe-xl-3 pe-lg-3 pe-md-3 pe-sm-0 pe-0 pb-3" wire:ignore>
                    @include('ecommerce.user.auth.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 order-li pb-3">
                    <div class="card p-xl-4 p-lg-4 p-md-4 p-sm-3 p-2 billing">
                        <h5 class="fw-bold">Account Settings</h5>
                        <hr>
                        <div class="showable">
                            <div>
                                <h6 class=" pb-3">Name</h6>
                            </div>
                            <div class="pb-3">
                                <input type="text" class="form-control texy-secondary  @if(!$is_edit) opacity-50 @endif rounded-1" placeholder="" wire:model="name" @if(!$is_edit) readonly @endif>
                                @error('name') <span class="error">{{$message}}</span> @endif
                            </div>
                            <div class="row">
                                <div class=" col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <h6 class="text-start  pb-1">Email</h6>
                                    <div class="pt-1 pb-3">
                                        <input type="email" class="form-control texy-secondary @if(!$is_edit) opacity-50 @endif rounded-1" placeholder="arunkumar@gmail.com" wire:model="email" @if(!$is_edit) readonly @endif>
                                        @error('email') <span class="error"> {{$message}}</span> @endif
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                    <h6 class="text-start  pb-1">Mobile</h6> 
                                    <div class="d-flex">
                                        <span class="dial_code @if(!$is_edit) opacity-50 @endif ">+91</span>
                                        <input type="text" class="form-control @if(!$is_edit) opacity-50 @endif  phonenumber_dial_code" placeholder="Phone number" wire:model="phone" @if(!$is_edit) readonly @endif>
                                    </div>
                                    @if($phone_validate) <div class="get_otp"><span class="cursor" data-bs-toggle="modal" data-bs-target="#numberverify" wire:click="verify_otp" class="cursor">Verify Otp</span></div> @endif 
                                    @if($verified_status=='verified') 
                                    <div class="phone_number_verified d-flex align-items-center justify-content-end gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 20 20">
                                            <defs>
                                                <clipPath id="clip-path1">
                                                <rect id="Rectangle_12179" data-name="Rectangle 12179" width="20" height="20"/>
                                                </clipPath>
                                            </defs>
                                            <g id="Mask_Group_23248" data-name="Mask Group 23248" clip-path1="url(#clip-path1)">
                                                <path id="Subtraction_4" data-name="Subtraction 4" d="M-6838,1502a10.011,10.011,0,0,1-10-10,10.012,10.012,0,0,1,10-10,10.011,10.011,0,0,1,10,10A10.01,10.01,0,0,1-6838,1502Zm-3.824-10.882-1.177,1.177,2.353,2.353,1.177,1.177,1.177-1.177,5.293-5.3-1.177-1.176-5.293,5.293Z" transform="translate(6848 -1482)" fill="#e4e4e4"/>
                                            </g>
                                        </svg> 
                                        <span>Verified</span>
                                    </div>
                                    @endif
                                    @error('phone') <span class="error"> {{$message}}</span> @endif
                                    @if($verified_status!='' && $verified_status!='verified') <span class="error">{{$verified_status}}</span> @endif
                                </div>
                            </div>
                            <div class="text-start pt-3">
                                <button class="btn text-white px-5 edit-info {{ ($is_edit)?'d-none':'' }}" wire:click.prevent="EditEnable">Edit info</button>
                                <button class="btn text-white px-5 save-info {{ ($is_edit)?'':'d-none' }}" wire:click.prevent="AccountUpdate">save</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@push('scripts')
<script>

    var remainingTime = '';
    var interval = '';
    $(document).on('click','.get_otp', function(){
        setTimeout(function(){ 
            runTimer();
        }, 1000); 
    });

    function runTimer(){
        // Set the initial time to 5 minutes
        remainingTime = parseInt($('#remainingTime').text());
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
</script>
@endpush