<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.label.index') }}">Label List</a></li>
    <li>Label</li>
  </ul>
  @livewire('label.create', ['label_id' => $id??''])
</x-admin.app-layout>