<x-admin.app-layout>   
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.invoices.index') }}">Invoices </a></li>
    <li>Invoice</li>
  </ul>
  @livewire('orders.invoice.edit', ['order_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>