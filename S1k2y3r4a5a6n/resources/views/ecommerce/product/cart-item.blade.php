@foreach($cart_products as $cart_product)
<div class="row py-2">
    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-8 pe-0">
        <div class="d-flex gap-2 justify-content-start align-items-center">
            <img src="{{ $cart_product['image'] }}" alt="" class="w-50 order-img">
            <div class="qty-clr">
                <h6 class="fw-bold">{{$cart_product['name']}}</h6>
                <div><h6 class="py-2 text-secondary fw-normal">{{ $cart_product['attributes'] }}</h6></div>
                <div class="pb-3"><h6 class="text-secondary fw-normal">Quantity : {{ $cart_product['quantity'] }} nos</h6></div>
                @if(isset($cart_product['discount']) && $cart_product['discount']!=0)
                    <div class="card border-0 jhjhj">
                        <small class="card border-0 text-center text-white">{{ $cart_product['discount'] }}% Off</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-4 align-self-center">
        @if(isset($cart_product['discount']) && $cart_product['discount']!=0)
            <del class="del-clr"><h6 class="text-secondary opacity-50 text-end">Rs {{ $cart_product['price'] }}</h6></del>
            <h5 class="price text-end">Rs {{ $cart_product['sale_price'] }}</h6>
        @else
            <h5 class="price text-end">Rs {{ $cart_product['price'] }}</h6>
        @endif
    </div>
</div>
<hr>
@endforeach
<div class="price-dtl pt-3" id="shipping">
    @if(!empty($coupon_code))
        <div class=" d-flex pb-3 gap-4">
            <div class="d-flex align-items-center gap-2">
                <img src="{{asset('asset/home/tick-icon.png')}}" alt="tickicon" class="tick_img">
                <h6 class="buy-color">Coupen Applied !</h6>
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
                    <img src="{{asset('asset/home/coupen.svg')}}" alt="coupen" class="tick_img">
                <span class=" text-secondary opacity-50">Apply Coupen</sapn>
                </div>
            </div>
        </div> 
    @endif      
    <div class="d-flex justify-content-between align-items-center pt-3">
        <h6>Sub total</h6>
        <h6 class="price">Rs {{ $total_price }}</h6>
    </div>
    @if($shipping_charges==0)
    <div class="d-flex justify-content-between align-items-center">
        <h6  class="free-delivery">Free delivery</h6>
        <span class="text-secondary lh-lg text-opacity-50">FREE</span>
    </div>
    @else
    <div class="d-flex justify-content-between align-items-center mt-2">
        <p>Shipping Fee</p>
        <span class="text-secondary lh-lg ">Rs {{$shipping_charges}}</span>
    </div>    
    @endif
    
    @if(!empty($coupon_code))
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="d-flex gap-1 align-items-center wrapper">
            <h6>Coupon Discount</h6>
            <img id="info-tooltip" class="info-tooltip" src="{{ asset('asset/home/info.svg') }}" alt="info" style="width: 18px;">
            <div class="tooltip align-items-center justify-content-between gap-5 p-2">
                @php $coupon_carts = auth()->user()->usercart->applicable_products??'';
                    $discount_type = auth()->user()->usercart->coupon->discount_type??'';
                    $discount = auth()->user()->usercart->coupon->discount??'';
                    $coupon_carts = (!empty($coupon_carts))?array_filter(explode(',',$coupon_carts)):[];
                @endphp

                @if($apply_for!='all' && !empty($discount_type))
                    @foreach($coupon_carts as $cart)
                        @if(isset($cart_products[$cart]))
                            <div class="d-flex align-items-center ewfwefw gap-2">
                                <div class=""><img src="{{ $cart_products[$cart]['image'] }}" alt="" style="width: 40px;"></div>
                                <div><span class="h-sm">Coupon discount for {{$cart_products[$cart]['name']}} <b> {{ ($discount_type=='percentage')? $discount.'%':''}}</b></span></div>
                                @if(isset($cart_products[$cart]['discount_coupon']))
                                    <div><span class="h-sm">-{{ $cart_products[$cart]['discount_coupon'] }}</span></div>
                                @else
                                    <div><span class="h-sm free-delivery">FREE shipping</span></div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @else                
                    <div class="d-flex align-items-center ewfwefw gap-2">
                        <div class=""></div>
                        <div><span class="h-sm">Coupon discount for you  <b>{{ ($discount_type=='percentage')? $discount.'%':''}}</b></span></div>
                        @if($coupon_discount!=0)
                            <div><span class="h-sm">-{{ $coupon_discount }}</span></div>
                        @else
                            <div><span class="h-sm free-delivery">FREE shipping</span></div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @if($coupon_discount!=0)
            <span class="text-secondary lh-lg text-opacity-50 free-delivery">- Rs {{ $coupon_discount }}</span>
        @endif
    </div>
    @endif
    <hr>
    <div class="d-flex justify-content-between align-items-center py-4">
        <h5 class="">Total</h5>
        <div class="price d-flex"> <del class="del-clr"><h5 class="text-secondary opacity-50 text-end me-2">Rs {{ $total_price }} </h5> </del> <h5> Rs {{ ($total_price - $coupon_discount) + $shipping_charges }}</h5></div>
    </div>
</div>
