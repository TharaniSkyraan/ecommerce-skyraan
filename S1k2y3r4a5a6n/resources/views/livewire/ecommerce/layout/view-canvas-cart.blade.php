<div>
    <div class="offcanvas-body cart_add_section pb-xl-5 pb-lg-5 pb-md-1 pb-sm-1 pb-0">
        @if(count($cart_products)!=0)
            <div class="row pb-2 list-items">
                <div class="col-12 pb-3">
                    @foreach($cart_products as $cart_product)
                        <div class="card cartList main-card p-2 mb-2 PrdRow" data-id="{{ $cart_product['id'] }}">
                            <span class="variant_id d-none">{{ $cart_product['variant_id'] }}</span>
                            <a href="javascript:void(0)">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="card card1 position-relative border-0 p-2 cursor">
                                            <a href="{{ route('ecommerce.product.detail', ['slug' => $cart_product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($cart_product['created_at'])->timestamp}}&product_variant={{$cart_product['variant_id']}}">
                                            <!-- <div class="position-absolute like-img">
                                                <img src="{{asset('asset/home/Group 32139.png')}}" alt="like" class="w-75">
                                            </div> -->
                                            <img src="{{ $cart_product['image'] }}" alt="list_items" class="w-100">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-7 price_info">
                                        <h6 class="fw-bold h-sms">{{ $cart_product['name'] }}</h6>
                                        <h6 class="py-1 h-sms">{{ $cart_product['attributes'] }}</h6>
                                        <h6 class="h-sms">
                                            @if(isset($cart_product['discount']) && $cart_product['discount']!=0)
                                                <del class="text-secondary opacity-75 del-clr h-sms">{{ $ip_data->currency_symbol??'₹' }} {{ $cart_product['price'] }}</del>
                                                <small class="fw-bold py-1 price ps-2">{{ $ip_data->currency_symbol??'₹' }}<span class="fw-bold product-price"> {{ $cart_product['sale_price'] }}</span></small>
                                            @else
                                                <small class="fw-bold py-1 price">{{ $ip_data->currency_symbol??'₹' }} <span class="fw-bold product-price">{{ $cart_product['price'] }}</span></small>
                                            @endif
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center pt-1 gap-2">
                                            @php $limit = ($cart_product['available_quantity'] <= $cart_product['cart_limit'])? $cart_product['available_quantity'] : $cart_product['cart_limit'];  @endphp
                                            @if($cart_product['quantity']<=$limit)
                                                <div class="qty-dropdown jkef2 position-relative">
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
                                            @endif

                                            @if($cart_product['product_type']>1 || ($cart_product['quantity']>$limit && $cart_product['available_quantity']!=0))
                                            <div>
                                                <button class="bg-unset border-0 QuickShop p-0" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                                    <img src="{{asset('asset/home/3917361.png')}}" alt="edit" class="w-75">
                                                </button>
                                            </div>
                                            @endif
                                            <div class="deleteCart cursor">
                                                <img src="{{asset('asset/home/3917378.svg')}}" alt="delete" class="w-75">
                                            </div>
                                        </div>
                                        @if($cart_product['quantity']>$limit)
                                            <span class="error">{{ ($cart_product['available_quantity']==0)?'Out of stock':(($cart_product['quantity']>$cart_product['available_quantity'])?'Only '.$cart_product['available_quantity'].' quantity is available.':'Only '.$limit.' quantity is allowed.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                @if(count($related_products) !=0)
                    <div class="related-items py-2">
                        <div class="row py-2">
                            <h6 class="text-center">You might also like</h6>
                        </div>
                        <div id="related-items-cart" class="owl-carousel px-3">
                            @foreach($related_products as $product)
                                @php
                                    $images = json_decode($product['images'], true);
                                    $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                                @endphp
                                <div class="owl-slide mx-1">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}">
                                        <img src="{{ $image }}" alt="image" class="cursor">
                                    </a>
                                </div> 
                            @endforeach
                        </div>
                    </div>  
                @endif 
            </div>
        @else
            <div class="row pb-2 list-items empty-cart ">
                <div class="col-12 pb-3">
                    <img src="{{ asset('asset/home/empty-cart-placeholder.svg') }}" alt="home">
                    <h6 class="py-4">Your cart is empty.</h6>
                    <a href="{{ url('/') }}" class="btnss cart-btn text-white h-sms py-2 px-3">RETURN TO SHOP</a>
                </div>
            </div>
        @endif
    </div>
    @if(count($cart_products)!=0)
        <div class=" cardsfww">
            <div class="py-2 subtotal px-1 bg-white">
                <div class="card p-2">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-dark fw-bold ">Subtotal</h6>
                        <h6 class="text-dark fw-bold ">{{ $ip_data->currency_symbol??'₹' }} <span class="sub-total fw-bold">{{ $total_price }}</span> {{ $ip_data->currency_code??'INR' }}</h6>
                    </div>
                </div>
            </div>
            <div class="check-out px-2 py-4 bg-white">
                <div class="d-flex justify-content-between">
                    @if(\Auth::check())
                        <a href="{{route('ecommerce.cart')}}" class="btn px-xl-4 px-lg-5 px-sm-5 px-md-5 px-4 text-white"><h6 class="fw-normal h-sms">Go to cart</h6></a>
                        <a href="{{route('ecommerce.checkout')}}" class="btn px-xl-4 px-lg-5 px-sm-5 px-md-5 px-4 text-white"><h6 class="fw-normal h-sms">Check out</h6></a>
                    @else
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#signin" class="btn px-xl-4 px-lg-5 px-sm-5 px-md-5 px-4 text-white"><h6 class="fw-normal h-sms">Go to cart</h6></a>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#signin" class="btn px-xl-4 px-lg-5 px-sm-5 px-md-5 px-4 text-white"><h6 class="fw-normal h-sms">Check out</h6></a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
