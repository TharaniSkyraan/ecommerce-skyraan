<x-ecommerce.app-layout>  
    <style>
        .contact-us-banner {
            background-image: url('{{ asset('asset/home/abt-header.svg') }}');
            background-size: cover;
            height:150px;
        }
    </style> 
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/header.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    @livewire('ecommerce.contactus')
</x-ecommerce.app-layout>
