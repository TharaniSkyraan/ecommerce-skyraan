<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/modal.css')}}" />
      <link rel="stylesheet" href="{{ asset('admin/css/ship.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.stock-history.index') }}">Stock History List</a></li>
    <li>Stock History</li>
  </ul>
  @livewire('stock-history.history', ['history_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>