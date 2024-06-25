<x-admin.app-layout>   
  <x-slot name="styles">
      <style>
        .ck-content {
            min-height: 250px;
            max-height: 700px !important;
            overflow-y: scroll !important;
            font-size: 14px;
        }
      </style>
      <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
  </x-slot>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.pages.index') }}">Pages </a></li>
    <li>Page</li>
  </ul>
  @livewire('page.edit', ['page_id' => $id??''])
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>