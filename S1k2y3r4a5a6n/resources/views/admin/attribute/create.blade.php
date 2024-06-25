<x-admin.app-layout>
    <x-slot name="styles">
        <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
    </x-slot>
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Dashboard</a></li>
        <li><a href="{{ route('admin.attribute.index') }}">Attribute List</a></li>
        <li>Attribute</li>
    </ul>
    @livewire('attribute.create', ['attribute_id' => $id??''])
</x-admin.app-layout>