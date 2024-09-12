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
                color: #6d6d6d !important;
                line-height: 1.9rem;
                margin: 7px 30px 10px 30px;
                font-weight: 500;
                font-size: 16px;
            }
            .welcome-img{
                margin:7px 10px 0px 10px;
                width:550px;
            }
            .title{
                font-size: 25px;
                font-weight: 400;
                border-bottom: 2px solid  #e0dede;
                text-align: center !important;
                background-color: transparent !important;
                padding: 10px 10px 10px 10px;
                color: #5f5f5f !important;
                width: 80%;
                margin: 0 auto;
            }
            .title img{
                width:150px;
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
            .header{
                padding:32px!important
            }
            .footer{
                color: #54535c !important;
                font-weight: 500 !important;
                padding:32px !important
            }
            .footer-content{
                font-size: 14px;
                text-align:center !important;
                padding: 1.5rem 0rem;
                border-top: 1px #e0dede solid;
                margin: 0px 10px;
            }
            .footer-content p{
                font-size:13px;
            }
            .rounded-circle {
                border-radius: 50% !important;
                margin: 18px 23px 13px 26px;
                padding: 20px!important;
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
                color:#fff;
            }
            .delivery-cnt{
                width: 100%;
                border-top: #e0dede 1px solid;
                border-bottom: #e0dede 1px solid;
                margin: 23px 0 38px 0;
            }
            .delivery-cnt td{
                font-size:14px;
            }
            
            .delivery-cnt table{
                margin-left: auto;
                margin-right: auto;             
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
                /* padding:0px 10px 2px 1px; */
            }
            .my-3{
                margin-top:20px;
                margin-bottom:20px;
            }
            a{
                text-decoration: none;
            }
            .td1{
                padding-bottom:25px!important;
            }
            .center {
                margin-left: auto;
                margin-right: auto;
            }
            .green{
                color:#073affc9!important;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <p class="title-content"><b>Welcome {{$name}} !</b></p>
    <p class="text-left text-center">We’re so happy to have you here! Thank you for choosing us as your go-to shopping destination.</p>
    <p class="text-left text-center">We hope to meet all your needs and expectations. {{ $siteSetting->site_name }} provides products in various categories and helps you stay updated about our new arrivals.  </p>
    <img class="welcome-img" src="{{asset('asset/home/welcome-mail.svg')}}" alt="welocme-mail">
    <div class="delivery-cnt text-center">
        <table class="center">
            <tr>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <img src="{{asset('asset/home/wel2.svg')}}" alt="welocme-mail">
                    </div>
                </td>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <img src="{{asset('asset/home/wel1.svg')}}" alt="welocme-mail">
                    </div>
                </td>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <img src="{{asset('asset/home/wel3.svg')}}" alt="welocme-mail">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td1">Secure Payments</td>
                <td  class="td1">Fast Shipping</td>
                <td  class="td1">Easy Returns</td>
            </tr>
        </table>
    </div>
    <div class="bottom-classs">
        <p class="title-content">Ready to start shopping?</p>
        <p class="text-left text-center">Use your sign-up bonus for your first purchase. Head over to {{ $siteSetting->site_name }} and let the adventure begin. </p>
        <div class="text-center my-3">
            <button class="text-center px-3 py-3">
                <a href="{{url('/')}}">
                    <span class="text-white">shop now</span>              
                    <img src="{{ asset('asset/home/forward-icon.png') }}" alt="Forward Icon" style="filter: brightness(0) invert(1) grayscale(1); width:15px; position: relative; bottom: -4px; left: 10px;">
                </a>
            </button>
        </div>
        <p class="text-left text-center">If you have any queries, feel free to reach out to our customer care support <a href="" class="green">{{$siteSetting->mail_support_address}}</a></p>
    </div>
    <div class="footer-content">
        <p class="text-center"> <b> Please note : </b>This is an auto-generated email, please do not reply to this email. If you’d like to unsubscribe and stop receiving these emails, <a href="{{url('/')}}/unsubscribe?email={{$email}}">click here</a></p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
          
        @endcomponent
    @endslot
@endcomponent
