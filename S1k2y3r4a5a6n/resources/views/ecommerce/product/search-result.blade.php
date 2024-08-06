<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/subcategory.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    <section class="sub_banner pt-2">
        <div class="container">
            <div class="row">
                @php $category = \App\Models\Category::whereSlug($slug)->first(); @endphp
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex align-items-center mt-2">
                        @if($type=='category')
                            <li class="breadcrumb-item text-dark h-sms"><a href="{{url('/')}}" class="text-dark">Home</a></li>
                            @if(isset($category->parent_category))
                            <li class="breadcrumb-item text-dark h-sms detail-dot" aria-current="page"><a href="{{url('/')}}/category?q={{$category->parent_category->slug}}" class="text-dark">{{ $category->parent_category->name }}</a></li>
                            @endif
                            <li class="breadcrumb-item text-dark active h-sms detail-dot" aria-current="page">{{ $category->name }}</li>
                        @else
                            <li class="breadcrumb-item text-dark active h-sms" aria-current="page"><span class="search_result_count"> </span> <span class="detail-dot">Results for <b>"{{ ucwords(str_replace('-',' ',$slug)) }}"</b></span></li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <main>
        <section class="filter-grid">
        @livewire('ecommerce.product.search-result',['type'=>$type,'slug'=>$slug])
        </section>
        @livewire('ecommerce.product.collection-list')
    </main>
</x-ecommerce.app-layout>