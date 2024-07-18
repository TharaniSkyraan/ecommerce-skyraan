<div>
    @if(count($banners)!=0)
        <section class="banner jkjew" wire:ignore>
            <div class="top_carousal">
                <div id="owl-example" class="owl-carousel">
                    @foreach($banners as $banner)
                    <div class="owl-slide">
                        <div class="">
                            @if(empty($banner['product_slug']))
                            <a href="{{ route('ecommerce.product.list', ['type' => 'product-collection', 'slug' => $banner['slug']]) }}">
                            @else
                            <a href="{{ route('ecommerce.product.detail', ['slug' => $banner['product_slug']]) }}?prdRef={{ \Carbon\Carbon::parse($banner['product_created'])->timestamp}}">
                            @endif
                            <img src="{{ asset('storage') }}/{{$banner['image']}}" alt="image" class="bnr-img">
                                <button class="owl--text d-flex py-xl-1  py-lg-1 py-md-1 py-sm-1 py-0 py-0 px-xl-3 px-lg-3 px-md-3 px-sm-3 px-1 align-items-center ">
                                    <h6 class="text-dark">visit product</h6>
                                </button>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <main>
        @if(count($categories)!=0)
        <section class="home_first_carousel" wire:ignore>
            <div class="container-fluid">
                <div class="row py-3">
                    <h5 class="text-center fw-bold">OUR TOP CATEGORIES</h5>
                </div>
            </div>
            <div id="home_first_carousel" class="owl-carousel jkjew px-xl-5 px-lg-5 px-md-4 px-sm-4 px-0">
                @foreach($categories as $key => $category)                
                    <div class="owl-slide">
                        <a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category['slug']]) }}" class="owl--text d-flex py-1 px-3 justify-content-center">
                            <div class="p-1 card {{ $key % 2 == 0 ? 'card1' : 'card2' }} {{ $key % 2 == 0 ? 'bg-lites' : 'bg-darks' }} border-0 round-2">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 align-self-center pe-0">
                                            <h6 class="title-cnt text-center lh-base h-sms">{{ $category['description'] }}</h6>
                                            <h5 class="title-cnt text-center lh-base fw-bold">{{ $category['name'] }}</h5>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                            @if($key % 2 == 0)
                                                <img src="{{ asset('asset/home/bg-lite.svg') }}" alt="bg-img">
                                            @else
                                                <img src="{{ asset('asset/home/bg-dark.svg') }}" alt="bg-img">
                                            @endif
                                            <img src="{{ asset('storage') }}/{{ $category['logo'] }}" alt="image" class="position-absolute ori-img">
                                        </div>
                                    </div>
                                </div>
                            </div>           
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
        @if(count($promotion_banners)!=0)
        <section class="top_cntr_card py-4">
            <div class="container">
                <div class="row">
                    @foreach($promotion_banners as $key => $promotion_banner)
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 pb-3">
                            @if(empty($promotion_banner['product_slug']))
                                <a href="{{ route('ecommerce.product.list', ['type' => 'product-collection', 'slug' => $promotion_banner['slug']]) }}">
                            @else
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $promotion_banner['product_slug']]) }}?prdRef={{ \Carbon\Carbon::parse($promotion_banner['product_created'])->timestamp}}">
                            @endif
                                <div class="card card1 border-0 round-2">
                                    <img src="{{ asset('storage') }}/{{$promotion_banner['image']}}" alt="image">
                                </div> 
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @if(count($top_selling_products)!=0)
        <section class="item-list">
            <div class="container">
                <div class="row pb-3">
                    <h5 class="text-center fw-bold with-horizontal-line">OUR TOP SELLING</h5>
                </div>
                <div class="row pb-xl-5 pb-lg-5 pb-md-3 pb-sm-3 pb-0">
                    @forelse($top_selling_products as $product)
                        <div class="col-xl-3 col-lg-3 col-sm-4 col-md-4 col-6 pb-4 ">
                            <div class="div px-2 ">
                                <div class="card border-0 round-1 p-1 PrdRow cursor h-100" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                    <div class="container-fluid">
                                    <div class="row pt-1 position-absolute w-100 reviews-div">
                                        <div class="col-6 px-0">
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
                                        <div class="col-6 d-flex justify-content-end pe-0 align-self-center">
                                            <div class=" rounded-circle bg-white">
                                                @if(in_array($product['id'], $wishlist)) 
                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img">
                                                @else
                                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else  data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}">
                                        <div class="text-center position-relative">
                                            <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image">
                                            @if(!empty($product['image2']))
                                            <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img  position-absolute  item-image pt-3">
                                            @endif
                                        </div> 
                                    </a>
                                    <div class="container-fluid position-absolute add-div">
                                        @if($product['product_type'] > 1)
                                            <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                                <h6 class="text-center text-white h-sms text-nowrap ">Quick Shop </h6>
                                                <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                            </button>
                                        @elseif($product['stock_status']=='out_of_stock')
                                            <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
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
                                        @endif
                                    </div>
                                </div>
                                <div class="price_info py-2">
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
                                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-6 col-12">
                                            <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @if(count($special_products)!=0)
        <section class="home_sec_carousel sys-view tab-view" wire:ignore>
            <div class="container-fluid">
                <div class="row py-2">
                    <div class="d-flex justify-content-center gap-2 heading-cnt pb-4">
                        <h5 class="text-center fw-bold">{{$siteSetting->site_name}} SPECIAL PRODUCTS</h5>
                        <img src="{{asset('asset/home/special-product.png')}}" alt="" >
                    </div>
                </div>
                <div id="home_sec_carousel" class=" row px-5 pb-5">
                    @foreach($special_products as $special_product)
                    <div class="col-6">
                    <div class="card border-0 round-2 p-2 ">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <img src="{{ asset('storage') }}/{{ $special_product['image'] }}" alt="" class=" center-card">
                                </div>
                                <div class="col-8 align-self-center">
                                    <h3 class="text-end fw-bold">{{ $special_product['name'] }}</h3>
                                    <div class=" d-flex justify-content-end pt-2">
                                        @if(empty($special_product['product_slug']))
                                            <a href="{{ route('ecommerce.product.list', ['type' => 'product-collection', 'slug' => $special_product['slug']]) }}" class="but_this d-flex align-items-center">
                                        @else
                                            <a href="{{ route('ecommerce.product.detail', ['slug' => $special_product['product_slug']]) }}?prdRef={{ \Carbon\Carbon::parse($special_product['product_created'])->timestamp}}"  class="but_this d-flex align-items-center">
                                        @endif
                                            <h6 class="">Buy this item</h6>
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @if(count($new_products)!=0)
        <section class="item-list">
            <div class="container">
                <div class="row py-4">
                <h5 class="text-center fw-bold with-horizontal-line">HURRY UP! LIMITED TIME ONLY</h5>
                </div>
                <div class="row pb-5">
                    @forelse($new_products as $product)
                        <div class="col-xl-3 col-lg-3 col-sm-4 col-md-4 col-6 pb-3">
                            <div class="div px-2">
                                <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                    <div class="container-fluid px-0">
                                    <div class="row pt-1 position-absolute w-100 reviews-div">
                                        <div class="col-6">
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
                                        <div class="col-6 d-flex justify-content-end pe-0 align-self-center">
                                            <div class=" rounded-circle bg-white">
                                                @if(in_array($product['id'], $wishlist)) 
                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')"class="like_img">
                                                @else
                                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}">
                                        <div class="text-center position-relative">
                                            <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image">
                                            @if(!empty($product['image2']))
                                            <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img  position-absolute item-image pt-3">
                                            @endif
                                        </div> 
                                    </a>
                                    <div class="container-fluid position-absolute add-div">
                                        @if($product['product_type'] > 1)
                                            <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                                <h6 class="text-center text-white h-sms text-nowrap">Quick Shop</h6>
                                                <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                            </button>
                                        @elseif($product['stock_status']=='out_of_stock')
                                            <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                                <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                            </button>                                    
                                        @else
                                            <div class=" row align-items-center add-to-cart">
                                                <div class="col-xl-4 col-lg-4 col-sm-4 col-md-4 col-5 qty-container d-flex align-items-center justify-content-center bg-clr p-1 rounded-1 text-white">
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
                                        @endif
                                    </div>
                                    </div>
                                </div>
                                <div class="price_info py-2">
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
                                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-6 col-12">
                                            <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @if(count($top_selling_products)!=0)
        <section class="card_and_carousal">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 sys-view">
                        <a href="javascript:void(0);" class="position-relative d-block">
                            <div class="card card1 border-0 rounded-3 d-flex justify-content-center cursor">
                                <div class="text-center d-flex justify-content-center">
                                    <img src="{{ $top_selling_products[0]['image1'] }}" alt="top_selling" class="w-50">
                                </div>
                                <div class="position-absolute bottom-0 start-50 translate-middle-x">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $top_selling_products[0]['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($top_selling_products[0]['created_at'])->timestamp}}">
                                        <div class="d-flex py-1 px-3 bg-clr buy-now flex-row align-items-center justify-content-center">
                                            <h6 class="text-white text-nowrap">Buy now</h6>
                                        </div> 
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12 px-0">
                        <div class="row ps-3">
                            <h5 classs="fw-bold ">HEROES OF THE WEEK</h5>
                        </div>
                        <div id="card_and_carousal" class="owl-carousel px-2 pt-4" wire:ignore>
                            @forelse($top_selling_products as $key => $product)
                                @if($key!=0)
                                <div class="px-2">
                                    <div class="owl-slide px-2">
                                        <div class="card card2 cursor border-0 round-1 p-1 PrdRow" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                            <div class="container-fluid position-absolute reviews-div">
                                                <div class="row pt-1">
                                                    <div class="col-6 px-0">
                                                        @if($product['stock_status']=='out_of_stock')
                                                        <div class="ps-xl-1 ps-lg-1 ps-md-1 ps-sm-1 ps-0"><div class="card bg-secondary p-2 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center">sold out</h6></div></div>
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
                                                    <div class="col-6 d-flex justify-content-end align-self-center">
                                                        <div class="rounded-circle bg-white">
                                                            @if(in_array($product['id'], $wishlist)) 
                                                                <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" data-id="unlike" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img likedislike">
                                                            @else
                                                                <img src="{{asset('asset/home/like.svg')}}" alt="un-like" data-id="like" @if(\Auth::check()) wire:click.prevent="addremoveWish('{{ $product['id'] }}')" @else data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" >
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}">
                                                <div class="text-center position-relative">
                                                    <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image">
                                                    @if(!empty($product['image2']))
                                                    <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img position-absolute item-image ">
                                                    @endif
                                                </div> 
                                            </a>                                
                                            <div class="container-fluid position-absolute add-div">                                            
                                                @if($product['product_type'] > 1)
                                                    <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                                        <h6 class="text-center text-white h-sms text-nowrap">Quick Shop</h6>
                                                        <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                                    </button>
                                                @elseif($product['stock_status']=='out_of_stock')
                                                    <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                                        <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                                    </button>
                                                @else
                                                    <div class="row align-items-center add-to-cart">
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
                                                @endif
                                            </div>
                                        </div>
                                        <div class="price_info py-2">
                                            <h6 class="text-dark fw-bold align-self-center max-height h-sms">{{ $product['name']}}</h6>
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
                                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <h6 class="text-secondary text-opacity-50 text-nowrap h-sms pt-1">{{$product['review_count']}} reviews</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endif
                            @endforeach                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        @if($why_choose->isNotEmpty())
        <section class="why-choose sys-view" wire:ignore>
            <div class="container  py-5 ">
                <div class="row">
                    <div class="col-3">
                        <div class="why_bnr p-3  d-table">
                            <div class="d-table-cell align-middle">
                                <h5 class="text-white fw-normal">WHY CHOOSE</h5>
                                <h3 class="text-white fw-bold pt-2">{{$siteSetting->site_name}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-9 align-self-center">
                        <div id="why-choose" class="owl-carousel">
                            @foreach($why_choose as $why_chooses)
                            <div class="owl-slide ">
                                <div class="card px-3 py-xl-0 py-lg-0 py-md-3 py-sm-3 py-0 border-0" >
                                    <div class="row">
                                        <div class="col-5 d-flex justify-content-center">
                                            <img src="{{ asset('storage') }}/{{$why_chooses->why_chs_img}}" alt="image" class="w-75 py-3" >
                                        </div>
                                        <div class="col-7 align-self-center">
                                            <h5 class="text-dark fw-bold">{{$why_chooses->why_chs_title}}</h6>
                                            <h6 class="text-dark pt-2 lh-base fw-normal">{{$why_chooses->why_chs_desc}}</h6>
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
        <section class="mbl-view"  wire:ignore>
            <div class="row py-4">
                <div><h5 class="fw-bold text-secondary opacity-50 text-center">WHY CHOOSE </h5></div>
                <div><h3 class="fw-bold text-secondary opacity-75 text-center">SKYRAA ORGANIC </h3></div>
                <div class="px-5 py-2">
                    <div id="why-choosnne" class="owl-carousel">
                        <div class="owl-slide">
                            <div class="card border-0 p-3">
                                <img src="{{asset('asset/home/Group 32129.png')}}" alt="">
                                <h6 class="lh-base">Organic foods retain a higher concentration of essential vitamins and minerals compared to conventionally grown counterparts, providing a nutrient-rich option for those seeking a healthier and more wholesome diet.</h6>
                            </div>
                        </div>
                        <div class="owl-slide">
                            <div class="card border-0 p-3">
                                <img src="{{asset('asset/home/Group 32129.png')}}" alt="">
                                <h6 class="lh-base">Organic foods retain a higher concentration of essential vitamins and minerals compared to conventionally grown counterparts, providing a nutrient-rich option for those seeking a healthier and more wholesome diet.</h6>
                            </div>
                        </div>
                        <div class="owl-slide">
                            <div class="card border-0 p-3">
                                <img src="{{asset('asset/home/Group 32129.png')}}" alt="">
                                <h6 class="lh-base">Organic foods retain a higher concentration of essential vitamins and minerals compared to conventionally grown counterparts, providing a nutrient-rich option for those seeking a healthier and more wholesome diet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if($reviews->isNotEmpty())
        <section class="abt_review" wire:ignore>
            <div class="container-fluid">
                <div class="row">
                    <h5 class="text-white text-center py-3">WHAT OUR CUSTOMERS SAY!</h5>
                </div>
            </div>
            <div id="abt_review" class="owl-carousel jkjew px-xl-5 px-lg-5 px-md-5 px-sm-5 px-4 pb-3">
                @foreach($reviews as $reviews_data)
                <div class="owl-slide px-3">
                    <a  href="javascript:void(0);" class="">
                        <div class="p-4 card  border-0 round-2">
                            <div class="row">
                                <div class="text-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="46.437" height="41.588" viewBox="0 0 46.437 41.588">
                                        <g id="Group_125" data-name="Group 125" transform="translate(-292.227 -5549.614)">
                                            <path id="Path_238" data-name="Path 238" d="M13.6-94.567q7.388-2.315,8.956-5.788A27.4,27.4,0,0,0,24.5-105.7l.3-3.211H13.227v-19.479H34.272v15.6q0,10.751-5.261,17.322T13.6-86.8Z" transform="translate(279 5678)" fill="none"/>
                                            <path id="Path_239" data-name="Path 239" d="M13.6-94.567q7.388-2.315,8.956-5.788A27.4,27.4,0,0,0,24.5-105.7l.3-3.211H13.227v-19.479H34.272v15.6q0,10.751-5.261,17.322T13.6-86.8Z" transform="translate(304.391 5678)" fill="none"/>
                                        </g>
                                    </svg>                                
                                </div>
                                <div class="py-3">
                                    <h6 class="text-start lh-base ">{{$reviews_data->commends}}</h6>
                                </div>
                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                    <div class="line-with-text"></div>
                                    <div>
                                    <h6 class=" text-end lh-base fw-bold">{{$reviews_data->user->name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>           
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        <section class="marquee_content ">
            <div class="marquee py-2">
                <div class="marquee__group">
                    @foreach($collections as $collection)
                        <div class="d-flex gap-2 align-items-center marquee-item">
                            <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="feature_image">
                            <h5 class="fw-normal">{{$collection['name']}}</h5>
                        </div>
                    @endforeach
                </div>
                <div aria-hidden="true" class="marquee__group">
                    @foreach($collections as $collection)
                        <div class="d-flex gap-2 align-items-center marquee-item">
                            <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="feature_image">
                            <h5 class="fw-normal">{{$collection['name']}}</h5>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>    
        @livewire('ecommerce.product.collection-list')
    </main>
</div>
