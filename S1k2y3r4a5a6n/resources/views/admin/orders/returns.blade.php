<x-admin.app-layout>    
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>

  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li>Orders Returns</li>
  </ul>
  @livewire('orders.order-returns')
</x-admin.app-layout>
