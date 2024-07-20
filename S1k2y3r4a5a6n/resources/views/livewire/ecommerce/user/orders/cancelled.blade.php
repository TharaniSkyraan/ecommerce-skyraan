<div>
    @forelse($orders as $order)
        @php
            $shipment_address = $order['shipment_address'];
            $shipment = $order['shipment'];
            $shipmentstatus = $shipment['status'];
            $items = $order['order_items'];
        @endphp            
        <div class="card border OrdRow rounded-0 mb-3">
            <div class="pb-3">
                <div class="card-head p-2  ">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-sm-6 col-md-4 col-12 border-end px-3 efkqr4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 pe-0 ">
                                                <div class="d-flex justify-content-between">
                                                    <span class="h-sms fw-bold">Order ID #{{$order['code']}}</span>
                                                    <div class="mbl-view">
                                                        <img src="{{asset('asset/home/forward-icon.png')}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 text-start text-xl-end text-lg-end text-xxl-end">
                                                <span class="h-sms">Order Placed</span>
                                                <span class="h-sms">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M y')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-sm-6 col-md-2 col-12 text-start border-end jnfgje sys-view">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12">
                                        <span class="h-sms">Ship to</span>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12 text-md-start text-start text-sm-end">
                                        <span class="h-sms" data-position="bottom">{{ ucwords($shipment_address['name']) }}</span>
                                        <img src="{{asset('asset/home/down-ar.svg')}}" alt="arrow">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-sm-6 col-md-2 col-12 text-start border-end-0 border-sm-end  sys-view">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12">
                                        <span class="h-sms">Total</span>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12 text-md-start text-start text-sm-end fwedw">
                                        <span>{{ $ip_data->currency_symbol??'₹' }} {{$order['total_amount']}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-sm-6 col-md-4 col-12 text-center  sys-view">
                               <a href="{{ route('ecommerce.order-detail') }}?ordId={{$order['code']}}&ordRef={{ \Carbon\Carbon::parse($order['created_at'])->timestamp}}"><span class="buy-color h-sms">view order details</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row pb-5 ">
                            <div class="col-xl-12 col-lg-12 col-sm-12 col-md-12 col-12 px-0">
                                <div class="d-flex align-items-center gap-1">
                                    <h6>Cancelled on 29 May2024</h6>
                                </div>
                            </div>
                        </div>
                        @foreach($items as $key => $item)
                        <div class="row PrdRow" data-id="{{ $item['product_id'] }}" data-variant-id="{{ $item['variant']['id']??'' }}" data-quantity="{{ $item['quantity'] }}">
                            <div class="col-3 ps-0">
                                <div class="card border-0 align-items-center">
                                    <img src="{{$item['product_image']}}" alt="{{$item['product_name']}}" class="product-img ">
                                </div>
                            </div>
                            <div class="col-9 align-self-center pe-0">
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $item['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($item['product']['created_at'])->timestamp}}" target="_blank">
                                        <h5 class="fw-bold pb-1 buy-color">{{ $item['product_name'] }}</h5>
                                    </a>
                                </div>
                                @php $attributes = \App\Models\AttributeSet::find(explode(',',$item['attribute_set_ids'])); @endphp
                                @foreach($attributes as $attribute)
                                <div class=""><span class="h-sms">{{ ucwords($attribute->attribute->name) }} : <span class="fw-bold">{{ ucwords($attribute->name) }}</span></span></div>
                                @endforeach
                                <div class=""><span class="h-sms">Quantity : <span class="fw-bold">{{ $item['quantity'] }} nos</span></span></div>
                                <h5 class="fw-bold py-2">{{ $ip_data->currency_symbol??'₹' }} {{ $item['total_amount'] }}</h5>
                            </div>
                            @if((count($items)-1) != $key) 
                                <div class="col-12"> <hr> </div> 
                            @else 
                                <div class="col-12 text-danger pt-4 px-5"><h6>{{ $order['order_status'] }}</h6> </div> 
                            @endif
                        </div>
                        @endforeach
                        <div class="mbl-view pt-2">
                            <div class="d-flex justify-content-between ">
                                <div>
                                    <span class="h-sms fw-bold">Ship To</span>
                                    <span class="h-sms text-opacity-75">{{ ucwords($shipment_address['name']) }}</span>
                                </div>   
                                <div>
                                    <span class="h-sms fw-bold">Total</span>
                                    <span class="h-sms text-opacity-75">{{ $ip_data->currency_symbol??'₹' }} {{ $order['total_amount'] }}</span>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    @empty    
        <div class="row pb-2 cart_add_section">
            <div class="col-12 pb-3 empty-cart">
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
    