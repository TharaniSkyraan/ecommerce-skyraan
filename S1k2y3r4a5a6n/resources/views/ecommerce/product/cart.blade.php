<x-ecommerce.app-layout>   
    <style>
        .sub_banner {
            /* background-image: url('{{ asset('asset/home/banner.png') }}'); */
            background-repeat: round;
            position: relative;
            height:26%;
            background-size: cover;
        }
    </style>
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/cart.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <section class="sub_banner">
        <div class="container py-3">
            <div class="row">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23FFFFFF'/%3E%3C/svg%3E&#34;);z-index: 1;" aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex align-items-center mb-0">
                        <li class="breadcrumb-item text-white h-sms"><a href="{{ route('ecommerce.home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item text-white active h-sms" aria-current="page">cart</li>
                    </ol>
                </nav>
                <div style="z-index: 1;">
                    <h4 class="text-center text-white pb-3">Shopping cart</h4>
                </div>
            </div>
        </div>
    </section>
    @livewire('ecommerce.product.cart')
    @livewire('ecommerce.product.collection-list')
    
    <x-slot name="customscript">
        <script src="{{asset('asset/js/cart.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>
