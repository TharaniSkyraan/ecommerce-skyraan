
<div>
    <div class="pb-2">
        <div class="card rounded-1 py-2 px-3 filter-select position-relative cursor" >
            <div class=" d-flex justify-content-between">
                <p class="h-sms">{{ ($sort_by=='title-ascending')?'Alphabetically, A-Z':
                                    (($sort_by=='title-descending')?'Alphabetically, Z-A':
                                    (($sort_by=='price-ascending')?'Price, Low to High':
                                    (($sort_by=='price-descending')?'Price, High to Low':
                                    (($sort_by=='created_at-ascending')?'Date, Old to New':
                                    (($sort_by=='created_at-descending')?'Date, New to Old':'Featured'))))) }}</p>
                <img src="{{asset('/asset/home/down-ar.svg')}}" alt="" >
            </div>
        </div>
    </div>
    
    <div class="card filter-dropdown rounded-0 border-0"  style="display:none;" >
        <div class="position-absolute w-100 bg-white py-2">
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='all')?'selected':'')}}" wire:click="sortByUpdate('all')">Featured</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='title-ascending')?'selected':'')}}" wire:click="sortByUpdate('title-ascending')">Alphabetically, A-Z</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='title-descending')?'selected':'')}}" wire:click="sortByUpdate('title-descending')">Alphabetically, Z-A</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='price-ascending')?'selected':'')}}" wire:click="sortByUpdate('price-ascending')">Price, Low to High</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='price-descending')?'selected':'')}}" wire:click="sortByUpdate('price-descending')">Price, High to Low</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='created_at-ascending')?'selected':'')}}" wire:click="sortByUpdate('created_at-ascending')">Date, Old to New</p>
            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='created_at-descending')?'selected':'')}}" wire:click="sortByUpdate('created_at-descending')">Date, New to Old</p>
        </div>
    </div>
</div>
