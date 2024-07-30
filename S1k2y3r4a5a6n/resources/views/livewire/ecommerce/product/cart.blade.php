<div>    
    @if(count($cart_products)!=0)
        <input type="hidden" id="total_price" value="{{$total_price}}">
        <section class="product-list cartpage">
            <div class="container">
                <div class="row pt-xl-4 pt-lg-4 pt-md-4 pt-sm-4 pt-0">
                    <div class="col-5">
                        <h6 class="sys-view">Product info</h6>
                    </div>
                    <div class="col-4">
                        <h6 class="sys-view">Quantity</h6>
                    </div>
                    <div class="col-3">
                        <h6 class="sys-view total">Total</h6>
                    </div>
                </div>
                <hr class="sys-view">
                @foreach($cart_products as $cart_product)
                    @php $limit = ($cart_product['available_quantity'] <= $cart_product['cart_limit'])? $cart_product['available_quantity'] : $cart_product['cart_limit']; @endphp
                    <div class="row py-2 cartList price-list PrdRow ps-2" data-id="{{ $cart_product['id'] }}" data-cid="{{ $cart_product['cart_id'] }}">
                        <span class="variant_id d-none">{{ $cart_product['variant_id'] }}</span>
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
                            <div class="row">
                                <div class="col-4 p-2">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $cart_product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($cart_product['created_at'])->timestamp }}" target="_blank">
                                        <img src="{{ $cart_product['image'] }}" alt="list_items" class="w-100 cart-li-img">
                                    </a>
                                </div>
                                <div class="col-8 price_info align-self-center">
                                    <h6 class="fw-bold">{{$cart_product['name']}}</h6>
                                    <div class="d-flex gap-3 align-items-center py-1">
                                        @if(isset($cart_product['discount']) && $cart_product['discount']!=0)
                                            <del class="del-clr text-secondary lh-lg text-opacity-50">{{ $ip_data->currency_symbol??'₹' }} {{ $cart_product['price'] }}</del>
                                            <h6 class="price lh-lg align-self-center">{{ $ip_data->currency_symbol??'₹' }} <span class="fw-bold product-price">{{ $cart_product['sale_price'] }}</span></h6>
                                        @else
                                            <h6 class="price lh-lg align-self-center">{{ $ip_data->currency_symbol??'₹' }} <span class="fw-bold product-price">{{ $cart_product['price'] }}</span></h6>
                                        @endif
                                    </div>
                                    <h6 class="pb-1">{{ $cart_product['attributes'] }}</h6>
                                    <div class="d-flex">
                                        @if($cart_product['product_type']>1 || ($cart_product['quantity']>$limit && $cart_product['available_quantity']!=0))
                                            <div>
                                                <button class="bg-unset border-0 px-0 me-3 EditQuickShop" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                                    <img src="{{asset('asset/home/3917361.png')}}" alt="edit" class="w-75">
                                                </button>
                                            </div>
                                        @endif
                                        <div class="deleteCart cursor mx-2">
                                            <img src="{{asset('asset/home/3917378.svg')}}" alt="delete" class="w-75">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-7 align-self-center pt-2 ps-0">
                            <!-- <div class="col-xl-4 col-lg-4 col-sm-4 col-md-4 col-5 qty-container d-flex align-items-center justify-content-center border p-1 rounded-1  text-dark">
                                <div class="col text-center px-1 qty-btn-minus"><span>-</span></div>
                                <div class="vr"></div>
                                <div class="col text-center px-1"><span class="input-qty h-sms">{{ $cart_product['quantity'] }}</span></div>
                                <div class="vr"></div>
                                <div class="col text-center px-1 qty-btn-plus"><span>+</span></div>
                            </div> -->
                            @if($cart_product['quantity']<=$limit)
                                <div class="qty-dropdown w-25 position-relative">
                                    <div class="card rounded-0 p-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="h-sms input-qty">{{ $cart_product['quantity'] }}</p>
                                            <img src="{{asset('asset/home/down-ar.svg')}}" alt="arrow">
                                        </div>
                                    </div>
                                    <div class="card-bodys" style="display:none;">
                                        @for ($i = 1; $i <= $limit; $i++) 
                                        <p class="h-sms p-1 qty-option" data-qty="{{ $i }}">{{$i}}</p>
                                        @endfor
                                    </div>
                                </div>
                            @else
                                <span class="error">{{ ($cart_product['available_quantity']==0)?'Out of stock':(($cart_product['quantity']>$cart_product['available_quantity'])?'Only '.$cart_product['available_quantity'].' quantity is available.':'Only '.$limit.' quantity is allowed.') }}</span>
                            @endif
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-5 d-flex align-self-center justify-content-start">
                            @if(isset($cart_product['discount']) && $cart_product['discount']!=0)
                                <h6 class="price_clr">{{ $ip_data->currency_symbol??'₹' }} {{ $cart_product['quantity'] * $cart_product['sale_price'] }}</h6>
                            @else
                                <h6 class="price_clr">{{ $ip_data->currency_symbol??'₹' }} {{ $cart_product['quantity'] * $cart_product['price'] }}</h6>
                            @endif    
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </section>
        <section class="nsc py-4">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-md-5 col-sm-5 col-12">
                        <h6 class="text-start pb-2 ">Order special instructions</h6>
                        <textarea class="form-control fw-normal text-areaa" placeholder="Order special instructions" id="order_notes" wire:model="notes"></textarea>
                        @error('notes')<span class="error">{{$message}}</span> @endif
                    </div>
                    <div class="col-xl-5 col-lg-5  col-md-7  col-sm-7  col-12 px-xl-5 px-lg-5 px-md-4 px-sm-4 px-4">
                        <div class="d-flex justify-content-between py-2">
                            <h6 class="">Subtotal</h6>
                            <h6 >{{ $ip_data->currency_symbol??'₹' }} <span class="sub-total fw-bold">{{ $total_price }}</span>  {{ $ip_data->currency_code??'INR' }}</h6>
                        </div>
                        <div>
                            <h6 class="taxt-secondary opacity-50 py-3 h-sms fw-normal">Taxes and shipping calculated at checkout</h6>
                        </div>
                    </div>
                </div>
                <div class="row" id="shipping">
                    <div class="col-xl-7 col-lg-7 col-md-5 col-sm-5 col-12">  
                        @if(!empty($coupon_code))
                            <div class=" d-flex pb-3 gap-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{asset('asset/home/icons-tick.svg')}}" alt="tickicon" class="tick_img">
                                    <h6 class="green">Coupen Applied !</h6>
                                </div> 
                                <div class="d-flex align-items-center gap-2 rounded-5 p-2 coupen-applied">
                                    <h6 class="opacity-75">{{ $coupon_code }}</h6>
                                    <div class="rounded-circle bg-white" wire:click="removeCoupon">
                                        <i class="bi bi-x p-1"></i>                                
                                    </div>
                                </div>
                            </div>
                        @else                               
                            <div class="pb-3 coupens">
                                <label for="coupen" class="form-label h-sms coupen">Have a coupen code?</label>
                                <div class="card py-2 px-3 cursor"  data-bs-toggle="modal" data-bs-target="#exampleModal_coupen" id="availableCoupon">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{asset('asset/home/coupen.svg')}}" alt="coupen" >
                                    <span class=" text-secondary opacity-50">Apply Coupen</sapn>
                                    </div>
                                </div>
                            </div> 
                        @endif      
                    </div>
                    <div class="col-xl-5 col-lg-5  col-md-7  col-sm-7  col-12 px-xl-5 px-lg-5 px-md-4 px-sm-4 px-4">
                        <div class="text-center pb-2">
                            <a href="javascript:void(0);" wire:click.prevent="Checkout" class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-3"><h6 class="fw-normal">Proceed to checkout</h6></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
@push('scripts')
<script src="{{asset('asset/livewire/js/crt.js')}}"></script>
@endpush
        