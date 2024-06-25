<x-admin.app-layout>
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
      <link rel="stylesheet" href="{{ asset('admin/css/modal.css')}}" />
      <link rel="stylesheet" href="{{ asset('admin/date_flatpicker/flatpickr.min.css')}}">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/themes/light.min.css">
      <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  </x-slot>
  <ul class="breadcrumb">
      <li><a href="{{url('/')}}">Dashboard</a></li>
      <li><a href="{{ route('admin.product.index') }}">Product List</a></li>
      <li>Product</li>
  </ul>
  @livewire('product.create', ['product_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>