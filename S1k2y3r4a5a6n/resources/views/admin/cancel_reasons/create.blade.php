<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.cancel_reasons.index') }}">Cancel Reason List</a></li>
    <li>Cancel Reason</li>
  </ul>
  @livewire('cancel-reasons.create', ['cancel_reason_id' => $id??''])
</x-admin.app-layout>