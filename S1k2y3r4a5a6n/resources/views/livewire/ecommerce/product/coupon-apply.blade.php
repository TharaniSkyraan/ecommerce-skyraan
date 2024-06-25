<div>
    <div class="d-flex align-items-center">
        <input class="form-control coupen_input" @if(!empty($coupon_code)) style="border-top-right-radius : 0;border-bottom-right-radius : 0;" @endif wire:model="coupon_code" placeholder="Enter coupen code">
        <span class="apply_coupon px-2 rounded-end-1 rounded-start-0 cursor" wire:click="applyCoupon" @if(empty($coupon_code)) style="display:none" @endif >Apply</sapn>
    </div>
    @error('coupon_code') <span class="error">{{$message}}</span> @endif
    @if(!empty($coupon_error)) <span class="error">{{$coupon_error}}</span> @endif
    @if(count($available_coupons)!=0)
        <div class="coupens_scroll pt-4">
            <div class="card p-3">
                <h6 class="text-secondary opacity-50 pb-3">Available Coupons</h6>
                @foreach($available_coupons as $available_coupon)
                    <div>
                        <div class="d-flex py-2 justify-content-between align-items-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{asset('asset/home/coupen_li.svg')}}" alt="">
                                <div class="position-absolute">
                                    <h6 class="code">{{$available_coupon['coupon_code']}}</h6>
                                </div>
                            </div>
                            <div>                               
                                @if($available_coupon['apply_for']=='minimum-order' && ($available_coupon['minimum_order'] > $total_price))
                                    <span class="text-danger">Add ₹{{ $available_coupon['minimum_order'] - $total_price }} more to avail this offer</span>
                                @else
                                <h5 class="buy-color cursor" wire:click="applyCoupon('{{ $available_coupon['coupon_code'] }}')">Apply</h5>
                                @endif
                            </div>
                        </div>
                        <div class="dashed-line"></div>
                        <div class="moredetail">
                            <div class="pt-2">
                            @if($available_coupon['apply_for']=='minimum-order')
                                @if($available_coupon['discount_type']=='free_shipping')
                                    <span class="text-secondary">Free shipping on orders only above {{$available_coupon['minimum_order']}}</span>
                                @else
                                    <span class="text-secondary">Maximum discount upto {{ $available_coupon['discount']}} {{($available_coupon['discount_type']=='percentage')?'%':''}} on orders only above {{$available_coupon['minimum_order']}}</span>
                                @endif
                            @else
                                @if($available_coupon['discount_type']=='free_shipping')
                                    <span class="text-secondary">Free shipping on orders only for your</span>
                                @else
                                    
                                    <span class="text-secondary">Save {{ $available_coupon['discount']}} {{($available_coupon['discount_type']=='percentage')?'%':''}} on orders only 
                                    
                                    @if($available_coupon['apply_for']=='customer' || $available_coupon['apply_for']=='once-per-customer') 
                                        for you                                 
                                    @elseif($available_coupon['apply_for']=='collection')
                                        for you  
                                    @elseif($available_coupon['apply_for']=='category')
                                    
                                    @elseif($available_coupon['apply_for']=='product')

                                    @endif
                                    
                                    </span>
                                @endif                                
                            @endif
                            </div>
                            <div class="py-2 plus cursor">
                                <span class="buy-color icon ">+</span>
                                <span class="buy-color text">More</span>
                            </div>
                            <div class="py-2 minus cursor" style="display:none">
                                <span class="buy-color icon ">-</span>
                                <span class="buy-color text">Less</span>
                            </div>

                            <div class="more-detl" style="display:none">
                                <h6 class="pb-2">Terms & Conditions</h6>
                                <ul>
                                    <li class="text-secondary opacity-75 h-sms">Applicable twice per user per month</li>
                                    @if(!empty($available_coupon['end_date']))
                                        <li class="text-secondary opacity-75 h-sms">Offer valid till {{ \Carbon\Carbon::parse($available_coupon['end_date'])->format('d M Y h:i A')}}</li>
                                    @endif
                                    <li class="text-secondary opacity-75 h-sms">Other T&Cs may apply</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    @else
        <!-- Placeholder image -->
        <div class="row py-4 cart_add_section">
            <div class="col-12 pb-3 empty-cart">
                <img src="{{ asset('asset/home/coupon-placeholder.svg') }}" alt="home">
                <h6>Oops! No Coupons Available</h6>
            </div>
        </div>

    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {        
        Livewire.on('appliedCouponSuccess', message => {
            Livewire.emit('CouponApplied');
            $('.btn-close').trigger('click');
        });
    });
</script>
@endpush