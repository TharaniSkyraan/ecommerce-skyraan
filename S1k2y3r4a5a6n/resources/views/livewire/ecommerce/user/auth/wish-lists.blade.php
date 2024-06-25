<div>
    <section class="wishlist">
        <div class="container">
            <div class="row py-5">
                @forelse($products as $product)
                    <div class="col-xl-3 col-lg-3 col-sm-3 col-6">
                        <div class="div px-2">
                            <div class="card border-0 round-1 p-1 PrdRow cursor" data-id="{{ $product['id'] }}" data-variant-id="{{ $product['variant_id'] }}">
                                <div class="row pt-1  position-absolute w-100 reviews-div">
                                    <div class="col-6">
                                        @if($product['stock_status']=='out_of_stock')
                                            <div class="ps-xl-1 ps-lg-1 ps-md-1 ps-sm-1 ps-0"><div class="card bg-secondary p-2 border-0 rounded-0  bg-opacity-50"><h6 class="text-white fw-bold text-center h-sms text-nowrap">sold out</h6></div></div>
                                        @elseif(!empty($product['label']))
                                            <img src="{{ $product['label'] }}" alt="best_seller" class="w-100 best_seller">
                                        @endif   
                                    </div>
                                    <div class="col-6 d-flex justify-content-end pe-0 align-self-center">
                                        <div class="rounded-circle bg-white">
                                            <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" wire:click.prevent="addremoveWish('{{ $product['id'] }}')" class="like_img" >
                                        </div>
                                    </div>
                                </div>   
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}">
                                    <div class="text-center position-relative">
                                        <img src="{{ $product['image1'] }}" alt="list_items" class="default-img product_img">
                                        @if(!empty($product['image2']))
                                            <img src="{{ $product['image2'] }}" alt="list_items_hover" class="hover-img position-absolute product_img">
                                        @endif
                                    </div> 
                                </a>
                                <div class="container-fluid">
                                    @if($product['product_type'] > 1)
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart QuickShop rounded-1" data-bs-toggle="modal" data-bs-target="#Editpopup">
                                            <h6 class="text-center text-white h-sms text-nowrap">Quick Shop</h6>
                                            <img src="{{asset('asset/home/cart.svg')}}" alt="add_to_cart" class="Quick-shop-img">
                                        </button>
                                    @elseif($product['stock_status']=='out_of_stock')
                                        <button class="btn d-flex justify-content-center w-fill align-items-center bg-clr add-to-cart rounded-1 NotifyMe">
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
                                                    <h6 class="text-white text-center py-1 px-xl-2 px-lg-2 px-md-0 px-sm-0 px-0 h-sms text-nowrap">Add to cart</h6>
                                                </a>
                                            </div>
                                        </div> 
                                    @endif 
                                </div>
                            </div>
                            <div class="price_info py-3">
                                <h6 class="text-dark fw-bold align-self-center h-sms">{{ $product['name']}}</h6>
                                <div class="d-flex gap-3 align-items-center py-1">
                                    @if($product['discount']!=0)
                                    <del class="del-clr text-secondary fw-bold lh-lg text-opacity-50">Rs {{$product['price']}}</del>
                                    <h6 class="price fw-bold lh-lg align-self-center">Rs {{$product['sale_price']}}</h6>
                                    @else
                                    <h6 class="price fw-bold lh-lg align-self-center">Rs {{$product['price']}}</h6>
                                    @endif
                                </div>
                                <div class="d-flex gap-xl-2 gap-lg-2 gap-0 align-self-center">
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
                                    <h6 class="text-secondary text-opacity-50 h-sms">{{$product['review_count']}} reviews</h6>
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
