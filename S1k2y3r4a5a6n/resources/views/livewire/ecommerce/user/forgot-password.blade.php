<div class="container-fluid">
    <div class="row eq-height">
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12 d-flex align-items-center justify-content-center py-4 sign-in">
            <h4 class="fw-bold text-dark text-center text-white">SKYRAAN</h4>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12 py-xl-5 py-lg-5 py-sm-5 py-md-5 py-2 align-self-center">
            <div class="px-xl-4 px-lg-4 px-sm-4 px-md-4 px-3">
                <h4 class="fw-bold">Reset your password</h4>
                <div class="py-3">
                    <h6 class="text-secondary opacity-75 h-sm">We will send you an email to reset your password.</h6>
                </div>
                <div class="pb-3">
                    <input type="email" class="form-control rounded-1" placeholder="Email" wire:model="email">
                    @error('email') <span class="error"> {{$message}}</span> @endif
                </div>
                <a href="javascript:void();" class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-100 rounded-1" wire:click.prevent="sendResetLink">
                    <h6 class="fw-normal">Submit</h6>
                </a>
                <div class="pt-3 gap-1 d-flex align-items-center justify-content-end">
                    <h6 class="text-center h-sm fw-normal">Already have an account ? <h6 class="click-here h-sm fw-normal red cursor" data-bs-toggle="modal" data-bs-target="#signin">Login</h6></h6> 
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
   document.addEventListener('livewire:load', function () {
        Livewire.on('showToast', message => {
            toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }

            toastr['success'](message, {
                closeButton: true,
                positionClass: 'toast-top-right',
                progressBar: true,
                newestOnTop: true
            });
        });
    });
</script>
@endpush