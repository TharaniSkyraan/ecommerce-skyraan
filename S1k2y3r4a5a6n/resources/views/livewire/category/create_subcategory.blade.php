<style>
.ck-content {
    min-height: 150px;
    max-height: 400px !important;
    overflow-y:scroll !important;
    font-size :14px;
 }
</style>
<h1 class="mb-3">{{ (($edit)?'Edit':'New') }} Sub Category</h1>
<form  id="store-category" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="category_id" id="category_id" wire:model="category_id"  placeholder="Category Id">    
    <div class="form-group mb-4">
        <label>Category</label>
        <select name="parent_id" id="parent_id" wire:model="parent_id">
            <option value="">Select</option>
            @foreach($categories as $category)
            <option value="{{$category->id}}" @if($parent_id == $category->id) selected @endif>{{$category->name}}</option>
            @endforeach
        </select>
        @error('parent_id') <span class="error"> Select category</span> @endif
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" id="name" placeholder="Category Name" wire:model="name">
        @error('name') <span class="error"> {{$message}}</span> @endif
    </div>
    <div class="row">
        <div class="col-4">    
            <div class="form-group">
                <label for="image" class="cursor-pointer">Image 
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="category-icon" class="my-1 cat-image">
                    @elseif($temp_image)
                        <img src="{{ asset('storage') }}/{{$temp_image}}" alt="category-icon" class="my-1 cat-image">
                    @else
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="category-icon" class="my-1 cat-image">
                    @endif
                </label>
                <input type="file" name="image" id="image" wire:model="image" accept=".png, .jpg, .jpeg" class="d-none">
                @error('image') <span class="error"> {{$message}}</span> @endif
            </div>
        </div>
        <div class="col-4">    
            <div class="form-group">
                <label for="logo" class="cursor-pointer">Logo 
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" alt="category-icon" class="my-1 cat-image">
                    @elseif($temp_logo)
                        <img src="{{ asset('storage') }}/{{$temp_logo}}" alt="category-icon" class="my-1 cat-image">
                    @else
                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="category-icon" class="my-1 cat-image">
                    @endif
                </label>
                <input type="file" name="logo" id="logo" wire:model="logo" accept=".png, .jpg, .jpeg" class="d-none">
                @error('logo') <span class="error"> {{$message}}</span> @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" id="description" placeholder="Category description"  wire:model="description"></textarea>
        @error('description') <span class="error"> {{$message}}</span> @endif
    </div>
    <div class="form-group mb-4">
        <label>Status</label>
        <select name="status" id="status" wire:model="status">
            <option value="">Select</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        @error('status') <span class="error"> {{$message}}</span> @endif
    </div>
    <div class="form-group">
        <div class="float-end">
            <button wire:click.prevent="cancel" class="btn btn-c btn-lg">Discard</button>
            <button wire:click.prevent="storeSubCategory" class="btn btn-s btn-lg">Submit</button>
        </div>
    </div>
</form>