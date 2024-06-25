<x-admin.app-layout>    
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  @livewire('zones', ['id' => $id??''])
</x-admin.app-layout>
