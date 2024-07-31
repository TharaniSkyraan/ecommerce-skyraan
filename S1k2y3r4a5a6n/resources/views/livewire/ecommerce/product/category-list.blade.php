<section  class="product">
<div class="container position-relative">
    <div class="d-flex align-items-center top-carousel-category">
        <div class="navbar d-flex gap-2 top-nav-scroll">
            @foreach($categories1 as $category)
                <div class="dropdown px-xl-4 px-lg-4 px-sm-2 px-md-2 px-2">
                    @if(count($category->sub_categories)==0)
                    <a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category['slug']]) }}" class="fw-light">
                        <span class="h-sms category-top-dot">{{ $category->name }}</span>
                    </a>
                    @else
                        <span class="h-sms category-top-dot">{{ $category->name }}</span>
                        <img class="dropdown-icon" src="{{asset('asset/home/down-ar.svg')}}" alt="">
                        <div class="dropdown-content px-2 mt-1">
                            <div class="row">
                                @php $count = count($category->sub_categories);
                                    $sub_categories = $category->sub_categories->toArray();
                                    if($count < 10){  
                                        $class_name = 12;
                                    }elseif($count < 20){
                                        $count = $count/2; 
                                        $class_name = 6;  
                                    }elseif($count < 30){
                                        $count = $count/3;     
                                        $class_name = 4;                                      
                                    }else{
                                        $count = $count/4; 
                                        $class_name = 3;  
                                    }
                                    $sub_categories = array_chunk($sub_categories,$count);   
                                    $j=1; 
                                @endphp

                                @foreach($sub_categories as $sub_category)
                                   <div class="col-{{$class_name}} {{ ($j % 2 == 0) ? 'color-filled' : '' }}">
                                        @foreach($sub_category as $subcategory)
                                            <a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category['slug']]) }}" class="fw-light">
                                                <p class="py-1 h-sms">{{ $subcategory['name'] }}</p>
                                            </a>
                                        @endforeach
                                        @php $j++; @endphp
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="dropdown ms-3 px-2 more">
            <span class="h-sms" >MORE</a>
            <div class="dropdown-content-more px-2 mt-2">
                <div class="row">
                    @php $k=0; $l=1; @endphp
                    @foreach($categories as $key => $category)
                        @if($k==0) <div class="col-{{$more_class_name}} {{ ($l % 2 == 0) ? 'color-filled' : '' }}"> @endif
                        @if(count($category->sub_categories)==0) 
                        <a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category['slug']]) }}" class="fw-light">
                            <p class="py-1 h-sms fw-bold">{{ $category->name }}</p>
                        </a>
                        @else
                            <p class="py-1 h-sms fw-bold">{{ $category->name }}</p>
                        @endif
                            @php $k++; @endphp
                        @if($k==$morecategoriescount) @php  $k=0; $l++; @endphp</div>@endif
                        @foreach($category->sub_categories as $sub_category)
                            @if($sub_category->status=='active')
                                @if($k==0) <div class="col-{{$more_class_name}} {{ ($l % 2 == 0) ? 'color-filled' : '' }}"> @endif
                                    <p class="py-1 h-sms"><a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $sub_category['slug']]) }}" class="fw-light">{{ $sub_category->name }}</a></p>
                                    @php $k++; @endphp
                                @if($k==$morecategoriescount) @php $k=0; $l++; @endphp </div>@endif
                            @endif
                        @endforeach
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
</div>
</section>
