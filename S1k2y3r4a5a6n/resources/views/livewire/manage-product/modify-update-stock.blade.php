<div class="modal-body modal-scroll">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-11 me-2 my-1">
                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="form-group mb-4 me-2">
                                <label for="fromlocationSets" id="fromwarehouseLabel">Reference Number</label>
                                <select name="fromlocationSets" id="fromlocationSets" wire:model="reference_number">
                                    <option value="">Select Reference Number</option>
                                    @foreach($stocks as $index => $stock)

                                        <option value="{{ $stock->reference_number }}">
                                            {{ ucwords($stock->reference_number) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reference_number') <span class="error"> {{$message}}</span> @endif
                            </div> 
                        </div>
                        <div class="col-12">
                            <div class="form-group position-relative table key-buttons text-md-nowrap" style="display:unset">
                                @if(count($selected_products) > 0)
                                    <table>
                                        <thead style="background: darkseagreen;color: #ffffff;">
                                            <tr>
                                                <th class="p-0"><p>Warehouse</p></th>
                                                <th class="p-0"><p>Product Variant</p></th>
                                                <th class="p-0"><p>Available Stock</p></th>
                                                <th class="p-0"><p>Uploaded Stock</p></th>
                                                <th class="p-0"><p>Last Modified Stock</p></th>
                                                <th class="p-0"><p>Quantity</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selected_products as $key => $sproduct)
                                                <tr>
                                                    <td> <p> {{ $sproduct['warehouse_name'] }} </p> </td>
                                                    <td><p>  {{ $sproduct['product_name'] }} </p> </td>
                                                    <td> <p class="text-center">  {{ $sproduct['available_stock'] }} </p> </td>
                                                    <td> <p class="text-center">  {{ $sproduct['upload_stock'] }} </p> </td>
                                                    <td> <p class="text-center">  {{ $sproduct['last_modified_quantity'] }} </p> </td>
                                                    <td> 
                                                        @if($sproduct['available_stock'] >= 1)
                                                        <div class="d-flex justify-content-around">
                                                            <div class="qty-container d-flex align-items-center justify-content-center border p-1 rounded-1 text-dark" style="width:150px; margin: 0 auto;">
                                                                <div class="text-center px-2 qty-btn-minus" wire:click="decreaseQuantity('{{ $key }}')" style="align-content: center;"><span>-</span></div>
                                                                <div class="vr"></div>
                                                                <div class="col text-center">
                                                                    <input type="number" min="1" class="input-qty h-sms" wire:model="selected_products.{{ $key }}.quantity" wire:keyup="updateQuantity('{{ $key }}')" style="border-top:transparent;border-bottom:transparent; border-top-left-radius: 0px;border-bottom-left-radius: 0px; border-top-right-radius: 0px;border-bottom-right-radius: 0px;"/>
                                                                </div>
                                                                <div class="vr"></div>
                                                                <div class="text-center px-2 qty-btn-plus" wire:click="increaseQuantity('{{ $key }}')" style="align-content: center;"><span>+</span></div>
                                                            </div>
                                                            @else
                                                            <div class="d-flex justify-content-evenly">                                                            
                                                                <span class="error">Out of stock</span>                                                            
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            @error('selected_products') <span class="error"> {{ $message }} </span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="text-align-justify">
            <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
            <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="productModify">Submit</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#fromwarehouseLabel').click(function() {
            $('#fromlocationSets').show();
            $(this).hide();
        });
    });

    document.addEventListener('livewire:load', function () {        
        Livewire.on('Success', message => {
            document.body.classList.remove('modal-open');
            $('.modify-updated-stock').removeClass('show');      
            $('.success').html(`<div class="alert-success my-2">
                Stock updated successfully.
            </div>`);  
        });
    });
</script>
@endpush
