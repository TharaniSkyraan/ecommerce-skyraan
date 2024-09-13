@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ url("/login") }}'])
        @endcomponent
        <style>
            .wrapper {
                background-color: #EEE !important;
            }
            .body {
                background-color: #EEE !important;
            }  
            td {
                padding: 10px !important;
                vertical-align: top;
            }
            p {
                color: #000000bd !important;
                margin: 5px 0;
            }
            .text-center {
                text-align: center !important;
            }
            .text-left {
                color: #000 !important;
                line-height: 1.5rem;
                margin: 19px 30px 10px 30px;
                font-weight: 400;
                font-size: 16px;
            }
            .welcome-img {
                width: 97%;
                margin: 7px 10px 0px 10px;
            }
            .title {
                font-size: 25px;
                font-weight: 400;
                text-align: center !important;
                background-color: transparent !important;
                padding: 10px 10px 0px 10px;
                color: #5f5f5f !important;
                width: 80%;
                margin: 0 auto;
            }
            .title img {
                width: 170px;
            }
            .footer {
                color: #54535c !important;
                font-weight: 500 !important;
                padding: 32px !important;
            }
            .footer-content {
                text-align: center !important;
                padding: 1.5rem 0rem;
                border-top: 1px #e0dede solid;
                margin: 0px 17px;
            }
            .footer-content p {
                font-size: 13px !important;
            }
            .delivery-cnt {
                margin: 25px;
                background-color: #FCFCFC;
                width: 100%;
            }
            .delivery-cnt img {
                margin: 10px;
                width: 100px;
                height: auto;
            }
            .delivery-cnt td {
                vertical-align: top;
            }
            .delivery-cnt tr {
                border-bottom: 1px solid #000;
            }
            .product-info {
                display: flex;
                justify-content: space-between;
                padding-left: 10px;
            }
            .product-info-left, .product-info-right {
                width: 50%;
                padding-left: 10px;
            }
            .product-info p {
                margin: 5px 0;
            }
            .product-info-left p {
                word-wrap: break-word;
                white-space: normal;
                max-width: 100%;
            }
            .view-cart-button {
                background-color: #797676;
                border: none;
                padding: 10px 20px;
                text-decoration: none;
                color: #fff;
                font-weight: bold;
                display: inline-block;
            }
            a {
                text-decoration: none;
                color: inherit;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <img class="welcome-img" src="{{asset('asset/home/forget-order.png')}}" alt="image">
    <p class="text-start">Hi {{$name}}, Your cart items are displayed below:</p>
    <div class="delivery-cnt">
        <table width="100%">
            @foreach($cart_products as $cart)
                <tr>
                    <td width="30%">
                        <img src="{{ $cart->image }}" alt="Product Image">
                    </td>
                    <td class="product-info">
                        <div class="product-info-left">
                            <p>{{ $cart->name }}</p> <!-- Long product names will wrap properly -->
                            <p>Quantity: <b>{{ $cart->quantity }} nos</b></p>
                        </div>
                        <div class="product-info-right">
                            <span>₹{{ $cart->price }}</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="text-center mt-3">
        <a href="{{url('/cart')}}" class="view-cart-button">View Cart</a>
    </div>
    <p class="text-left text-center">If you have any queries, feel free to reach out to our customer care support <a href="" class="green">{{$siteSetting->mail_support_address}}</a></p>
    <div class="footer-content">
        <p class="text-center"><b>Please note:</b> This is an auto-generated email, please do not reply. To unsubscribe, <a href="{{url('/')}}/unsubscribe?email={{$email}}">click here</a>.</p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
