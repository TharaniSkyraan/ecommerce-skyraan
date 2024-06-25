<div id="loader-container">
    <!-- Loader -->
    <div id="loader" wire:loading></div>
    <label for="">Images</label>
    <div class="card mul-image-upload">  
        <div class="row">
            <div class="col-12" id="dataList" wire:loading.class="opacity-50">      
                <div class="row">
                    @forelse($variantImageList as $index => $row)
                        @php
                            if(!empty($variantImageList[$index]['image'])){
                                $image = asset('storage').'/'.$variantImageList[$index]['image'];
                            }elseif(!empty($variantImageList[$index]['temp_image'])){
                                $image = asset('storage').'/'.$variantImageList[$index]['temp_image'];
                            }else{
                                $image = '';
                            }
                        @endphp
                        <div class="col-2 {{ (empty($image))? 'd-none':''}}">
                            <div class="d-flex">                        
                                <input type="hidden" name="id" wire:model="variantImageList.{{ $index }}.image" ></td>
                                <input type="file" class="d-none" name="image[]" id="variantImageList.{{ $index }}.image" wire:model="variantImageList.{{ $index }}.image" wire:change="addvariantImageRow">
                                <div class="imageupload">
                                    <label for="variantImageList.{{ $index }}.image" class="cursor-pointer" id="variantImage{{ $index }}">
                                       <img src="{{ $image??asset('admin/images/placeholder.png') }}" alt="ATtr-icon" class="my-1 cat-image">
                                    </label>
                                    <i class="bx bx-x-circle cursor-pointer" wire:click="removevariantImageRow({{ $index }})"></i>
                                </div>
                            </div>
                        </div>  
                    @empty      
                    @endforelse
                </div>     
            </div>     
            @if(count($variantImageList)==0 || (count($variantImageList)==1 && (empty($variantImageList[0]['image']) && empty($variantImageList[0]['temp_image']))))
                <div class="col-12 text-center my-5">
                    <p>No Image upload</p>
                </div>
            @endif       
        </div>        
        <div class="">
            <a class="cursor-pointer" href="javascript:void(0);" id="addvariantImageRow" data-value="{{ count($variantImageList) }}">Add Image</a> 
        </div>
    </div>
</div>