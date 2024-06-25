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
            .rounded-circle {
                border-radius: 50% !important;
                margin: 23px 66px 13px 67px;
                padding: 15px;
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
                td{
                    font-size:14px;
                }
              
                table{
                    margin: 23px 12px 38px 10px;
                    border-top: #e0dede 1px solid;
                    border-bottom: #e0dede 1px solid;               
                }

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
        </style>
    @endslot
    {{-- Body --}}
    <p class="title"><img src="{{asset('asset/home/default-hover2.png')}}" alt=""></p>
    <p class="title-content"><b>Welcome Elena !</b></p>
    <p class="text-left text-center">We’re so happy to have you here! Thank you for choosing us as your go-to shopping destination.</p>
    <p class="text-left text-center">We hope to meet all your needs and expectations. Skyraa E-Commerce provides products in various categories and helps you stay updated about our new arrivals.  </p>
    <img class="welcome-img" src="{{asset('asset/home/welcome-mail.svg')}}" alt="welocme-mail">
    <div class="delivery-cnt text-center">
        <table align="center">
            <tr>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <g clip-path="url(#clip0_978_540)">
                        <path d="M4.58325 14.1668C5.27361 14.1668 5.83325 13.6071 5.83325 12.9168C5.83325 12.2264 5.27361 11.6668 4.58325 11.6668C3.8929 11.6668 3.33325 12.2264 3.33325 12.9168C3.33325 13.6071 3.8929 14.1668 4.58325 14.1668Z" fill="#808080"/>
                        <path d="M15.8332 2.50011H4.16654C3.06188 2.50144 2.00284 2.94085 1.22173 3.72196C0.440613 4.50308 0.00120114 5.56212 -0.00012207 6.66678L-0.00012207 13.3334C0.00120114 14.4381 0.440613 15.4972 1.22173 16.2783C2.00284 17.0594 3.06188 17.4988 4.16654 17.5001H15.8332C16.9379 17.4988 17.9969 17.0594 18.778 16.2783C19.5591 15.4972 19.9986 14.4381 19.9999 13.3334V6.66678C19.9986 5.56212 19.5591 4.50308 18.778 3.72196C17.9969 2.94085 16.9379 2.50144 15.8332 2.50011ZM4.16654 4.16678H15.8332C16.4963 4.16678 17.1321 4.43017 17.601 4.89901C18.0698 5.36786 18.3332 6.00374 18.3332 6.66678H1.66654C1.66654 6.00374 1.92994 5.36786 2.39878 4.89901C2.86762 4.43017 3.5035 4.16678 4.16654 4.16678ZM15.8332 15.8334H4.16654C3.5035 15.8334 2.86762 15.5701 2.39878 15.1012C1.92994 14.6324 1.66654 13.9965 1.66654 13.3334V8.33345H18.3332V13.3334C18.3332 13.9965 18.0698 14.6324 17.601 15.1012C17.1321 15.5701 16.4963 15.8334 15.8332 15.8334Z" fill="#4CAF50"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_978_540">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                    </div>
                </td>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <g clip-path="url(#clip0_978_540)">
                        <path d="M4.58325 14.1668C5.27361 14.1668 5.83325 13.6071 5.83325 12.9168C5.83325 12.2264 5.27361 11.6668 4.58325 11.6668C3.8929 11.6668 3.33325 12.2264 3.33325 12.9168C3.33325 13.6071 3.8929 14.1668 4.58325 14.1668Z" fill="#808080"/>
                        <path d="M15.8332 2.50011H4.16654C3.06188 2.50144 2.00284 2.94085 1.22173 3.72196C0.440613 4.50308 0.00120114 5.56212 -0.00012207 6.66678L-0.00012207 13.3334C0.00120114 14.4381 0.440613 15.4972 1.22173 16.2783C2.00284 17.0594 3.06188 17.4988 4.16654 17.5001H15.8332C16.9379 17.4988 17.9969 17.0594 18.778 16.2783C19.5591 15.4972 19.9986 14.4381 19.9999 13.3334V6.66678C19.9986 5.56212 19.5591 4.50308 18.778 3.72196C17.9969 2.94085 16.9379 2.50144 15.8332 2.50011ZM4.16654 4.16678H15.8332C16.4963 4.16678 17.1321 4.43017 17.601 4.89901C18.0698 5.36786 18.3332 6.00374 18.3332 6.66678H1.66654C1.66654 6.00374 1.92994 5.36786 2.39878 4.89901C2.86762 4.43017 3.5035 4.16678 4.16654 4.16678ZM15.8332 15.8334H4.16654C3.5035 15.8334 2.86762 15.5701 2.39878 15.1012C1.92994 14.6324 1.66654 13.9965 1.66654 13.3334V8.33345H18.3332V13.3334C18.3332 13.9965 18.0698 14.6324 17.601 15.1012C17.1321 15.5701 16.4963 15.8334 15.8332 15.8334Z" fill="#4CAF50"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_978_540">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                    </div>
                </td>
                <td class="">
                    <div class="rounded-circle clr-grey ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <g clip-path="url(#clip0_978_540)">
                        <path d="M4.58325 14.1668C5.27361 14.1668 5.83325 13.6071 5.83325 12.9168C5.83325 12.2264 5.27361 11.6668 4.58325 11.6668C3.8929 11.6668 3.33325 12.2264 3.33325 12.9168C3.33325 13.6071 3.8929 14.1668 4.58325 14.1668Z" fill="#808080"/>
                        <path d="M15.8332 2.50011H4.16654C3.06188 2.50144 2.00284 2.94085 1.22173 3.72196C0.440613 4.50308 0.00120114 5.56212 -0.00012207 6.66678L-0.00012207 13.3334C0.00120114 14.4381 0.440613 15.4972 1.22173 16.2783C2.00284 17.0594 3.06188 17.4988 4.16654 17.5001H15.8332C16.9379 17.4988 17.9969 17.0594 18.778 16.2783C19.5591 15.4972 19.9986 14.4381 19.9999 13.3334V6.66678C19.9986 5.56212 19.5591 4.50308 18.778 3.72196C17.9969 2.94085 16.9379 2.50144 15.8332 2.50011ZM4.16654 4.16678H15.8332C16.4963 4.16678 17.1321 4.43017 17.601 4.89901C18.0698 5.36786 18.3332 6.00374 18.3332 6.66678H1.66654C1.66654 6.00374 1.92994 5.36786 2.39878 4.89901C2.86762 4.43017 3.5035 4.16678 4.16654 4.16678ZM15.8332 15.8334H4.16654C3.5035 15.8334 2.86762 15.5701 2.39878 15.1012C1.92994 14.6324 1.66654 13.9965 1.66654 13.3334V8.33345H18.3332V13.3334C18.3332 13.9965 18.0698 14.6324 17.601 15.1012C17.1321 15.5701 16.4963 15.8334 15.8332 15.8334Z" fill="#4CAF50"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_978_540">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
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
        <p class="text-left text-center">Use your sign-up bonus for your first purchase. Head over to Skyraa Ecommerce and let the adventure begin. </p>
        <div class="text-center my-3"><button class="text-center px-3 py-3"><span class="text-white">shop now</span><img src="{{asset('asset/home/forward-icon.png')}}" alt="" class="forward-icon text-center"></button></div>
        <p class="text-left text-center">If you have any queries, feel free to reach out to our customer care support <a href="">help@skyraaorganics.com</a></p>
    </div>
    <div class="footer-content">
        <p class="text-center"> <b> Please note : </b>This is an auto-generated email, please do not reply to this email. If you’d like to unsubscribe and stop receiving these emails, <a href="">click here</a></p>
    </div>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
          
        @endcomponent
    @endslot
@endcomponent
