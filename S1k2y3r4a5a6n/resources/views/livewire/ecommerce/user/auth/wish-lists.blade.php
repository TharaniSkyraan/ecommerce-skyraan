<div>
    <section class="wishlist">
        <div class="container">
            <div class="row py-xl-3  py-lg-3 py-md-3 py-sm-3 py-2">
                @forelse($products as $product)
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
                                    <img src="{{ $product['image1'] }}" alt="list_items" class="w-100 default-img item-image" style="height:250px; object-fit:contain;">
                                    @if(!empty($product['image2']))
                                    <img src="{{ $product['image2'] }}" alt="list_items_hover" class="w-100 hover-img  position-absolute  item-image pt-3"  style="height:250px; object-fit:contain;">
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
                @empty
                    <div class="col-12 no-product">
                        <img src="{{asset('asset/home/empty-wishlist-placeholder.svg')}}" alt="no-product">
                        <h6 class="pt-2">No wishlist found !</h6>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
