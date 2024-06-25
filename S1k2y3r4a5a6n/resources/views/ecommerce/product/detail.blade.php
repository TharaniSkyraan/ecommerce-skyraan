<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/detail.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    @livewire('ecommerce.product.detail',['slug'=>$slug])    
    @livewire('ecommerce.product.collection-list')
    <x-slot name="customscript">
        <script src="{{asset('asset/js/detail.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>
