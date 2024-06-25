<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/dashboard.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    @livewire('ecommerce.product.category-list')
    @livewire('ecommerce.user.auth.order-detail')    
    @livewire('ecommerce.product.collection-list')
</x-ecommerce.app-layout>