<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/home.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <style>   
        .banner .top_carousal .owl-slide{
            background-repeat: round;
        }
        .why-choose .why_bnr{
            background:var(--price-content-color );
            background-repeat: round;
            height: 342px;
            width: 371px;
        }
        .abt_review{
            position: relative;
            /* background-image: url('{{ asset('asset/home/abt-header.svg') }}'); */
            background-repeat: round;
            background-size: cover;
        }
    </style>
    @livewire('ecommerce.home')
    <x-slot name="customscript">
        <script>
            AOS.init({
                duration: 900, 
                once: true
            });
        </script>
        <script src="{{asset('asset/js/home.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>
