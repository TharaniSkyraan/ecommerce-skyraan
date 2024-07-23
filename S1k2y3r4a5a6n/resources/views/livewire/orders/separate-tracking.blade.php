
<div class="timeline-w">
    <div class="horizontal timeline">
        @if($order_status!='cancelled' && $order_status!='return' && $order_status!='refund' && $order_status!='replaced')
        @php 
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
                $width = 75;
                break;
        }
        @endphp
        <div class="steps">
            <div class="step {{($order_status=='new_request')?'current':(($width!=0)?'completed':'')}}">
                <span>Order Placed  <br><text> {{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M y h:i A') }} </text> </span>
            </div>
            <div class="step {{($order_status=='order_confirmed')?'current':(($width>=25)?'completed':'')}}">
                <span>Order Confirm  @if($order->order_histories['order_confirmed']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['order_confirmed'])->format('d M y h:i A') }} </text> @endif  </span>
            </div>
            <div class="step {{($order_status=='shipped')?'current':(($width>=50)?'completed':'')}}">
                <span>Shipped  @if($order->order_histories['shipped']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['shipped'])->format('d M y h:i A') }} </text> @endif </span>
            </div>
            <div class="step {{($order_status=='out_for_delivery')?'current':(($width>=75)?'completed':'')}}">
                <span>Out for deliery  @if($order->order_histories['out_for_delivery']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['out_for_delivery'])->format('d M y h:i A') }}</text> @endif </span>
            </div>
            <div class="step {{(($width==100)?'completed':'')}}">
                <span>Delivered @if($order->order_histories['delivered']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['delivered'])->format('d M y h:i A') }}</text> @endif </span>
            </div>
        </div>
        <div class="line" style="width:{{$width}}%"></div>
        @elseif($order_status=='cancelled')        
            <div class="steps">
                <div class="step completed">
                    <span>Order Placed  <br><text> {{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M y h:i A') }} </text> </span>
                </div>
                <div class="step cancelled">
                    <span>Cancelled  @if($order->order_histories['cancelled']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['cancelled'])->format('d M y h:i A') }}</text> @endif </text></span>
                </div>
            </div>
            <div class="line" style="width:{{$width??'100%'}}"></div>
        @elseif($order_status=='refund')  
            <div class="steps">
                <div class="step return">
                    <span>Return @if($order->order_histories['refund']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['refund'])->format('d M y h:i A') }}</text> @endif </span>
                </div>
                <div class="step return">
                    <span>Refund @if($order->order_histories['refund']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['refund'])->format('d M y h:i A') }}</text> @endif</span>
                </div>
            </div>
            <div class="line return" style="width:{{$width??'100%'}}"></div>
        @elseif($order_status=='replaced')         
            <div class="steps">
                <div class="step return">
                    <span>Return @if($order->order_histories['replaced']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['replaced'])->format('d M y h:i A') }}</text> @endif</span>
                </div>
                <div class="step return">
                    <span>Pickup & Replace @if($order->order_histories['replaced']) <br><text> {{ \Carbon\Carbon::parse($order->order_histories['replaced'])->format('d M y h:i A') }}</text> @endif</span>
                </div>
            </div>
            <div class="line return" style="width:{{$width??'100%'}}"></div>
        @endif
    </div>
</div>
