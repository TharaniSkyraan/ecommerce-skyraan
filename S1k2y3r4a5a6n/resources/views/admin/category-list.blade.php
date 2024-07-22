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
    #card{
      overflow-y: scroll;
      height: 520px;
      padding-right: 5px;
    }    
    #card::-webkit-scrollbar {
      width: 5px;
    }
    #card::-webkit-scrollbar-thumb {
      background-color: #808080;
      border-radius: 3px;
    }
    #card::-webkit-scrollbar-track {
      background-color: #f1f1f1;
      border-radius: 3px;
    }
  </style>
  <link rel="stylesheet" href="{{ asset('admin/css/cat1es.css')}}" />
</x-slot>

<ul class="breadcrumb">
  <li><a href="{{url('/')}}">Dashboard</a></li>
  <li>Category</li>
</ul>
@livewire('categories')
</x-admin.app-layout>
