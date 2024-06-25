
<form autocomplete="off">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">    
                <div class="row">
                    <h4 class="my-2">Order followed by :</h4>  
                    <hr>           
                    <div class="d-flex mt-3">
                        <input type="radio" wire:model="place_order" id="place_order1" value="separate">
                        <label for="place_order1"> &nbsp; Is separate order based on product? </label>
                    </div>
                    <div class="d-flex">
                        <input type="radio" wire:model="place_order" id="place_order2" value="common">
                        <label for="place_order2"> &nbsp; Is common order for all checkout product?</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="google_map_api_key">Google map api key </label>
                        <input type="text" name="google_map_api_key" id="google_map_api_key" placeholder="Google map api key" wire:model="google_map_api_key">
                        @error('google_map_api_key') <span class="error"> {{$message}}</span> @endif
                    </div>  
                    <h4 class="mt-4 mb-2">Payment Platform :</h4>                                   
                    <hr>             
                    <div class="form-group mt-2">
                        <label for="payment_platform">Payment platform</label>
                        <select class="form-control" id="payment_platform" wire:model="payment_platform">
                            <option value="">Select</option>
                            <option value="razorpay">Razorpay</option>
                        </select>
                        @error('payment_platform') <span class="error"> {{$message}}</span> @endif
                    </div>            
                    <div class="form-group" >
                        <label for="payment_app_key">App key </label>
                        <input type="text" name="payment_app_key" id="payment_app_key" placeholder="Payment app key" wire:model="payment_app_key">
                        @error('payment_app_key') <span class="error"> {{$message}}</span> @endif
                    </div>            
                    <div class="form-group" >
                        <label for="payment_secret_key">Secret key </label>
                        <input type="text" name="payment_secret_key" id="payment_secret_key" placeholder="Payment secret key" wire:model="payment_secret_key">
                        @error('payment_secret_key') <span class="error"> {{$message}}</span> @endif
                    </div>
                    <h4 class="mt-4 mb-2">Shipping Charges :</h4>                                   
                    <hr>                       
                    <div class="d-flex mt-2">
                        <input type="checkbox" wire:model="is_enabled_shipping_charges" id="is_enabled_shipping_charges" value="yes">
                        <label for="is_enabled_shipping_charges"> &nbsp; Is enable shipping charges? </label>
                    </div>   
                    @if($is_enabled_shipping_charges)   
                        <div class="form-group">
                            <label for="minimum_km">Minimum Km</label>
                            <input type="text" name="minimum_km" id="minimum_km" placeholder="Minimum Km" wire:model="minimum_km">
                            @error('minimum_km') <span class="error"> {{$message}}</span> @endif
                        </div>            
                        <div class="form-group" >
                            <label for="cost_minimum_km">Cost for minimum Km</label>
                            <input type="text" name="cost_minimum_km" id="cost_minimum_km" placeholder="Cost for minimum Km" wire:model="cost_minimum_km">
                            @error('cost_minimum_km') <span class="error"> {{$message}}</span> @endif
                        </div>            
                        <div class="form-group" >
                            <label for="cost_per_km">Cost Per Km</label>
                            <input type="text" name="cost_per_km" id="cost_per_km" placeholder="Cost Per Km" wire:model="cost_per_km">
                            @error('cost_per_km') <span class="error"> {{$message}}</span> @endif
                        </div>   
                        <div class="form-group mt-2">
                            <label for="minimum_kg">Minimum Kg</label>
                            <input type="text" name="minimum_kg" id="minimum_kg" placeholder="Minimum kg" wire:model="minimum_kg">
                            @error('minimum_kg') <span class="error"> {{$message}}</span> @endif
                        </div>            
                        <div class="form-group" >
                            <label for="cost_minimum_kg">Cost for minimum Kg</label>
                            <input type="text" name="cost_minimum_kg" id="cost_minimum_kg" placeholder="Cost for minimum Kg" wire:model="cost_minimum_kg">
                            @error('cost_minimum_kg') <span class="error"> {{$message}}</span> @endif
                        </div>            
                        <div class="form-group" >
                            <label for="cost_per_kg">Cost Per Kg</label>
                            <input type="text" name="cost_per_kg" id="cost_per_kg" placeholder="Cost Per Kg" wire:model="cost_per_kg">
                            @error('cost_per_kg') <span class="error"> {{$message}}</span> @endif
                        </div>   
                    @endif  
                </div>
            </div>
            <div class="form-group py-5">
                <div class="float-end">
                    <a href="{{ url('admin/settings') }}?tab=shipment" class="btn btn-c btn-lg" >Reset</a>
                    <button wire:click.prevent="storeshipment" class="btn btn-s btn-lg">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>   