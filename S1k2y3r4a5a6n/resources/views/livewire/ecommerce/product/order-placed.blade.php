<div>
    <div class="container-fluid">
        <div class="order-placed">
            <div class="text-center py-4">
                <img src="{{asset('asset/home/order_placed_successfully_icon.svg')}}" alt="order-placed" class="orderpacedpng">
                <h5 class="buy-color py-3">Order Placed Successfully!</h5>
             
                <div class="multiple-orders py-3">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 ">
                            <div class="row py-4">
                                <div class="col-4">
                                    <span class="h-sms fw-bold">Order Placed : {{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y') }}</span>
                                </div>
                                <div class="col-4">
                                    <span class="h-sms fw-bold">Grand Total : {{ $ip_data->currency_symbol??'₹' }} {{$total_amount}}</span>
                                </div>
                                <div class="col-4">
                                    <span class="h-sms fw-bold">Payment Mode : {{ $order->payments?ucwords(($order->payments->payment_chennal=='card')?'Online':(($order->payments->payment_chennal=='cod')?'Cash on delivery':$order->payments->payment_chennal)):'' }}</span>
                                </div>
                            </div>
                            <div class="container-fluid class-row">
                                <div class="row ">
                                    <div class="card rounded-0 px-0 sys-view">
                                        <div class="card-head">
                                            <div class="container-fluid">
                                                <div class="row py-2">
                                                    <div class="col-3 text-start"><span class="h-sms" >Order ID</span></div>
                                                    <div class="col-5 text-start"><span class="h-sms">Order Items</span></div>
                                                    <div class="col-2 text-start"><span class="h-sms">Quantity</span></div>
                                                    <div class="col-2 text-end"><span class="h-sms">Total Amount</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($orderitems as $item)
                                            <div class="card-body py-2 px-0">
                                                <div class="container-fluid">
                                                    <div class="row align-items-center justify-content-start border-bottom pb-2">
                                                        <div class="col-3 text-start"><span class="h-sms">#{{$item->orders->code}}</span></div>
                                                        <div class="col-5">
                                                            <div class="d-flex gap-2 align-items-center">
                                                                <img src="{{$item->product_image}}" class="w-50" alt="{{$item->product_name}}">
                                                                <div><span class="h-sms">{{$item->product_name}}</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 text-start"><span class="h-sms">{{$item->quantity}}</span></div>
                                                        <div class="col-2 text-end"><span class="h-sms">{{ $ip_data->currency_symbol??'₹' }}{{$item->total_amount}}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="accordion accordion-flush mbl-view" id="accordionFlushExample">
                                        <div class="accordion-item py-2">
                                            
                                            @foreach($orderitems as $item)
                                                <h2 class="accordion-header" id="flush-headingOne{{$item->id}}">
                                                <button class="accordian-color accordion-button collapsed fw-bold h-sms" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne{{$item->id}}" aria-expanded="false" aria-controls="flush-collapseOne{{$item->id}}">
                                                Order ID : #{{$item->orders->code}}
                                                </button>
                                                </h2>
                                                <div id="flush-collapseOne{{$item->id}}" class="accordion-collapse collapse accordian-color show" aria-labelledby="flush-headingOne{{$item->id}}" data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body pt-0">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <img src="{{$item->product_image}}" class="w-50" alt="{{$item->product_name}}">
                                                            </div>
                                                            <div class="col-8">
                                                                <p class="h-sms text-start cls-clr buy-color">{{$item->product_name}}</p>
                                                                <p class="h-sms py-2 text-start">Quantity : <span class="fw-bold h-sms">{{$item->quantity}} </span></p>
                                                                <p class="h-sms text-start fw-bold">{{ $ip_data->currency_symbol??'₹' }}{{$item->total_amount}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-4  col-lg-4 col-md-8 col-sm-8 col-12 py-5 ">
                        <div class="">
                            <a href="{{ url('/') }}" class="btnss btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-75 text-nowrap"><h6> Continue Shopping</h6></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
