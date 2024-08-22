<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-9">
            <div class="card mx-2">
                <input type="hidden" name="attribute_id" id="attribute_id" wire:model="attribute_id"  placeholder="Attribute Id">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Attribute Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <!-- <div class="form-group mb-4">
                    <label for="name">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div> -->
                <div class="form-group">
                    <div class="card">       
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                 <h1>Attribute List</h1>
                            </div> 
                            <div class="action-btn">
                                <a class="btn btn-s btn-lg" href="javascript:void(0);" wire:click="addRow">Add Attribute</a> 
                            </div>
                        </div>
                        <div>
                            <table class="table dataTable form-group">
                                <thead>
                                    <tr class="text-center">
                                        <td>Is Default</td>
                                        <td>Title</td>
                                        <td>Color</td>
                                        <td>Image</td>
                                        @if(empty($attribute_id))
                                        <td>Remove</td>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach($attributeList as $index => $row)
                                        <tr>
                                            <td width="10px"><input type="radio" name="is_default" wire:model="is_default" value="{{ $index }}"></td>
                                            <td><input type="text" name="name" wire:model="attributeList.{{ $index }}.name"></td>
                                            <td><input type="color" name="color" wire:model="attributeList.{{ $index }}.color"></td>
                                            <td>
                                                <label for="attributeList.{{ $index }}.image" class="cursor-pointer">
                                                    @if(!empty($attributeList[$index]['image']))
                                                        <img src="{{ $attributeList[$index]['image']->temporaryUrl() }}" alt="ATtr-icon" class="my-1 cat-image" style="width: 50%;height:50%">
                                                    @elseif(!empty($attributeList[$index]['temp_image']))
                                                        <img src="{{ asset('storage') }}/{{ $attributeList[$index]['temp_image'] }}" alt="ATtr-icon" class="my-1 cat-image" style="width: 50%;height:50%">
                                                    @else
                                                        <img src="{{ asset('admin/images/placeholder.png') }}" alt="ATtr-icon" class="my-1 cat-image" style="width: 25%;height:25%">
                                                    @endif
                                                </label>
                                                <input type="file" class="d-none" name="image[]" id="attributeList.{{ $index }}.image" wire:model="attributeList.{{ $index }}.image">
                                            </td>
                                            @if(empty($attribute_id))
                                            <td><i class="bx bx-trash btn btn-d cursor-pointer" wire:click="removeRow({{ $index }})"></i></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @error('is_default') <span class="error"> {{ $message }} </span> <br> @enderror
                            @error('attributeList.*.name') <span class="error"> {{ $message }} </span>  <br> @enderror
                            @error('attributeList.*.color') <span class="error"> {{ $message }} </span> <br> @enderror
                            @error('attributeList.*.file') <span class="error"> {{ $message }} </span>  <br> @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group py-5 action-btn">
                    <div class="float-end">
                        <a href="{{ route('admin.attribute.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
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
        </div>
    </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
      // Get the full URL
      var url = window.location.href;
    // Split the URL by '/' and get the last segment
    var segments = url.split('/');
    var lastSegment = segments.pop() || segments.pop();  // Handle trailing slash
    if(lastSegment !='edit' && lastSegment !='create'){
        $('input, select, textarea').prop('disabled', true);
        $('.action-btn').html('');
    }
</script>
@endpush
