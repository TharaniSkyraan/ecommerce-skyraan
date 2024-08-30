<div>
    <div class="card mx-2">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-4 me-2">
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
            <div class="col-6">
                <div class="form-group mb-4 me-2">
                    <label for="customer_phone">Customer Phone</label>
                    <div class="d-flex" wire:ignore>
                        <span class="dial_code">+91</span>
                        <input type="text" class="form-control phonenumber_dial_code" placeholder="Phone number" wire:model="customer_phone">
                    </div>
                    @error('customer_phone') <span class="error"> {{$message}}</span> @endif
                </div> 
            </div>
            <div class="col-6">
                <div class="form-group mb-4 me-2">
                    <label for="customer_email">Customer email</label>
                    <input type="text" name="customer_email" id="customer_email" placeholder="email" wire:model="customer_email" autocomplete="off">
                </div>
                @error('customer_email') <span class="error"> {{$message}}</span> @endif
            </div>
            <div class="col-6 {{ ($customer_data)?'':'d-none' }}">
                <div class="form-group mb-4 me-2">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" placeholder="Name" wire:model="customer_name" autocomplete="off">
                </div>
                @error('customer_name') <span class="error"> {{$message}}</span> @endif
            </div>
            <div class="col-12 {{ ($customer_data)?'':'d-none' }}">
                <div class="form-group mb-4 me-2">
                    <label for="customer_address">Address</label>
                    <textarea name="customer_address" id="customer_address" placeholder="address" wire:model="customer_address" autocomplete="off"></textarea>
                </div>
                @error('customer_address') <span class="error"> {{$message}}</span> @endif
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="form-group me-2">
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
                                                    {{ $productVariant->product_name }} 
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
            <div class="col-3">  
                <div class="mt-4">      
                    <div class="d-flex">
                        <input type="checkbox" wire:model="have_shipping_charge" id="have_shipping_charge" value="yes">
                        <label for="have_shipping_charge"> &nbsp;Shipping Charge </label>
                    </div>    
                </div>        
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group position-relative table key-buttons text-md-nowrap" style="display:unset">
                    
                @php $sub_amount = $shipping_amount = 0; @endphp
                    @if(count($selected_products) > 0)
                        <table>
                            <thead style="background: darkseagreen;color: #ffffff;">
                                <tr>
                                    <th class="p-0"><p>Product Variant</p></th>
                                    <th class="p-0"><p>Product Price</p></th>
                                    <th class="p-0"><p>Available Qty</p></th>
                                    <th class="p-0"><p>Qty</p></th>
                                    <th class="p-0"><p>Price</p></th>
                                    <th class="p-0"><p>Shipping <br> Charge</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selected_products as $key => $sproduct)
                                    <tr>
                                        <td><p>  {{ $sproduct['product_name'] }} </p> </td>
                                        <td>
                                            
                                        @if(isset($sproduct['discount']) && $sproduct['discount']!=0)
                                            <p class="text-center"><del class="p-0"><span class="currency">₹</span> {{$sproduct['price']}}</del><br><span class="currency">₹</span> {{ $sproduct['sale_price'] }} </p> 
                                            @php $price = $sproduct['sale_price']; @endphp</td>
                                        @else
                                            <p class="text-center"> <span class="currency">₹</span> {{$sproduct['price']}} </p>
                                            @php $price = $sproduct['sale_price']; @endphp
                                        @endif
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
                                            </div>
                                        </td>
                                        <td><span class="currency">₹</span> {{ $price * $sproduct['quantity']}}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                @if($have_shipping_charge) <input type="number" class="h-sms" wire:model="selected_products.{{ $key }}.shipping_charge" style="width: 50%;"/>  @else <span class="currency">₹</span> 0 @endif
                                            
                                                @if(count($selected_products)>1)
                                                    <div class="align-self-center">
                                                        <i class="bx bx-x cursor-pointer"  wire:click="removeProduct('{{ $key }}')" style="color:red"></i>
                                                    </div>
                                                @endif
                                            </div>    
                                        </td>
                                    </tr>
                                    @php $sub_amount += ($price * $sproduct['quantity']);
                                    $shipping_amount += !empty($sproduct['shipping_charge'])?$sproduct['shipping_charge']:0; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @error('selected_products') <span class="error"> {{ $message }} </span> @endif
            </div>
        </div>        <br><br>
        <div class="row mx-2">
            <div class="col-12 text-end">
                <div class="price"><p>Sub amount : </p> <span> <span class="currency">₹</span> {{$sub_amount}}</span> </div>
            </div>
            <div class="col-12 text-end">
                <div class="price"><p>Shipping charges : </p><span><span class="currency">₹</span> {{$shipping_amount}}</span></div>
            </div>
            <div class="col-12 text-end">
                @php $total_amount = $sub_amount + $shipping_amount; @endphp
                <div class="total-price price font-bold"><p>Total amount : </p><span><span class="currency">₹</span> {{$total_amount}}</span></div>
            </div>
            <div class="col-12">
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script> 
    $(document).ready(function() {
        $(document).on('click', '#query', function () { 
            Livewire.emit('suggestion');
        });
    });
    $(document).on('click', '#doneproduct', function () { 
        Livewire.emit('unsetsuggestion');
    });
    document.addEventListener('livewire:load', function () { 
        Livewire.on('previewInvoice', pdfData => {
            var newWindow = window.open('');
            newWindow.document.write('<embed src="data:application/pdf;base64,' + pdfData + '" type="application/pdf" width="100%" height="100%"/>');
        });
    });
</script>
@endpush