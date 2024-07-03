<div class="modal-body modal-scroll">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-11 me-2 my-1">

                    @if($action == "new")
                        <div class="form-group mb-4">
                            <label for="locationSets" id="warehouseLabel">Select Warehouse</label>
                            <section wire:ignore>
                                <select name="locationSets" id="locationSets" wire:model="warehouse_id">
                                    @foreach($warehouses as $index => $warehouse)
                                        <option value="{{ $warehouse->id }}" @if($index == 0) selected @endif>
                                            {{ ucwords($warehouse->address) }}
                                        </option>
                                    @endforeach
                                </select>
                            </section>
                            @error('warehouse_id') <span class="error">{{ $message }}</span> @enderror
                        </div> 
                        <div class="form-group position-relative">
                            <label for="query">Product</label>
                            <input type="search" name="query" id="query" placeholder="Product" wire:model="query" autocomplete="off">
                            @if($suggestion)
                                @if(count($products) != 0)
                                    <div class="autocomplete">
                                        <ul>
                                            @foreach($products as $productVariant)
                                                <li class="product_id d-flex @if($this->isSelected($productVariant->id)) bg-selected @endif" wire:click="ProductsArray({{ $productVariant->id }})">
                                                    <img src="{{ asset('storage/' . $productVariant->product_image) }}" alt="Product Image">
                                                    {{ $productVariant->product->name }} {{ !empty($productVariant->getSetAttribute()) ? '/' . $productVariant->getSetAttribute() : '' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="autocomplete">
                                        <ul>
                                            <span>No product found</span>
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    <div class="form-group position-relative">
                        @if(count($selected_products) > 0)
                            <div class="row py-4">
                            @if($action != "new")
                            <div class="col-4 text-center py-3 bg-secondary">Warehouse</div>
                            @endif
                                <div class="col-4 text-center py-3 bg-secondary">Products</div>
                                <div class="col-4 text-center py-3 bg-secondary">Available Stock</div>
                                <div class="col-4 text-center py-3 bg-secondary">Quantity</div>
                            </div>
                            <div class="container-fluid">
                                @foreach($selected_products as $sproduct)
                                    <div class="row justify-content-center">
                                        @if($action != "new")
                                        <div class="col-4 text-center py-2">{{ $sproduct['warehouse_name'] }}</div>
                                        @endif
                                        <div class="col-4 text-center py-2">{{ $sproduct['product_name'] }}</div>
                                        <div class="col-4 text-center py-2">{{ $sproduct['available_stock'] }}</div>
                                        <div class="col-4 text-center py-2">
                                            <div class="d-flex justify-content-between">
                                                <div class="qty-container d-flex align-items-center justify-content-center border p-1 rounded-1 text-dark" style="width:150px; margin: 0 auto;">
                                                    <div class="text-center px-2 qty-btn-minus" wire:click="decreaseQuantity({{ $sproduct['variant_id'] }})" style="align-content: center;"><span>-</span></div>
                                                    <div class="vr"></div>
                                                    <div class="col text-center">
                                                        <input type="number" min="1" class="input-qty h-sms" wire:model="selected_products.{{ $sproduct['variant_id'] }}.quantity" style="border-top:transparent;border-bottom:transparent; border-top-left-radius: 0px;border-bottom-left-radius: 0px; border-top-right-radius: 0px;border-bottom-right-radius: 0px;"/>
                                                    </div>
                                                    <div class="vr"></div>
                                                    <div class="text-center px-2 qty-btn-plus" wire:click="increaseQuantity({{ $sproduct['variant_id'] }})" style="align-content: center;"><span>+</span></div>
                                                </div>
                                                <div>
                                                    <i class="bx bx-x cursor-pointer" wire:click="removeProduct({{ $sproduct['variant_id'] }})" style="color:red"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="text-align-justify">
            <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
            <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="productUpdate">Submit</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#warehouseLabel').click(function() {
            $('#locationSets').show();
            $(this).hide();
        });
    });

    $(document).ready(function() {
        var blurTimer; 
        $(document).on('click', '#query', function () { 
            Livewire.emit('suggestion');
        });

        $(document).on('click', '#query', function () { 
            clearTimeout(blurTimer);
        });
    });

    document.addEventListener('livewire:load', function () {        
        Livewire.on('Success', message => {
            document.body.classList.remove('modal-open');
            $('.modal-window').removeClass('show');
        });
    });
</script>
@endpush
