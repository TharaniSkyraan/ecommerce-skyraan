<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/subcategory.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    <section class="sub_banner pt-2">
        <div class="container">
            <div class="row">
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex align-items-center mt-2">
                        <li class="breadcrumb-item text-white"><a href="{{url('/')}}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Product List</li>
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
