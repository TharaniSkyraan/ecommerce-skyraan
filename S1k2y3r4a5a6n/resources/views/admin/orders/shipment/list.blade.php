<x-admin.app-layout>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.shipments.index') }}">Shipment List</a></li>
  </ul>
  <div class="card">
@if(session()->has('message'))
    <div class="alert-success my-2">
        {{session('message')}}
    </div>                
@endif
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="datatable" class="table key-buttons text-md-nowrap">
                <thead>
                    <tr class="form-group">
                        <td></td>
                        <td>
                            <input id="tracking_id" name="tracking_id" type="text" placeholder="Search...">
                        </td>
                        <td>
                            <input id="order_code" name="order_code" type="text" placeholder="Search...">
                        </td>
                        <td>
                            <input id="name" name="name" type="text" placeholder="Search...">
                        </td>
                        <td>
                            <select name="status" id="status">
                            <option value="all" selected="selected">Is all?</option>
                            @foreach($statuses as $status)
                            <option value="{{$status->name}}">{{$status->name}}</option>
                            @endforeach
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="border-bottom-0">ID</th>
                        <th class="border-bottom-0">Tracking Id</th>
                        <th class="border-bottom-0">Order Id</th>
                        <th class="border-bottom-0">Customer Name</th>
                        <th class="border-bottom-0">Status</th>
                        <th class="border-bottom-0">Updated At</th>
                        <th class="border-bottom-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{asset('admin/js/dataTable/jquery.dataTables.min.js')}}"></script>
<script>
var dataTable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            "order": [[0, "desc"]],
            ajax: {
                url: '{!! route('admin.fetch.shipments.data') !!}',
                data: function (d) {
                    d.tracking_id = $('input[name=tracking_id]').val();
                    d.order_code = $('input[name=order_code]').val();
                    d.name = $('input[name=name]').val();
                    d.status = $('#status').val();         
                }
            }, columns: [
                {data: 'id', name: 'id'},
                {data: 'tracking_id', name: 'tracking_id'},
                {data: 'order_code', name: 'order_code'},
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    $('#tracking_id').on('keyup', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    $('#order_code').on('keyup', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    $('#name').on('keyup', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    $('#status').on('change', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
</script>
</div>
</x-admin.app-layout>