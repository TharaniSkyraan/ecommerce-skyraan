<div>
        @if(session()->has('message'))
            <div class="alert-success">
                {{session('message')}}
            </div>                
        @endif
    <div class="row">
        <div class="col-12 shipment">
            <div class="card mt-3 mx-2">
                <h1 class="mb-3 font-bold"> Stock Information @if(in_array($stock_history->warehouse_to_id, $warehouse_ids) && $stock_history->status=='sent')  <a href="javascript:void(0)" id="stockReceived" class="btn btn-s float-end me-2">Stock receive </a> @endif</h1>
                <hr> <br>
                @php 
                    $histories = $stock_history->updatedproducts;
                @endphp
                <div>
                    <div class="product-det">
                        <div class="title">Reference Number :</div>
                        <div class="font-bold">#{{ $stock_history->reference_number }}</div>
                    </div>
                    <div class="product-det">
                        <div class="title">Warehouse :</div>
                        <div class="font-bold">{{ $stock_history->warehouse_to->name??'-' }}</div>
                    </div>
                    <div class="product-det">
                        <div class="title">Warehouse From:</div>
                        <div class="font-bold">{{ $stock_history->warehouse_from->name??'' }}</div>
                    </div>
                    <div class="product-det">
                        <div class="title">Stock Type :</div>
                        <div class="font-bold"> {{ ucwords($stock_history->stock_type) }}</div>
                    </div> 
                    <div class="product-det">
                        <div class="title">Sent Date :</div>
                        <div class="font-bold">{{ (!empty($stock_history->sent_date))?\Carbon\Carbon::parse($stock_history->sent_date)->format('d M y h:i A'):''}}</div>
                    </div> 
                    <div class="product-det">
                        <div class="title">Received Date :</div>
                        <div class="font-bold">{{ (!empty($stock_history->received_date))?\Carbon\Carbon::parse($stock_history->received_date)->format('d M y h:i A'):''}}</div>
                    </div>  
                    <div class="product-det">
                        <div class="title">No.of Product:</div>
                        <div class="font-bold">{{ count($stock_history->updatedproducts) }}</div>
                    </div>  
                    @if(in_array($stock_history->warehouse_to_id, $warehouse_ids)) 
                        <div class="product-det">
                            <div class="title">Status:</div>
                            <div class="font-bold">{{ ($stock_history->status=='sent')?'Pending':ucwords($stock_history->status); }}</div>
                        </div>  
                    @else
                        <div class="product-det">
                            <div class="title">Status:</div>
                            <div class="font-bold">{{ ucwords($stock_history->status) }}</div>
                        </div>  
                    @endif
                </div> 
            </div>
        </div>
        @if(count($histories)!=0)
            <h1 class="px-3 mt-3 mx-2 font-bold card">Product Stock History</h1>
            <div class="card mt-3 mx-2 mb-3">
                <div class="row tracking-history">
                    <div class="col-12">
                        <div class="row titles">
                            <div class="col-3" style="border-right: 2px solid #fff;">Warehouse</div>
                            <div class="col-3" style="border-right: 2px solid #fff;">Product</div>
                            <div class="col-3" style="border-right: 2px solid #fff;">Updated Qty</div>
                            <div class="col-3">Total Available Qty</div>
                        </div>
                        <hr>
                        @foreach($histories as $key => $history)
                        <div class="row {{ $key % 2 == 0 ? '' : 'odd' }}" >
                            <div class="col-3 description">{{ $history->warehouse->name??'' }}</div>
                            <div class="col-3 description">{{ $history->product_name }}</div>
                            <div class="col-3 description {{ ($history->available_quantity < $history->previous_available_quantity)?'danger':'success' }}">{{ ($history->available_quantity < $history->previous_available_quantity)?'-':'+' }} {{ $history->updated_quantity }}</div>
                            <div class="col-3 description">{{ $history->available_quantity }}</div>
                        </div>
                        @if($key!=(count($histories)-1)) <hr> @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
    $('#stockReceived').on('click', function (e) {
        if (confirm('Are you sure! stock is received?')) {
            Livewire.emit('stockReceived');
        }
    });
</script>

@endpush