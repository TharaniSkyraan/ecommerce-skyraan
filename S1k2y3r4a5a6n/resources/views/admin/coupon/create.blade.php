<x-admin.app-layout>   
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/date_flatpicker/flatpickr.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/themes/light.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.coupon.index') }}">Coupons List</a></li>
    <li>Coupon</li>
  </ul>
  @livewire('coupon.create', ['coupon_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>