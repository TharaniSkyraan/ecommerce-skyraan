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
            td {
                padding:0px !important;
            }
            p{
                color : #000000bd !important;
            }
            .text-center{
                text-align:center !important;
            }
            .text-left{
                color: #000 !important;
                line-height: 1.5rem;
                margin: 19px 30px 10px 30px;
                font-weight: 400;
                font-size: 14px;
            }
            .welcome-img{
                width:97%;
                margin:7px 10px 0px 10px;
            }
            .title{
                font-size: 25px;
                font-weight: 400;
                text-align: center !important;
                background-color: transparent !important;
                padding: 10px 10px 0px 10px;
                color: #5f5f5f !important;
                width: 80%;
                margin: 0 auto;
                img{
                    width:170px;
                }
            }
            .title-content{
                color: #000000bd !important;
                line-height: 1.5em;
                font-size: 23px;
                text-align: left !important;
                font-weight: 500;
                margin: 20px 10px 20px 10px;
                text-align:center!important;
            }
            .footer{
                color: #54535c !important;
                font-weight: 500 !important;
                padding:32px !important
            }
            .footer-content{
                text-align:center !important;
                padding: 1.5rem 0rem;
                border-top: 1px #e0dede solid;
                margin: 0px 17px;
            }
            .footer-content p{
                font-size:14px!important;
            }
            .py-2 {
                padding-top: .5rem !important;
                padding-bottom: .5rem !important;
            }
            .px-2 {
                padding-right: .7rem !important;
                padding-left: .7rem !important;
            }
            .px-3 {
                padding-right: 2rem !important;
                padding-left: 2rem !important;
            }
            .px-4{
                padding-right: 3rem !important;
                padding-left: 3rem !important;
            }
            .py-3{
                padding-top: .9rem !important;
                padding-bottom: .9rem !important;
            }
            .clr-grey{
                background-color: #e0dede;
            }
            .text-white{
                color:#fff!important;
            }
            .View span{
                font-weight:bold;
                font-size:14px;
            }
            button{
                background-color:#797676;
                border:none;
            }
            .forward-icon{
                filter: brightness(0) invert(1) grayscale(1);
                width:15px;
                bottom: -4px;
                left: 10px;                
            }
            .my-3{
                margin-top:20px;
                margin-bottom:20px;
            }
            a{
                text-decoration: none;
            }

            .text-start{
               font-size:23px;
               font-weight:600;
               float:left!important;
               padding:20px 20px 20px 20px;
               width:100%;
               margin-bottom:0px;
            }
            .delivery-cnt p{
                margin-bottom:0px;
            }
            .delivery-cnt span{
                font-weight:bold;
                font-size:14px;
                color:#000;
            }
            .delivery-cnt .margin-left p,span{
                margin:0px 25px 0px 28px;
                font-size:14px;
            }
            .delivery-cnt img{
                margin: 18px 25px 24px 26px;
                width:57px;
            }
            .delivery-cnt table{
                margin:25px;
                background-color:#FCFCFC;
            }
            .delivery-cnt td{
                margin:25px;
            }
            .delivery-cnt tr{
               margin-bottom:10px;
            }
            .mt-3{
                margin-top:20px;
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
                width:57px;
                margin:18px;
            }
            .font-bold{
                font-weight:700;
            }
            .td1 p{
                margin-bottom:2px;
                font-size:14px;
            }
            .hr-border{
                border-bottom:1px solid #000;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <img class="welcome-img" src="{{asset('asset/home/forget-order.png')}}" alt="welocme-mail">
    <p class="text-start">Hi Elena, Your cart items displayed here</p>
    <div class="delivery-cnt">
        <table>
            <tr>
                <td class="">
                    <img src="{{asset('asset/home/special-product.png')}}" alt="">
                </td>
                <td class="margin-left">
                    <p>Barnyard Millet Boiled / Kuthraivali Pulungal</p>
                    <p>Item weight : <b>1 Kg</b></p>
                    <p>Quantity : : <b>1 nos</b></p>
                    <span>₹250.00</span>
                </td>
            </tr>
            <tr>
                <td class="">
                    <img src="{{asset('asset/home/special-product.png')}}" alt="">
                </td>
                <td class="margin-left">
                    <p>Barnyard Millet Boiled / Kuthraivali Pulungal</p>
                    <p>Item weight : <b>1 Kg</b></p>
                    <p>Quantity : : <b>1 nos</b></p>
                    <span>₹250.00</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="text-center mt-3 View"><button class="text-center px-3 py-3"><span class="text-white">View Cart</span><img src="{{asset('asset/home/forward-icon.png')}}" alt="" class="forward-icon text-center"></button></div>
    <p class="text-start">You may also like</p>
    <div class="related-products text-center">
        <table>
            <div >
                <tr class="hr-border">
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
                <tr class="hr-border">
                    <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
                    <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
                    <td class="td1"><p class="text-center">Barnyard Millet 1kg</p><p class="font-bold text-center">₹250.00</p></td>
                </tr>
            </div>
        </table>
    </div>
    <p class="text-left text-center">If you have any queries, feel free to reach out to our customer care support <a href="">help@skyraaorganics.com</a></p>
    <div class="footer-content">
        <p class="text-center"> <b>Please note : </b>This is an auto-generated email, please do not reply to this email. If you’d like to unsubscribe and stop receiving these emails <a href=""> click here</a></p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
          
        @endcomponent
    @endslot
@endcomponent
