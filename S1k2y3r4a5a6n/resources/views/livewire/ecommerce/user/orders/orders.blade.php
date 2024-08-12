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
                                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 px-0 ">
                                                <div class="jkefjnjfqjwqd">
                                                    <span class="h-sms fw-bold">Order ID</span>
                                                    <h6 class="h-sms fw-bold pt-xl-1 pt-lg-1 pt-md-0 pt-sm-0 pt-0">#{{$order['code']}}</h6>
                                                </div>
                                                <div class="d-md-flex d-sm-flex d-lg-none d-xl-none d-none align-items-center">
                                                    <span class="h-sms fw-bold">Order ID</span>
                                                    <h6 class="h-sms fw-bold pt-xl-1 pt-lg-1 pt-md-0 pt-sm-0 pt-0">#{{$order['code']}}</h6>
                                                </div>
                                                <div class="mbl-view">
                                                    <a href="{{ route('ecommerce.order-detail') }}?ordId={{$order['code']}}&ordRef={{ \Carbon\Carbon::parse($order['created_at'])->timestamp}}">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="h-sms fw-bold ">Order ID #{{$order['code']}}</span><br>
                                                            <div class="">
                                                            <img src="{{asset('asset/home/forward-icon.png')}}" alt="">
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 text-start text-xl-end text-lg-end text-xxl-end px-xl-1 px-lg-1 px-md-0 px-sm-0 px-0">
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
                                        <div class="tooltip-container1">
                                            <img src="{{asset('asset/home/down-ar.svg')}}" alt="arrow" id="down-tooltip">
                                            <div class="tooltip-text" id="tooltip">
                                                <span class="fw-bold h-sms text-start">{{ ucwords($shipment_address['name']) }}</span>
                                                <span class="h-sm text-start">{{ ucwords($shipment_address['address']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-sm-6 col-md-2 col-12 text-start border-end-0 border-sm-end  sys-view">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12 px-xl-3 px-lg-3 px-md-3 px-sm-1 px-0">
                                        <span class="h-sms">Total</span>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-sm-6 col-md-12 col-12 text-md-start text-start text-sm-end fwedw">
                                        <span>{{ $ip_data->currency_symbol??'₹' }} {{$order['total_amount']}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-sm-6 col-md-4 col-12 text-end sys-view pe-0">
                                <div class="d-flex {{ ($order['status']=='delivered')?'justify-content-start':'justify-content-end'}}">
                                    <div class="text-start">
                                        <a href="{{ route('ecommerce.order-detail') }}?ordId={{$order['code']}}&ordRef={{ \Carbon\Carbon::parse($order['created_at'])->timestamp}}"><span class="buy-color h-sms">view order details</span></a>
                                        @if($order['status']=='delivered')
                                        <span class="buy-color px-1">|</span>
                                        <a href="javascript:void(0);" class="buy-color h-sms"><span class="buy-color h-sms cursor" wire:click="invoiceGenerate('{{$order['id']}}')">Invoice</span></a>
                                        @endif
                                    </div>
                                </div>                                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid px-0">
                        <div class="row pb-xl-4 pb-lg-4 pb-md-4 pb-sm-4 pb-4">
                            <div class="col-xl-6 col-lg-6 col-sm-6 col-md-6 col-6 align-self-center">
                                <div class="d-flex align-items-center gap-1">
                                    @if($shipmentstatus=='shipped' || $shipmentstatus=='out_for_delivery') <img src="{{asset('asset/home/shipping-orders.svg')}}" alt="">@endif
                                    <h6 class="h-sms {{ ($shipmentstatus=='cancelled')?'text-danger':'' }} shipmentstatus">{{ ucwords(str_replace('_',' ',$shipmentstatus)) }}</h6>
                                </div>
                                <!-- <span class="h-sms">Expected Delivery by 29 May2024</span> -->
                            </div>
                            <div class="col-xl-6 col-lg-6 col-sm-6 col-md-6 col-6 align-self-center">
                                @if($shipmentstatus != 'order_placed' && $shipmentstatus != 'cancelled' && $shipmentstatus != 'delivered')
                                    <div class="text-end mb-3">
                                        <a href="{{ route('ecommerce.order-detail') }}?ordId={{$order['code']}}&ordRef={{ \Carbon\Carbon::parse($order['created_at'])->timestamp}}" class="text-white h-sms py-2 px-xl-5 px-lg-5 px-sm-5 px-md-5 px-2 rounded-0 bg-secondary text-nowrap">Track Order</a>
                                    </div>
                                @endif
                                @if($order['status']=='new_request'||$order['status']=='order_confirmed')
                                    <div class="text-end">
                                        <a href="javascript:void(0);" class="btns-danger h-sms py-xl-2 py-lg-2 py-md-2 py-sm-2 py-1 px-xl-5 px-lg-5 px-sm-5 px-md-5 px-2 rounded-0 cancel-order-request" id="cancellord_{{$order['code']}}" wire:click="cancelOrderRequest('{{$order['code']}}')">Cancel Order</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @foreach($items as $key => $item)
                        <div class="row PrdRow" data-id="{{ $item['product_id'] }}" data-variant-id="{{ $item['variant']['id']??'' }}" data-quantity="{{ $item['quantity'] }}">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-4 px-0">
                                <div class="card border-0 align-items-center">
                                    <img src="{{$item['product_image']}}" alt="{{$item['product_name']}}" class="product-img ">
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-8 align-self-center pe-0">
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $item['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($item['product']['created_at'])->timestamp}}&product_variant={{$item['variant']['id']}}" target="_blank">
                                        <h5 class="fw-bold pb-1 buy-color">{{ $item['product_name'] }}</h5>
                                    </a>
                                </div>
                                @php $attributes = \App\Models\AttributeSet::find(explode(',',$item['attribute_set_ids'])); @endphp
                                @foreach($attributes as $attribute)
                                <div class=""><span class="h-sms">{{ ucwords($attribute->attribute->name??'') }} : <span class="fw-bold">{{ ucwords($attribute->name??'') }}</span></span></div>
                                @endforeach
                                <div class=""><span class="h-sms">Quantity : <span class="fw-bold">{{ $item['quantity'] }} nos</span></span></div>
                                <h5 class="fw-bold py-2 sys-view">{{ $ip_data->currency_symbol??'₹' }} {{ $item['total_amount'] }}</h5>
                                @if($order['status']=='delivered')
                                    <div class="row ">
                                        @if(isset($item['variant']) || (count($attributes)==0))
                                            <div class="col-xl-4 col-lg-4 col-md-6 cl-sm-6 col-5 py-2 px-0">
                                                <!-- @if(isset($item['variant']))
                                                    @if($item['variant']['stock_status']=='in_stock')
                                                        <a href="javascript:void(0);" class="cart-btn text-white h-sms py-2 px-xl-5 px-sm-5 px-lg-5 px-md-5 px-3 rounded-0 text-white text-nowrap AddCart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Buy Again</a>
                                                    @else
                                                    @endif
                                                @endif -->
                                                <a href="{{ route('ecommerce.product.detail', ['slug' => $item['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($item['product']['created_at'])->timestamp}}&product_variant={{$item['variant']['id'] }}" target="_blank" class="cart-btn text-white h-sms py-2 px-xl-5 px-sm-5 px-lg-5 px-md-5 px-3 rounded-0 text-white text-nowrap">Buy Again</a>
                                            </div>
                                            @if(!isset($item['review']))
                                            <div class="col-xl-4 col-lg-4 col-md-6 cl-sm-6 col-7 py-2 px-0 text-center">
                                                <a href="{{ route('ecommerce.product.detail', ['slug' => $item['product']['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($item['product']['created_at'])->timestamp}}&product_variant={{$item['variant']['id']}}&tab=review" target="_blank" class="border border-secondary h-sms py-2 px-2 rounded-0 text-secondary">Write  Review</a>
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                             @if((count($items)-1) != $key) <div class="col-12"> <hr> </div> @endif
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
        <div class="row py-5 cart_add_section">
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
    <!-- Cancel order -->
    <div class="modal fade {{$isopenmodel}}" id="cancel-order" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="text-end sys-view">
                <img src="{{asset('asset/home/close.svg')}}" alt="close" aria-label="Close" class="close-btn">
            </div>
                <div class="modal-bodys p-3">
                    <div class="text-end mbl-view">
                        <i class="bi bi-x-lg close-btn" data-bs-dismiss="modal" aria-label="Close" class=""></i>
                    </div>
                    <div class="text-center head">
                        <h4 class="hding">Cancel order</h4>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-start ">
                        <div>
                            <small class="fw-bold">Order ID &nbsp;: &nbsp;</small>
                        </div>
                        <div>
                            <small class="fw-bold"> {{ $order_code }}</small>
                        </div>
                    </div>

                    <div class="card p-2 pt-4 rounded-0">  
                        <div class="py-1">           
                            <div class="form-group">
                                <label for="reason" class="fw-bold h-sms pb-2">Please select the reason for your cancellation</label>
                                <select class="form-select h-sms w-100" wire:model="reason" placeholder="Select">
                                    <option value="">Select Reason</option>
                                    @foreach($reasons as $reaso)
                                        <option value="{{$reaso}}">{{$reaso}}</option>
                                    @endforeach
                                </select>
                            </div>                    
                            @error('reason') <span class="error">{{$message}}</span> @endif
                        </div>
                        <div class="py-1">         
                            <label for="notes" class="fw-bold h-sms">Notes</label>         
                            <textarea class="form-control" id="notes" placeholder="Notes" wire:model="notes"> </textarea>              
                            @error('notes') <span class="error">{{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-center pt-2">
                        <button class="btn text-white h-sms" wire:click.prevent="cancelOrder">Submit Request</button>
                    </div>
                </div>   
            </div>   
        </div>   
    </div>
    
    <div class="modal fade" id="submit-cancel-order" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-end">
                    <img src="{{asset('asset/home/close.svg')}}" alt="close" aria-label="Close" class="close-btn">
                </div>
                <div class="modal-bodys p-5">
                    <div class="col-12 text-center pt-2">
                        <img src="{{asset('asset/home/cancel-order.svg')}}" alt="cancel order" class="pb-3">
                        <h6 class="fw-bold">Your request for cancellation has been submitted successfully</h6>
                        <div class="pt-4">
                        <button class="btn text-white" data-bs-dismiss="modal" aria-label="Close" >Ok</button>
                        </div>
                    </div>
                </div>   
            </div>   
        </div>   
    </div>
</div>