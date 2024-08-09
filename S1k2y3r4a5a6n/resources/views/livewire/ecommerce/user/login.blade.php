
<div class="container-fluid">
    <div class="row eq-height">
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12 py-4 sign-in d-flex align-items-center justify-content-center">
        <img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="logo" class="sign-img">
        </div>
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12 py-xl-5 py-lg-5 py-sm-5 py-md-5 py-2 sign-in-bg jhhrf">
            <div class="px-xl-3 px-lg-3 px-sm-3 px-md-3 px-2">
                <form autocomplete="off">
                    <h4 class="fw-bold heading text-start">Sign in</h4>
                    <div class="pt-3 d-flex username" wire:ignore>
                        <input type="text" name="username" wire:model="username" class="form-control" id="username" placeholder="Email or Phone" autocomplete="off">
                    </div>
                    @error('username') <span class="error"> {{$message}}</span> @endif
                    <div class="py-3">
                        <div class="d-flex" wire:ignore>
                            <input type="password" wire:model="password" name="password" class="form-control password" placeholder="Password" autocomplete="off">
                            <span class="view-password cursor" data-value="show">Show</span>
                        </div>                     
                        @error('password') <span class="error">{{$message}}</span> @endif
                        @if($errorMessage) <span class="error">Invalid Password</span> @endif
                        <br><span class="error nointernet" wire:ignore></span>
                    </div>
                    <button class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-100 submitbutton" wire:click.prevent="signin" wire:loading.attr="disabled" wire:target="signin">
                        <h5>Sign in</h5>
                    </button>
                    <div class="pt-2 d-flex align-items-center gap-1 justify-content-between">
                        <h6 class="h-sm fw-normal cursor" data-bs-toggle="modal" data-bs-target="#forgotpassword">Forgot password</h6>
                        <div class="d-flex align-items-center gap-1">
                            <h6 class="text-center py-1 h-sm fw-normal">New Customers? <span class="click-here h-sms" data-bs-toggle="modal" data-bs-target="#signup">start here</span></h6> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{asset('asset/livewire/js/lgn!24.js')}}"></script>
@endpush