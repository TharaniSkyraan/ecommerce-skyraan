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
                margin: -5px 55px 5px 53px;
                font-weight: 500;
                font-size: 14px;
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
            .summary .p1{
               color:#373737;
               margin-bottom:15px;
               margin-top:25px;
            }
            .summary .p2{
               color:#5F5F5F;
               margin-bottom:25px;
               font-size:20px;
               font-weight:700;
               text-align:center;
            }
            .text-end{
                float:right!important;
            }
            .txt-start{
                float:left!important;
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
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/contact-user.png')}}" alt="">
        <p class="title-content text-center"><b>Support Team</b></p>
        <p class="text-left text-center">We are here to assist you!</p>
    </div>
    <div class="summary text-center">
        <p class="p1">Our team is currently reviewing your message and will get back to you as soon as possible. If you have any further questions or additional information to share, please feel free to reply to this email.</p>
        <p class="p2">Thank you for reaching out to us</p>
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
