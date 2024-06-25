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
                img{
                    width:170px;
                }
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
            button{
                background-color:#797676;
                border:none;
                border-radius:5px;
                span{
                    font-size:18px;
                }
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
            .footer-content{
                p{
                    color:#111111;
                    margin-bottom:0px;
                    font-size: 14px!important;
                }
            }
            .related-products{
                margin-bottom:20px;
            }
            .related-products .border{
                margin:10px;
                border:1px solid #eee;
                border-radius:3px;
            }
            .related-products td{
                width:200px;
            }
            .related-products img{
                width:58px;
                margin:18px;
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
            .order-summary {
                background-color:#F9FCF6;
            }
            .order-summary p{
               color:#000;
               margin-bottom:5px;
            }
            .div1{
                width: 48%;
                border-right: 1px solid #b9b9b9;
                padding: 14px;
            }
            .div2{
                width: 50%;
                position: absolute;
                left: 51%;
                top: 3px;
                padding: 14px;
            }
            .fs-2{
                font-size:14px;
            }
            .text-end{
                float:right!important;
            }
            .txt-start{
                float:left!important;
            }

            .products table{
                margin-top:20px;
                margin-bottom:20px;
                th{
                    color:#000;
                    background-color:#F9FCF6;
                    padding:8px;
                }
                .th1{
                    width:900px;
                }
            }
            .products table img{
                width:50px;
            }
            .products .tr{
                padding-bottom:20px;
                padding-top:10px;
                border-bottom: 1px solid #ccc!important;
            }
            .cnt{
                position: absolute;
                left: 70px;
                font-size: 14px;
                top: 18px;
            }
            .price{
                left: 30px;
            }
            .mb-0{
                margin-bottom:3px;
            }
            .products .total p,span{
               font-size:13px;
            }
            .related-products p{
                font-size:13px;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/password-change.png')}}" alt="">
        <p class="title-content text-center"><b>order placed successfully</b></p>
        <p class="text-left">Your order has been placed successfully. We will notify you once the order is shipped.</p>
        <div class="text-center my-3"><button class="px-3 py-2"><span class="text-white">View Order</span></button></div>
    </div>
    <div class="order-summary">
        <div class="div1">
            <p class="font-bold">Order Summary</p>
            <p class="fs-2">Order Summary : #1234567910</p>
            <p class="fs-2">Order Placed : 23 Jun 2024</p>
            <p class="fs-2">Order Total : ₹1207.00</p>
        </div>
        <div class="div2">
            <p class="font-bold">Shipping Address</p>
            <p class="fs-2">Suite 389 25272 Bechtelar Islands, Gislasontown, ND 98919</p>
        </div>
    </div>
    <div class="products">
        <table>
            <tr>
                <th class="th1 "><span class="txt-start">Product</span></th>
            </tr>
            <tr >
                <td class="tr">
                    <div>
                        <img src="{{asset('asset/home/special-product.png')}}" alt="">
                    </div>
                    <div class="cnt">
                        <span>Barnyard Millet Boiled / Kuthraivali Pulungal 1 Kg</span>
                        <div>
                            <p class="txt-start text-dark">Qty : 2</p>    
                            <p class="txt-start font-bold text-dark price">₹235.00</p>  
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tr">
                    <div>
                        <img src="{{asset('asset/home/special-product.png')}}" alt="">
                    </div>
                    <div class="cnt">
                        <span>Barnyard Millet Boiled / Kuthraivali Pulungal 1 Kg</span>
                        <div>
                            <p class="txt-start text-dark">Qty : 2</p>    
                            <p class="txt-start font-bold text-dark price">₹235.00</p>  
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="text-end total">
            <p class="mb-0">Items Subtotal : <span >₹1,200.00</span></p>
            <p class="mb-0">Coupon Discount : <span>₹1,200.00</span></p>
            <p class="mb-0">Shipping Cost : <span>₹1,200.00</span></p>
            <p class="mb-0 font-bold text-dark">Total <span >₹1,200.00</span></p>
        </div>
    </div>
    <p class="text-start">You may also like</p>
    <div class="related-products text-center">
        <table>
            <tr>
                <td class="">
                    <div class="border">
                        <img src="{{asset('asset/home/special-product.png')}}" alt="">
                    </div>
                </td>
                <td class="">
                    <div class="border">
                        <img src="{{asset('asset/home/special-product.png')}}" alt="">
                    </div>
                </td>
                <td class="">
                    <div class="border">
                        <img src="{{asset('asset/home/special-product.png')}}" alt="">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
                <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
                <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
            </tr>
        </table>
    </div>
    <div class="footer-content">
        <p class="text-center">Contact us via email <a href="">help@skyraaorganics.com</a> regarding any queries.</p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
