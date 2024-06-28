<x-admin.app-layout>
    <x-slot name="styles">
        <link rel="stylesheet" href="{{ asset('admin/css/modal.css')}}" />

    </x-slot>
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
        .highcharts-credits, .highcharts-button-symbol{
            display: none;
        }
        .selected-products, .selected-customers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding: 10px;
            background: #eeeeee38;
        }
        .autocomplete{
            height: 400px;
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
    </style>
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Dashboard</a></li>
        <li>Stock History List</li>
    </ul>
    <div class="card mb-3">
        <div class="warehouse">
            <div class="d-flex">
                <select name="warehouse" id="warehouse">
                    <option value="" selected="selected">All warehouse</option>
                    @foreach($warehouse_from as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>
    </div>                
    <div class="card">
        @if(session()->has('message'))
            <div class="alert-success my-2">
                {{session('message')}}
            </div>                
        @endif
        <div class="row">
            <div class="col-12">
                <h1 class="font-bold mb-3">Product Stock List</h1>
                <div class="float-end"> <a class="btn btn-s btn-lg modal-edit" href="javascript:void(0)">Add Product Stock</a> </div>
                <div class="table-responsive">
                    <table id="datatable" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr class="form-group">
                                <td></td>
                                <td>
                                    <input id="product_name" name="product_name" type="text" placeholder="Search...">
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <select name="status" id="status">
                                    <option value="" selected="selected">Is all?</option>
                                    <option value="in_stock">In stock</option>
                                    <option value="out_of_stock">Out of Stock</option> </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="border-bottom-0"><input type="checkbox" id="all_product_stock" name="all_product_stock"/></th>
                                <th class="border-bottom-0">Product Name</th>
                                <th class="border-bottom-0">Warehouse</th>
                                <th class="border-bottom-0">Available Quantity</th>
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
        
        <div class="modal-window modal-window-lg">
            <div class="modal-toggle"> 
                <div class="modal-header">
                    <h1 > Add Product Stock </h1>
                    <a href="javascript:void(0)" title="Close" class="modal-close">Close</a>
                </div>
                @livewire('manage-product.update-stock')                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="{{asset('admin/js/dataTable/jquery.dataTables.min.js')}}"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script>
          var dataTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: false,
                scrollX: true,
                "order": [[0, "desc"]],
                ajax: {
                    url: '{!! route('admin.fetch.manage-stock.data') !!}',
                    data: function (d) {
                        d.product_name = $('input[name=product_name]').val();      
                        d.warehouse = $('#warehouse').val();            
                        d.status = $('#status').val();         
                    }
                }, columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false}, 
                    {data: 'product_name', name: 'product_name'},
                    {data: 'warehouse', name: 'warehouse'},
                    {data: 'available_quantity', name: 'available_quantity'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                drawCallback: function() {
                    $('input[type="checkbox"]').prop('checked', false);
                    // Additional actions after each draw
                }
            });
            $('#status, #warehouse').on('change', function (e) {
                dataTable.draw();
                e.preventDefault();
                updateChart();
            });
            (function (H) {
                H.seriesTypes.pie.prototype.animate = function (init) {
                    const series = this,
                        chart = series.chart,
                        points = series.points,
                        {
                            animation
                        } = series.options,
                        {
                            startAngleRad
                        } = series;

                    function fanAnimate(point, startAngleRad) {
                        const graphic = point.graphic,
                            args = point.shapeArgs;

                        if (graphic && args) {

                            graphic
                                // Set inital animation values
                                .attr({
                                    start: startAngleRad,
                                    end: startAngleRad,
                                    opacity: 1
                                })
                                // Animate to the final position
                                .animate({
                                    start: args.start,
                                    end: args.end
                                }, {
                                    duration: animation.duration / points.length
                                }, function () {
                                    // On complete, start animating the next point
                                    if (points[point.index + 1]) {
                                        fanAnimate(points[point.index + 1], args.end);
                                    }
                                    // On the last point, fade in the data labels, then
                                    // apply the inner size
                                    if (point.index === series.points.length - 1) {
                                        series.dataLabelsGroup.animate({
                                            opacity: 1
                                        },
                                        void 0,
                                        function () {
                                            points.forEach(point => {
                                                point.opacity = 1;
                                            });
                                            series.update({
                                                enableMouseTracking: true
                                            }, false);
                                            chart.update({
                                                plotOptions: {
                                                    pie: {
                                                        innerSize: '50%',
                                                        borderRadius: 8
                                                    }
                                                }
                                            });
                                        });
                                    }
                                });
                        }
                    }

                    if (init) {
                        // Hide points on init
                        points.forEach(point => {
                            point.opacity = 0;
                        });
                    } else {
                        fanAnimate(points[0], startAngleRad);
                    }
                };
            }(Highcharts));

            var chart = Highcharts.chart('container', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Overall Stock Report',
                    align: 'left'
                },
                subtitle: {
                    text: 'Overall stock report',
                    align: 'left'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        borderWidth: 2,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.percentage}%',
                            distance: 20
                        }
                    }
                },
                series: [{
                    // Disable mouse tracking on load, enable after custom animation
                    enableMouseTracking: false,
                    animation: {
                        duration: 2000
                    },
                    colorByPoint: true,
                    data: [{
                        name: 'Total Instock Product',
                        y: {{ $instock }}
                    }, {
                        name: 'Total Outofstock product',
                        y: {{ $outofstock }}
                    }]
                }]
            });

            function updateChart(){
                $.ajax({
                    url: '{!! route('admin.fetch.data') !!}',
                    method: 'GET',
                    data: {
                        warehouse : $('#warehouse').val()   
                    },
                    dataType: 'json',
                    success: function(res) {
                        chart.series[0].update({
                            data: [{
                                name: 'Total Instock Product',
                                y: res.instock
                            }, {
                                name: 'Total Outofstock product',
                                y: res.outofstock
                            }]
                        });
                    }
                });
            }
          
            $('#all_product_stock').on('click', function(){
                if ($('#all_product_stock').prop('checked')) {
                    $('input[type="checkbox"]').prop('checked', true);
                }else{
                    $('input[type="checkbox"]').prop('checked', false);
                }
            });
            
            $(document).on('click', '.modal-edit', function () {   
                // alert(2); 

                Livewire.emit('OpenUpdatestock','new');
                document.body.classList.add('modal-open');
                $('.modal-window').addClass('show');
            });
            $(document).on('click', '.modal-update', function () { 
                var productId = $(this).attr('data-id'); 
                Livewire.emit('OpenUpdatestock','update',productId);
                document.body.classList.add('modal-open');
                $('.modal-window').addClass('show');
            });

            $(document).on('click', '.modal-close, .modal-dismiss', function () {   
                document.body.classList.remove('modal-open');
                $('.modal-window').removeClass('show');
                Livewire.emit('resetInputvalues');
            });
    
        </script>
    </div>
  <x-slot name="scripts">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </x-slot>
</x-admin.app-layout>