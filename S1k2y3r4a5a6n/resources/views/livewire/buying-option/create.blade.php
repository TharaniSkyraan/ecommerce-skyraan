<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <input type="hidden" name="buying_option_id" id="buying_option_id" wire:model="buying_option_id"  placeholder="Buying Option Id">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Buying Option Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="image">Logo 
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Buying Option-icon" class="my-1 cat-image">
                        @elseif($temp_image)
                        <img src="{{ asset('storage') }}/{{$temp_image}}" alt="Buying Option-icon" class="my-1 cat-image">
                        @else
                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="Buying Option-icon" class="my-1 cat-image">
                        @endif
                    </label>
                    <input type="file" name="image" id="image" wire:model="image" accept=".svg" class="d-none">
                    @error('image') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group" wire:ignore>
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Buying Option description" wire:model="description">{!! $description !!}</textarea>
                </div>
                <div class="form-group">
                    <label for="feature_type">Feature Type</label>
                    <div class="row">
                        <div class="col-3 d-flex">
                            <input type="radio" wire:model="feature_type" id="feature_type3" value="all">
                            <label for="feature_type3"> &nbsp; All</label>
                        </div>
                        <div class="col-3 d-flex">
                            <input type="radio" wire:model="feature_type" id="feature_type1" value="product">
                            <label for="feature_type1"> &nbsp; Product</label>
                        </div>
                        <div class="col-3 d-flex">
                            <input type="radio" wire:model="feature_type" id="feature_type2" value="buying">
                            <label for="feature_type2"> &nbsp; Buying</label>
                        </div>
                    </div>
                </div>
                @error('description') <span class="error"> {{$message}}</span> @endif
                <div class="form-group mb-4">
                    <label for="name">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group py-5 action-btn">
                    <div class="float-end">
                        <a href="{{ route('admin.buying-option.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>   
    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('description', editor.getData());
        })
    });
    
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