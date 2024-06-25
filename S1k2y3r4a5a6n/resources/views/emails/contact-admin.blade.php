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
            .button-blue{
                background-color: #4285f4 !important;
                padding: 2px 10px;
                border:none !important;
                font-size:13px;
            }
            .button-gray{
                background-color: #817a7a !important; 
                padding: 2px 10px;
                border:none;
            }
            .text-center{
                text-align:center !important;
            }
            .text-left{
                color: #474242 !important;
                line-height: 2em;
                text-align: left !important;
                margin: 30px;
                font-weight: 500;
                font-size: 16px;
            }
            .title{
                font-size: 25px;
                font-weight: 400;
                border-bottom: 1px dotted;
                text-align: center !important;
                background-color: transparent !important;
                padding: 30px 10px 30px 10px;
                color: #5f5f5f !important;
                width: fit-content;
                margin: 0 auto;
                border-color: #5692f5ba;
            }
            .title-content{
                color: #000000bd !important;
                line-height: 1.5em;
                font-size: 18px;
                text-align: left !important;
                font-weight: 500;
                margin: 30px;
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
                border-top: 1px #4285f4 solid;
                margin: 0px 30px;
            }
            .table{
                padding: 0% 5% !important;
            }
            .table thead th{
                font-weight: 500 !important;
                color: #0060ff !important;
                text-align:left !important;
                line-height: 30px !important;
                border : none !important;
                font-size: 20px !important;
            }
            .company_name{
                font-size: 13px !important;
                color: #373737 !important;
            }
            .table tbody td{
                line-height: 22px !important;
                font-size: 12px !important;
                font-weight: 600 !important;
                color: #000000 !important;
            }
            .blue-color{
                / color: #0060ff !important; /
            }
            .table table{
                padding-bottom: 3%;        
                border: 1px #4285f4 solid;
                border-radius: 10px;
                padding: 0px 0px 0px 5px;
            }
            .expiry_date
            {                
                font-size: 10px !important;
                font-weight: 500 !important;
                color: #817a7a !important;
            }     
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><b>Conact From - {{$data['name']}}</b></p>
    <p class="title-content"><b>Dear Admin,</b></p>
    <p class="text-left"></p>
    <p class="text-left">Following email has been recieved from contact form :</p>
    <p class="text-left">
        <strong>Name</strong> : {{$data['name']}}<br>
        <strong>Email</strong> : {{$data['email']}}<br>
        {{ $data['feedback'] }}
    </p>
    <div class="footer-content">
        <span> <b> Contact us <b> </span>
        <span style="margin: 5px;">:</span>
       <span> <img draggable="false" src="{{ asset('asset/home/email-mail.png') }}" style="margin-bottom: -2px;width: 15px;"/> mailto:support@mugaam.com </span>
       <span style="margin: 5px;">|</span>
       <span> <img draggable="false" src="{{ asset('asset/home/email-phone.png') }}" style="margin-bottom: -2px;width: 15px;"/> +91 9150555033 </span>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            You have received this mail because your e-mail ID
            is registered with mugaam.com. This is a system-generated e-mail 
            regarding your account preferences, please don't reply to this message. 
            The jobs sent in this mail have been posted by the clients. <br> © {{ date('Y') }} SKyraa Ecommerce, All rights reserved
        @endcomponent
    @endslot
@endcomponent
