<x-admin.app-layout>    
  <x-slot name="styles">
      <link rel="stylesheet" href="{{ asset('admin/css/modal.css')}}" />
      <style>
        .selected-products, .selected-customers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding: 10px;
            background: #eeeeee38;
        }
        .autocomplete {
            background: #eeeeee66;
            border-radius: 5px;
        }
        .autocomplete .prdlist{
            max-height: 400px;
            min-height: fit-content;
            overflow-y: scroll;
        }
        .bg-selected {
            background-color: #e2e9e6; 
        }
        .bg-secondary{
           background-color:#eee;
        }
        .autocomplete .product_id {
            display: flex;
            align-items: center;
            font-size: 13px;
            margin:10px 20px 10px 10px;
        }
        .autocomplete span{
            display: flex;
            justify-content:center;
            font-size: 17px;
            margin:10px 20px 10px 10px;
        }
        .autocomplete img{
            border: 1px solid #dedede;
            border-radius: 5px;
            width: 60px;
            height: 60px;
            margin: 0px 10px;
        }
        .prdlist::-webkit-scrollbar {
            width: 5px;
        }

        .prdlist::-webkit-scrollbar-thumb {
            background-color: #bdbdbd5c;
            border-radius: 3px;
        }

        .prdlist::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            border-radius: 3px;
        }
        </style>
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.orders.index') }}">Orders </a></li>
    <li>Orders Create</li>
  </ul>
  @livewire('orders.create', ['order_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
  
</x-admin.app-layout>