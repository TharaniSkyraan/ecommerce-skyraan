<div class="container">
    <div class="row">  
        <div class="col-3 filter-col">
            <div class="card pe-3 pt-3 rounded-0 border-0 border-end filter_inside ">
                <h5 class="fw-bold">Filter</h5>   
                <div class="{{($loader)?'':'d-none'}}">
                    <div class="card-skeleton mt-3 p-2">
                        <div class="skeleton-text skeleton-title"></div>
                    </div>
                    <div class="card-skeleton mt-3 p-1">
                        <div class="skeleton-text skeleton-title"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                    </div>
                    <div class="card-skeleton mt-3 p-1">
                        <div class="skeleton-text skeleton-title"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                    </div>
                    <div class="card-skeleton mt-3 p-1">
                        <div class="skeleton-text skeleton-title"></div>
                        <div class="skeleton-text"></div>
                        <div class="skeleton-text"></div>
                    </div>
                </div>
                @if($is_mobile=='')
                <div class="{{($loader)?'d-none':''}}">
                    @livewire('ecommerce.product.filter',['type'=>$type])
                </div>
                @endif
            </div>       
        </div>       
        <div class="col-xl-9 col-xxl-9 col-lg-9 col-md-12 col-sm-12 col-12 py-4 grid-view">
            <div class="row grid-view-align">
                <div class="col-xl-0 col-lg-0 col-md-4 col-sm-4 col-6 filter_response ">
                    <div class="d-flex align-items-center">
                        <img src="{{asset('asset/home/filter.svg')}}" alt="" >
                        <button class="btn h-sms" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-menu" aria-controls="offcanvasExample">
                            Filter
                        </button>
                    </div>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="filter-menu" aria-labelledby="offcanvasExampleLabel">
                        <div class="card p-4 pt-3 rounded-0 border-0 filter_responses" style="overflow-y: auto; max-height: 100vh;">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-bold">Filter</h5>
                                <img id="closeIcons" src="{{asset('asset/home/close-vector.svg')}}" alt="close_icon" class="cursor">
                            </div>
                            <div class="filter-reponsive-mob">
                                @if($is_mobile=='yes')
                                @livewire('ecommerce.product.filter',['type'=>$type])
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-9 col-lg-8 col-md-5 col-sm-4 col-6 adsas">
                    @livewire('ecommerce.product.grid-filter')
                </div>
                <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-12 jfw">
                    @livewire('ecommerce.product.sort-by')
                </div>
            </div>
            @livewire('ecommerce.product.product-list',['type'=>$type,'slug'=>$slug])
        </div>
    </div>
</div>
