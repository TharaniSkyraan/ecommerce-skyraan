<form autocomplete="off" >
    @if(session()->has('message'))
        <div class="alert-success my-2">
            {{session('message')}}
        </div> 
    @endif
    <div class="row">
        <div class="col-12">
            <div class=" mx-2">    
                <input type="hidden" wire:model="default_tax_id">
                <div class="mx-2">                                      
                    <!-- <div class="d-flex">
                        <input type="checkbox" wire:model="price_include_with_product" id="price_include_with_product" value="yes">
                        <label for="price_include_with_product"> &nbsp; Display product price including taxes </label>
                    </div>                                   
                    <div class="d-flex">
                        <input type="checkbox" wire:model="display_with_product" id="display_with_product" value="yes">
                        <label for="display_with_product"> &nbsp; Display tax information fields at the checkout page </label>
                    </div> -->
                    <div class="d-flex">
                        <input type="checkbox" wire:model="is_enabled_default_tax" id="is_enabled_default_tax" value="yes">
                        <label for="is_enabled_default_tax"> &nbsp; Enable Default Tax </label>
                    </div>
                </div>
                <div class="form-group mb-4 {{ ($is_enabled_default_tax)?'':'d-none'}}">
                    <label for="default_tax">Default Tax</label>
                    <select name="default_tax" id="default_tax" wire:model="default_tax">
                        <option value="">Select</option>
                        @foreach($taxes as $tax)
                        <option value="{{$tax->id}}">{{$tax->name}}</option>
                        @endforeach
                    </select>
                    @error('default_tax') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="mx-2">           
                    <div class="d-flex">
                        <input type="checkbox" wire:model="is_enabled_shipping_tax" id="is_enabled_shipping_tax" value="yes">
                        <label for="is_enabled_shipping_tax"> &nbsp; Enable Shipping Tax </label>
                    </div>
                </div>
                <div class="form-group mb-4 {{ ($is_enabled_shipping_tax)?'':'d-none'}}">
                    <label for="shipping_tax">Shipping Tax</label>
                    <select name="shipping_tax" id="shipping_tax" wire:model="shipping_tax">
                        <option value="">Select</option>
                        @foreach($taxes as $tax)
                        <option value="{{$tax->id}}">{{$tax->name}}</option>
                        @endforeach
                    </select>
                    @error('shipping_tax') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group py-5">
                    <div class="float-end">
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
