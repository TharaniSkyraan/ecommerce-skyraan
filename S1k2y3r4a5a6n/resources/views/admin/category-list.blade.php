<x-admin.app-layout>
   
<x-slot name="styles">
    <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
</x-slot>

<ul class="breadcrumb">
  <li><a href="{{url('/')}}">Dashboard</a></li>
  <li>Category</li>
</ul>
@livewire('categories')
</x-admin.app-layout>
