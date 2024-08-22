<div id="loader-container">
    <!-- Loader -->
    <div id="loader" wire:loading></div>
    <label for="">Images</label>
    <div class="card mul-image-upload">  
        <div class="row">
            <div class="col-12" id="dataList" wire:loading.class="opacity-50">      
                <div class="row">
                    @forelse($imageList as $index => $row)
                        @php
                            if(!empty($imageList[$index]['image'])){
                                $image = asset('storage').'/'.$imageList[$index]['image'];
                            }elseif(!empty($imageList[$index]['temp_image'])){
                                $image = asset('storage').'/'.$imageList[$index]['temp_image'];
                            }else{
                                $image = '';
                            }
                        @endphp
        
                        <div class="col-3 {{ (empty($image))? 'd-none':''}}">
                            <div class="d-flex">                        
                                <input type="hidden" name="id" wire:model="imageList.{{ $index }}.image" ></td>
                                <input type="file" class="d-none" name="image[]" id="imageList.{{ $index }}.image" wire:model="imageList.{{ $index }}.image" wire:change="addRow">
                                <div class="imageupload">
                                    <label for="imageList.{{ $index }}.image" class="cursor-pointer" id="{{ $index }}">
                                       <img src="{{ $image??asset('admin/images/placeholder.png') }}" alt="ATtr-icon" class="my-1 cat-image">
                                    </label>
                                    <div class="{{ (empty($page))? 'd-none':''}}">
                                        <i class="bx bx-x-circle cursor-pointer" wire:click="removeRow({{ $index }})"></i>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    @empty      
                    @endforelse
                </div>     
            </div>     
            @if(count($imageList)==0 || (count($imageList)==1 && (empty($imageList[0]['image']) && empty($imageList[0]['temp_image']))))
                <div class="col-12 text-center my-5">
                    <p>No Image upload</p>
                </div>
            @endif       
        </div>        
        <div class="{{ (empty($page))? 'd-none':''}}">
            <a class="cursor-pointer" href="javascript:void(0);" id="addRow" data-value="{{ count($imageList) }}">Add Product</a> 
        </div>
    </div>
</div>