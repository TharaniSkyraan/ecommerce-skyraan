<div>
    <section class="bread-crumb">
        <div class=" px-xl-5 px-lg-5 px-md-5 px-sm-3 px-3 py-2">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item h-sms "><a class="h-sm buy-color" href="{{ route('ecommerce.home') }}">Home</a></li>
                    <li class="breadcrumb-item h-sms"><a class="h-sm buy-color" href="{{ route('ecommerce.orders') }}">My Account</a></li>
                    <li class="breadcrumb-item h-sms"><a class="h-sm buy-color" href="javascript:void(0);">Order Detail</a></li>
                </ol>
            </nav>
        </div>
    </section>    
    @php 
        $order_statuses = \App\Models\OrderHistory::whereOrderId($this->order->id)->pluck('description')->first();
        $shipment_address = $order->shipmentAddress;
        $order_status = $order->status;
        $width=100;

        switch($order_status){                        
            case 'new_request':
                $width = 0;
                break;            
            case 'order_confirmed':
                $width = 25;
                break;            
            case 'shipped':
                $width = 50;
                break;            
            case 'out_for_delivery':
                $width = 70;
                break;
        }
    @endphp       
    <section class="order_details">
        @if($screenWidth >= 767)
            <div class="container systems-view">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="fw-bold">Order Details</span>
                    </div>
                    <div class="col-9 text-end">
                        @if($order_status=='delivered'||$order_status=='replaced')
                        <span class="h-sm buy-color cursor" wire:click="invoiceGenerate">Download Invoice</span>
                        @endif
                    </div>
                </div>
                <div class="py-4">
                    <div class="card p-3 rounded-1">
                        <div class="row">
                            <div class="col-4">
                                <div class="card card1 p-3 border-0 h-100">
                                    <span class="h-sms fw-bold pb-2 ">Order Detail</span>
                                    <p class="h-smk py-1 fw-bold text-secondary">Order ID : #{{ $order['code'] }}</p>
                                    <p class="h-smk ">Order Placed :{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y h:i A')}}</p>
                                    @if($order->order_histories['delivered']) <p class="h-smk ">Order Delivered : {{ \Carbon\Carbon::parse($order->order_histories['delivered'])->format('d M Y')}}</p> @endif
                                    @if($order->order_histories['cancelled']) <p class="h-sms red py-2">Cancelled On {{ \Carbon\Carbon::parse($order->order_histories['cancelled'])->format('d M Y h:i A')}}</p> @endif
                                    <p class="h-sms">Payment Mode : {{ $order->payments?ucwords($order->payments->payment_chennal):'' }}</p>
                                    <p class="h-sms">Payment Status : {{ $order->payments?(($order->payments->status=='completed')?'Paid':'Unpaid'):'' }}</p>
                                </div>
                            </div>
                            <div class="col-4 px-0">
                                <div class="card card1 p-3 border-0 h-100">
                                    <span class="h-sms fw-bold pb-2">Shipping Detail</span>
                                    <p class="h-smk py-1 fw-bold text-secondary">{{ ucwords($shipment_address->name) }}</p>
                                    <p class="h-smk lh-base">{{ $shipment_address->address }}</p>
                                    <p class="h-smk lh-base">{{ $shipment_address->phone }},{{ $shipment_address->alternative_phone }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card  card1 p-3 border-0 h-100">
                                    <span class="h-sms fw-bold pb-2">Order Summary</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk">Items Subtotal</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{ $order->sub_total }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk py-1">Coupon Discount</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{ $order->discount_amount}}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk">Shipping Cost</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{ $order->shipping_amount }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="fw-bold opacity-75 h-sms">Total</p>
                                        </div>
                                        <div>
                                            <p class="fw-bold opacity-75 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{ $order->total_amount }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-3">
                    
                    <div class="card container-fluid rounded-1">
                        <div class="order-track  px-2 py-3">
                            <div class="row header p-2 ">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-2 ps-0">
                                    <p class="fw-bold h-sms">Tracking ID</p>
                                    <p class="fw-bold h-sms pt-1"># {{ $order->shipment->tracking_id }}</p>
                                </div>
                                <!-- <div class="col-3">
                                    <p class="fw-bold h-sms">Expected Delivery</p>
                                    <p class="h-sm pt-1">29 May 2024</p>
                                </div>
                                <div class="col-4">
                                    <p class="fw-bold h-sms">Shipping By</p>
                                    <p class="h-sm pt-1">ABT Parcel Services | <i class="bi bi-telephone"></i> +91 9876654321</p>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-2 col-2 px-0">
                                    <p class="fw-bold h-sms ">Expected Delivery</p>
                                    <p class="h-sm pt-1">Out for Delivery</p>
                                </div> -->
                            </div>
                            <div id="tracking">
                                <div class="modal-bodys">
                                    <div class="container-fluid px-0">
                                        <div class="row">
                                            @if($order->status!='cancelled' && $order->status!='refund' && $order->status!='replaced')
                                            <div class="col-12 ">
                                                <div class="d-flex justify-content-between py-3">
                                                    <div class="order-tracking completed px-0">
                                                        <span class="is-complete"><img src="{{asset('asset/home/track1.svg')}}" alt="track" class="pt-2"></span>
                                                        <p class="h-sms fw-bold p1">Order placed</p>
                                                        <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y h:i A') }}</p>
                                                    </div>
                                                    <div class="order-tracking px-0 {{ ($order->status=='new_request')?'completed1':(($width>=25)?'completed':'')}}">
                                                        <span class="is-complete"><img src="{{asset('asset/home/track.svg')}}" alt="track" class="pt-2"></span>
                                                        <p class="h-sms fw-bold p1">Order confirmed</p>
                                                        <!-- <p class="h-sms fw-normal p2">Your order is shipped </p> -->
                                                        @if($order->order_histories['order_confirmed']) <p class="h-sm fw-normal p3">{{ \Carbon\Carbon::parse($order->order_histories['order_confirmed'])->format('d M y h:i A') }} </p> @endif
                                                    </div>
                                                    <div class="order-tracking px-0 {{ ($order->status=='order_confirmed')?'completed1':(($width>=50)?'completed':'')}}">
                                                        <span class="is-complete"><img src="{{asset('asset/home/track2.svg')}}" alt="track" class="pt-2"></span>
                                                        <p class="h-sms fw-bold p1">Shipped</p>
                                                        <!-- <p class="h-sms fw-normal p2">Your order is shipped </p> -->
                                                        @if($order->order_histories['shipped']) <p class="h-sm fw-normal p3">{{ \Carbon\Carbon::parse($order->order_histories['shipped'])->format('d M y h:i A') }} </p> @endif
                                                    </div>
                                                    <div class="order-tracking px-0 {{ ($order->status=='shipped')?'completed1':(($width>=70)?'completed':'')}}">
                                                        <span class="is-complete"><img src="{{asset('asset/home/track3.svg')}}" alt="track" class="pt-2"></span>
                                                        <p class="h-sms fw-bold p1">Out for delivered</p>
                                                        <!-- <p class="h-sms fw-normal p2">Your order reached salem and picked for delivery </p> -->
                                                        @if($order->order_histories['out_for_delivery']) <p class="h-sm fw-normal p3">{{ \Carbon\Carbon::parse($order->order_histories['out_for_delivery'])->format('d M y h:i A') }}</p> @endif 
                                                    </div>
                                                    <div class="order-tracking px-0 {{ ($order->status=='out_for_delivery')?'completed1':(($width==100)?'completed':'') }}">
                                                        <span class="is-complete"><img src="{{asset('asset/home/track4.svg')}}" alt="track" class="pt-2"></span>
                                                        <p class="h-sms fw-bold p1">Delivered</p>
                                                        @if($order->order_histories['delivered']) <p class="h-sm fw-normal p3">{{ \Carbon\Carbon::parse($order->order_histories['delivered'])->format('d M y h:i A') }}</p> @endif 
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($order->status=='cancelled')
                                                <div class="col-12 cancelled">
                                                    <div class="d-flex justify-content-between py-3">
                                                        <div class="order-tracking completed px-0">
                                                            <span class="is-complete"><img src="{{asset('asset/home/track1.svg')}}" alt="track" class="pt-2"></span>
                                                            <p class="h-sms fw-bold p1">Order placed</p>
                                                            <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y') }}</p>
                                                        </div>
                                                        
                                                        <div class="order-tracking px-0 completed1" >
                                                            <span class="is-complete" style="background-color:#FF4C4C"><img src="{{asset('asset/home/track-cancel.svg')}}" alt="track" class="cancel-color"></span>
                                                            <p class="h-sms fw-bold">Cancelled</p>
                                                            <p class="h-sm fw-normal text-danger">* {{ $order_statuses }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>   
                            </div>   
                        </div>
                    </div>

                    <div class="card container-fluid rounded-1 mt-4">
                        <div class="row p-2 card-hd">
                            <div class="col-4">
                            <span class="h-sms">Order Items</span>
                            </div>
                            <div class="col-3">
                            <span class="h-sms">Price</span>
                            </div>
                            <div class="col-2">
                            <span class="h-sms">Quantity</span>
                            </div>
                            <div class="col-3 text-end">
                            <span class="h-sms">Total Amount</span>
                            </div>
                        </div>
                            
                        @foreach($order->orderItems as $item)
                        @php
                            $attributes = \App\Models\AttributeSet::find(explode(',',$item->attribute_set_ids));
                        @endphp
                        <div class="row p-2 pt-3 product-lisst">
                            <div class="col-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <img src="{{$item->product_image}}" alt="{{$item->product_name}}" class="product-img">
                                    <span class="h-sms">
                                        {{$item->product_name}}
                                        @foreach($attributes as $attribute)
                                        | {{ $attribute->name }}
                                        @endforeach
                                    </span>
                                </div>
                            </div>
                            <div class="col-3">
                            <span class="h-sms">{{ $ip_data->currency_symbol??'₹' }} {{ $item->gross_amount/$item->quantity }}</span>
                            </div>
                            <div class="col-2">
                            <span class="h-sms">{{ $item->quantity }}</span>
                            </div>
                            <div class="col-3 text-end">
                            <span class="h-sms">{{ $ip_data->currency_symbol??'₹' }} {{ $item->gross_amount }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($screenWidth < 766)
            <div class="system1-view">
                <div class="container-fluid">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordian-color accordion-button collapsed fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Order Detail
                            </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body pt-0">
                                    <p class="h-smk py-1 fw-bold text-secondary">Order ID : #{{ $order->code }}</p>
                                    <p class="h-smk ">Order Placed :{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y h:i A')}}</p>
                                    @if($order->order_histories['delivered']) <p class="h-smk ">Order Delivered : {{ \Carbon\Carbon::parse($order->order_histories['delivered'])->format('d M Y')}}</p> @endif
                                    @if($order->order_histories['cancelled']) <p class="h-sms red pt-2">Cancelled On {{ \Carbon\Carbon::parse($order->order_histories['cancelled'])->format('d M Y h:i A')}}</p> @endif
                                    <p class="h-sms">Payment Mode : {{ $order->payments?ucwords($order->payments->payment_chennal):'' }}</p>
                                    <p class="h-sms">Payment Status : {{ $order->payments?(($order->payments->status=='completed')?'Paid':'Unpaid'):'' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed accordian-color fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Shipping Detail
                            </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body pt-0">
                                <p class="h-smk py-1 fw-bold text-secondary">{{ ucwords($shipment_address->name) }}</p>
                                <p class="h-smk lh-base">{{ $shipment_address->address }}</p>
                                <p class="h-smk lh-base">{{ $shipment_address->phone }},{{ $shipment_address->alternative_phone }}</p>
                            </div>
                        </div>
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="flush-headingfour">
                            <button class="accordion-button collapsed accordian-color fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-collapseThree">
                            Order Summary
                            </button>
                            </h2>
                            <div id="flush-collapsefour" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body pt-0">
                            <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk">Items Subtotal</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{$order->sub_total}}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk py-1">Coupon Discount</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{$order->discount_amount}}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="h-smk">Shipping Cost</p>
                                        </div>
                                        <div>
                                            <p class="h-smk">{{ $ip_data->currency_symbol??'₹' }} {{$order->shipping_amount}}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="fw-bold opacity-75 h-sms">Total</p>
                                        </div>
                                        <div>
                                            <p class="fw-bold opacity-75 h-sms">{{ $ip_data->currency_symbol??'₹' }} {{ $order->total_amount }}</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed accordian-color fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-collapsefive">
                            Order Items
                            </button>
                            </h2>
                            <div id="flush-collapsefive" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body pt-0">
                                
                                    @foreach($order->orderItems as $item)
                                    @php
                                        $attributes = \App\Models\AttributeSet::find(explode(',',$item->attribute_set_ids));
                                    @endphp
                                        <div class="row">
                                            <div class="col-3 ps-0">
                                                <div class="d-flex justify-content-center border-0 align-self-center">
                                                    <!-- <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="product-img-history"> -->
                                                    <img src="{{$item->product_image}}" alt="{{$item->product_name}}" class="product-img-history">
                                                </div>
                                            </div>
                                            <div class="col-9 align-self-center">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <h5 class="fw-bold pb-1 buy-color">{{$item->product_name}}
                                                        @foreach($attributes as $attribute)
                                                        | {{ $attribute->name }}
                                                        @endforeach</h5>
                                                </div>
                                                <div class=" "><span class=" h-sms">Quantity  : <span class="fw-bold">{{ $item->quantity }} nos</span></span></div>
                                                <h5 class="fw-bold py-2">{{ $ip_data->currency_symbol??'₹' }} {{ $item->gross_amount/$item->quantity }}</h5>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>                    
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="flush-headingsix">
                            <button class="accordion-button collapsed accordian-color fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsesix" aria-expanded="false" aria-controls="flush-collapsesix">
                            Tracking Details
                            </button>
                            </h2>
                            <div id="flush-collapsesix" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingsix" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body pt-0 order-track">
                                    <p class="fw-bold h-sm mb-2">Tracking ID : #{{ $order->shipment->tracking_id }}</p>
                                    <div id="tracking">
                                        <div class="modal-bodys">
                                            <div class="container-fluid px-0">
                                                @if($order->status!='cancelled' && $order->status!='refund' && $order->status!='replaced')
                                                <div class="row">
                                                    <div class="col-12 position-relative">
                                                        <div class="vertical-line"></div>
                                                        <div class="vertical-line-filled" style="height:{{($width==100)?'90':($width+20)}}%"></div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking completed px-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track1.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start completed px-0">
                                                                    <p class="h-sms fw-bold p1">Order placed</p>
                                                                    <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y h:i A') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking {{ ($order->status=='new_request')?'completed1':(($width>=25)?'completed':'')}} px-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start {{ ($order->status=='new_request')?'completed1':(($width>=25)?'completed':'')}} px-0">
                                                                    <p class="h-sms fw-bold p1">Order confirmed</p>
                                                                    @if($order->order_histories['order_confirmed']) <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['order_confirmed'])->format('d M Y') }}</p> @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking {{ ($order->status=='order_confirmed')?'completed1':(($width>=50)?'completed':'')}} px-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track2.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start  {{ ($order->status=='order_confirmed')?'completed1':(($width>=50)?'completed':'')}} px-0">
                                                                    <p class="h-sms fw-bold p1">Shipped</p>
                                                                    @if($order->order_histories['shipped']) <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['shipped'])->format('d M Y') }}</p> @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking {{ ($order->status=='shipped')?'completed1':(($width>=70)?'completed':'')}} px-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track3.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start {{ ($order->status=='shipped')?'completed1':(($width>=70)?'completed':'')}} px-0">
                                                                    <p class="h-sms fw-bold p1">Out for delivered</p>
                                                                    @if($order->order_histories['out_for_delivery']) <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['out_for_delivery'])->format('d M Y') }}</p> @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking {{ ($order->status=='out_for_delivery')?'completed1':(($width==100)?'completed':'') }} px-0 mb-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track4.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start {{ ($order->status=='out_for_delivery')?'completed1':(($width==100)?'completed':'') }} px-0">
                                                                    <p class="h-sms fw-bold p1">Delivered</p>
                                                                    @if($order->order_histories['delivered']) <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['delivered'])->format('d M Y') }}</p> @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @elseif($order->status=='cancelled')
                                                <div class="row">
                                                    <div class="col-12 cancelled position-relative">
                                                        <div class="vertical-line-filled vertical-line-filled1"></div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking completed px-0">
                                                                    <span class="is-complete"><img src="{{asset('asset/home/track1.svg')}}" alt="track" class="pt-2"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start completed px-0">
                                                                    <p class="h-sms fw-bold p1">Order placed</p>
                                                                    <p class="h-sm fw-normal p2">{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2 col-3 align-self-center">
                                                                <div class="order-tracking completed1 px-0">
                                                                    <span class="is-complete" style="background-color:#FF4C4C"><img src="{{asset('asset/home/track-cancel.svg')}}" alt="track" class="pt-2 mt-1"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10 col-9">
                                                                <div class="order-tracking text-start completed1 px-0">
                                                                    <p class="h-sms fw-bold text-danger">Cancelled</p>
                                                                    <p class="h-sm fw-normal text-danger">* {{ $order_statuses }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>
<script>

document.addEventListener('livewire:load', function () {
            var width = screen.width;
            Livewire.emit('screenSizeCaptured', width);
        });
        </script>