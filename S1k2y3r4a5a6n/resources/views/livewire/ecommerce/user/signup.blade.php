<div class="container-fluid">
    <div class="row rfkjref">
        <div class="py-5 py-md-0 py-sm-0 py-xl-0 py-lg-0 col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12 d-flex align-items-center justify-content-center py-4 sign-in">
            <h4 class="fw-bold text-dark text-center text-white">SKYRAAN</h4>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12 py-xl-5 py-lg-5 py-sm-5 py-md-5 py-2 sign-in-bg ">
            <div class="px-xl-4 px-lg-4 px-sm-4 px-md-4 px-3">
                <h6 class="fw-bold heading">Sign up</h5>
                <div class="py-3">
                    <input type="text" class="form-control" placeholder="Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="pb-3">
                    <input type="email" class="form-control" placeholder="Email" wire:model="email">
                    @error('email') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="pb-3">
                    <div class="d-flex" wire:ignore>
                        <span class="dial_code">+91</span>
                        <input type="text" class="form-control phonenumber_dial_code" placeholder="Phone number" wire:model="phone">
                    </div>
                    @if($phone_validate) <div class="get_otp"><span class="cursor" data-bs-toggle="modal" data-bs-target="#numberverify" wire:click="verify_otp">Verify Otp</span></div> @endif 
                    @if($verified_status=='verified') 
                    <div class="phone_number_verified">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 20 20">
                            <defs>
                                <clipPath id="clip-path1">
                                <rect id="Rectangle_12179" data-name="Rectangle 12179" width="20" height="20"/>
                                </clipPath>
                            </defs>
                            <g id="Mask_Group_23248" data-name="Mask Group 23248" clip-path1="url(#clip-path1)">
                                <path id="Subtraction_4" data-name="Subtraction 4" d="M-6838,1502a10.011,10.011,0,0,1-10-10,10.012,10.012,0,0,1,10-10,10.011,10.011,0,0,1,10,10A10.01,10.01,0,0,1-6838,1502Zm-3.824-10.882-1.177,1.177,2.353,2.353,1.177,1.177,1.177-1.177,5.293-5.3-1.177-1.176-5.293,5.293Z" transform="translate(6848 -1482)" fill="#e4e4e4"/>
                            </g>
                        </svg> <span>Verified</span>
                    </div>
                    @endif
                    @error('phone') <span class="error"> {{$message}}</span> @endif
                    @if($verified_status!='' && $verified_status!='verified') <span class="error"> {{$verified_status}}</span> @endif
                </div>
                <div class="pb-2">
                    <div wire:ignore>
                        <div class="d-flex">
                            <input type="password" class="form-control password" id="password" placeholder="Password" wire:model="password">
                            <span class="view-password cursor" data-value="show">Show</span>
                        </div>
                        <div class="py-1">
                            <div class="passwordStrength LengthValid">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 20 20">
                                    <defs>
                                        <clipPath id="clip-path">
                                        <rect id="Rectangle_12178" data-name="Rectangle 12178" width="20" height="20"/>
                                        </clipPath>
                                    </defs>
                                    <g id="Mask_Group_23249" data-name="Mask Group 23249" clip-path="url(#clip-path)">
                                        <path id="Subtraction_3" data-name="Subtraction 3" d="M-6838,1502a10.011,10.011,0,0,1-10-10,10.012,10.012,0,0,1,10-10,10.011,10.011,0,0,1,10,10A10.01,10.01,0,0,1-6838,1502Zm-3.824-10.882-1.177,1.177,2.353,2.353,1.177,1.177,1.177-1.177,5.293-5.3-1.177-1.176-5.293,5.293Z" transform="translate(6848 -1482)" fill="#e4e4e4"/>
                                    </g>
                                </svg> <span class="mx-1">8 Characters</span>
                            </div> 
                            <div class="passwordStrength CapitalLetter">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 20 20">
                                    <defs>
                                        <clipPath id="clip-path">
                                        <rect id="Rectangle_12178" data-name="Rectangle 12178" width="20" height="20"/>
                                        </clipPath>
                                    </defs>
                                    <g id="Mask_Group_23249" data-name="Mask Group 23249" clip-path="url(#clip-path)">
                                        <path id="Subtraction_3" data-name="Subtraction 3" d="M-6838,1502a10.011,10.011,0,0,1-10-10,10.012,10.012,0,0,1,10-10,10.011,10.011,0,0,1,10,10A10.01,10.01,0,0,1-6838,1502Zm-3.824-10.882-1.177,1.177,2.353,2.353,1.177,1.177,1.177-1.177,5.293-5.3-1.177-1.176-5.293,5.293Z" transform="translate(6848 -1482)" fill="#e4e4e4"/>
                                    </g>
                                </svg> <span class="mx-1">1 Capital Letter</span>
                            </div> 
                            <div class="passwordStrength SpecialCharacter"> 
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 20 20">
                                    <defs>
                                        <clipPath id="clip-path">
                                        <rect id="Rectangle_12178" data-name="Rectangle 12178" width="20" height="20"/>
                                        </clipPath>
                                    </defs>
                                    <g id="Mask_Group_23249" data-name="Mask Group 23249" clip-path="url(#clip-path)">
                                        <path id="Subtraction_3" data-name="Subtraction 3" d="M-6838,1502a10.011,10.011,0,0,1-10-10,10.012,10.012,0,0,1,10-10,10.011,10.011,0,0,1,10,10A10.01,10.01,0,0,1-6838,1502Zm-3.824-10.882-1.177,1.177,2.353,2.353,1.177,1.177,1.177-1.177,5.293-5.3-1.177-1.176-5.293,5.293Z" transform="translate(6848 -1482)" fill="#e4e4e4"/>
                                    </g>
                                </svg> <span class="mx-1">1 Special Character</span>
                            </div> 
                        </div>
                    </div>
                    @error('password') <span class="error"> {{$message}}</span> @endif                    
                </div>    
                <button class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-100" wire:click.prevent="signup">
                    <h5 class="fw-normal">Submit</h5>
                </button>
                <div class="pt-3 gap-1 d-flex align-items-center justify-content-center">
                    <h6 class="text-center h-sm fw-normal">Already have an account ? <span class="click-here h-sm fw-normal" data-bs-toggle="modal" data-bs-target="#signin">Login</span></h6> 
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{asset('asset/livewire/js/sngup.js')}}"></script>
@endpush