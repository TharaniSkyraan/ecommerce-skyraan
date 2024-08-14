
<div>
    @forelse($orders as $order)
        <div class="card border OrdRow rounded-0 mb-3">
            <div class="card-body buy-again-products">
                <div class="container-fluid">
                    <div class="row pb-2 PrdRow" data-id="{{ $order['product']['id'] }}" data-variant-id="{{ $order['product']['variant_id'] }}">
                        <div class="col-3 ps-0">
                            <div class="card border-0 align-items-center">
                                <img src="{{ $order['product']['image1']??'' }}" alt="{{ $order['product']['product_name'] }}" class="product-img ">
                            </div>
                        </div>
                        <div class="col-9 align-self-center">
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $order['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($order['product']['created_at'])->timestamp}}" target="_blank"><h5 class="fw-bold pb-1 buy-color"> {{ $order['product']['name'] }} {{ $order['product']['attributes']? '| '.$order['product']['attributes']:''}}</h5></a>
                            </div>                            
                            @if($order['product']['discount']!=0)
                            <div class="d-flex py-2">
                                <del class="del-clr">{{ $ip_data->currency_symbol??'₹' }} {{$order['product']['price']}} </del>
                                <h6 class="fw-bold px-2 align-self-center">{{ $ip_data->currency_symbol??'₹' }} {{$order['product']['sale_price']}}</h6>
                            </div>
                            @else
                                <h6 class="fw-bold py-2 sys-view">{{ $ip_data->currency_symbol??'₹' }} {{$order['product']['price']}}</h6>
                            @endif
                            <!-- <div class="row pb-3">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 rfjjfjnew">
                                    <div class="qty-container d-flex align-items-center justify-content-center border rounded-0  text-dark">
                                        <div class="col text-center qty-btn-minus"><span>-</span></div>
                                        <div class="vr"></div>
                                        <div class="col text-center"><span class="input-qty h-sms px-1">1</span></div>
                                        <div class="vr"></div>
                                        <div class="col text-center qty-btn-plus"><span>+</span></div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"><span class="h-sm">Availability : <span class="buy-color h-sm">{{ ($order['product']['stock_status']=='in_stock')?'In stock':'Out of stock' }}</span></span></div> -->
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12"><span class="h-sm">Last Buy : {{ \Carbon\Carbon::parse($order['product']['last_buy'])->format('d M Y') }}</span></div>
                            </div>
                            <div class="d-flex gap-2 pt-2">
                                <a href="{{ route('ecommerce.product.detail', ['slug' => $order['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($order['product']['created_at'])->timestamp }}" class="btnss cart-btn text-white h-sms py-2 px-5 rounded-1">Buy Again</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    @empty
        <div class="row py-5 cart_add_section">
            <div class="col-12 py-3 empty-cart">
                <img src="{{ asset('asset/home/empty-cart-placeholder.svg') }}" alt="home">
                <h6 class="py-4">No orders found!.</h6>
                <a href="{{ url('/') }}" class="btnss cart-btn text-white h-sms py-2 px-3">RETURN TO SHOP</a>
            </div>
        </div>
    @endforelse   
    @if($morepage)
        <div wire:loading.remove wire:target="loadMore" id="load-more" class="text-center">
            <a href="javascript:void(0)" wire:click="loadMore">
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
            </a>
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