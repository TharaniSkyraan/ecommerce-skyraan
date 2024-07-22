<div>
<section class="bread-crumb">
    <div class="container py-3">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item h-sms"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                <li class="breadcrumb-item active text-dark h-sms detail-dot" aria-current="page">{{ $product['name'] }}</li>
            </ol>
        </nav>
    </div>
</section>
<section  class="detail-card">
    <div class="container">
        <div class="row PrdRow " data-id="{{ $product['id'] }}">
            <span class="variant_id d-none">{{ $variant }}</span>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="container-fluid position-relative tag">
                    <div class="row position-absolute w-100">
                        <div class="col-6">
                            @if(!empty($product['label']))
                            <div class="trapezoid1" style="border-color:{{$product['label_color_code']}};"><span class="h-sms">{{$product['label']}}</span></div>
                            @endif
                        </div>
                        <div class="col-6 text-end pt-2">
                            <div>
                                @if(in_array($product['id'], $wishlist)) 
                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img rounded-circle bg-white cursor">
                                @else
                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img rounded-circle bg-white cursor">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="detail-img text-center position-relative border">
                    <img src="{{asset('storage')}}/{{$images[0]}}" alt="image" class="w-100 demo-trigger" data-zoom="{{asset('storage')}}/{{$images[0]}}">
                    @if($stock_status=='out_of_stock')
                        <h5 class="sold-out-text text-nowrap">SOLD OUT</h5>
                    @endif
                </div>
                @if(count($images)>1)
                    <div class="detail_page_carousal pt-3">
                        <div id="detail-card-carousel" class="owl-carousel owl-theme d-flex justify-content-center">
                            @foreach($images as $key => $image)
                                <div class="item px-1 productItem">
                                    <div class="card p-2 {{($key==0)?'active':''}} cursor">
                                        <img src="{{asset('storage')}}/{{$image}}" alt="related-image{{$key}}">
                                    </div>
                                </div>
                            @endforeach                      
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 price-detail pt-xl-0 pt-lg-0 pt-sm-4 pt-md-0 pt-4">
            <div class="zoom-in"></div>
                <h3 class="text-dark prdct_name">{{ $product['name'] }}</h3>
                <div class="d-flex justify-content-start gap-4 py-2 price-discount align-items-center">
                    @if(isset($discount) && $discount!=0)
                        <del class="del-clr"><h6 class= "text-secondary opacity-50">{{ $ip_data->currency_symbol??'₹' }} {{ $price}}</h6></del>
                        <h6 class="price_clr">{{ $ip_data->currency_symbol??'₹' }} {{ $sale_price}}</h6>
                        <div class="card border-0">
                            <small class="card border-0 text-center text-white px-1">{{$discount}}% Off</small>
                        </div>
                    @else
                        <h6 class="price">{{ $ip_data->currency_symbol??'₹' }} {{ $price??0}}</h6>
                    @endif
                </div>
                <div class="d-flex py-2">
                    <h6 class="text-dark fw-bold">Availablity : </h6>
                    <h6 class="text-dark">&nbsp;@if(isset($stock_status) && $stock_status=='in_stock') In stock @else Out of stock @endif</h6>
                </div>
                <hr>
                <div class="d-flex  gap-2 align-items-center ">
                    @if($product['review']==0)
                    <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
                    @elseif($product['review']==1)
                    <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
                    @elseif($product['review']==2)
                    <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
                    @elseif($product['review']==3)
                    <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
                    @elseif($product['review']==4)
                    <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
                    @elseif($product['review']==5)
                    <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
                    @endif
                    <h6 class="fw-normal">{{$product['review_count']}} reviews</h6>
                </div>
                <div class="price-content pt-1">
                <h6 class="lh-base text-secondary text-opacity-75 fw-normal truncate-text">{!! Str::limit($product['description'], 100, '...') !!}</h6>
                </div>                    
                @php 
                    $selected_attributes_set_ids = array_keys(array_filter(array_filter($selected_attributes_set_ids, function($key) {
                                                        return $key !== "";
                                                    }, ARRAY_FILTER_USE_KEY)))
                @endphp
                @foreach($parent_attribute as $attribute)
                <div class="price py-1">
                    <h6 class="text-dark fw-bold">{{ucwords($attribute['name'])}}</h6>
                    <div class="d-flex gap-3 align-items-center py-1 attribute">
                        @foreach($attribute['sets'] as $attribute_set)
                            <label class="attribute-label parent-attribute-label card px-2 py-1 border-0 cursor {{ (in_array($attribute_set['id'], $selected_attributes_set_ids)? 'active': '')}}" id="{{$attribute_set['id']}}"><small>{{ucwords($attribute_set['name'])}}</small></label>
                            <input class="form-check-input d-none" type="checkbox" id="selected_attributes_set_ids{{$attribute_set['id']}}" wire:model="selected_attributes_set_ids.{{$attribute_set['id']}}">
                        @endforeach
                    </div>
                </div>
                @endforeach
                @foreach($attributes as $attribute)
                <div class="price py-1">
                    <h6 class="text-dark fw-bold">{{ucwords($attribute['name'])}}</h6>
                    <div class="d-flex gap-3 align-items-center py-1 attribute">
                        @foreach($attribute['sets'] as $attribute_set)
                            <label class="cursor {{ (count(array_intersect($parent_available_variant_ids, $attribute_set['available_variant_ids']))!=0)? 'attribute-label' :'outofstock text-dark'}} card px-2 py-1 border-0 {{ (in_array($attribute_set['id'], $selected_attributes_set_ids)? 'active': '')}}" id="{{$attribute_set['id']}}"><small>{{ucwords($attribute_set['name'])}}</small></label>
                            <input class="form-check-input d-none" type="checkbox" id="selected_attributes_set_ids{{$attribute_set['id']}}" wire:model="selected_attributes_set_ids.{{$attribute_set['id']}}">
                        @endforeach
                    </div>
                </div>
                @endforeach
                <div class="quantity PY-2">
                    <div class="d-flex gap-4 align-items-center">
                        <h6 class="text-dark fw-bold">Quantity :</h6>
                        <div class="d-flex gap-3 align-items-center pt-1">
                            <div class="qty-container d-flex align-items-center justify-content-center border p-1 rounded-1  text-dark">
                                <div class="col text-center px-2  qty-btn-minus"><span>-</span></div>
                                <div class="vr"></div>
                                <div class="col text-center px-2 "><span class="input-qty h-sms">1</span></div>
                                <div class="vr"></div>
                                <div class="col text-center px-2  qty-btn-plus"><span>+</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid px-0">
                    <div class=" ps-3 row align-items-center add-to-cart py-3 w-75 adadas">
                        @if($stock_status=='out_of_stock')
                            <a href="javascript:void(0)" class="col-6 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                <div class="card card2 border-0 p-3">
                                    <h5 class="text-white text-center fw-normal">Notify Me</h5>
                                </div>
                            </a>
                        @else
                            <a href="javascript:void(0);" class="col-6 AddCart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <div class="card card1 border-0 py-3">
                                    <h5 class="text-dark text-center fw-normal">Add to cart</h5>
                                </div>
                            </a>
                            @if(\Auth::check())
                                <a href="javascript:void(0);" class="col-6 AddCart" wire:click="checkout">
                                    <div class="card card2 border-0 p-3">
                                        <h5 class="text-white text-center fw-normal">Buy Now</h5>
                                    </div>
                                </a>
                            @else
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#signin" class="col-6 AddCart">
                                    <div class="card card2 border-0 p-3">
                                        <h5 class="text-white text-center fw-normal">Buy Now</h5>
                                    </div>
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
                <div class="d-flex gap-2 delivery-cnt align-self-center py-2">
                    <img src="{{asset('asset/home/Group 25167.png')}}" alt="delivery">
                    <h6 class="text-secondary fw-normal">Order Now to get it before {{ \Carbon\Carbon::now()->addWeek()->format('l, jS F'); }}</h6>
                </div>
                <div class="d-flex gap-xl-5 gap-lg-5 gap-sm-4 gap-md-4 gap-3 align-items-center delivery py-2 justify-content-start">
                    @foreach($buying_options as $option)
                        <div class="text-center">
                            <img src="{{asset('storage')}}/{{$option['image']}}" alt="">
                            <h6 class="text-dark h-sms pt-2">{{$option['name']}}</h6>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<section class="details py-5">
    <div class="container">
        <div class="d-flex gap-xl-5 gap-lg-5 gap-md-5 gap-sm-5 gap-4     justify-content-center pb-4 tab-head">
            <h6 class=" text-secondary opacity-75 fw-bold product-tab {{ ($tab=='description')?'activated':''}}" data-tab="description" >Product Description</h6>
            <h6 class="text-secondary opacity-75 fw-bold product-tab {{ ($tab=='information')?'activated':''}}" data-tab="information">Additional information</h6>
            <h6 class="text-secondary opacity-75 fw-bold product-tab {{ ($tab=='reviews'||$tab=='review')?'activated':''}}" data-tab="reviews">Reviews</h6>
        </div>
        <div id="description" class="tab-content {{ ($tab=='description')?'active':''}} ">
            <h6 class="lh-base  fw-normal h-sms">{!! $product['description'] !!}</h6>
        </div>
        <div id="information" class="tab-content fw-normal {{ ($tab=='information')?'active':''}} ">
            <h6 class="lh-base  fw-normal h-sms">{!! $product['content'] !!}</h6>
        </div>
        <div id="reviews" class="tab-content review fw-normal {{ ($tab=='reviews'||$tab=='review')?'active':''}} ">                
            @livewire('ecommerce.product.reviews',['id'=>$product_id])   
        </div>
    </div>
</section> 
@if(count($related_products)!=0)
<section class="liked_images" wire:ignore>
    <div class="container">
        <h5 class="text-dark fw-bold py-2 text-center hghg">YOU MIGHT ALSO LIKE</h5>
        <div class="row py-4">
            <div class="carousel-wrap">
                <div class="owl-carousel d-flex justify-content-center" id="related-images">
                    @foreach($related_products as $product)
                        <div class="item px-2">
                            <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                <div class="row pt-1  position-absolute w-100 ">
                                    <div class="col-6 tag">
                                        @if($product['stock_status']=='out_of_stock')
                                            <div class="ps-xl-2 ps-lg-2 ps-md-2 ps-sm-1 ps-0"><div class="card bg-secondary p-xl-2 p-lg-2 p-sm-2 p-md-2 p-1 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sms text-nowrap">sold out</h6></div></div>
                                        @elseif(!empty($product['label']))
                                            <div class="position-relative best-seller">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-img" viewBox="0 0 198 57" fill="none">
                                                    <g clip-path="url(#clip0_296_5)">
                                                        <path d="M0.933105 0.0898438H198L179.694 21.4394L198 44.2241H0.933105V0.0898438Z" fill="{{$product['label_color_code']}}"/>
                                                        <path d="M14.2766 44.2246V56.051L0.838867 44.2246H14.2766Z" fill="{{$product['label_color_code']}}"/>
                                                        <text x="10" y="28" fill="white" font-size="20">{{$product['label']}}</text>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_296_5">
                                                    <rect width="197.161" height="55.9617" fill="white" transform="translate(0.838867 0.0898438)"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>                                               
                                            </div>
                                        @endif  
                                    </div>
                                    <div class="col-6 tag d-flex justify-content-end pe-0 align-self-center">
                                        <div class=" rounded-circle bg-white">
                                            @if(in_array($product['id'], $wishlist)) 
                                                <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img likedislike" data-id="unlike">
                                            @else
                                                <img src="{{asset('asset/home/like.svg')}}" alt="un-like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" data-id="like">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                                    <div class="text-center position-relative">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image">
                                        @if(!empty($product['image2']))
                                        <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img  position-absolute  item-image">
                                        @endif
                                    </div> 
                                </a>
                                <div class="container-fluid position-absolute add-div">
                                    <button class="btn d-flex justify-content-center w-100 w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                        <h6 class="text-center text-white h-sms  text-nowrap ">Quick Shop &nbsp;&nbsp;</h6>
                                        <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                    </button>
                                    <!-- @if($product['product_type'] > 1)
                                        <button class="btn d-flex justify-content-center w-100 w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms  text-nowrap ">Quick Shop &nbsp;&nbsp;</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                        </button>
                                    @elseif($product['stock_status']=='out_of_stock')
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 w-100 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                            <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                        </button>                                    
                                    @else
                                        <div class=" row align-items-center add-to-cart">
                                            <div class=" col-xl-4 col-lg-4 col-sm-4 col-md-4 col-5 qty-container d-flex align-items-center justify-content-center bg-clr p-1 rounded-1 text-white">
                                                <div class="col text-center px-1 qty-btn-minus"><span>-</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center px-1"><span class="input-qty h-sms">1</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center px-1 qty-btn-plus"><span>+</span></div>
                                            </div>
                                            <div class="col-xl-8 col-lg-8 col-sm-8 col-md-8 col-7 pe-0 ps-1 hover-pading">
                                                <a href="javascript:void(0);" class="card d-flex py-1 px-lg-2 px-xl-3 px-md-3 px-sm-3 px-0 bg-clr rounded-1 border-0  flex-row align-self-center justify-content-center AddCart"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                                    <h6 class="text-white text-center py-1 px-xl-2 px-lg-2 px-md-0 px-sm-0 px-0 h-sm text-nowrap sys-view">Add to cart</h6>
                                                    <img src="{{asset('asset/home/cart.svg')}}" alt="cart" class="mbl-view mbl-cart-img">
                                                </a>
                                            </div>
                                        </div> 
                                    @endif -->
                                </div>
                            </div>
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name']}}</h6>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-6 px-0">
                                            <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                        </div>
                                        <div class="col-6 px-0">
                                            @if($product['discount']!=0)
                                            <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                            @else
                                            <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-6 col-12">
                                        @if($product['review']==0)
                                            <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==1)
                                            <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==2)
                                            <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==3)
                                            <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==4)
                                            <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==5)
                                            <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
                                        @endif
                                    </div>
                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-6 col-12 pt-1">
                                        <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@if(count($frequently_bought_products)!=0)
<section class="liked_images" wire:ignore>
    <div class="container">
        <h5 class="text-dark fw-bold py-2 text-center hghg">FREQUENTLY BOUGHT TOGETHER</h5>
        <div class="row py-4">
            <div class="carousel-wrap">
                <div class="owl-carousel d-flex justify-content-center" id="frequent-images">                    
                    @foreach($frequently_bought_products as $product)
                        <div class="item px-2">
                            <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                <div class="row pt-1  position-absolute w-100 ">
                                    <div class="col-6 tag">
                                        @if($product['stock_status']=='out_of_stock')
                                            <div class="ps-xl-2 ps-lg-2 ps-md-2 ps-sm-1 ps-0"><div class="card bg-secondary p-xl-2 p-lg-2 p-sm-2 p-md-2 p-1 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sms text-nowrap">sold out</h6></div></div>
                                        @elseif(!empty($product['label']))
                                            <div class="position-relative best-seller">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-img" viewBox="0 0 198 57" fill="none">
                                                    <g clip-path="url(#clip0_296_5)">
                                                        <path d="M0.933105 0.0898438H198L179.694 21.4394L198 44.2241H0.933105V0.0898438Z" fill="{{$product['label_color_code']}}"/>
                                                        <path d="M14.2766 44.2246V56.051L0.838867 44.2246H14.2766Z" fill="{{$product['label_color_code']}}"/>
                                                        <text x="10" y="28" fill="white" font-size="20">{{$product['label']}}</text>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_296_5">
                                                    <rect width="197.161" height="55.9617" fill="white" transform="translate(0.838867 0.0898438)"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>                                               
                                            </div>
                                        @endif  
                                    </div>
                                    <div class="col-6 tag d-flex justify-content-end pe-0 align-self-center">
                                        <div class=" rounded-circle bg-white">
                                            @if(in_array($product['id'], $wishlist)) 
                                                <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img likedislike" data-id="unlike">
                                            @else
                                                <img src="{{asset('asset/home/like.svg')}}" alt="un-like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" data-id="like">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                                    <div class="text-center position-relative">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image">
                                        @if(!empty($product['image2']))
                                        <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img  position-absolute  item-image">
                                        @endif
                                    </div> 
                                </a>
                                <div class="container-fluid position-absolute add-div">
                                    <button class="btn d-flex justify-content-center w-100 w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                        <h6 class="text-center text-white h-sms  text-nowrap ">Quick Shop &nbsp;&nbsp;</h6>
                                        <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                    </button>
                                    <!-- @if($product['product_type'] > 1)
                                        <button class="btn d-flex justify-content-center w-100 w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms  text-nowrap ">Quick Shop &nbsp;&nbsp;</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                        </button>
                                    @elseif($product['stock_status']=='out_of_stock')
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 w-100 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                            <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                        </button>                                    
                                    @else
                                        <div class=" row align-items-center add-to-cart">
                                            <div class=" col-xl-4 col-lg-4 col-sm-4 col-md-4 col-5 qty-container d-flex align-items-center justify-content-center bg-clr p-1 rounded-1 text-white">
                                                <div class="col text-center px-1 qty-btn-minus"><span>-</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center px-1"><span class="input-qty h-sms">1</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center px-1 qty-btn-plus"><span>+</span></div>
                                            </div>
                                            <div class="col-xl-8 col-lg-8 col-sm-8 col-md-8 col-7 pe-0 ps-1 hover-pading">
                                                <a href="javascript:void(0);" class="card d-flex py-1 px-lg-2 px-xl-3 px-md-3 px-sm-3 px-0 bg-clr rounded-1 border-0  flex-row align-self-center justify-content-center AddCart"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                                    <h6 class="text-white text-center py-1 px-xl-2 px-lg-2 px-md-0 px-sm-0 px-0 h-sm text-nowrap sys-view">Add to cart</h6>
                                                    <img src="{{asset('asset/home/cart.svg')}}" alt="cart" class="mbl-view mbl-cart-img">
                                                </a>
                                            </div>
                                        </div> 
                                    @endif -->
                                </div>
                            </div>
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name']}}</h6>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-6 px-0">
                                            <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                        </div>
                                        <div class="col-6 px-0">
                                            @if($product['discount']!=0)
                                            <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                            @else
                                            <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-6 col-12">
                                        @if($product['review']==0)
                                            <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==1)
                                            <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==2)
                                            <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==3)
                                            <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==4)
                                            <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
                                            @elseif($product['review']==5)
                                            <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
                                        @endif
                                    </div>
                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-6 col-12 pt-1">
                                        <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
</div>
@push('scripts')
<script>
    $(document).on('click', '.detail-card .parent-attribute-label', function () { 
        var id = $(this).attr('id');
        @this.set('parent_attribute_set_id', id);
    });
    $(document).on('click','.product-tab', function(){
        var tab = $(this).data('tab');
        @this.set('tab', tab);
    });
    
    @if(\Auth::check())
        var tab = '{{ $tab }}';
        var is_purchased = '{{ $product["is_purchased"] }}';
        var is_reviewed = '{{ $product["is_reviewed"] }}';
        
        if(tab=='review' && is_purchased=='yes' && is_reviewed=='no'){
            $('html, body').animate({
                scrollTop: $('.details').offset().top
            }, 500);
        }
    @endif
</script>
<script src="{{asset('asset/livewire/js/dtail.js')}}"></script>
@endpush