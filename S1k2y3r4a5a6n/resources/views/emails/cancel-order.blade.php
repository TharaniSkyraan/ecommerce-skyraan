@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ url("/login") }}'])
             
        @endcomponent
        <style>
            .wrapper{
                background-color:#EEE !important
            }
            .body{
                background-color:#EEE !important;
            }  
            .text-center{
                text-align:center !important;
            }
            .text-left{
                color: #474242 !important;
                line-height: 1.5rem;
                text-align: center !important;
                margin: 10px 55px 10px 53px;
                font-weight: 500;
                font-size: 15px;
            }
            .title{
                font-size: 25px;
                font-weight: 400;
                border-bottom: 2px solid  #e0dede;
                text-align: center !important;
                background-color: transparent !important;
                padding: 0px 0px 0px 0px;
                color: #5f5f5f !important;
                width: 99%;
                margin: 0 auto;
            }
            .title img{
                width:170px;
            }
            .title-content{
                color: #000000bd !important;
                line-height: 1.5em;
                font-size: 25px;
                text-align: center !important;
                font-weight: 600;
                margin: 20px;
            }
            .footer-content{
                text-align:center !important;
                padding: 1.2rem 0rem;
                border-top: 2px solid #e0dede;
            }
            .my-3{
                margin-top:20px;
                margin-bottom:20px;
            }
            .text-white{
                color:#fff;
            }
            .px-3{
                padding-right:20px;
                padding-left:20px;
            }
            .py-2{
                padding-top:8px;
                padding-bottom:8px;
            }
            .text-dark{
                color:#000;
            }
            .main-div{
                margin-top:20px;
            }
            a{
                text-decoration:none;
            }
            b{
                color:#242323;
            }
            .footer-content p{
                color:#111111;
                margin-bottom:0px;
                font-size: 13px!important;
            }
            .text-start{
               font-size:23px;
               font-weight:600;
               float:left!important;
               padding:10px 20px 10px 20px;
               width:100%;
               margin-bottom:0px;
               color:#000;
            }
            .font-bold{
                font-weight:700;
            }
            .td1 p{
                margin-bottom:2px;
                color:#000;
            }
            .order-summary p{
               color:#000;
               margin-bottom:5px;
            }
            .div1{
                width: 48%;
                padding: 14px 0px 14px 0px;
            }
            .fs-2{
                font-size:14px;
            }
            .text-end{
                float:right!important;
            }
            .bottom-div p{
                font-size:16px;
                color:#000;
            }
            .txt-start{
                float:left!important;
            }
            .order-div p{
                color:#000;
                margin-bottom:2px;
                font-size:14px;
            }
            .order-div table{
                margin:15px 0px 15px 0px ;
                border: 1px solid #eee;
            }
            .order-div .td1 img{
                padding:20px 20px 20px 0px;
                /* border-bottom:1px solid #eee; */
            }
            .order-div .td2 {
                padding-right:20px;
                /* border-bottom:1px solid #eee; */
            }
            .red{
                color:#FF4C4C!important;
            }
            .green{
                color:#073affc9!important;
            }
            .product-img{
                width:100px;
                height:100px;
            }
            table{
                width:100%
            }
            .td1{
               width:140px
            }

        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/cancel-mail.png')}}" alt="">
        <p class="title-content text-center"><b>We're Sad to See You Cancel</b></p>
        <p class="text-left text-center">We regret to let you know that your recent order has been successfully cancelled.</p>
    </div>
    <div class="order-summary text-center">
        <div class="div1">
            <p class="font-bold">Order Summary</p>
            <p class="fs-2">Order number : #{{$order->code}}</p>
            <p class="fs-2">Order Placed : {{$order->order_histories['order_placed']}}</p>
        </div>
    </div>
    <div class="order-div text-center">
        <table>
            @foreach($order->orderItems as $item)
                <tr>
                    <td class="td1">
                        <img src="{{$item->product_image}}" alt="{{$item->product_name}}" class="product-img">
                    </td>
                    <td class="td2">
                        <p>{{$item->product_name}}</p>
                        <p>Quantity : <b> {{$item->quantity}}</b></p>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="bottom-div">
        <p>We strive to provide the best service to our customers, but unfortunately you had to cancel your order. We apologise for failing to satisfy your needs. We’ll make sure that such activities will not happen in the future.</p>
        <p class="font-bold text-center">Hoping to receive an order from you very soon!</p>
    </div>
    <div class="footer-content">
        <p class="text-center"><b>Note:</b> If you have any query, then kindly contact our customer support via <a href="" class="green">{{$siteSetting->mail_support_address}}</a> </p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
