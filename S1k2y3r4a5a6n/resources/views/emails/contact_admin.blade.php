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
                margin: -9px 55px 21px 53px;
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

            .main-div{
                margin-top:20px;
            }
            b{
                color:#242323;
            }
            .card .p1{
               color:#373737;
               font-size:18px;
               font-weight:500;
            }
            .card .p2{
               color:#5F5F5F;
               margin-bottom: 12px;
               margin-top: 10px;
               font-size:15px;
            }
            .text-end{
                float:right!important;
            }
            .txt-start{
                float:left!important;
            }
            .card{
                border:1px solid #eee;
                padding: 18px;
            }
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <div class="main-div text-center">
        <img src="{{asset('asset/home/contact-admin.png')}}" alt="">
        <p class="title-content text-center"><b>Hello Team!</b></p>
        <p class="text-left text-center">We've got a new form submission.</p>
    </div>
    <div class="card">
        <div class="p1">Name</div>
        <div class="p2">{{$data['name']}}</div>
        <hr>
        <div class="p1">E Mail ID</div>
        <div class="p2">{{$data['email']}}</div> 
        <hr>       
        <div class="p1">Feedback</div>
        <div class="p2">I hope you’re doing well. I’m sending this email regarding the issue I faced while using the website. I lost all my data and the products I added to the cart since I did not log in for a while. Also, the delivery is getting delayed more than the estimated time.I hope your team will help me resolve this issue as soon as possible.</div>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
