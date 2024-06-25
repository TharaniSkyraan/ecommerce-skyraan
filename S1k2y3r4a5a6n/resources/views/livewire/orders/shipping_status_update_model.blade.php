<div class="modal-toggle"> 
    <div class="modal-header">
        <h1> Update shipping status </h1>
        <a href="javascript:void(0)" title="Close" class="modal-close">Close</a>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-4">
                    <label for="Status">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        @foreach($statuses as $statu)
                            <option value="{{$statu->slug}}">{{ ucwords($statu->name)}}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="error"> {{$message}} </span> @endif    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-align-justify">
                <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
                <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="UpdateStatus">Submit</a>
            </div>
        </div>
    </div>
</div>