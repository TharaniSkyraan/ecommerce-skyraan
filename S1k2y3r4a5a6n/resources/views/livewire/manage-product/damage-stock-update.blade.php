<div class="modal-body modal-scroll">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-11 me-2 my-1">
                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="form-group me-2">
                                <label for="reference_number" class="d-flex">Reference Number  <!-- <span wire:click="GenerateReference" class="primary generate">Generate</span> --></label>
                                <input type="text" name="reference_number" id="reference_number" placeholder="Reference Number" wire:model="reference_number" disabled>
                                @error('reference_number') <span class="error"> {{$message}}</span> @endif
                            </div>                        
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-4">
                                <label for="locationSets" id="warehouseLabel">Warehouse</label>
                                <section wire:ignore>
                                    <select name="locationSets" id="locationSets" wire:model="warehouse_id">
                                        <option value="">Select warehouse</option>
                                        @foreach($warehouses as $index => $warehouse)
                                            <option value="{{ $warehouse->id }}" @if($index == 0) selected @endif>
                                                {{ ucwords($warehouse->address) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </section>
                                @error('warehouse_id') <span class="error">{{ $message }}</span> @enderror
                            </div> 
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="query">Product</label>
                                <input type="search" name="query" id="query" placeholder="Product" wire:model="query" autocomplete="off">
                                @if($suggesstion)
                                    <div class="position-relative">
                                        @if(count($products) != 0)
                                            <div class="autocomplete" style="padding-bottom:10px; margin-bottom:10px">
                                                <div class="prdlist">
                                                    <ul>
                                                        @foreach($products as $productVariant)
                                                            <li class="product_id d-flex @if(in_array(($productVariant['id'].'-'.$warehouse_id), array_keys($selected_products))) bg-selected @endif" wire:click="ProductsArray({{ $productVariant->id }})">
                                                                <img src="{{ asset('storage/' . $productVariant->product_image) }}" alt="Product Image">
                                                                {{ $productVariant->product->name }} {{ !empty($productVariant->getSetAttribute()) ? '/' . $productVariant->getSetAttribute() : '' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <hr>
                                                <div class="text-end" style="margin: 10px 0px;">
                                                    <a class="btn btn-p btn-lg" id="doneproduct" href="javascript:void(0)">Ok</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="autocomplete">
                                                <div class="prdlist">
                                                    <ul>
                                                        <span>No product found</span>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group position-relative table key-buttons text-md-nowrap" style="display:unset">
                                @if(count($selected_products) > 0)
                                    <table>
                                        <thead style="background: darkseagreen;color: #ffffff;">
                                            <tr>
                                                <th class="p-0"><p>Product Variant</p></th>
                                                <th class="p-0"><p>Available Stock</p></th>
                                                <th class="p-0"><p>Quantity</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selected_products as $key => $sproduct)
                                                <tr>
                                                    <td><p>  {{ $sproduct['product_name'] }} </p> </td>
                                                    <td> <p class="text-center">  {{ $sproduct['available_stock'] }} </p> </td>
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
                                                            @if(count($selected_products)>1)
                                                                <div class="align-self-center">
                                                                    <i class="bx bx-x cursor-pointer"  wire:click="removeProduct('{{ $key }}')" style="color:red"></i>
                                                                </div>
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
            <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="damageProductUpdate">Submit</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

    document.addEventListener('livewire:load', function () {        
        Livewire.on('Success', message => {
            document.body.classList.remove('modal-open');
            $('.damage-stock').removeClass('show');      
            $('.success').html(`<div class="alert-success my-2">
                Stock updated successfully.
            </div>`);  
        });
    });
</script>
@endpush
