<x-admin.app-layout>    
  <x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
  </x-slot>

  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li>Settings</li>
  </ul>
  @livewire('settings.settings')
</x-admin.app-layout>
