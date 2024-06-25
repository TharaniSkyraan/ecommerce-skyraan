<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-9">
            <div class="card mx-2">
                <input type="hidden" name="auth_id" id="auth_id" wire:model="auth_id"  placeholder="Auth Id">
                <input type="hidden" name="product_id" id="product_id" wire:model="product_id"  placeholder="Product Id">
                <input type="hidden" name="product_variant_ids" id="product_variant_ids" wire:model="product_variant_ids"  placeholder="Product Id">
                <input type="hidden" wire:model="cross_selling_product_ids">
                <input type="hidden" wire:model="product_ids">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Product Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group" wire:ignore>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" wire:model="description" placeholder="Product Description">{!! $description !!}</textarea>
                </div>  
                <div>
                    @error('description') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group" wire:ignore>
                    <label for="content">Content</label>
                    <textarea name="content" id="content" wire:model="content" placeholder="Product Content">{!! $content !!}</textarea>
                </div> 
                <div class="form-group">
                    @livewire('product.images')    
                </div>
                @error('imageList') <span class="error"> Image is required</span> @endif

            </div>

            <div class="card m-2">

                <div class="d-flex justify-content-between my-2">
                    <div>
                        <h4>Product has variations</h4>
                    </div>
                    <div>
                        <a href="javascript:void(0)" id="addEditAttrmodal" class="btn-p btn">Edit Attribute</a>
                        <a href="javascript:void(0)" id="addEditVariantmodal" class="btn-p btn">Add New variation</a>
                    </div>
                </div>
                <hr>
                <div class="my-2">
                    <div class="productVariant">
                        <table class="table dataTable form-group">
                            <thead>
                                <tr class="text-center">
                                    @foreach($product_variant_menus as $menu)
                                        <td>{{ $menu }}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productVariantList as $index => $variant)
                                    <tr>
                                    @foreach($product_variant_menus as $key => $menu)
                                        @if($key=='id')
                                        <td>{{ !empty($variant[$key])?$variant[$key]:($index+1); }}</td>
                                        @elseif($key=='image')
                                            @php
                                                if(!empty($variant['image'])){
                                                    $image = asset('storage').'/'.$variant['image'];
                                                }else{
                                                    $image = asset('admin/images/placeholder.png');
                                                }
                                            @endphp
                                            <td><img src="{{ $image }}" alt="ATtr-icon" class="my-1 cat-image"> </td>
                                        @elseif($key=='available_quantity')
                                        <td>{{ !empty($variant[$key])?$variant[$key]:'∞'; }}</td>
                                        @elseif($key=='price')
                                        <td>{{ $variant[$key] }}</td>
                                        @elseif($key=='is_default')
                                        <td><input type="radio" name="is_default" wire:model="is_default" value="{{ $index }}"></td>
                                        @elseif($key=='action')
                                        <td> 
                                            <a class="btn btn-d" href="javascript:void(0)" wire:click="deleteProductVariant({{$index}})"><i class="bx bx-trash"></i></a> 
                                            <a class="btn btn-s modal-edit" href="javascript:void(0)" wire:click="editProductVariant({{$index}})"><i class="bx bx-pencil"></i></a>
                                        </td>
                                        @else
                                        <td>{{ $variant[$key][0]??'--' }}</td>
                                        @endif
                                    @endforeach
                                    </tr>
                                @endforeach
                            </tbody> 
                        </table>
                    </div>
                    @error('productVariantList') <span class="error"> Add product variant atleast one </span> @endif    
                    @error('is_default') <span class="error"> {{$message}} </span> @endif    
                </div>
            </div>
            <div class="card m-2">
                <div class="form-group">
                    <label for="query">Related products</label>
                    <input type="search" name="query" id="query" placeholder="Product" wire:model="query" autocomplete="off">
                    <div class="position-relative">
                        @if($suggesstion)
                            @if(count($products)!=0)
                                <div class="autocomplete">
                                    <ul>
                                        @foreach($products as $product)
                                            <li class="product_id" wire:click="addProduct({{$product->id}})"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> {{$product->name}}</li>
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
                    @error('product_ids') <span class="error"> Select any single product</span> @endif
                    <label for="selected-products">Selected Product</label>
                    <div class="">
                        @foreach($selected_products as $sproduct)
                            <div class="selected-products">
                                <div class="product"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> <span> {{$sproduct->name}} </span> </div>
                                <div><i class="bx bx-x cursor-pointer" wire:click="removeProduct({{$sproduct->id}})"></i></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card m-2">
                <div class="form-group">
                    <label for="crosssellingquery">Cross-selling products</label>
                    <input type="search" name="crosssellingquery" id="crosssellingquery" placeholder="Product" wire:model="crosssellingquery">
                    <div class="position-relative">
                        @if($cross_selling_suggesstion)
                            @if(count($products)!=0)
                                <div class="autocomplete">
                                    <ul>
                                        @foreach($products as $product)
                                            <li class="product_id" wire:click="addcrossSellingProduct({{$product->id}})"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> {{$product->name}}</li>
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
                    @error('cross_selling_product_ids') <span class="error"> Select any single product</span> @endif
                    <label for="selected-products">Selected Product</label>
                    <div class="">
                        @foreach($selected_cross_selling_products as $sproduct)
                            <div class="selected-products">
                                <div class="product"> <img src="{{ asset('admin/images/placeholder.png') }}" alt="Collection-icon"> <span> {{$sproduct->name}} </span> </div>
                                <div><i class="bx bx-x cursor-pointer" wire:click="removecrossSellingProduct({{$sproduct->id}})"></i></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card m-2">
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.attribute.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            
            <div class="card">
                <h3>Status</h3><br>
                <div class="form-group" wire:ignore>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active" @if($status=='active') selected @endif>Active</option>
                        <option value="inactive" @if($status=='inactive') selected @endif>Inactive</option>
                    </select>
                </div>
                @error('status') <span class="error"> {{$message}}</span> @endif
            </div>

            <div class="card my-2">
                <h3>Category</h3><br>
                <div class="py-2">                    
                    @foreach($categories as $index => $category)
                        @if(count($category->sub_categories)==0)                      
                            <div class="d-flex">
                                <input type="checkbox" wire:model="category_ids.{{ $category->id }}" id="checkbox{{ $category->id }}">
                                <label for="checkbox{{ $category->id }}"> &nbsp; {{ ucwords($category->name) }}</label>
                            </div>
                        @else
                            <label for="" class="fw-bold">{{ ucwords($category->name) }}</label>
                            @foreach($category->sub_categories as $index => $subcategory)
                                <div class="d-flex mx-4">                                
                                    <input type="checkbox" wire:model="category_ids.{{ $subcategory->id }}" id="checkbox{{ $subcategory->id }}">
                                    <label for="checkbox{{ $subcategory->id }}"> &nbsp; {{ ucwords($subcategory->name) }}</label>
                                </div>                            
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card my-2">
                <h3>Brand</h3><br>
                <div class="py-2">  
                    <div class="form-group" wire:ignore>
                        <select name="brand" id="brand" wire:model="brand">
                            <option value="">Select</option>
                            @foreach($brands as $bran)
                            <option value="{{$bran->id}}" @if($bran->id==$brand) selected @endif>{{$bran->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>  
            </div>
            
            <div class="card my-2">
                <h3>Product Collection</h3><br>
                <div class="py-2">                    
                    @foreach($collections as $index => $collection)
                        <div class="d-flex">
                            <input type="checkbox" wire:model="collection_ids.{{ $collection->id }}" id="collection{{ $collection->id }}">
                            <label for="collection{{ $collection->id }}"> &nbsp; {{ ucwords($collection->name) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card my-2">
                <h3>Label</h3><br>
                <div class="py-2">
                    <div class="form-group" wire:ignore>
                        <select name="label_id" id="label_id" wire:model="label_id">
                            <option value="">Select</option>
                            @foreach($labels as $label)
                            <option value="{{$label->id}}" @if($label->id==$label_id) selected @endif>{{$label->name}}</option>
                            @endforeach
                        </select>
                    </div>       
                </div>
            </div>

            <div class="card my-2">
                <h3>Tax</h3><br>
                <div class="py-2">           
                    <div class="form-group" wire:ignore>
                        <select name="tax_ids" id="tax_ids" wire:model="tax_ids">
                            <option value="">Select</option>
                            @foreach($taxes as $tax)
                            <option value="{{$tax->id}}" @if($tax->id==$tax_ids) selected @endif>{{$tax->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-window {{ $attrModalisOpen }}">
        @include('livewire.product.attribute_modal')
    </div>
    <div class="modal-window {{ $variantModalisOpen }} modal-window-lg">
        @include('livewire.product.product_variant')
    </div>
</form>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 
<script src="{{ asset('admin/date_flatpicker/flatpickr.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>

<script>
    window.addEventListener("DOMContentLoaded", function () {
        Livewire.emit("initialize");
    });
    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('description', editor.getData());
        })
    });
    ClassicEditor
    .create(document.querySelector('#content'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('content', editor.getData());
        })
    });   
    
    $('#status').select2();
    $('#status').on('change', function (e) {
        @this.set('status', e.target.value);
    });

    $('#brand').select2();
    $('#brand').on('change', function (e) {
        @this.set('brand', e.target.value);
    });

    $('#tax_ids').select2();
    $('#tax_ids').on('change', function (e) {
        @this.set('tax_ids', e.target.value);
    });

    $('#label_id').select2();
    $('#label_id').on('change', function (e) {
        @this.set('label_id', e.target.value);
    });

    $(document).on('click', '#addEditAttrmodal', function () {   
        Livewire.emit('attropenModal');
        document.body.classList.add('modal-open');
    });
    $(document).on('click', '#addEditVariantmodal', function () {   
        Livewire.emit('variantopenModal');
        document.body.classList.add('modal-open');
    });
    $(document).on('click', '.modal-close, .modal-dismiss', function () {   
        Livewire.emit('closeModal');
        document.body.classList.remove('modal-open');
    });
    $(document).on('click', '.modal-submit', function () {   
        document.body.classList.remove('modal-open');
    });
    $(document).on('click', '.modal-edit', function () {   
        document.body.classList.add('modal-open');
    });
    
    $(document).on('click', '#addRow', function () {   
        var id = $(this).attr('data-value')-1;
        $('#'+id).trigger('click');
    });
    
    $(document).on('click', '#addvariantImageRow', function () {   
        var id = $(this).attr('data-value')-1;
        $('#variantImage'+id).trigger('click');
    });

    // Related Products

    $(document).ready(function() {
        var blurTimer; 
        $(document).on('focus', '#query', function () { 
            Livewire.emit('suggestion');
        });
        $(document).on('blur', '#query', function () { 
            blurTimer = setTimeout(function() {            
                Livewire.emit('unsetsuggestion');
            }, 500);
        });
        
        // Cancel blur timer if the input is focused again before it triggers
        $(document).on('focus', '#query', function () { 
            clearTimeout(blurTimer);
        });
    });
    // Cross Selling Product

    
    $(document).ready(function() {
        var blurTimer1; 
        $(document).on('focus', '#crosssellingquery', function () { 
            Livewire.emit('cross_selling_suggestion');
        });
        $(document).on('blur', '#crosssellingquery', function () { 
            blurTimer1 = setTimeout(function() {            
                Livewire.emit('unset_cross_selling_suggestion');
            }, 500);
        });
        
        // Cancel blur timer if the input is focused again before it triggers
        $(document).on('focus', '#crosssellingquery', function () { 
            clearTimeout(blurTimer1);
        });
    });

    </script>
    @endpush
