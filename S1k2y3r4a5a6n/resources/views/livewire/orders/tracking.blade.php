<div class="tracking-list mb-5">
    <div class="tracking-item {{ (count($trackings)==0)?'mb-3':''}}">
        <div class="tracking-content">Order Confirmed<span>{{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M y h:i A') }}</span></div>
        <div class="tracking-icon {{ (count($trackings)==0)?'status-current blinker':'status-intransit'}} ">
            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
            </svg>
        </div>
    </div>
    @php $counttracking = count($trackings)-1; @endphp
    @foreach($trackings as $key => $tracking)
    <div class="tracking-item {{ ($counttracking==$key)?'mb-3':'' }}">
        <div class="tracking-content">{{ $tracking->action }}<span>{{ \Carbon\Carbon::parse($tracking->created_at)->format('d M y h:i A') }}</span></div>
        <div class="tracking-icon {{ ($counttracking==$key)?'status-current blinker':'status-intransit' }}">
            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
            </svg>
        </div>
    </div>
    @endforeach
    @if($shipmentstatus!='Delivered')
        <div class="tracking-item-pending">
            <div class="tracking-content">Delivered</div>
            <div class="tracking-icon status-intransit">
                <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                    <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                </svg>
            </div>
        </div>
    @endif
</div>