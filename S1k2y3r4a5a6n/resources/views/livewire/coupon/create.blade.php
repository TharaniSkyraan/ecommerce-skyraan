<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <input type="hidden" name="coupon_id" id="coupon_id" wire:model="coupon_id"  placeholder="Coupon Id">
                <input type="hidden" name="product_ids" id="product_ids" wire:model="product_ids"  placeholder="Product Id">
                <input type="hidden" name="customer_ids" id="customer_ids" wire:model="customer_ids"  placeholder="Customer Id">
                <label for="coupon_code">Coupon Code</label>
                <div class="form-group couponcode">
                    <input type="text" name="coupon_code" id="coupon_code" placeholder="Coupon Code" wire:model="coupon_code">
                    <span wire:click="GenerateCouponCode">Generate Coupon Code</span>
                </div>
                @error('coupon_code') <span class="error"> {{$message}}</span> @endif
                <div class="d-flex">
                    <input type="checkbox" wire:model="display_at_checkout" id="display_at_checkout" value="yes">
                    <label for="display_at_checkout"> &nbsp; Display coupon code at the checkout page? <br> (Only applicable for - Order amount from,Customer,Once per customer)</label>
                </div> 
                <div class="d-flex mb-2">
                    <input type="checkbox" wire:model="unlimited_coupon" id="unlimited_coupon" value="yes">
                    <label for="unlimited_coupon"> &nbsp; Unlimited Coupon? </label>
                </div>     
                <div class="form-group" wire:ignore>
                    <label for="terms_and_condition">Terms and Condition</label>
                    <textarea name="terms_and_condition" id="terms_and_condition" placeholder="Terms and Condition" wire:model="terms_and_condition">{!! $terms_and_condition !!}</textarea>
                </div>
                @error('terms_and_condition') <span class="error"> {{$message}}</span> @endif
                <div class="form-group {{ ($this->unlimited_coupon!='yes')?'':'d-none'}}">
                    <label for="count">Enter Count</label>
                    <input type="text" name="count" id="count" placeholder="Coupon count" wire:model="count">
                    @error('count') <span class="error"> {{$message}}</span> @endif
                </div>  
                <div class="form-group mb-4">
                    <label for="name">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div>
                <h1 class="my-2 mt-4 px-1">Coupon type</h1>
                <hr><br>

                <div class="row">
                    <div class="col-4">        
                        <div class="form-group mb-4">
                            <label for="discount_type">Discount Type</label>
                            <select name="discount_type" id="discount_type" wire:model="discount_type">
                                <option value="flat">Flat</option>
                                <option value="percentage">Percentage %</option>
                                <option value="free_shipping">Free Shipping</option>
                            </select>
                            @error('discount_type') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                    <div class="col-4 pl-3 {{($discount_type=='free_shipping')?'d-none':''}}">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" name="discount" id="discount" placeholder="Discount" wire:model="discount">
                            @error('discount') <span class="error"> {{$message}}</span> @endif
                        </div>                          
                    </div>
                    <div class="col-4 pl-3 {{($discount_type!='flat')?'d-none':''}}">
                        <div class="form-group">
                            <label for="above_order">Order Should be Above</label>
                            <input type="text" name="above_order" id="above_order" placeholder="Order Above" wire:model="above_order">
                            @error('above_order') <span class="error"> {{$message}}</span> @endif
                        </div> 
                    </div>   
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group mb-4">
                                <label for="apply_for">Apply For</label>
                                <select name="apply_for" id="apply_for" wire:model="apply_for">
                                    <option value="all-orders">All orders</option>
                                    @if($discount_type!='flat')
                                    <option value="minimum-order">Order amount from</option>
                                    @endif
                                    <option value="collection">Product collection</option>
                                    <option value="category">Product category</option>
                                    <option value="product">Product</option>
                                    <option value="customer">Customer</option>
                                    <option value="once-per-customer">Once per customer</option>
                                </select>
                                @error('apply_for') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>
                        <div class="col-4 pl-3 {{($apply_for!='minimum-order')?'d-none':''}}">
                            <div class="form-group">
                                <label for="minimum_order">Minimum amount order</label>
                                <input type="text" name="minimum_order" id="minimum_order" placeholder="Discount" wire:model="minimum_order">
                                @error('minimum_order') <span class="error"> {{$message}}</span> @endif
                            </div> 
                        </div>   
                        <div class="col-4 pl-3 {{($apply_for!='collection')?'d-none':''}}">    
                            <div class="form-group mb-4">
                                <label for="collection">Collection</label>
                                <select name="collection" id="collection" wire:model="collection">
                                    <option value="">Select</option>
                                    @foreach($collections as $col)
                                        <option value="{{$col->id}}">{{$col->name}}</option>
                                    @endforeach
                                </select>
                                @error('collection') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>
                        <div class="col-4 pl-3 {{($apply_for!='category')?'d-none':''}}">    
                            <div class="form-group mb-4">
                                <label for="category">Category</label>
                                <select name="category" id="category" wire:model="category">
                                    <option value="">Select</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}" >{{$cat->name}}</option>
                                    @endforeach
                                </select>
                                @error('category') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>  
                        <div class="col-4 pl-3 {{(count($sub_categories)==0 || ($apply_for!='category'))?'d-none':''}}">    
                            <div class="form-group mb-4">
                                <label for="sub_category">Sub Category</label>
                                <select name="sub_category" id="sub_category" wire:model="sub_category">
                                    <option value="">Select</option>
                                    @foreach($sub_categories as $sub_cat)
                                        <option value="{{$sub_cat->id}}" >{{$sub_cat->name}}</option>
                                    @endforeach
                                </select>
                                @error('sub_category') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>                  
                    </div>
                    <div class="row">
                        <div class="col-12 {{($apply_for!='product')?'d-none':''}}">                            
                            <div class="form-group">
                                <label for="product">Product</label>
                                <input type="search" name="product" id="product" placeholder="Product" wire:model="product">
                                <div class="position-relative">
                                    @if($suggesstion)
                                        @if(count($products)!=0)
                                            <div class="autocomplete">
                                                <ul>
                                                    @foreach($products as $product)
                                                        @php
                                                            $images = json_decode($product->images, true);
                                                            $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('admin/images/placeholder.png');
                                                        @endphp
                                                        <li class="product_id" wire:click="addProduct({{$product->id}})"> <img src="{{ $image }}" alt="Collection-icon"> {{$product->name}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="autocomplete">
                                                <ul>
                                                No product found
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                @error('product_ids.*') <span class="error"> Select any single product</span> @endif
                                <label for="selected-products">Selected Product</label>
                                <div class="">
                                    @foreach($selected_products as $sproduct)
                                        @php
                                            $images = json_decode($sproduct->images, true);
                                            $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('admin/images/placeholder.png');
                                        @endphp
                                        <div class="selected-products">
                                            <div class="product"> <img src="{{ $image }}" alt="Collection-icon"> <span> {{$sproduct->name}} </span> </div>
                                            <div><i class="bx bx-x cursor-pointer" wire:click="removeProduct({{$sproduct->id}})"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 {{($apply_for!='customer')?'d-none':''}}">                            
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <input type="search" name="customer" id="customer" placeholder="Customer" wire:model="customer">
                                <div class="position-relative">
                                    @if($customer_suggesstion)
                                        @if(count($customers)!=0)
                                            <div class="autocomplete">
                                                <ul>
                                                    @foreach($customers as $customer)
                                                        <li class="customer_id" wire:click="addCustomer({{$customer->id}})"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> {{$customer->name}} - {{$customer->phone}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="autocomplete">
                                                <ul>
                                                No customer found
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                @error('customer_ids.*') <span class="error"> Select any single customer</span> @endif
                                <label for="selected-customers">Selected Customer</label>
                                <div class="">
                                    @foreach($selected_customers as $scustomer)
                                        <div class="selected-customers">
                                            <div class="customer"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> <span> {{$scustomer->name}} - {{$scustomer->phone}}</span> </div>
                                            <div><i class="bx bx-x cursor-pointer" wire:click="removeCustomer({{$scustomer->id}})"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h1 class="my-2 fw-bold">Time</h1>
                <hr>
                <div class="d-flex mt-4">
                    <input type="checkbox" wire:model="never_expired" id="never_expired" value="yes">
                    <label for="never_expired"> &nbsp; Never Expired? </label>
                </div>  
                <div class="form-group" wire:ignore>
                    <label for="start_date">Start Date</label>
                    <input type="date" wire:model="start_date" id="start_date" placeholder="Start Date">
                </div>
                @error('start_date') <span class="error"> {{$message}}</span> @endif
                <div class="{{ ($never_expired=='yes')?'d-none':'' }}">
                    <div class="form-group" wire:ignore>
                        <label for="end_date">End Date</label>
                        <input type="date" wire:model="end_date" id="end_date" placeholder="End Date">
                    </div>
                    @error('end_date') <span class="error"> {{$message}}</span> @endif
                </div> 
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('admin/date_flatpicker/flatpickr.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
         
        flatpickr("#start_date", {
            enableTime: true,
            altInput: true,
            dateFormat: "d-m-Y H:i",
            altFormat:"d-m-Y H:i",
            disableMobile: "true",
            defaultDate:'{{ $start_date??$today }}',
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('start_date', dateStr);
            }
        });   
        
        flatpickr("#end_date", {
            enableTime: true,
            altInput: true,
            dateFormat: "d-m-Y H:i",
            altFormat:"d-m-Y H:i",
            disableMobile: "true",
            defaultDate:'{{ $end_date }}',
            plugins: [
                ShortcutButtonsPlugin({
                    button: {
                        label: 'Clear',
                    },
                    onClick: (index, fp) => {
                        fp.clear();
                        fp.close();
                    }
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('end_date', dateStr);
            }
        });    
        
    });
$(document).ready(function() {

    var blurTimer; 
    $(document).on('focus', '#product', function () { 
        Livewire.emit('suggestion');
    });
    $(document).on('blur', '#product', function () { 
        blurTimer = setTimeout(function() {            
            Livewire.emit('unsetsuggestion');
        }, 500);
    });
    
    // Cancel blur timer if the input is focused again before it triggers
    $(document).on('focus', '#product', function () { 
        clearTimeout(blurTimer);
    });
});

$(document).ready(function() {
    var blurTimer1; 
    $(document).on('focus', '#customer', function () { 
        Livewire.emit('customer_suggestion');
    });
    $(document).on('blur', '#customer', function () { 
        blurTimer1 = setTimeout(function() {            
            Livewire.emit('unset_customer_suggestion');
        }, 500);
    });
    
    // Cancel blur timer if the input is focused again before it triggers
    $(document).on('focus', '#customer', function () { 
        clearTimeout(blurTimer1);
    });
});


    ClassicEditor
    .create(document.querySelector('#terms_and_condition'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('terms_and_condition', editor.getData());
        })
    });
    
</script>
@endpush

