<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <div class="form-group">
                    <label for="name">Reason</label>
                    <textarea name="reason" id="reason" wire:model="reason" placeholder="Reason"></textarea>
                    @error('reason') <span class="error"> {{$message}}</span> @endif
                </div>                   
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.label.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
