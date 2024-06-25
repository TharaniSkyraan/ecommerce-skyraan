<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.brand.index') }}">Brand List</a></li>
    <li>Brand</li>
  </ul>
  @livewire('brand.create', ['brand_id' => $id??''])
</x-admin.app-layout>