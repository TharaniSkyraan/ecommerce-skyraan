<div>
    <div class="offcanvas-body position-relative rounded-0">
        <form autocomplete="off" class="rounded-0">
            @csrf
            <input type="search" name="search" wire:model="query" placeholder="Search for here" id="productsearch" class="input-text rounded-0" aria-label="Search our store" autocomplete="off" required>   
            <button class="search-btn btn p-0" wire:click.prevent="Search"><i class="bi bi-search"></i></button>    
        </form>
    </div>
    <div class="position-absolute w-100 ">
    <!-- {{($show_result)?'':'d-none'}} -->
        <ul class="list-group text-start rounded-2 px-xl-3 px-lg-3 px-md-3 px-sm-3 px-2" id="result">
            <h6 class="fw-bold pb-2 h-sms">Suggestions ({{ count($products)}})</h6>
            @forelse($products as $product)
                <div class="p-2">
                    <li  class="container-fluid">
                        <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp }}" class="row pb-2">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-4 px-0">
                                <div class="card card1 position-relative border-0">
                                    <img src="{{$product['image']}}" alt="list_items" class="srch-img">
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-8 price_info align-self-center">
                                <h6 class="fw-bold h-sms">{{$product['name']}}</h6>
                                @if($product['discount']!=0)
                                    <del class="text-secondary opacity-75 h-sms del-clr">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                    <small class="fw-bold py-1 price">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</small>
                                    <small class="off">{{$product['discount']}}% off</small>
                                @else                                    
                                    <small class="fw-bold py-1 price">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</small>
                                @endif
                                
                                <div class="d-flex  gap-1 align-items-center">
                                    @if($product['review']==0)
                                    <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_starssearch">
                                    @elseif($product['review']==1)
                                    <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_starssearch">
                                    @elseif($product['review']==2)
                                    <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_starssearch">
                                    @elseif($product['review']==3)
                                    <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_starssearch">
                                    @elseif($product['review']==4)
                                    <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_starssearch">
                                    @elseif($product['review']==5)
                                    <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_starssearch">
                                    @endif
                                    <h6 class="text-secondary text-opacity-50 h-sms">{{$product['review_count']}} reviews</h6>
                                </div>
                            </div>
                        </a>
                    </li>
                </div>
            @empty
            <div class="p-2">
                <li  class="container-fluid no-search">No product found<li>
            </div>
            @endforelse
        </ul>
    </div> 
</div>

@push('scripts')
<script src="{{asset('asset/livewire/js/psrch.js')}}"></script>
@endpush