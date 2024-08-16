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
                margin: 10px 42px 10px 47px;
                font-weight: 500;
                font-size: 16px;
            }
            .title{
                font-size: 25px;
                font-weight: 400;
                border-bottom: 2px solid  #e0dede;
                text-align: center !important;
                background-color: transparent !important;
                padding: 0px 0px 0px 0px;
                color: #5f5f5f !important;
                width: 80%;
                margin: 0 auto;
            }
            .title img{
                width:170px;
            }

            .title-content{
                color: #000000bd !important;
                line-height: 1.5em;
                font-size: 29px;
                text-align: center !important;
                font-weight: 700;
                margin: 20px;
            }
        
            .footer-content{
                font-size: 14px;
                text-align:center !important;
                padding: 1.5rem 0rem;
                border-top: 2px solid #e0dede;
            }
            .my-3{
                margin-top:20px;
                margin-bottom:20px;
            }
            .card{
                background-color:#ebebeb80;
                border:none;
                box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
            }
            .card span{
                font-size:18px;
            }
            .card .numbers{
                font-weight:700;
                color:#111;
                font-size:24px;
                margin-top:5px;
                margin-bottom:5px;
            }
            
            .text-white{
                color:#fff;
            }
            .px-3{
                padding-right:20px;
                padding-left:20px;
            }
            .py-2{
                padding-top:13px;
                padding-bottom:13px;
            }
            .text-dark{
                color:#000;
            }
            .main-div{
                margin-top:20px;
            }
            .main-div img{
                padding-left: 237px;
                top: -29px;
            }
            
            a{
                text-decoration:none;
            }
            b{
                color:#242323;
            }
            .footer-content p{
                color:#111111;
                font-size:13px;
            }
            .card-div{
                margin:0px 130px 0px 130px;
            }
            .notes{
                margin: -11px 35px 26px 20px;
            }
            .notes p{
                margin:0px;
            }
            .green{
                color:#073affc9!important;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <div class="main-div text-center">
        <p class="title-content text-center">You're just a click away</p>
        <p class="text-left">From completing your email verification for your {{ $siteSetting->site_name }} account.</p>
        <div class="text-center my-3 card-div"><div class="card px-3 py-2"><span class="text-dark">Your verification OTP is </span><p class="numbers text-center">862368</p></div></div>
        <img src="{{asset('asset/home/cursor-finger.png')}}" alt="">
        <div class="notes"><p class="text-center text-dark ">Please note that the code will be</p><p class="text-center"><b>valid only for 5 minutes</b></p></div>
    </div>
    <div>
        <p class="text-center text-dark">Do not share your OTP with anyone for security reasons. We immensely care for all our users’ privacy, and our customer service will never ask any users to disclose their OTP. For any concern, you can contact us via <a href="">{{$siteSetting->mail_support_address}}</a></p>
    </div>
    <div class="footer-content">
        <p class="text-center"> <b> Please note : </b>This is an auto-generated email, please do not reply to this email. If you’d like to unsubscribe and stop receiving these emails,<a href="" class="green">click here</a></p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')

        @endcomponent
    @endslot
@endcomponent
