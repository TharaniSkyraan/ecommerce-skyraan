<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.banner.index') }}">Banner List</a></li>
    <li>Banner</li>
  </ul>
  @livewire('banner.create', ['banner_id' => $id??''])
</x-admin.app-layout>