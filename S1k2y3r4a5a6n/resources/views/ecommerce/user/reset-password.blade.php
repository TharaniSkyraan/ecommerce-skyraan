<x-ecommerce.app-layout>   
    
<section class="forgot-password">
    <div class="container">
        <div class="row justify-content-center py-5 ">
            <div class="col-6">
                <div class="card px-5 py-4">
                    <div class="position-relative">
                        <h4 class="fw-bold">Reset your password</h4>
                    </div>
                    <div class="py-3">
                        <h6 class="text-secondary opacity-75 fw-normal h-sm">Enter a new password for {{old('email', $email)}}</h6>
                    </div>                    
                    @if (Session::has('error'))
                        <span class="error">
                            {{ Session::get('error') }}
                        </span>
                    @endif
                    <div>
                        <form method="POST" action="{{ route('password.update') }}" onsubmit='return validateForm()'>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{old('email', $email)}}">
                            @error('email') <span class="error"> {{$message}}</span> @endif                    
                            <div class="">
                                <label for="newpassword" class="form-label h-sms">Password</label>
                                <input type="password" name="password" class="form-control" id="newpassword" placeholder="new password" required>
                            </div>
                            <span class="error password err_msg"> </span>                     
                            @error('password') <span class="error"> {{$message}}</span> @endif                    
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
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label h-sms">Confirm Password</label>
                                <input type="password" name="password_confirmation"  class="form-control" id="password_confirmation" placeholder="confirm password" autocomplete="new-password" required>
                                <span class="error password_confirmation err_msg"> </span>                     
                                @error('password_confirmation') <span class="error"> {{$message}}</span> @endif                    
                            </div>
                            <div class="text-center" >
                                <button type="submit" class="btn px-5 py-2 text-white rounded-1">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="{{asset('asset/livewire/js/rpwd.js')}}"></script>
@endpush
</x-ecommerce.app-layout>