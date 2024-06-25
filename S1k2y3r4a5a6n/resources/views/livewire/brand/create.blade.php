<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <input type="hidden" name="brand_id" id="brand_id" wire:model="brand_id"  placeholder="Brand Id">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Brand Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="name">Image 
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Brand-icon" class="my-1 cat-image">
                        @elseif($temp_image)
                        <img src="{{ asset('storage') }}/{{$temp_image}}" alt="Brand-icon" class="my-1 cat-image">
                        @else
                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="Brand-icon" class="my-1 cat-image">
                        @endif
                    </label>
                    <input type="file" name="image" id="image" wire:model="image" accept=".png, .jpg, .jpeg">
                    @error('image') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group" wire:ignore>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Brand description" wire:model="description">{!! $description !!}</textarea>
                </div>
                @error('description') <span class="error"> {{$message}}</span> @endif
                <div class="form-group">
                    <label for="website_link">Website Link</label>
                    <input type="text" name="website_link" id="website_link" placeholder="Brand website_link" wire:model="website_link">
                    @error('website_link') <span class="error"> {{$message}}</span> @endif
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
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.brand.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h3>Category</h3><br>
                <div class="py-3">                    
                    @foreach($categories as $index => $category)
                        @if(count($category->sub_categories)==0)                      
                            <div class="d-flex">
                                <input type="checkbox" wire:model="category_ids.{{ $category->id }}" id="checkbox{{ $category->id }}">
                                <label for="checkbox{{ $category->id }}"> &nbsp; {{ ucwords($category->name) }}</label>
                            </div>
                        @else
                            <label for="" class="fw-bold">{{ ucwords($category->name)}}</label>
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
        </div>
    </div>
</form>

@push('scripts')
<script>   

    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('description', editor.getData());
        })
    });
    
</script>
@endpush
