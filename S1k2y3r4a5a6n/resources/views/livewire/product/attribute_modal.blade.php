<div class="modal-toggle"> 
    <div class="modal-header">
        <h1> Select Attribute </h1>
        <a href="javascript:void(0)" title="Close" class="modal-close">Close</a>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                @foreach($attrList as $index => $attr)
                    <div class="d-inline-flex mx-2">
                        <input type="checkbox" wire:model="attr_ids.{{ $attr->id }}" id="attr{{ $attr->id }}">
                        <label for="attr{{ $attr->id }}"> &nbsp; {{ ucwords($attr->name) }}</label>
                    </div>
                @endforeach 
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-align-justify">
                <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
                <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)" wire:click="addAttr">Submit</a>
            </div>
        </div>
    </div>
</div>