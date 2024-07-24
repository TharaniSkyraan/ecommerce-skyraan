<div>
    <div class="container-fluid">
        <div class="order-placed">
            <div class="text-center py-4">
                <img src="{{asset('asset/home/order_placed_successfully_icon.svg')}}" alt="order-placed" class="orderpacedpng">
                <h5 class="buy-color py-3">Order Placed Successfully!</h5>
                <h5 class=" opacity-75">Order ID #{{$order->code}}</h5>
                <div class="row justify-content-center pt-5">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-4">
                        <h6 class="pb-3 text-nowrap">Order Date</h6>
                        <span class="h-sms ">{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d / m / Y') }}</span>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-4 o-border">
                        <h6 class="pb-3 text-nowrap">Total</h6>
                        <span class="h-sms ">{{ $ip_data->currency_symbol??'₹' }} {{$order->total_amount}}</span>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-4">
                        <h6 class="pb-3 text-nowrap">Payment Mode</h6>
                        <span class="h-sms ">{{ $order->payments?ucwords($order->payments->payment_chennal):'' }}</span>
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
