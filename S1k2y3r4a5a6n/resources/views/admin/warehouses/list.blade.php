<x-admin.app-layout>
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Dashboard</a></li>
        <li>Warehouses List</li>
    </ul>
    <div class="card">
        @if(session()->has('message'))
            <div class="alert-success my-2">
                {{session('message')}}
            </div>                
        @endif
        <div class="row">
            <div class="col-12">
                <div class="float-end"> <a class="btn btn-s btn-lg" href="{{ route('admin.warehouses.create') }}">Create Warehouse</a> </div>
                <div class="table-responsive">
                    <table id="datatable" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr class="form-group">
                                <td></td>
                                <td>
                                    <input id="address" name="address" type="text" placeholder="Search...">
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
                                <th class="border-bottom-0">Warehouse Address</th>
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
                    "order": [[0, "desc"]],
                    ajax: {
                        url: '{!! route('admin.fetch.warehouses.data') !!}',
                        data: function (d) {
                            d.address = $('input[name=address]').val();
                            d.status = $('#status').val();         
                        }
                    }, columns: [
                        {data: 'id', name: 'id'},
                        {data: 'address', name: 'address'},
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
            function delete_warehouse(id) {
                if (confirm('Are you sure! you want to delete?')) {
                    $.post("{{ url('admin/warehouses') }}/"+id, {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#datatable').DataTable();
                            table.row('warehouse_dt_row_' + id).remove().draw(false);
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