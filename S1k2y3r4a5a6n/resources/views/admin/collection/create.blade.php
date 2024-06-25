<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.collection.index') }}">Collection List</a></li>
    <li>Collection</li>
  </ul>
  @livewire('collection.create', ['collection_id' => $id??''])
</x-admin.app-layout>