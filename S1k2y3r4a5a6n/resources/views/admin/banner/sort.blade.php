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
    <li><a href="{{url('/banner')}}">Banner</a></li>
    <li>Sort</li>
    </ul>
    <div class="row">
        <div class="col-12">   
            <div class="card mx-2">
                @if(session()->has('message'))
                    <div class="alert-success">
                        {{session('message')}}
                    </div>                
                @endif
                <ul id="card">
                    @forelse($banners as $banner)
                        <li id="{{$banner->id}}">
                            <div class="data-list-group">
                                <span>
                                    <span class="text-truncate cursor-pointer"><b>{{$banner->name}}</b></span><br>
                                    <img src="{{ asset('storage') }}/{{$banner->image}}" alt="Banner-icon" class="my-1 cat-image" style="width:100% !important;height:150px !important;">
                                </span>
                            </div>
                        </li>
                    @empty
                        <li>No Data Found</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $( function() {
        $( "#card" ).sortable({
            update: function (event, ui) {
                var ids = $(this).sortable('toArray').toString();
                $.post("{{ route('admin.banner.sort') }}", {ids: ids, _method: 'POST', _token: '{{ csrf_token() }}'})
            }
        });
    });
    </script>

</x-admin.app-layout>