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
                margin: 5px 55px 5px 53px;
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
            .summary .p1{
               color:#373737;
               margin-bottom:15px;
               margin-top:25px;
            }
            .summary .p2{
               color:#373737;
               margin-bottom:25px;
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
        <img src="{{asset('asset/home/refund-cancel.svg')}}" alt="">
        <p class="title-content text-center"><b>Refund Requested <span class="red">Failed</span></b></p>
        <p class="text-left text-center">We are sorry to inform you that we encountered an issue processing your refund request.</p>
    </div>
    <div class="summary text-center">
        <p class="p1">After a brief review (state the reason), we’ve identified discrepancies between the information provided and our records. This mismatch has prevented us from proceeding with your refund this time.</p>
        <p class="p2">We apologize for any inconvenience this may have caused and appreciate your understanding in this matter.</p>
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
