<div>
    <section class="contact-us">
        <section class="contact-us-banner">
            <div class="py-3 container-fluid">
                <div class="row">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);z-index: 1;" aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item h-sms"><a href="{{ route('ecommerce.home') }}" class="">Home</a></li>
                            <li class="breadcrumb-item h-sms  active" aria-current="page">cart</li>
                        </ol>
                    </nav>
                    <div style="z-index: 1; ">
                        <h1 class="buy-color center-cnt">Contact Us</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="contact-us-body container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pt-5 ps-xl-5 ps-lg-5 ps-md-5 ps-sm-5 ps-3">
                     <h5 class="py-2 buy-color">Got a question?</h5>
                     <span class="h-sms buy-color">We had love to hear from you. send us a message and we will respond as soon as possible.</span>
                     <div class="d-flex align-items-center gap-4 pt-3">
                        <div class="rounded-circle py-2 px-2 clrs">
                           <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="tect-center">
                            <h6>Address</h6>
                            <span class="h-sms">{{$siteSetting->address}}</span>
                        </div>
                     </div>
                     <div class="d-flex align-items-center gap-4 py-4">
                        <div class="rounded-circle py-2 px-2 clrs">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="tect-center">
                            <h6>Phone</h6>
                            <span class="h-sms">+91 {{$siteSetting->phone}}</span>
                        </div>
                     </div>
                     <div class="d-flex align-items-center gap-4 pb-3">
                        <div class="rounded-circle py-2 px-2 clrs">
                           <i class="bi bi-envelope"></i>
                        </div>
                        <div class="tect-center">
                            <h6>Email</h6>
                            <span class="h-sms">{{$siteSetting->mail_from_address}}</span>
                        </div>
                     </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col6 pb-5">
                    <div class="card p-xl-5 p-lg-5 p-md-5 p-sm-5 p-3">
                       <h5 class="buy-color">Drop us message</h5>
                        <div>
                            <h6 class="pt-3 pb-1">Name</h6>
                        </div>
                        <div class="pb-3">
                            <input type="text" class="form-control rounded-1" placeholder="Enter your Name" wire:model="name">
                            @error('name') <span class="error"> {{$message}}</span> @endif
                        </div>
                        <div>
                            <h6 class="pb-1">E Mail ID</h6>
                        </div>
                        <div class="pb-3">
                            <input type="text" class="form-control rounded-1" placeholder="Enter your E Mail" wire:model="email">
                            @error('email') <span class="error"> {{$message}}</span> @endif

                        </div>
                        <div>
                            <h6 class="pb-1">Feedback</h6>
                        </div>
                        <div class="pb-3">
                           <textarea class="form-control fw-normal " placeholder="Feedback" id="feedback" wire:model="feedback" style="height: 112px;"></textarea>
                           @error('feedback') <span class="error"> {{$message}}</span> @endif                        
                        </div>
                        <div class="text-end">
                            <button class=" btn text-white" wire:click="contactus">submit</button>
                        </div>
                    </div>
                </div>
                <div class="location pb-5">
                    <div class="text-center">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15663.17991639013!2d76.9948079!3d11.0539925!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba8592c7f4600cf%3A0x6c2bd5c3ae846ffa!2sSkyraan%20Technologies%20Private%20Limited!5e0!3m2!1sen!2sin!4v1698208880747!5m2!1sen!2sin" 
                                style="width:83vw; height:20vw; border:0;" allowfullscreen="" loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

@push('scripts')
<script>

    document.addEventListener('livewire:load', function () {    
        Livewire.on('SendSuccess', data => {  
    
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
        toastr['success'](data, {
            closeButton: true,
            positionClass: 'toast-top-right',
            progressBar: true,
            newestOnTop: true
        });
    });
});
</script>
@endpush