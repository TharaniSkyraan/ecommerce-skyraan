<x-admin.app-layout>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li>Cancel Reason List</li>
  </ul>
  <div class="card">
@if(session()->has('message'))
    <div class="alert-success my-2">
        {{session('message')}}
    </div>                
@endif
<div class="row">
    <div class="col-12">
        <div class="float-end"> @if(in_array('add',$privileges) || in_array('all',$privileges)) <a class="btn btn-s btn-lg" href="{{ route('admin.cancel_reasons.create') }}">Create Cancel Reason</a> @endif </div>
        <div class="table-responsive">
            <table id="datatable" class="table key-buttons text-md-nowrap">
                <thead>
                    <tr class="form-group">
                        <td></td>
                        <td>
                            <input id="name" name="name" type="text" placeholder="Search...">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="border-bottom-0">ID</th>
                        <th class="border-bottom-0">Cancel Reason</th>
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
            scrollX: true,
            "order": [[0, "desc"]],
            ajax: {
                url: '{!! route('admin.fetch.cancel_reasons.data') !!}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.status = $('#status').val();         
                }
            }, columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    $('#name').on('keyup', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    function delete_cancel_reason(id) {
        if (confirm('Are you sure! you want to delete?')) {
            $.post("{{ url('admin/cancel_reasons') }}/"+id, {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                if (response == 'ok')
                {
                    var table = $('#datatable').DataTable();
                    table.row('cancel_reason_dt_row_' + id).remove().draw(false);
                } else
                {
                    alert('Request Failed!');
                }
            });
        }
    }
</script>
</div>
</x-admin.app-layout>