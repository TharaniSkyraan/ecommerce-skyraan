<x-admin.app-layout>    
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>

  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li>Special Products</li>
  </ul>
  @livewire('special-product-list')
</x-admin.app-layout>
