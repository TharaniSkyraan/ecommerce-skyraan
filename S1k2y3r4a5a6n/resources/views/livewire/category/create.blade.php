<style>
.ck-content {
    min-height: 150px;
    max-height: 400px !important;
    overflow-y:scroll !important;
    font-size :14px;
 }
</style>
<h1 class="mb-3">{{ (($edit)?'Edit':'New') }}  Category</h1>
<form  id="store-category" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="category_id"  wire:model="category_id"  placeholder="Category Id">
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Category Name" wire:model.defer="name">
        @error('name') <span class="error"> {{$message}}</span> @endif
    </div>
    <div class="row">
        <div class="col-4">                     
            <div class="form-group">
                <label for="cat-image" class="cursor-pointer">Image 
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="category-icon" class="my-1 cat-image">
                    @elseif($temp_image)
                        <img src="{{ asset('storage') }}/{{$temp_image}}" alt="category-icon" class="my-1 cat-image">
                    @else
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="category-icon" class="my-1 cat-image">
                    @endif
                </label>
                <input type="file" name="image" id="cat-image" wire:model="image" accept=".png, .jpg, .jpeg" class="d-none">
                @error('image') <span class="error"> {{$message}}</span> @endif
            </div>  
        </div>
        <div class="col-4">  
            <div class="form-group">
                <label for="cat-logo" class="cursor-pointer">Logo 
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" alt="category-icon" class="my-1 cat-image">
                    @elseif($temp_logo)
                        <img src="{{ asset('storage') }}/{{$temp_logo}}" alt="category-icon" class="my-1 cat-image">
                    @else
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="category-icon" class="my-1 cat-image">
                    @endif
                </label>
                <input type="file" name="logo" id="cat-logo" wire:model="logo" accept=".png, .jpg, .jpeg" class="d-none">
                @error('logo') <span class="error"> {{$message}}</span> @endif
            </div> 
        </div>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" placeholder="Category description"  wire:model.defer="description">{!! $description !!}</textarea>
    </div>
    @error('description') <span class="error"> {{$message}}</span> @endif
    <div class="form-group mb-4">
        <label>Status</label>
        <select name="status" wire:model="status">
            <option value="">Select</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        @error('status') <span class="error"> {{$message}}</span> @endif
    </div>
    @if (in_array('add',$privileges) || (in_array('edit',$privileges) && !empty($category_id)) || in_array('all',$privileges)) 
    <div class="form-group">
        <div class="float-end">
            <button wire:click.prevent="cancel" class="btn btn-c btn-lg" >Discard</button>
            <button wire:click.prevent="storeCategory" class="btn btn-s btn-lg">Submit</button>
        </div>
    </div>
    @endif
</form>