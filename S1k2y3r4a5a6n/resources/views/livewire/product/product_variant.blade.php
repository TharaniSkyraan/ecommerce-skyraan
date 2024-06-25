<div class="modal-toggle"> 
    <div class="modal-header">
        <h1> Select Variant </h1>
        <a href="javascript:void(0)" title="Close" class="modal-close">Close</a>
    </div>
    <div class="modal-body modal-scroll">
        <div class="row">
            <input type="hidden" wire:model="iseditproductVariant">
            @foreach($selectedattrList as $index => $attr)
                <div class="col-3 me-2 my-1">
                    <div class="form-group">
                        <label for="productAttributeList.{{ $index }}.{{$attr->slug}}">{{ ucwords($attr->name) }}</label>

                        <select name="productAttributeList.{{ $index }}.{{$attr->slug}}" id="productAttributeList.{{ $index }}.{{$attr->slug}}" wire:model="productAttributeList.{{ $index }}.{{$attr->slug}}">
                            <option value="">Select</option>
                            @foreach($attr->attributeSets as $attrset)
                            <option value="{{$attrset->slug}}">{{$attrset->name}}</option>
                            @endforeach
                        </select>
                        @error("productAttributeList.$index.{$attr->slug}")
                            <span class="error">Field is required</span>
                        @enderror
                    </div>
                </div>
            @endforeach 
            <div class="col-12">
                <div class="row">                            
                    <div class="col-3 me-2 my-1">
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" placeholder="Sku" wire:model="sku">
                            @error('sku') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="col-3 me-2 my-1">
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" name="price" id="price" placeholder="Price" wire:model="price">
                            @error('price') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="col-3 me-2 my-1">
                        <div class="form-group">
                            <label for="sale_price">Sale Price</label>
                            <input type="text" name="sale_price" id="sale_price" placeholder="Sale Price" wire:model="sale_price">
                            @error('sale_price') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="col-11 me-2 my-1">
                        <div class="d-flex">
                            <input type="checkbox" wire:model="discount_duration" id="discount_duration" value="yes">
                            <label for="discount_duration"> &nbsp; Choose Discount Duration? </label>
                        </div>
                    </div>                
                    <div class="col-12 {{($discount_duration=='yes')?'':'d-none'}}">
                        @if($discount_duration=='yes')
                            @livewire('product.discount-date', ['start' => $discount_start_date??'','end' => $discount_end_date??'']) 
                        @endif
                        @error('discount_start_date') <span class="error"> {{$message}}</span> @endif
                        @error('discount_end_date') <span class="error"> {{$message}}</span> @endif
                    </div>                    
                    <div class="col-3 me-2 my-1 d-none">
                        <div class="form-group">
                            <label for="cost_per_item">Cost per item</label>
                            <input type="text" name="cost_per_item" id="cost_per_item" placeholder="Cost Per item" wire:model="cost_per_item">
                            @error('cost_per_item') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="col-11 me-2 my-1">
                        <div class="card">
                            <div class="form-group">
                                <label for="available_quantity">Available Quantity</label>
                                <input type="text" name="available_quantity" id="available_quantity" placeholder="Available Quantity" wire:model="available_quantity">
                                @error('available_quantity') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-11">
                        <div class="card">
                            <label for="" class="fw-bold">Stock Status</label>
                            <div class="form-group d-inline-flex mx-2">
                                <input type="radio" name="stock_status" id="stock_status1" wire:model="stock_status" value="in_stock">
                                <label for="stock_status1"> &nbsp;In Stock</label>
                            </div>
                            <div class="form-group d-inline-flex mx-2">
                                <input type="radio" name="stock_status" id="stock_status2" wire:model="stock_status" value="out_of_stock">
                                <label for="stock_status2"> &nbsp;Out of stock</label>
                            </div>
                        </div>
                        @error('stock_status') <span class="error"> {{$message}}</span> @endif
                    </div>
                    <div class="col-11 my-3">
                        <div class="card">
                            <label for="" class="fw-bold">Shipping</label>
                            <div class="row">
                                <div class="col-4 me-2 my-1">                                            
                                    <div class="form-group">
                                        <label for="shipping_weight"> Weight (g)</label>
                                        <input type="text" name="shipping_weight" id="shipping_weight" wire:model="shipping_weight">
                                    </div>
                                    @error('shipping_weight') <span class="error"> {{$message}}</span> @endif
                                </div>
                                <div class="col-4 me-2 my-1">                                            
                                    <div class="form-group">
                                        <label for="shipping_length"> Length (cm)</label>
                                        <input type="text" name="shipping_length" id="shipping_length" wire:model="shipping_length">
                                    </div>
                                    @error('shipping_length') <span class="error"> {{$message}}</span> @endif
                                </div>
                                <div class="col-4 me-2 my-1">                                            
                                    <div class="form-group">
                                        <label for="shipping_wide"> Wide (cm)</label>
                                        <input type="text" name="shipping_wide" id="shipping_wide" wire:model="shipping_wide">
                                    </div>
                                    @error('shipping_wide') <span class="error"> {{$message}}</span> @endif
                                </div>
                                <div class="col-4 me-2 my-1">                                            
                                    <div class="form-group">
                                        <label for="shipping_height"> Height (cm)</label>
                                        <input type="text" name="shipping_height" id="shipping_height" wire:model="shipping_height">
                                    </div>
                                    @error('shipping_height') <span class="error"> {{$message}}</span> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-11 my-3">    
                        <div class="card">
                            <div class="form-group">
                                @livewire('product.variant-images')    
                            </div>
                        </div>     
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <div class="text-align-justify">
            <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
            <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="addVariant">Submit</a>
        </div>
    </div>
</div>