<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-9">
            <div class="card mx-2">
                <input type="hidden" wire:model="product_ids">
                <input type="hidden" wire:model="special_id">
                <div class="form-group position-relative">
                    @if(in_array('edit',$privileges) || in_array('all',$privileges))
                    <label for="query">Product</label>
                    <input type="search" name="query" id="query" placeholder="Product" wire:model="query" autocomplete="off">
                    @endif
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
                    <label for="selected-products">Selected Product</label>
                    <div class="">
                        @foreach($selected_products as $sproduct)
                            @php
                                $images = json_decode($sproduct->images, true);
                                $image = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('admin/images/placeholder.png');
                            @endphp
                            <div class="selected-products">
                                <div class="product"> <img src="{{ $image }}" alt="Banner-icon"> <span> {{$sproduct->name}} </span> </div>
                                @if(in_array('delete',$privileges) || in_array('all',$privileges)) <div><i class="bx bx-x cursor-pointer" wire:click="removeProduct({{$sproduct->id}})"></i></div> @endif
                            </div>
                        @endforeach
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
