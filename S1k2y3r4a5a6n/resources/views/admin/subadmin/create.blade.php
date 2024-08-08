<x-admin.app-layout>   
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
      <link rel="stylesheet" href="https://s.cdpn.io/55638/selectize.0.6.9.css" />
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    @if(\Auth::guard('admin')->user()->id!=$id)
    <li><a href="{{ route('admin.subadmin.index') }}">Subadmin List</a></li>
    <li>Subadmin</li>
    @else
    <li>Profile Update</li>
    @endif
  </ul>
  @livewire('subadmin.create', ['subadmin_id' => $id??''])
</x-admin.app-layout>