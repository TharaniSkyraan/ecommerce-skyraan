<div>
    <form autocomplete="off">
        <div class="row">
            <div class="col-9">
                <div class="card mx-2">
                    <h1> <span>Name :</span> {{ ucwords(str_replace('_',' ',$name)) }}</h1>
                    <div class="form-group mt-2" wire:ignore>
                        <label for="content">Description</label>
                        <textarea name="content" id="content" wire:model="content" placeholder="Page Content">{!! $content !!}</textarea>
                    </div>  
                    <div>
                        @error('content') <span class="error"> {{$message}}</span> @endif
                    </div>
                    <div class="form-group py-5">
                        <div class="float-end">
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-c btn-lg" >Back</a>
                            <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
    ClassicEditor
    .create(document.querySelector('#content'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('content', editor.getData());
        })
    });
</script>
@endpush