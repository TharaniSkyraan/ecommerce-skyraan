<x-admin.app-layout>
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
      <li><a href="{{url('/')}}">Dashboard</a></li>
      <li><a href="{{ route('admin.tax.index') }}">Taxes List</a></li>
      <li>Taxes</li>
  </ul>
  @livewire('settings.tax.create', ['tax_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
    
</x-admin.app-layout>