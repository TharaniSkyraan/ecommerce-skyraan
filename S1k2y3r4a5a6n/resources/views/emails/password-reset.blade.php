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
                margin: 10px;
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
                font-size: 14px;
                text-align:center !important;
                padding: 1.5rem 0rem;
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
                padding-top:13px;
                padding-bottom:13px;
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
                }
            }
            .green{
                color:#4CAF50!important;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/reset-password.png')}}" alt="">
        <p class="title-content text-center"><b>No Worries, We Got You!</b></p>
        <p class="text-left">Hi {{name}}, We have received a request to reset the password for your Skyraa E-Commerce account</p>
        <div class="text-center my-3"><button class="px-3 py-2"><a href="{{$resetLink}}"><span class="text-white">Reset Password Link</span></a></button></div>
        <p class="text-center text-dark">Link valid only for 10 minutes.</p>
    </div>
    <div class="footer-content">
        <p class="text-center"> <b> Note : </b>If the password reset is no longer required, kindly disregard this email. For further queries, please contact us through <a href="" class="green">help@skyraaorganics.com</a></p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
