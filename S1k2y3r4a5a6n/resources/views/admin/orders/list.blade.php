<x-admin.app-layout>
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Dashboard</a></li>
        <li>Order List</li>
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
                              <td></td>
                              <td>
                                  <input id="name" name="name" type="text" placeholder="Search...">
                              </td>
                              <td>
                                  <input id="phone" name="phone" type="text" placeholder="Search...">
                              </td>
                              <td>
                                    <select name="status" id="status">
                                        <option value="all" selected="selected">All Orders?</option>
                                        <option value="new_request">New Request</option>
                                        <option value="order_confirmed">Order Confirmed</option>
                                        <option value="out_for_delivery">Out for delivery</option>
                                        <option value="delivered">Delivered</option>  
                                        <option value="cancelled">Cancelled</option> 
                                        <option value="refund">Refund</option>
                                        <option value="replaced">Replaced</option> 
                                    </select>
                              </td>
                              <td></td>
                          </tr>
                          <tr>
                              <th class="border-bottom-0"></th>
                              <th class="border-bottom-0">Order ID</th>
                              <th class="border-bottom-0">Customer Name</th>
                              <th class="border-bottom-0">Phone</th>
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
        function format(d) {
            // `d` is the original data object for the row
            return (
                `<dl>`+
                `<dt><span class="fw-bold">Amount : </span>` + d.sub_total + ` </dt><hr>` +
                `<dt><span class="fw-bold">Shipping Amount : </span> ` + d.shipping_amount + ` </dt><hr>` +
                `<dt><span class="fw-bold">Tax Amount : </span> ` + d.tax_amount + ` </dt><hr>` +
                `<dt><span class="fw-bold">Discound : </span> ` + d.discount_amount + ` </dt><hr>` +
                `<dt><span class="fw-bold">Total : </span> ` + d.total_amount + ` </dt><hr>` +
                `<dt><span class="fw-bold">Payment Method : </span> ` + d.payment_method + ` </dt><hr>` +
                `<dt><span class="fw-bold">Payment Status : </span> ` + d.payment_status + ` </dt><hr>` +
                `<dt><span class="fw-bold">Order At : </span> ` + d.order_at + ` </dt><hr>` +
                `</dl>`
            );
        }
        
      var dataTable = $('#datatable').DataTable({
                  processing: true,
                  serverSide: true,
                  stateSave: true,
                  searching: false,
                  scrollX: true,
                  "order": [[0, "desc"]],
                  ajax: {
                      url: '{!! route('admin.fetch.orders.data') !!}',
                      data: function (d) {
                          d.name = $('input[name=name]').val();
                          d.status = $('#status').val();         
                      }
                  }, columns: [
                      {                        
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                      },
                      {data: 'id', name: 'id'},
                      {data: 'name', name: 'name'},
                      {data: 'phone', name: 'phone'},
                      {data: 'status', name: 'status'},
                      {data: 'action', name: 'action', orderable: false, searchable: false}
                  ]
              });
            dataTable.on('click', 'td.dt-control', function (e) {
                let tr = e.target.closest('tr');
                let row = dataTable.row(tr);
            
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                }
                else {
                    // Open this row
                    row.child(format(row.data())).show();
                }
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