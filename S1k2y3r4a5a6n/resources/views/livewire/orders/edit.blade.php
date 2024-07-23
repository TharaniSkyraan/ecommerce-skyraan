<div>
@if(session()->has('message'))
    <div class="alert-success my-2">
        {{session('message')}}
    </div>                
@endif
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <h1 class="mb-3 font-bold">Order Information #{{$order->code}} <span class="font-bold float-end btn btn-p me-2 text-white"> <i class="bx bx-cart"></i> {{ ucwords(str_replace('_',' ',$order_status)) }}</span> </h1>
                <hr>
                <div class="row order-items">
                    <div class="col-12">
                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2" style="padding-left: 20px;">Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $items = $order->orderItems; $tax = 0;@endphp
                                @foreach($items as $item)
                                    @php $price = $item->price;
                                        $tax += $item->tax_amount;
                                        $attributes = \App\Models\AttributeSet::find(explode(',',$item->attribute_set_ids));
                                    @endphp
                                    <tr>    
                                        <td class="d-flex p-2">
                                            <img src="{{$item->product_image}}" alt="{{$item->product_name}}">
                                            <p class="px-2">
                                                <span class="font-bold">{{$item->product_name}}</span>
                                                @foreach($attributes as $attribute)
                                                <br><span>{{ $attribute->attribute->name }} : {{ $attribute->name }}</span>
                                                @endforeach
                                            </p>
                                        </td>
                                        <td><p> @if($item->sale_price!=0) <span class="currency">₹</span> {{ $item->sale_price }}  <del> <span class="currency">₹</span> {{$item->price}}</del>  @php $price = $item->sale_price; @endphp @else <span class="currency">₹</span> {{$item->price}} @endif</p></td>
                                        <td><p>{{$item->quantity}}</p></td>
                                        <td> <span class="currency">₹</span> {{$item->taxable_amount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>               
                </div>
            </div>
            <div class="card mt-3 mx-2">
                <div class="row mx-2">
                    <div class="col-12 text-end">
                        <div class="price"><span class="font-bold">Cart Totals</span> <span class="font-bold"> Price</span> </div>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="col-12 text-end">
                        <div class="price"><p>Sub amount : </p> <span> <span class="currency">₹</span> {{$order->sub_total-$tax}}</span> </div>
                    </div>
                    <div class="col-12 text-end">
                        <div class="price"><p>Discount : </p><span><span class="currency">₹</span> {{$order->discount_amount}}</span></div>
                    </div>
                    <div class="col-12 text-end">
                        <div class="price"><p>Shipping charges : </p><span><span class="currency">₹</span> {{$order->shipping_amount}}</span></div>
                    </div>
                    <div class="col-12 text-end">
                        <div class="price"><p>Tax : </p><span><span class="currency">₹</span> {{$tax}}</span></div>
                    </div>
                    <div class="col-12 text-end">
                        <div class="total-price price font-bold"><p>Total amount : </p><span><span class="currency">₹</span> {{$order->total_amount}}</span></div>
                    </div>
                </div>
            </div>  
        </div>  
        <div class="col-4">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card mx-2">
                        <h2 class="mb-2 font-bold d-flex justify-content-between">Summary  @if($order_status=='delivered'||$order_status=='replaced') <span class="font-bold float-end me-2 primary cursor-pointer" wire:click="invoiceGenerate"><p class="p-0">View Invoice <i class="bx bx-download"></i></p></span> @endif </h2>
                        <div class="d-flex justify-content-between">
                            <div> <p> Order ID </p> </div> 
                            <div> <p class="font-bold"> #{{$order->code}} </p> </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> <p>Order Date </p> </div> 
                            <div> <p class="font-bold"> {{ \Carbon\Carbon::parse($order->order_histories['order_placed'])->format('d M Y')}} </p> </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> <p>Total </p> </div> 
                            <div> <p class="font-bold"> {{$order->total_amount}} </p> </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> <p>Order Status </p> </div> 
                            <div> <p class="font-bold"> {{ ucwords(str_replace('_',' ',$order_status))}} </p> </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> <p>Payment Method </p> </div> 
                            <div> <p class="font-bold"> {{ $order->payments?ucwords($order->payments->payment_chennal):'' }} </p> </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> <p>Payment Status </p> </div> 
                            <div> <p class="font-bold {{ $order->payments?(($order->payments->status=='completed')?'success':'danger'):'' }}"> {{ $order->payments?(($order->payments->status=='completed')?'Paid':'Unpaid'):'' }} </p> </div>
                        </div>
                        @if($order_status=='new_request'||$order_status=='order_confirmed')
                            <div class="card1">
                                <div>
                                @if($order_status=='new_request')
                                    <a href="javascript:void(0)" wire:click="confirmOrder"> <span class="btn btn-s">Confirm Order</span></a>
                                @endif
                                @if($order_status=='new_request'||$order_status=='order_confirmed')
                                    <a href="javascript:void(0)" class="cancelOrder"> <span class="btn btn-c">Cancel Order</span></a>
                                @endif
                                </div>
                            </div>
                        @endif
                    </div>                    
                </div>
                <div class="col-12 mb-3">
                    <div class="card mx-2">
                        <h2 class="mb-2 font-bold">Customer Detail</h2>
                        <p><i class="bx bx-user me-2"></i>{{ ucwords($order->user->name) }}</p>
                        <p><i class="bx bx-envelope me-2"></i>{{ $order->user->email }}</p>
                        <p><i class="bx bx-phone me-2"></i>{{ $order->user->phone }}</p>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card mx-2">
                        <h2 class="mt-2 mb-3 font-bold">Shipping Detail</h2>
                        @php
                            $shipment_address = $order->shipmentAddress;
                        @endphp                
                        <p><i class="bx bx-user me-2"></i>{{ ucwords($shipment_address->name) }}</p>
                        <p><i class="bx bx-phone me-2"></i>{{ $shipment_address->phone }},{{ $shipment_address->alternative_phone }}</p>
                        <p><i class="bx bx-map me-2"></i>{{ $shipment_address->address }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 shipment">
            @php 
                $trackings = $shipment->shipping_history;
                $shipmentstatus = $shipment->status;
            @endphp
            <div class="card mt-3 mx-2">
                <h1 class="mb-3 font-bold"> Shipment Information - <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="primary" target="_blank">#{{ $shipment->tracking_id }}</a> </h1>
                <hr>
                <div class="mt-3">
                    <div class="product-det">
                        <div class="title">Shipping Status:</div>
                        <div class="font-bold">{{ ucwords(str_replace('_',' ',$shipment->shipping_status->name??$shipmentstatus)) }}</div>
                    </div>  
                    <div class="product-det">
                        <div class="title">Last Update At :</div>
                        @php $updated_at = $shipment->updated_at->copy()->timezone('Asia/Kolkata') @endphp
                        <div class="font-bold">{{ \Carbon\Carbon::parse($updated_at)->format('d M y h:i A')}}</div>
                    </div>   
                </div> 
                @if($order_status=='order_confirmed' || $order_status=='shipped' || $order_status=='out_for_delivery')
                <div class="trackbutton">
                    <a href="javascript:void(0)" class="btn btn-lg modal-edit float-end cursor-pointer" wire:click="ShipmentStatusUpdate()"><i class="bx bxs-truck"></i> Update Shipping Status </a>
                </div>  
                @endif
                @if(isset($trackings))
                    @include('livewire.orders.separate-tracking')
                @endif
            </div>
        </div>
    </div>
    <div class="modal-window {{ $modalisOpen }}">
        @include('livewire.orders.shipping_status_update_model')
    </div>
</div>
@push('scripts')
<script>

    $(document).on('click', '.modal-edit', function () {   
        document.body.classList.add('modal-open');
    });
    $(document).on('click', '.modal-close, .modal-dismiss', function () {   
        Livewire.emit('IsModalOpen');
        document.body.classList.remove('modal-open');
    });
    $(document).on('click', '.modal-submit', function () {   
        document.body.classList.remove('modal-open');
    });    
    $(document).on('click', '.cancelOrder', function () {  

        var reason =prompt('Please provide a reason for cancellation:');
        if (reason !== null && reason !== "") {
            // Proceed with cancellation using the provided reason
            Livewire.emit('cancelOrder',reason);  
            alert('Order Cancelled');
            // Your logic for handling the cancellation
        } else {
            alert('Cancellation reason is required.');
        }
    });
    document.addEventListener('livewire:load', function () { 
        Livewire.on('previewInvoice', pdfData => {
            var newWindow = window.open('');
            newWindow.document.write('<embed src="data:application/pdf;base64,' + pdfData + '" type="application/pdf" width="100%" height="100%"/>');
        });
    });
</script>
@endpush