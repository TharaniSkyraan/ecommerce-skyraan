<div>
    <section class="userdashboard">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 select-li ps-xl-3 ps-lg-3 ps-md-3 ps-sm-0 ps-0 pe-xl-3 pe-lg-3 pe-md-3 pe-sm-0 pe-0 pb-3" wire:ignore>
                    @include('ecommerce.user.auth.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 order-li pb-3">
                    <div class="card  p-xl-4 p-lg-4 p-md-4 p-sm-3 p-1 billing">
                        <h5 class="fw-bold">Saved Addresses</h5><hr><div class="mul-address">
                            @foreach($addresses as $address)        
                                <div class="py-2">
                                    <div class="card p-xl-3 p-lg-3 p-md-3 p-sm-3 p-2">
                                        <div class="row njkjk">
                                            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10 align-self-center">
                                                <div class="d-flex gap-2">
                                                    <h6 class="fw-bold">{{ ucwords($address['name']) }}</h6>
                                                    @if($address['is_default']=='yes') <h6 class="red h-sms">Default</h6> @endif<br>
                                                </div>
                                                <h6 class="h-sms">{{ucwords($address['address'])}}, {{$address['city']}}, {{$address['postal_code']}}. </h6> <h6 class="h-sms py-2"> {{$address['phone']}}, {{$address['alternative_phone']}}.</h6>
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">                    
                                                <div class="dropdown text-end">
                                                    <img class="dropdown-toggle icons-menu cursor " src="{{ asset('asset/home/icons-menu.svg') }}" alt="menu" data-bs-toggle="dropdown">
                                                    <ul class="dropdown-menu drop-hover py-1" aria-labelledby="navbarDropdownMenuLink">
                                                        <li class="h-sms cursor p-2" data-bs-toggle="modal" data-bs-target="#Editaddress" wire:click="edit({{$address['id']}});">Edit</li>
                                                        <li class="h-sms RemoveAddress cursor p-2" data-id="{{$address['id']}}">Remove</li>
                                                        @if($address['is_default']=='no') <li class="h-sms MakeDefaultAddress cursor p-2" data-id="{{$address['id']}}">Set as default</li> @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(count($addresses) < 5)
                        <div class="p-3 card1 rounded-1 cursor">
                            <div class="d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#Editaddress" wire:click="edit()">
                                <img src="{{asset('asset/home/Plus.svg')}}" alt="add" class="plus-icon">
                                <h6 class="text-white">Add Address</h6>
                            </div> 
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="Editaddress" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore>
    <div class="modal-dialog">
        <div class="text-end">
            <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
        </div>
        <div class="modal-content rounded-0">
            <div class="modal-bodys">
                @livewire('ecommerce.user.auth.saved-addresses')
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.MakeDefaultAddress', function () {
        if (confirm('Are you sure you want to set as default?')) {
            var id=$(this).attr('data-id'); 
            Livewire.emit('setdefaultAddress',id);
            Livewire.on('setdefaultAddressToast', message => {
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
        }
    });

    $(document).on('click', '.RemoveAddress', function () {
        if (confirm('Are you sure you want to delete this address as inappropriate?')) {
            var id=$(this).attr('data-id'); 
            Livewire.emit('remove',id);
            Livewire.on('delateAddressToast', message => {
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
        }
    });
});
</script>
@endpush