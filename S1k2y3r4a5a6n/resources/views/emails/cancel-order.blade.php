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
                padding:20px 20px 0px 20px;
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
                color:#4CAF50!important;
            }

        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/cancel-mail.png')}}" alt="">
        <p class="title-content text-center"><b>We're Sad to See You Cancel</b></p>
        <p class="text-left text-center">We regret to let you know that your recent order has been successfully cancelled.</p>
    </div>
    <div class="order-summary text-center">
        <div class="div1">
            <p class="font-bold">Order Summary</p>
            <p class="fs-2">Order number : #1234567910</p>
            <p class="fs-2">Order Placed : 23 Jun 2024</p>
        </div>
    </div>
    <div class="order-div text-center">
        <table>
            <tr>
                <td class="td1">
                    <img src="{{asset('asset/home/special-product.png')}}" alt="">
                </td>
                <td class="margin-left td2">
                    <p>Barnyard Millet Boiled / Kuthraivali Pulungal</p>
                    <p>Quantity : <b>2</b></p>
                    <p>Reason for Cancellation <span class="red">Incorrect Item</span></p>
                </td>
            </tr>
            <tr>
                <td class="td1">
                    <img src="{{asset('asset/home/special-product.png')}}" alt="">
                </td>
                <td class="margin-left td2">
                    <p>Barnyard Millet Boiled / Kuthraivali Pulungal</p>
                    <p>Quantity : <b>2</b></p>
                    <p>Reason for Cancellation <span class="red">Incorrect Item</span></p>
                </td>
            </tr>
        </table>
    </div>
    <div class="bottom-div">
        <p>We strive to provide the best service to our customers, but unfortunately you had to cancel your order. We apologise for failing to satisfy your needs. We’ll make sure that such activities will not happen in the future.</p>
        <p class="font-bold text-center">Hoping to receive an order from you very soon!</p>
    </div>
    <div class="footer-content">
        <p class="text-center"><b>Note:</b> If you have any query, then kindly contact our customer support via <a href="" class="green">help@skyraaorganics.com</a> </p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
