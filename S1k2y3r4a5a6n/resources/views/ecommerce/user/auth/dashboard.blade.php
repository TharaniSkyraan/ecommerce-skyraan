<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/dashboard.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    <section class="bread-crumb">
        <div class=" px-xl-5 px-lg-5 px-md-5 px-sm-3 px-3 py-2">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item h-sms"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                    <li class="breadcrumb-item h-sms"><a href="javascript:void(0);">Dahsboard</a></li>
                </ol>
            </nav>
        </div>
    </section>
    @livewire('ecommerce.user.auth.dashboard')
    <x-slot name="customscript">
        <script src="{{asset('asset/js/dashboard.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>