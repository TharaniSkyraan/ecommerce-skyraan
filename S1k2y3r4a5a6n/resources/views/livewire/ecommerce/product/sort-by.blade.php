<div>
    <select class="form-select h-sms cursor" aria-label="Default select example" wire:model="sort_by">
        <option value="all" class="h-sms">Featured</option>
        <option value="title-ascending" class="h-sms cursor">Alphabetically, A-Z</option>
        <option value="title-descending" class="h-sms cursor">Alphabetically, Z-A</option>
        <option value="price-ascending" class="h-sms cursor">Price, low to high</option>
        <option value="price-descending" class="h-sms cursor">Price, high to low</option>
        <option value="created_at-ascending" class="h-sms cursor">Date, old to new</option>
        <option value="created_at-descending" class="h-sms cursor">Date, new to old</option>
    </select>
<div>
    <!-- <div>
        <div class="pb-2">
            <div class="card rounded-1 py-2 px-3 filter-select position-relative cursor" wire:model="sort_by" >
                <div class=" d-flex justify-content-between">
                    <p class="h-sms">Alphabetically, A-Z</p>
                    <img src="{{asset('/asset/home/down-ar.svg')}}" alt="" >
                </div>
            </div>
        </div>
        
        <div class="card filter-dropdown rounded-0 border-0"  style="display:none;" >
            <div class="position-absolute w-100 bg-white py-2">
                <p class="px-2 py-1 h-sms">Alphabetically, A-Z</p>
                <p class="px-2 py-1 h-sms">Alphabetically, Z-A</p>
                <p class="px-2 py-1 h-sms">Price, low to high</p>
                <p class="px-2 py-1 h-sms">Price, high to low</p>
                <p class="px-2 py-1 h-sms">Date, old to new</p>
                <p class="px-2 py-1 h-sms">Date, new to old</p>
            </div>
        </div>
    </div> -->
</div>
