<div class="grid-cards py-3">
    <div class="load hidden">{{$pageloading}}</div>
    @if($view=='two')
        <div id="grid-two">
            <div class="row">
                @forelse($products as $product)
                    <div class="col-xl-6 col-lg-6 col-sm-6 col-6 pb-3">
                        <div class="div px-2 box-shawdow pt-2 prdDet cursor">
                            <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}"  data-slug="{{ $product['slug'] }}" data-prdref="{{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                                <div class="container-fluid position-absolute reviews-div">
                                    <div class="row pt-1">
                                        <div class="col-6 px-0">
                                            @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                                <div class="ps-xl-2 ps-lg-2 ps-md-2 ps-sm-1 px-0 "><div class="card bg-secondary p-xl-2 p-lg-2 p-sm-2 p-md-2 p-1 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sm text-nowrap">Sold Out</h6></div></div>
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
                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" data-id="unlike" class="like_img likedislike">
                                                @else
                                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" data-id="like" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" >
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="position-relative text-center">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="product_img default-img">
                                        @if(!empty($product['image2']))
                                        <img src="{{ $product['image2'] }}" alt="list_items_hover" class="product_img hover-img  position-absolute">
                                        @endif
                                    </div>
                                <div class="container-fluid position-absolute add-div ps-xl-1 ps-lg-1 ps-sm-1 ps-md-1 ps-0">
                                    @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                            <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                        </button>
                                    @else
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms text-nowrap">Quick Shop &nbsp;&nbsp;</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img-bottom">
                                        </button>
                                    @endif
                                </div>
                            </div> 
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name'] }}</h6>
                                    <div class="container-fluid">
                                        <div class="row align-items-center">
                                        @if($product['discount']!=0)
                                            <div class="col-xl-2 col-lg-3 col-md-2 col-sm-3 col-6 px-0">
                                                <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-2 col-sm-3 col-6 px-0">
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-2 col-sm-4 col-12 px-0"><small class="off h-sms">{{$product['discount']}}% off</small></div>
                                        @else
                                            <div class="col-6 px-0">
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                            </div>
                                        @endif
                                        </div>
                                    </div>                                
                                    <div class="row align-items-center">
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
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
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-6 col-12">
                                            <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 no-product pt--5">
                        <img src="{{asset('asset/home/no-product.svg')}}" alt="no-product">
                        <h6 class="pt-2">No products found !</h6>
                    </div>
                @endforelse
                @if($morepage)
                    <div wire:loading.remove wire:target="loadMore" id="load-more" class="text-center">
                        <button wire:click="loadMore">
                             <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <rect x="0" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="10" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="20" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                            </svg> <br> Loading..
                        </button>
                    </div>
                    <div wire:loading wire:target="loadMore">
                         <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                            <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                        </svg> <br> Loading..
                    </div>
                @endif
            </div>
        </div>
    @elseif($view=='three')
        <div id="grid-tre">
            <div class="row">
                @forelse($products as $product)
                    <div class="col-xl-4 col-lg-4 col-sm-4 col-6 pb-3 ">
                        <div class="div px-2 pt-2 prdDet cursor">
                            <div  class="card border-0 round-1 p-1 PrdRow cursor " data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}"   data-slug="{{ $product['slug'] }}" data-prdref="{{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                                <div class="container-fluid position-absolute reviews-div">
                                    <div class="row pt-1">
                                        <div class="col-6 px-0">
                                            @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                                <div class="ps-xl-2 ps-lg-2 ps-md-2 ps-sm-1 px-0"><div class="card bg-secondary p-2 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sm text-nowrap">Sold Out</h6></div></div>
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
                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" data-id="unlike" class="like_img likedislike">
                                                @else
                                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" data-id="like" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" >
                                                @endif
                                            </div>
                                        </div>
                                    </div>   
                                </div>                    
                                    <div class="text-center position-relative">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="product_img default-img">
                                        @if(!empty($product['image2']))
                                            <img src="{{ $product['image2'] }}" alt="list_items_hover" class="product_img hover-img position-absolute">
                                        @endif
                                    </div> 
                                <div class="container-fluid position-absolute add-div ps-xl-1 ps-lg-1 ps-sm-1 ps-md-1 ps-0">
                                    @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                            <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                        </button>
                                    @else
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms text-nowrap">Quick Shop &nbsp;&nbsp;</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img-bottom">
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name']}}</h6>
                                <div class="container-fluid">
                                    <div class="row align-items-center">
                                        @if($product['discount']!=0)
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-6 px-0">
                                                <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-6 px-0">
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-12 px-0"><small class="off h-sms">{{$product['discount']}}% off</small></div>
                                        @else
                                            <div class="col-6 px-0">                                           
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-6 col-12">
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
                                    <div class="col-xl-8 col-lg-7 col-md-7 col-sm-6 col-12">
                                        <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 no-product pt--5">
                        <img src="{{asset('asset/home/no-product.svg')}}" alt="no-product">
                        <h6 class="pt-2">No products found !</h6>
                    </div>
                @endforelse
                @if($morepage)
                    <div wire:loading.remove wire:target="loadMore" id="load-more" class="text-center">
                        <button wire:click="loadMore">
                             <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <rect x="0" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="10" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="20" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                            </svg> <br> Loading..
                        </button>
                    </div>
                    <div wire:loading wire:target="loadMore">
                         <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                            <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                        </svg> <br> Loading..
                    </div>
                @endif
            </div>
        </div> 
    @elseif($view=='four')
        <div id="grid-for" class="sys-views">
            <div class="row">
                @forelse($products as $product)
                    <div class="col-xl-3 col-lg-3 col-sm-3 col-6 pb-3">
                        <div class="div px-2 box-shawdow pt-2 prdDet cursor">
                            <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}"   data-slug="{{ $product['slug'] }}" data-prdref="{{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                                <div class="container-fluid position-absolute reviews-div">
                                    <div class="row pt-1">
                                        <div class="col-6 px-0">
                                            @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                                <div class="ps-xl-1 ps-lg-1 ps-md-1 ps-sm-1 px-0"><div class="card bg-secondary p-2 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sm text-nowrap">Sold Out</h6></div></div>
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
                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" data-id="unlike" class="like_img likedislike">
                                                @else
                                                    <img src="{{asset('asset/home/like.svg')}}" alt="un-like" data-id="like" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" >
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                                    <div class="text-center position-relative">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="product_img default-img">
                                        @if(!empty($product['image2']))
                                            <img src="{{ $product['image2'] }}" alt="list_items_hover" class="hover-img position-absolute product_img">
                                        @endif
                                    </div> 

                                <div class="container-fluid position-absolute add-div ps-xl-1 ps-lg-1 ps-sm-1 ps-md-1 ps-0">
                                    @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                            <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                        </button>
                                    @else
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms text-nowrap">Quick Shop &nbsp;&nbsp;</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img-bottom">
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name']}}</h6>
                                <div class="container-fluid">
                                    <div class="row align-items-center">
                                        @if($product['discount']!=0)
                                            <div class="col-4 px-0">
                                                <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                            </div>
                                            <div class="col-4 px-0">
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 px-0"><small class="off h-sms">{{$product['discount']}}% off</small></div>
                                        @else
                                            <div class="col-12 px-0">
                                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>                                
                                <div class="row align-items-center">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
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
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h6 class="text-secondary text-opacity-50 text-nowrap h-sms">{{$product['review_count']}} reviews</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 no-product pt--5">
                        <img src="{{asset('asset/home/no-product.svg')}}" alt="no-product">
                        <h6 class="pt-2">No products found !</h6>
                    </div>
                @endforelse
                @if($morepage)
                    <div wire:loading.remove wire:target="loadMore" id="load-more" class="text-center">
                        <button wire:click="loadMore">
                             <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <rect x="0" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="10" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                                <rect x="20" y="0" width="4" height="10" fill="#333">
                                <animateTransform attributeType="xml"
                                    attributeName="transform" type="translate"
                                    values="0 0; 0 20; 0 0"
                                    begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                                </rect>
                            </svg> <br> Loading..
                        </button>
                    </div>
                    <div wire:loading wire:target="loadMore">
                         <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                            <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                        </svg> <br> Loading..
                    </div>
                @endif
            </div>
        </div> 
    @elseif($view=='one')
        <div id="grid-one">
            @forelse($products as $product)
                <div class="row align-items-center py-2 box-shawdow px-3 prdDet cursor">
                    <div class="col-xl-3 col-xxl-3 col-lg-3 col-md-3 col-sm-3 col-12 ">
                        <div  class="card border-0 round-1 p-1 PrdRow cursor " data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}"   data-slug="{{ $product['slug'] }}" data-prdref="{{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}">
                            <div class="container-fluid position-absolute reviews-div">
                                <div class="row pt-1">
                                    <div class="col-6 px-0">
                                        @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                            <div class="ps-xl-1 ps-lg-1 ps-md-1 ps-sm-1 px-0"><div class="card bg-secondary p-2 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sm text-nowrap">Sold Out</h6></div></div>
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
                                            <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" data-id="unlike" class="like_img likedislike">
                                        @else
                                            <img src="{{asset('asset/home/like.svg')}}" alt="un-like" data-id="like" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif class="like_img {{ (\Auth::check())?'likedislike':''}}" >
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative text-center">
                                <img src="{{ $product['image1'] }}" alt="list_items" class="product_img default-img ">
                                @if(!empty($product['image2']))
                                    <img src="{{ $product['image2'] }}" alt="list_items_hover" class="product_img hover-img position-absolute">
                                @endif
                            </div>
                        </div>   
                    </div>
                    <div class="col-xl-9 col-xxl-9 col-lg-9 col-md-9 col-sm-9 col-12">
                        <div class="price_info py-3">
                            <h6 class="text-dark fw-bold align-self-center h-sms max-height">{{ $product['name']}}</h6>
                            <div class="d-flex gap-3 align-items-center">
                                @if($product['discount']!=0)
                                <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</h6>
                                <div class=" px-0"><small class="off h-sms">{{$product['discount']}}% off</small></div>
                                @else
                                <h6 class="price fw-bold lh-lg align-self-center h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</h6>
                                @endif
                            </div>
                            <h6 class="text-secondary text-opacity-50 pb-2 lh-base h-sms">Ajwain The benefits of these seeds are not only limited to the taste but go much beyond that. The goodness of these tiny fruit pods also includes weight loss. Ajwain...</h6>
                            <div class="d-flex gap-3 align-items-center pb-2">
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
                                <h6 class="text-secondary text-opacity-50 ">{{$product['review_count']}} reviews</h6>
                            </div>
                            @if($product['stock_status']=='out_of_stock' && !empty($zone_data['warehouse_ids']))
                                <button class="btn d-flex justify-content-center align-items-center bg-clr add-to-cart rounded-1 {{ (\Auth::check())?'NotifyMe':''}}" @if(!(\Auth::check())) data-bs-toggle="modal" data-bs-target="#signin" @endif>
                                    <h6 class="text-center text-white h-sms text-nowrap">Notify Me</h6>
                                </button>
                            @else
                                <button class="btn d-flex justify-content-center align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                    <h6 class="text-center text-white h-sms text-nowrap">Quick Shop &nbsp;&nbsp;</h6>
                                    <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                    <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img-bottom">
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 no-product pt--5">
                    <img src="{{asset('asset/home/no-product.svg')}}" alt="no-product">
                    <h6 class="pt-2">No products found !</h6>
                </div>
            @endforelse
            @if($morepage)
                <div wire:loading.remove wire:target="loadMore" id="load-more" class="text-center">
                    <button wire:click="loadMore">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                            <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                            <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml"
                                attributeName="transform" type="translate"
                                values="0 0; 0 20; 0 0"
                                begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                            </rect>
                        </svg> <br> Loading..
                    </button>
                </div>
                <div wire:loading wire:target="loadMore">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <rect x="0" y="0" width="4" height="10" fill="#333">
                        <animateTransform attributeType="xml"
                            attributeName="transform" type="translate"
                            values="0 0; 0 20; 0 0"
                            begin="0" dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="10" y="0" width="4" height="10" fill="#333">
                        <animateTransform attributeType="xml"
                            attributeName="transform" type="translate"
                            values="0 0; 0 20; 0 0"
                            begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="20" y="0" width="4" height="10" fill="#333">
                        <animateTransform attributeType="xml"
                            attributeName="transform" type="translate"
                            values="0 0; 0 20; 0 0"
                            begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                        </rect>
                    </svg> <br> Loading..
                </div>
            @endif
        </div> 
    @endif
</div>
@push('scripts')
<script src="{{asset('asset/livewire/js/plist.js')}}"></script>
@endpush
