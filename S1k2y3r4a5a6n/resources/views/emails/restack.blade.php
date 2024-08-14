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
                img{
                    width:170px;
                }
            }
            .title-content{
                color: #000 !important;
                line-height: 1.5em;
                font-size: 23px;
                font-weight: 500;
                margin: 20px 10px 20px 10px;
                text-align:center!important;
            }
            .title-contents{
                color: #000000bd !important;
                line-height: 1.5em;
                font-size: 18px;
                text-align: center !important;
                font-weight: 600;
                margin: 18px 133px 20px 143px;
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

            button{
                background-color:#797676;
                border:none;
                border-radius:3px;
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
            .fdfd{
                font-weight: 500;
            }
            .green{
                color:#4CAF50!important;
            }
            @media only screen and (max-width:460px){
                .title-contents{
                    margin: 18px 18px 20px 18px!important;
                } 
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt=""></p>
    <p class="title-content"><b>Back In Stock!</b></p>
    <p class="text-left text-center">FINALLY!!! The wait is over. We’re so thrilled to inform you that the product you’ve been eagerly awaiting have finally been restocked.</p>
    <div class="text-center">
        <img  src="{{ $product['image'] }}" alt="restock">
    </div>
    <div class="bottom-classs">
        <p class="title-contents">{{ $product['name'] }}</p>
        <div class="text-center my-3"><button class="text-center px-3 py-3"><a href="{{ $product['link'] }}"><span class="text-white">Get it Now</span><img src="{{asset('asset/home/forward-icon.png')}}" alt="" class="forward-icon text-center"></a></button></div>
        <p class="title-content">Why are you still waiting?</p>
        <p class="text-center fdfd">Own it before it’s too late!</p>
    </div>
    <div class="footer-content">
        <p class="text-center">Contact us via email <a href="" class="green">{{$siteSetting->mail_support_address}}</a>  regarding any queries.</p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
          
        @endcomponent
    @endslot
@endcomponent
