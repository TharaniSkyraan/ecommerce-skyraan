<form autocomplete="off">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <input type="hidden" name="tax_id" id="tax_id" wire:model="tax_id"  placeholder="Coupon Id">
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="name" id="name" placeholder="Title" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>                  
                <div class="form-group">
                    <label for="percentage">Percentage %</label>
                    <input type="text" name="percentage" id="percentage" placeholder="Percentage" wire:model="percentage">
                    @error('percentage') <span class="error"> {{$message}}</span> @endif
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
                        <a href="{{ route('admin.tax.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

