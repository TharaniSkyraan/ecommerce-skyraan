<div>
    <div class="pt-3">
        <div class="card px-xl-4 px-lg-4 px-md-4 px-sm-3 px-3 py-2 border-0 ">
            <small class="text-secondary {{($filtercount!=0)?'':'opacity-50'}}">{{$filtercount}} filters  <a href="javascript:void(0);" wire:click="ResetAllFilters" class="float-end ResetAllFilters"><small class="text-dark text-decoration-underline"> @if($filtercount!=0) Reset @endif</small></a> </small>
            <div> 
                @if(count($selectedStocks)!=0)<small class="selectedfilter h-sms">Availability ({{count($filters['availablestock'])}}) <a href="javascript:void(0)" wire:click="resetAvailable">×</a></small> @endif
                @if(($max != $max_price)||($min != $min_price))<small class="selectedfilter h-sms">Price <a href="javascript:void(0)" class="resetPrice" wire:click="resetPrice">×</a></small>@endif
                @if(count($rating_ids)!=0)<small class="selectedfilter h-sms">Rating ({{count($filters['rating'])}}) <a href="javascript:void(0)" wire:click="resetRating">×</a></small> @endif
            </div>
        </div>
    </div>
    <div class="pt-3">
        <div class="card px-xl-4 px-lg-4 px-md-4 px-sm-3 px-3 py-2 border-0 ">
            <h6 class="fw-bold pb-2">Availability</h6>
            @foreach($stocks as $key => $stock)
            <div class="form-check ">
                <input class="form-check-input cursor" type="checkbox" id="stock{{$key}}" wire:model="selectedStocks.{{ $key }}">
                <label class="form-check-label h-sms cursor" for="stock{{$key}}">
                    {{ $stock }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    <div class="pt-3">
        <div class="card px-xl-4 px-lg-4 px-md-4 px-sm-3 px-3 py-2 border-0">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">Price</h6>
                <a href="javascript:void(0)" wire:click="resetPrice" class="resetPrice"><small class="text-secondary text-decoration-underline"> {{ (($max != $max_price)||($min != $min_price))?'Reset':'' }} </small></a>
            </div>
            <div @if($max != 0) wire:ignore @endif>
                <small class="text-secondary opacity-50 py-1 pb-3 h-sms">Maximum price is {{ $ip_data->currency_symbol??'₹' }}.{{$max}}</small>
                <div class="slider mt-2" >
                    <div class="progress"></div>
                </div>
                <div class="range-input">
                    <input type="range" class="range-min" min="{{$min}}" max="{{$max}}" value="{{$min}}" step="100" >
                    <input type="range" class="range-max" min="{{$min}}" max="{{$max}}" value="{{$max}}" step="100">
                </div>
                <div class="row pt-3">
                    <div class="col-6"><small class="text-secondary opacity-50">From</small></div>
                    <div class="col-6"><small class="text-secondary opacity-50">To</small></div>
                </div>
                <div class="row input-range price-input align-items-center">
                    <div class="col-5">
                        <div class="input-group input-range price-input align-items-center bg-white">
                            <span class="input-group-text border-0  bg-white  text-secondary">{{ $ip_data->currency_symbol??'₹' }}</span>
                            <input class="input-min form-control border-0 text-end text-secondary h-sms" data-id="min_price" wire:model="min_price" value="{{$min_price}}" readonly>
                        </div>
                    </div>
                    <div class="col-1 text-center"> - </div>
                    <div class="col-5 pe-0">
                        <div class="input-group input-range price-input bg-white align-items-center">
                            <span class="input-group-text border-0 bg-white  text-secondary">{{ $ip_data->currency_symbol??'₹' }}</span>
                            <input class="input-max form-control border-0 text-end text-secondary px-1 h-sms" data-id="max_price" wire:model="max_price" value="{{$max_price}}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($categories)!=0)
    <div class="pt-3">
        <div class="card px-xl-4 px-lg-4 px-md-4 px-sm-3 px-3 py-2 border-0">
            <h6 class="fw-bold pb-2">Categories</h6>
            <div class="cat-card" >
                @foreach($categories as $category)
                    <div class="d-flex gap-2 cat-div cursor category-filter" data-id="{{$category->slug}}">
                        <img src="{{asset('asset/home/left-ar.svg')}}" alt="arrow">
                        <p class="detail-dot h-sms fw-bold {{ (in_array($category->id, array_keys($category_ids))?'':'opacity-75') }}">{{ $category->name }}</p>
                    </div>
                    @foreach($category->active_sub_categories() as $sub_category)
                        @if($sub_category->status =='active')
                            <div class="ps-3 pt-2 show-div">
                                <p class="h-sms category-filter {{ (in_array($sub_category->id, array_keys($category_ids))?'fw-bold':'cursor opacity-75') }}" data-id="{{$sub_category->slug}}">{{ $sub_category->name}}</p>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <div class="pt-3">
        <div class="card px-xl-4 px-lg-4 px-md-4 px-sm-3 px-3 py-2 border-0 ">
            <h6 class="fw-bold pb-3">Ratings</h6>
            @foreach($ratings as $rating)
                <div class="form-check">
                    <input class="form-check-input mt-2 cursor" type="checkbox" id="rating{{$rating}}" wire:model="rating_ids.{{$rating}}">
                    <label class="form-check-label cursor" for="rating{{$rating}}">
                        <img src="{{asset('asset/home/')}}/{{$rating}}.svg" alt="">
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    
const rangeInput = document.querySelectorAll(".range-input input"),
  priceInput = document.querySelectorAll(".price-input input"),
  range = document.querySelector(".slider .progress");

    var priceGap = 100;

    priceInput.forEach((input) => {
        input.addEventListener("input", (e) => {
            let minPrice = parseInt(priceInput[0].value),
            maxPrice = parseInt(priceInput[1].value);
            
            if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
                if (e.target.classList.contains("input-min")) {
                    rangeInput[0].value = minPrice;
                    range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                    @this.set('min_price', minPrice);
                } else {
                    rangeInput[1].value = maxPrice;
                    range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                    @this.set('max_price', maxPrice);
                }
            }
        });
    });

    rangeInput.forEach((input) => {
        input.addEventListener("input", (e) => {
            let minVal = parseInt(rangeInput[0].value),
            maxVal = parseInt(rangeInput[1].value);

            if (maxVal - minVal < priceGap) {
                if (e.target.className === "range-min") {
                    rangeInput[0].value = maxVal - priceGap;
                } else{
                    rangeInput[1].value = minVal + priceGap;
                }
            } else {
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
                @this.set('max_price', maxVal);
                @this.set('min_price', minVal);

            }
        });
    });
    
    $(document).on('click', '.resetPrice, .ResetAllFilters', function () { 
        let minPrice = parseInt('{{$min_price}}'),
            maxPrice = parseInt('{{$max_price}}');
        
        priceInput[0].value = minPrice;
        priceInput[1].value = maxPrice;
        rangeInput[1].value = maxPrice;
        @this.set('max_price', maxPrice);
        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
        rangeInput[0].value = minPrice;
        @this.set('min_price', minPrice);
        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                
    });
    setTimeout(function() {setValue();}, 2000); 
    setTimeout(function() {setValue();}, 1000); 

    function setValue(){
        let minPrice = parseInt(priceInput[0].value),
        maxPrice = parseInt(priceInput[1].value);

        rangeInput[1].value = maxPrice;
        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
        rangeInput[0].value = minPrice;
        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
    }

    $(document).ready(function(){
        $(".show-div").hide();
        $(".cat-div").click(function(){
            $(".show-div").toggle();
        });
    });
    
    $(document).on('click','.category-filter', function()
    {
        var slug = $(this).data('id');
        var url = "{{ url('/') }}/category?q="+slug;
        var queryParams = {};
        var urlParams = new URLSearchParams(window.location.search);
        
        urlParams.forEach(function(value, key) {
            queryParams[key] = value;

            if(key!='category' && key!='q' && key!='min_price' && key!='max_price'){
                url = url+'&'+key+'='+value;
            }
        });
        window.location.href = url;
    });
</script>