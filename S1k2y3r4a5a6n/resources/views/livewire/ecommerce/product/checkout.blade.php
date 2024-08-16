<div >
    <div class="text-center">
        <div class="checkoutpageloader d-none">
            <div class="">
                <svg width="150px" height="75px" viewBox="0 0 187.3 93.7" preserveAspectRatio="xMidYMid meet">
                    <path  stroke="#565454" id="outline" fill="none" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
                    <path id="outline-bg" opacity="0.05" fill="none" stroke="#565454" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
                </svg>
            </div>
        </div>
    </div>
    <div id="checkoutpage">
        @if(count($cart_products)!=0)
            <section class="checkoutpage check_out_li">          
                <div class="content mbl-view">
                    <div class="mbl-top">
                        <div class="d-flex justify-content-between align-items-center gap-2 py-3" id="order-list">
                            <div class="ps-2" wire:ignore>
                                <span class="fw-normal h-sms cursor summary">Show order summary</span>
                                <img  class="cursor down-ars" src="{{asset('asset/home/down-ar.svg')}}" alt="">
                                <img class="cursor up-ars" style="display:none; width: 9px;" src="{{asset('asset/home/up-ar.svg')}}" alt="">
                            </div>
                            <div class="pe-2">
                                <span>{{ $ip_data->currency_symbol??'₹' }}</sapn> <span class="price_clr">{{ ($total_price - $coupon_discount) + $shipping_charges }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-list-view px-2" @if($summary_show==false) style="display:none" @endif>
                        @include('ecommerce.product.cart-item')
                    </div> 
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 py-3">
                            <div class="stickyclass">
                                <div>
                                    <h6 class="fw-bold py-3">Shipping Address</h6>
                                    @error('address_id') <span class="error mb-2"> {{ $message }} </span> @endif
                                </div>
                                @if(count($addresses)!=0)
                                    <div>
                                        @php $cartaddress = auth()->user()->usercart->address??auth()->user()->address; @endphp
                                        @isset($cartaddress)
                                            <div class="card p-3">
                                                <div class="row">
                                                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-8 col-8">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="fw-bold h-sms"> {{ $cartaddress->name }} </span>
                                                        </div>
                                                        <div class="pt-1">
                                                            <span class="fw-normal h-sms"> {{ ucwords($cartaddress->address) }}, {{ $cartaddress->city }}, {{ $cartaddress->state }}, {{ $cartaddress->postal_code }}. </span>
                                                            <h6 class="h-sms py-2"> {{ $cartaddress->phone }}, {{ $cartaddress->alternative_phone }}. </h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 col-4 text-end">
                                                        <a href="javascript:void(0);" class="btns btn h-sm text-center" data-bs-toggle="modal" data-bs-target="#Editaddress" wire:click="edit({{$cartaddress->id}})">Edit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset
                                    </div>
                                    <div class="d-flex align-items-center gap-2" id="changeAddress">
                                        <hr class="flex-grow-1 align-items-center">
                                        <span class="fw-normal h-sms cursor">Change Address</span>
                                        <img id="toggleImg" class="cursor" src="{{asset('asset/home/down-ar.svg')}}" alt="">
                                    </div>
                                    <div class="multple-address" @if($addresslist==false) style="display:none" @endif>
                                        <div class="card p-xl-3 p-lg-3 p-md-3 p-sm-2 p-1 mul-address">
                                            @if(count($addresses) < 5)
                                                <div class="p-3 card1 rounded-1 cursor">
                                                    <div class="d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#Editaddress" wire:click="edit()">
                                                        <img src="{{ asset('asset/home/Plus.svg')}}" alt="add" class="plus-icon">
                                                        <h6 class="text-white">Add Address</h6>
                                                    </div> 
                                                </div>
                                            @endif
                                            <div class="max-height">
                                                @foreach($addresses as $address)
                                                    <div class="py-2">
                                                    <div class="card p-3">
                                                            <div class="row njkjk">
                                                                <label class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 align-self-center d-flex gap-2" for="address{{$address['id']}}">
                                                                    <input id="address{{$address['id']}}" class="cursor form-check-input mt-0 addressId" type="radio" name="address_id" value="{{$address['id']}}" wire:model="address_id" aria-label="Radio button for following text input">
                                                                    <div>
                                                                        <h6 class="fw-bold">{{ ucwords($address['name']) }}</h6>
                                                                        <h6 class="h-sms">{{ ucwords($address['address']) }}, {{ $address['city'] }} - {{ $address['postal_code'] }}.</h6>
                                                                        <h6 class="h-sms py-2">{{ $address['phone'] }}, {{ $address['alternative_phone'] }}.</h6>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <div class="multple-address">
                                    <div class="card p-xl-3 p-lg-3 p-md-3 p-sm-2 p-1 mul-address">
                                        <div class="p-3 card1 rounded-1 cursor">
                                            <div class="d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#Editaddress" wire:click="edit()">
                                                <img src="{{ asset('asset/home/Plus.svg')}}" alt="add" class="plus-icon">
                                                <h6 class="text-white">Add Address</h6>
                                            </div> 
                                        </div>                    
                                    </div>                    
                                </div>                    
                                @endif
                                <div>
                                    <h6 class="fw-bold py-3">Payment Method</h6>
                                </div>
                                <div class="input-group css">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="radio" id="payment_method1" wire:model="payment_method" value="cash" aria-label="Radio button for following text input">
                                    </div>
                                    <label class="form-control" for="payment_method1">Cash on delivery</label>
                                </div>
                                @if(!empty($siteSetting->payment_platform))
                                <div class="input-group mt-3 css">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="radio" id="payment_method2" wire:model="payment_method" value="net_banking" aria-label="Radio button for following text input">
                                    </div>
                                    <label class="form-control" for="payment_method2">Net Banking</label>
                                </div>                    
                                @endif
                                <div class="text-center py-4">
                                    @if($payment_method=='cash')
                                        <a href="javascript:void(0)" class="btnss btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-75 {{ !empty($action)?'outof-stock':'' }}" @if(empty($action)) wire:click="completeOrder" @endif><h6> Place Order </h6></a>
                                    @else
                                        <a href="javascript:void(0)" class="btnss btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-75 {{ !empty($action)?'outof-stock':'' }}" @if(empty($action)) wire:click="initiatePayment" @endif><h6> Pay Now  </h6></a>
                                        <div id="razorpay-container"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 sys-view pb-3">
                            <div class="order-summary">
                                <div class="px-xl-4 px-lg-4 px-md-3 px-sm-3 px-1">
                                    <h5 class="py-3">Order Summary</h5>
                                    @include('ecommerce.product.cart-item')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="Editaddress" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore>
                <div class="modal-dialog">
                    <div class="modal-content rounded-0">
                        <div class="text-end">
                            <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                        </div>
                        <div class="modal-bodys">
                            @livewire('ecommerce.user.auth.saved-addresses')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade"  id="exampleModal_coupen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Coupen</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-bodys p-3">
                            @livewire('ecommerce.product.coupon-apply')
                        </div>
                    </div>
                </div>
            </div>

            <!-- coupen applied popup -->
            <div class="modal fade" id="coupenapplied" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-0">
                        <div class="modal-bodys p-4">
                            <div class="text-center"> 
                                <img src="{{asset('asset/home/coupen_applied.svg')}}" alt="coupen_applied" class="pb-2">
                                @if(isset($coupon_discount) && $coupon_discount!=0)<h5 class="fw-bold">{{ $ip_data->currency_symbol??'₹' }} {{ $coupon_discount }}</h5>
                                <h6 class="text-secondary opacity-75 py-2">saved</h6>@endif
                                <h5 class="text-secondary">{{ $coupon_code }} Applied !</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        @else
            <section class="product-list">
                <div class="container">
                    <div class="row py-5">
                        <div class="col-12 no-product">
                            <img src="{{asset('asset/home/empty-cart-placeholder.svg')}}" alt="no-product">
                            <h5 class="py-4">No product in cart !</h5>
                            <a href="{{ url('/') }}" class="btnss cart-btn text-white my-5 h-sms px-4 py-2">RETURN TO SHOP</a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@push('scripts')
<script>

    $(document).on('click','.outof-stock', function()
    {
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
        toastr['error']('Selected product is out of stock remove and proceed', {
            closeButton: true,
            positionClass: 'toast-top-right',
            progressBar: true,
            newestOnTop: true
        });
    });

    $(document).on('click','#availableCoupon', function(){
        Livewire.emit('availableCoupon',{{$total_price}});
    });
    
    document.addEventListener('livewire:load', function () {    
        @this.set('place_order','{{ $siteSetting->place_order }}');        
        Livewire.on('initiateRazorpay', data => {            
            var options = {
                key: "{{ config('shipping.razorpay.razorpay_key') }}",
                amount: data.amount,
                currency: 'INR',
                name: '{{ config("siteSetting.site_name") }}',
                description: 'Purchase Description',
                order_id: data.orderId,
                handler: function(response) {
                    // Handle successful payment response
                    @this.set('payment_id', response.razorpay_payment_id);
                    Livewire.emit('completeOrder');
                },
                prefill: {
                   name : data.name,
                   email : '{{ auth()->user()->email }}',
                   contact : data.phone,
                },
                theme: {
                    "color": "{{ $siteSetting->theme_secondary_color }}"
                }
            };
            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response) {
                // Handle payment failure
                alert('Payment Failed! Retry');
            });
            rzp.open();
        });
        Livewire.on('appliedCouponSuccessToast', message => {
            $('#coupenapplied').modal('show');            
            setTimeout(function(){ 
                $('#coupenapplied').modal('hide')
            }, 3000);
        });
        Livewire.on('clearCart', code => {
            localStorage.setItem('cart',JSON.stringify({}));
            window.location.href = "{{ route('ecommerce.order-placed')}}/"+code;
        });
    });

</script>
@endpush