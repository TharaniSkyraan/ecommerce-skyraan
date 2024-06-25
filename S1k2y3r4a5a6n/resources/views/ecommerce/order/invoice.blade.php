<html>
    <head>
        <style>
            .container{
                margin-left: 0%;
                margin-right: 4%;
            }
            .py-4{
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            .fw-bold {
            font-weight: 700 !important;
            }
            .text-end {
                text-align: right !important;
            }
            .py-2 {
                padding-top: .5rem !important;
                padding-bottom: .5rem !important;
            }
            .fw-normal {
                font-weight: 400 !important;
            }
            .pe-0 {
                padding-right: 0 !important;
            }
            .col-2 {
                flex: 0 0 auto;
                width: 46.66666667%;
            }
            .pe-xl-3 {
                padding-right: 1rem !important;
            }
            .py-3 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            .col-3 {
                flex: 0 0 auto;
                width: 25%;
            }
            table {
                margin-top: 5px;
            }

            .px-3 {
                padding-right: 1rem !important;
                /* padding-left: 1rem !important; */
            }
            .p-4 {
                padding: 0.5rem !important;
            }
            .text-dark {
                color:#000 !important;
            }
            .text-center {
                text-align: center !important;
            }
            .p-3 {
                padding: 1rem !important;
            }

            hr {
                margin-top:10px;
            }
            .invoice-footer {
                background-color: var(-- -content-color);
            }
            .py-5 {
                padding-top: 0rem !important;
                padding-bottom: 3rem !important;
            }
            .align-self-center {
                align-self: center !important;
            }
            .border-end {
                border-right:1px solid #fff !important;
            }
            .col-4 {
                flex: 0 0 auto;
                width: 33.33333333%;
            }
            .pb-3 {
                padding-bottom: 1rem !important;
            }
            .pb-2 {
                padding-bottom: .5rem !important;
            }
            .h-sms {
                font-size: 14px;
            }
            .lh-lg {
                line-height: 2 !important;
            }
            .erfef {
                height: 128px!important;
            }
            .px-4 {
                padding-right: 1.5rem !important;
                padding-left: 1.5rem !important;
            }
            .align-items-center {
                align-items: center !important;
            }
            .px-5 {
                padding-right: 3rem !important;
                padding-left: 3rem !important;
            }
            .gap-2 {
                gap: .5rem !important;
            }
            .ms-2 {
                margin-left: .5rem !important;
            }
            .gap-1 {
                gap: .25rem !important;
            }
            .class-header {
                text-align-last: justify!important;
                span{
                   font-size:29px;
                }
            }

            .text-start{
               float:left!important;
            }
            .text-end{
                float:right!important;
            }
            .hjjfehj{
                font-size:18px;
                margin-bottom: 10px;
                margin-top: 10px;
            }
            .order-history{
               small{
                font-size:16px;
               } 
               span{
                font-size:15px;
               } 
            }
            .ewhjd{
                font-size:13px;
            }
            th h6{
                margin-bottom:10px;
            }
            .ewf{
                font-size:19px;
            }
            .fonts{
                font-size:13px;
            }
            tr{
                font-size: 12px;
            }
            .class-headers span {
                font-size: 14px;
            }
            .col-foo{
                width:250px;
            }
            .pt-0{
                padding-top:0px!important;
                padding-bottom:0!important;
            }
            .pt-2{
                padding-top:1px;
            }
            .jkef{
                padding-left:50px;
            }
            .jkef h6{
                font-size:10px;
            }
            .hjfew{
                font-size:20px;
                height:60px;
            }
            .invoice-container {
                overflow: hidden;
            }
            .logo-img{
                width:125px;
            }
            .tabless{
                table {
                   border-collapse: collapse;
                }

                thead th {
                    border: 1px solid #000;
                   
                }

                tbody td {
                    border: 1px solid #000;
                   
                }

                tbody tr {
                    border: 1px solid #000;
                   
                }
                tbody th {
                    border: 1px solid #000;
                   
                }

                th, td {
                    padding: 10px;
                    text-align: center;
                }

                thead tr {
                    border: 1px solid #000;
                   
                }

            }
            .b-none{
                border:none!important;
            }

        </style>
    </head>
    <body>
        <div class="container pb-4" id="invoice">
            <div>
                <div class="invoice-container">
                    <p class="fw-normal b-0 hjfew"><img src="{{ $logo_base64 }}" alt="" class="text-start logo-img"> <span class="text-end"> Invoice No : {{ $order['invoice_number']??'' }} <br><br> Date : {{ \Carbon\Carbon::parse($order['invoice_date'])->format('d-m-Y h:i A')}} </span></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-2 pe-0 order-history">
                    <p class="">
                        <small class="fw-bold">Order Number</small>
                        <span class="pe-xl-3 pe-0">:&nbsp;&nbsp;{{ $order['code'] }}</span>
                    </p>
                    <p class="">
                        <small class="fw-bold">Order Date</small>
                        <span class="pe-xl-3 pe-0">:&nbsp;&nbsp;{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y h:i: A')}}</span>
                    </p>
                    <p class=" pb-3">
                        <small class="fw-bold">Shipping Address</small>
                        <span class="pe-xl-3 pe-0">:&nbsp;&nbsp;{{ $shipment_address['address'] }}</span>
                    </p>
                </div>
            </div>
            <div class="billed tabless">
                <table style="border-collapse: collapse!important;">
                    <thead>
                        <tr>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Description</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Qty</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Gross Amount</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Discount</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Taxable Amount</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Tax Amount</h6></th>
                            <th scope="col"><h6 class="p-4 text-dark fw-bold ewhjd">Total Amount</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tax_amount = $taxable_amount = 0; @endphp
                        @foreach($order_items as $item)
                            @php $attributes = \App\Models\AttributeSet::where('id',explode(',',$item['attribute_set_ids']))->pluck('name')->toArray(); @endphp
                            <tr class="text-center">
                                <td class="p-4"><span class="text-start">{{ $item['product_name'] }} @if(count($attributes)!=0) | {{ implode(' |',$attributes) }} @endif | {{$item['tax'] }}</span></td>
                                <td class="p-3">{{ $item['quantity'] }}</td>
                                <td class="p-3">{{ $item['gross_amount'] }}</td>
                                <td class="p-3">{{ $item['discount_amount'] }}</td>
                                <td class="p-3">{{ $item['taxable_amount'] }}</td>
                                <td class="p-3">{{ $item['tax_amount'] }}</td>
                                <td class="p-3">{{ $item['sub_total'] }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="p-4"><span class="text-start">Shipping Charges | {{$item['shipping_tax'] }}</span></td>
                                <td class="p-3"> </td>
                                <td class="p-3">{{ $item['shipping_gross_amount'] }}</td>
                                <td class="p-3">{{ $item['shipping_discount_amount'] }}</td>
                                <td class="p-3">{{ $item['shipping_taxable_amount'] }}</td>
                                <td class="p-3">{{ $item['shipping_tax_amount'] }}</td>
                                <td class="p-3">{{ $item['shipping_sub_total'] }}</td>
                            </tr>
                            @php $tax_amount += ($item['shipping_tax_amount'] + $item['tax_amount']); 
                            $taxable_amount += ($item['shipping_taxable_amount'] + $item['taxable_amount']); @endphp
                        @endforeach
                        
                        <tr class="text-center">
                            <td class="py-3 fw-bold ewf"><span class="text-start">Total</span></td>
                            <td class="py-3"></td>
                            <td class="py-3"></td>
                            <td class="py-3"></td>
                            <th class="py-3 text-dark ewf">{{ $taxable_amount }}</th>
                            <th class="py-3 text-dark ewf">{{ $tax_amount }}</th>
                            <th class="py-3 text-dark ewf">{{ $order['total_amount'] }}</th>
                        </tr>
                    </tbody>
                </table>
                <div class="row class-headers py-4 px-3">
                    <span class=" text-start fw-bold" >AMOUNT IN WORDS</span>
                    <span class=" text-end   fw-bold" style="padding-left:60px;">FIVE HUNDRED AND SIXTEEN RUPES AND SIXTY SIX PAISE</span>
                </div>
            </div>
        </div>
        <div class="invoice-footer">
            <table class="b-none">
                <thead class="b-none">
                    <tr class="b-none">
                        <th class="col b-none "><h6 class="fw-bold ewhjd text-start ">SOLD BY SKYRAA ORGANICS</h6></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center b-none">
                        <td class="p-4 col-foo b-none text-start">Hari Complex, Opp.to: Prozon Mall, Saravanampatti, Coimbatore, Tamil Nadu - 641 035, India</td>
                        <td class=" pt-0 jkef b-none ">info@skyraan.com <h6 class="pt-2 "> +91 78453 35332</h6> </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </body>
</html>