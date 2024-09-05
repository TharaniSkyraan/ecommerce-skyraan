<div>
    <div class="text-end mbl-view">
        <i class="bi bi-x-lg close-btn" data-bs-dismiss="modal" aria-label="Close" class=""></i>
    </div>
    <div class="text-center py-3">
        <h5 class="fw-bold">ADD TO CART</h5>
    </div>
    <div class="container-fluid">
        <div class="text-center">
            <div class="loader p-4" wire:loading>
            </div>
        </div>
        <div class="row PrdRow dffe" data-id="{{ $product_id }}" data-pvid="{{ $product_previous_variant_id }}" wire:loading.remove>
            <span class="variant_id d-none">{{ $product_variant_id }}</span>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 pb-3">
                <div class="text-center">
                    <img src="{{ $image1??asset('asset/home/default-hover1.png') }}" alt="image" class="w-100 quick-shop-img ">
                </div>                            
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 price-detail ps-4 pb-3 pt-2">
                <h6 class="text-dark fw-bold">{{($cart_product->name??'Wheat Flour')}}</h6>
                <input type="hidden" wire:model="product_id">
                <div class="d-flex justify-content-start gap-2 py-2 price-discount align-items-center">
                    @if(isset($discount) && $discount!=0)
                    <del class="del-clr"><h6 class= "text-secondary opacity-50 fw-bold">{{ $ip_data->currency_symbol??'₹' }} {{ $price}}</h6></del>
                    <h6 class="price fw-bold">{{ $ip_data->currency_symbol??'₹' }} {{ $sale_price}}</h6>
                    <div class="card border-0">
                        <small class="card border-0 text-center text-white px-1">{{$discount}}% Off</small>
                    </div>
                    @else
                    <h6 class="price fw-bold">{{ $ip_data->currency_symbol??'₹' }} {{ $price??0}}</h6>
                    @endif
                </div>
                <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                    @php 
                        $review = $review??0;
                        $review_count = $review_count??0;
                    @endphp
                    @if($review==0)
                        <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
                    @elseif($review==1)
                        <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
                    @elseif($review==2)
                        <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
                    @elseif($review==3)
                        <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
                    @elseif($review==4)
                        <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
                    @elseif($review==5)
                        <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
                    @endif
                    <h6 class="text-secondary text-opacity-50 h-sms">{{$review_count}} reviews</h6>
                </div>
                <hr class="my-2 q-s-hr">
                <div class="d-flex py-1">
                    <small class="text-dark fw-bold">Availablity : &nbsp;</small>
                    <small class="text-dark ">@if(isset($stock_status) && $stock_status=='in_stock') In stock @else Out of stock @endif</small>
                </div>
                @isset($cart_product)
                    @php 
                        $selected_attributes_set_ids = array_keys(array_filter(array_filter($selected_attributes_set_ids, function($key) {
                                                            return $key !== "";
                                                        }, ARRAY_FILTER_USE_KEY)))
                    @endphp
                    @foreach($parent_attribute as $attribute)
                        <div class="price">
                            <small class="text-dark fw-bold">{{ucwords($attribute['name'])}}</small>
                            <div class="d-flex gap-3 align-items-center py-1 attribute">
                                @foreach($attribute['sets'] as $attribute_set)
                                <label class="cursor attribute-label parent-attribute-label card px-2 py-1 border-0 {{ (in_array($attribute_set['id'], $selected_attributes_set_ids)? 'active': '')}}" id="{{$attribute_set['id']}}"><small>{{ucwords($attribute_set['name'])}}</small></label>
                                <input class="form-check-input d-none" type="checkbox" id="selected_attributes_set_ids{{$attribute_set['id']}}" wire:model="selected_attributes_set_ids.{{$attribute_set['id']}}">
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    @foreach($attributes as $attribute)                                                        
                        <div class="price">
                            <small class="text-dark fw-bold">{{ucwords($attribute['name'])}}</small>
                            <div class="d-flex gap-3 align-items-center py-1 attribute">
                                @foreach($attribute['sets'] as $attribute_set)
                                <label class="{{ (count(array_intersect($parent_available_variant_ids, $attribute_set['available_variant_ids']))!=0)? 'attribute-label other-attributes' :'outofstock text-dark'}} card px-2 py-1 border-0 {{ (in_array($attribute_set['id'], $selected_attributes_set_ids)? 'active': '')}}" id="{{$attribute_set['id']}}"><small>{{ucwords($attribute_set['name'])}}</small></label>
                                <input class="form-check-input d-none" type="checkbox" id="selected_attributes_set_ids{{$attribute_set['id']}}" wire:model="selected_attributes_set_ids.{{$attribute_set['id']}}">
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endisset
                <div class="quantity py-1">
                    <div class="d-flex gap-2 align-items-center">
                        @php $limit = ($available_quantity <= $cart_limit)? $available_quantity : $cart_limit; @endphp
                        @if($limit !=0)
                            <small class="text-dark fw-bold">Quantity :</small>
                            <div class="qty-dropdown position-relative jkef1 cursor">
                                <div class="card rounded-0 px-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="h-sms input-qty py-1">1</p>
                                        <img src="{{asset('asset/home/down-ar.svg')}}" alt="arrow">
                                    </div>
                                </div>
                                <div class="card-bodys" style="display:none;">
                                    @for ($i = 1; $i <= $limit; $i++) 
                                    <p class="h-sms px-2 py-1 qty-option" data-qty="{{ $i }}">{{$i}}</p>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if(isset($stock_status) && $stock_status=='in_stock')
                    @if($quickshop_type=='edit')
                        <div class="row add-to-cart py-2 w-100 px-2">
                            <a href="javascript:void(0)" class="col-12 px-0 AddCart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <div class="card card1 border-0 py-3 px-5">
                                    <h6 class="text-dark text-center">Add to cart</h6>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="row add-to-cart py-2 w-100 px-2">
                            <a href="javascript:void(0)" class="col-12 px-0 ReplaceCart" data-cid="{{ $cart_product->cart_id }}">
                                <div class="card card1 border-0 py-3 px-5">
                                    <h6 class="text-dark text-center">Replace Item</h6>
                                </div>
                            </a>
                        </div>
                    @endif
                @elseif(!empty($zone_data['warehouse_ids']))
                    <div class="row add-to-cart py-2 w-100 px-2">
                        <a href="javascript:void(0)" class="col-12 px-0 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                            <div class="card card1 border-0 py-3 px-5">
                                <h6 class="text-dark text-center">Notify Me</h6>
                            </div>
                        </a>
                    </div>
                @else
                    <span class="text-danger">*Currently unavailable</span>
                @endif
                @if($cart_product)
                <div class="py-2">
                    <a href="{{  route('ecommerce.product.detail', ['slug' => $cart_product->slug??'']) }}?prdRef={{ \Carbon\Carbon::parse($cart_product->created_at??'')->timestamp}}">

                    <div class="d-flex justify-content-start align-items-center gap-1 cursor">
                        <h6>View details</h6>
                        <img src="{{asset('asset/home/right-arrow.png')}}" alt="arrow">
                    </div></a>
                </div>
                @endif
            </div>
        </div>
        <div class="nointernet text-danger text-center"></div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '.parent-attribute-label', function () { 
        var id = $(this).attr('id');
        @this.set('parent_attribute_set_id', id);
    });
</script>
@endpush