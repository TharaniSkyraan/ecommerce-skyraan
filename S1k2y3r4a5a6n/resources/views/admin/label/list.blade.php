<x-admin.app-layout>
  <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li>Label List</li>
  </ul>
  <div class="card">
@if(session()->has('message'))
    <div class="alert-success my-2">
        {{session('message')}}
    </div>                
@endif
<div class="row">
    <div class="col-12">
        <div class="float-end"> <a class="btn btn-s btn-lg" href="{{ route('admin.label.create') }}">Create Label</a> </div>
        <div class="table-responsive">
            <table id="datatable" class="table key-buttons text-md-nowrap">
                <thead>
                    <tr class="form-group">
                        <td></td>
                        <td>
                            <input id="name" name="name" type="text" placeholder="Search...">
                        </td>
                        <td>
                            <select name="status" id="status">
                            <option value="all" selected="selected">Is active?</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option> </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="border-bottom-0">ID</th>
                        <th class="border-bottom-0">Label</th>
                        <th class="border-bottom-0">Status</th>
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
                url: '{!! route('admin.fetch.label.data') !!}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.status = $('#status').val();         
                }
            }, columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    $('#name').on('keyup', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    $('#status').on('change', function (e) {
        dataTable.draw();
        e.preventDefault();
    });
    function delete_label(id) {
        if (confirm('Are you sure! you want to delete?')) {
            $.post("{{ url('admin/label') }}/"+id, {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                if (response == 'ok')
                {
                    var table = $('#datatable').DataTable();
                    table.row('label_dt_row_' + id).remove().draw(false);
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