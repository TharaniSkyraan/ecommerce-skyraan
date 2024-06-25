<x-ecommerce.app-layout>   
    <style>
        .top-nav{
            display:none;
        }
        .search_menu_nav{
            display:none;
        }
    </style>
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/cart.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <section class="check_out_nav py-xl-4  py-lg-4 py-sm-3  py-md-4  py-2">
        <div class="container">
            <div class="logo d-flex gap-4 align-items-center">
                <a href="{{ route('ecommerce.cart') }}"><img src="{{asset('asset/home/back-icon.png')}}" alt="" class="bck-btn"></a>
                <a href="{{ url('/') }}"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="" class="responsive_logo"></a>
            </div>
        </div>
    </section>
    @livewire('ecommerce.product.checkout')
    <x-slot name="customscript">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.8.1/prism.min.js"></script>
        <script src="{{asset('asset/js/cart.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>