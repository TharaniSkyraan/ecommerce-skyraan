<x-ecommerce.app-layout>  
    <style>
        .about-us-banner {
            background-image: url('{{ asset('asset/home/abt-header.svg') }}');
            background-size: cover;
            height:150px;
        }
    </style> 
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/header.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.aboutus')
</x-ecommerce.app-layout>
