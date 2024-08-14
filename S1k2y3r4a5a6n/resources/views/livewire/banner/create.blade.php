<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-9">
            <div class="card mx-2">
                <input type="hidden" wire:model="banner_id">
                <input type="hidden" wire:model="product_ids">
                <div class="d-flex">
                    <input type="checkbox" wire:model="promotion_banner" id="promotion_banner" value="yes">
                    <label for="promotion_banner"> &nbsp; Is Combo Offer (or) Promotion Banner? </label>
                </div>    
                <div class="d-flex">
                    <input type="checkbox" wire:model="special_product" id="special_product" value="yes">
                    <label for="special_product"> &nbsp; Special Products? </label>
                </div>    
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="name" id="name" placeholder="Banner Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="image" class="cursor-pointer">Image 
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Banner-icon" class="my-1 cat-image">
                        @elseif($temp_image)
                            <img src="{{ asset('storage') }}/{{$temp_image}}" alt="Banner-icon" class="my-1 cat-image">
                        @else
                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="Banner-icon" class="my-1 cat-image">
                        @endif
                    </label>
                    <input type="file" name="image" id="image" wire:model="image" accept=".png, .jpg, .jpeg" class="d-none">
                    @error('image') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group" wire:ignore>
                    <label>Description</label>
                    <textarea placeholder="Banner description" id="description" wire:model="description">{!! $description !!}</textarea>
                </div>
                @error('description') <span class="error"> {{$message}}</span> @endif
                <div class="form-group mb-4">
                    <label for="status">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="product_type">Product Type</label>
                    <div class="row">
                        <div class="col-3 d-flex">
                            <input type="radio" wire:model="product_type" id="product_type1" value="single">
                            <label for="product_type1"> &nbsp; Single</label>
                        </div>
                        <div class="col-3 d-flex">
                            <input type="radio" wire:model="product_type" id="product_type2" value="multiple">
                            <label for="product_type2"> &nbsp; Multiple</label>
                        </div>
                    </div>
                </div>
                <div class="form-group position-relative">
                    <label for="query">Product</label>
                    <input type="search" name="query" id="query" placeholder="Product" wire:model="query" autocomplete="off">
                    @if($suggesstion)
                        @if(count($products)!=0)
                            <div class="autocomplete">
                                <ul>
                                    @foreach($products as $product)
                                        @php
                                            $images = json_decode($product->images, true);
                                            $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('admin/images/placeholder.png');
                                        @endphp
                                        <li class="product_id" wire:click="addProduct({{$product->id}})"> <img src="{{ $image }}" alt="Banner-icon"> {{$product->name}}</li>
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
                <div class="form-group">
                    @error('product_ids') <span class="error"> Select any single product</span> @endif
                    <label for="selected-products">Selected Product</label>
                    <div class="">
                        @foreach($selected_products as $sproduct)
                            @php
                                $images = json_decode($sproduct->images, true);
                                $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('admin/images/placeholder.png');
                            @endphp
                            <div class="selected-products">
                                <div class="product"> <img src="{{ $image }}" alt="Banner-icon"> <span> {{$sproduct->name}} </span> </div>
                                <div><i class="bx bx-x cursor-pointer" wire:click="removeProduct({{$sproduct->id}})"></i></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
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
</script>
