<x-admin.app-layout>    
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
    <link rel="stylesheet" href="https://s.cdpn.io/55638/selectize.0.6.9.css" />
  </x-slot>
  @livewire('warehouses', ['id' => $id??''])
</x-admin.app-layout>
