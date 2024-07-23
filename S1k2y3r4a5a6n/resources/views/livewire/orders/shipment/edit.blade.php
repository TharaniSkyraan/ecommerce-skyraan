<div>
@if(session()->has('message'))
    <div class="alert-success my-2">
        {{session('message')}}
    </div>                
@endif
    <div class="row">
        <div class="col-12 shipment">
            <div class="card mt-3 mx-2">
                @php 
                    $trackings = $shipment->shipping_history;
                    $shipmentstatus = $shipment->status;
                    $order = $shipment->order;
                    $order_status = $order->status;
                @endphp
                <h1 class="mb-3 font-bold"> Shipment Information  @if($order_status=='delivered'||$order_status=='replaced') <span class="font-bold float-end me-2 success"><a href="javascript:void(0)" class="primary" wire:click="invoiceGenerate"><p>View Invoice <i class="bx bx-download"></i></p></a> </span> @endif </h1>
                <hr> <br>
                <div>
                    <div class="product-det">
                        <div class="title">Order Id :</div>
                        <div class="font-bold"><a href="{{ route('admin.orders.edit', $shipment->order_id) }}" target="_blank">#{{ $shipment->order->code }}</a></div>
                    </div>
                    <div class="product-det">
                        <div class="title">Order Date :</div>
                        @php $created_at = $shipment->order->created_at->copy()->timezone('Asia/Kolkata'); @endphp

                        <div class="font-bold">{{ \Carbon\Carbon::parse($created_at)->format('d M y h:i A')}}</div>
                    </div> 
                    <div class="product-det">
                        <div class="title">Order Status :</div>
                        <div class="font-bold"> {{ ucwords(str_replace('_',' ',$shipment->order->status))}}</div>
                    </div> 
                    <div class="product-det">
                        <div class="title">Tracking Id :</div>
                        <div class="font-bold"><a href="javascript:void(0);">#{{ $shipment->tracking_id }}</a></div>
                    </div> 
                    <div class="product-det">
                        <div class="title">Last Update At :</div>                        
                        @php $updated_at = $shipment->updated_at->copy()->timezone('Asia/Kolkata'); @endphp
                        <div class="font-bold">{{ \Carbon\Carbon::parse($updated_at)->format('d M y h:i A')}}</div>
                    </div>   
                    <div class="product-det">
                        <div class="title">Shipping Status:</div>
                        <div class="font-bold">{{ ucwords(str_replace('_',' ',$shipmentstatus)) }}</div>
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
        @if(count($trackings)!=0)
            <h1 class="px-3 mt-3 mx-2 font-bold card">Shipping History</h1>
            <div class="card mt-3 mx-2 mb-3">
                <div class="row tracking-history">
                    <div class="col-12">
                        <div class="row titles">
                            <div class="col-6" style="border-right: 2px solid #fff;">Action</div>
                            <div class="col-6">Updated At</div>
                        </div>
                        <hr>
                        @foreach($trackings as $key => $tracking)
                        <div class="row {{ $key % 2 == 0 ? '' : 'odd' }}" >
                            <div class="col-6 description">{{ $tracking->shipping_status->name ?? ucwords(str_replace('_',' ',$tracking->action)) }}</div>
                            @php $created_at = $tracking->created_at->copy()->timezone('Asia/Kolkata'); @endphp
                            <div class="col-6 description text-center">{{ \Carbon\Carbon::parse($created_at)->format('d M Y h:i A') }}</div>
                        </div>
                        @if($key!=(count($trackings)-1)) <hr> @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
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
    document.addEventListener('livewire:load', function () { 
        Livewire.on('previewInvoice', pdfData => {
            var newWindow = window.open('');
            newWindow.document.write('<embed src="data:application/pdf;base64,' + pdfData + '" type="application/pdf" width="100%" height="100%"/>');
        });
    });
</script>
@endpush