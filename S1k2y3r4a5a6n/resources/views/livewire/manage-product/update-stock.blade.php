<div class="modal-body modal-scroll">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-11 me-2 my-1">
                    <div class="card">
                        <div class="form-group">
                            <label for="available_quantity">Available Quantity</label>
                            <input type="text" name="available_quantity" id="available_quantity" placeholder="Available Quantity" wire:model="available_quantity">
                            @error('available_quantity') <span class="error"> {{$message}}</span> @endif
                        </div>
                    </div>
                </div>
                <div class="col-11">
                    <div class="card">
                        <label for="" class="fw-bold">Stock Status</label>
                        <div class="form-group d-inline-flex mx-2">
                            <input type="radio" name="stock_status" id="stock_status1" wire:model="stock_status" value="in_stock">
                            <label for="stock_status1"> &nbsp;In Stock</label>
                        </div>
                        <div class="form-group d-inline-flex mx-2">
                            <input type="radio" name="stock_status" id="stock_status2" wire:model="stock_status" value="out_of_stock">
                            <label for="stock_status2"> &nbsp;Out of stock</label>
                        </div>
                    </div>
                    @error('stock_status') <span class="error"> {{$message}}</span> @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="text-align-justify">
            <a class="btn btn-c btn-lg modal-dismiss" href="javascript:void(0)">Cancel</a>
            <a class="btn btn-s btn-lg modal-submit" href="javascript:void(0)">Submit</a>
        </div>
    </div>
</div>