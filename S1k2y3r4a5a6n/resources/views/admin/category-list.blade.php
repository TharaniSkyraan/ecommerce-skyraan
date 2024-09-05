<x-admin.app-layout>
   
<x-slot name="styles">
  <style>
    .main-container{
      padding-top: 70px;
    }
    .cat-image{
      width: 80px !important;
      height: 80px !important;
    }
  </style>
  <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
</x-slot>

<ul class="breadcrumb">
  <li><a href="{{url('/')}}">Dashboard</a></li>
  <li>Category</li>
</ul>
@livewire('categories',['privileges'=>$privileges])
</x-admin.app-layout>
