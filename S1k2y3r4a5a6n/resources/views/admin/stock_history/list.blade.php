<x-admin.app-layout>
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
                <h1 class="font-bold mb-3">Stock Update History</h1>
                <div class="table-responsive">
                    <table id="datatable" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr class="form-group">
                                <td></td>
                                <td>
                                    <input id="reference_number" name="reference_number" type="text" placeholder="Search...">
                                </td>
                                <td>
                                    <select name="stock_type" id="stock_type">
                                    <option value="" selected="selected">Is all?</option>
                                    <option value="upload">Update</option>
                                    <option value="transfer">Transfer</option> 
                                    <option value="transfer">Order</option> </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <select name="status" id="status">
                                    <option value="" selected="selected">Is all?</option>
                                    <option value="sent">Sent</option>
                                    <option value="received">Received</option>
                                    <option value="new_order">New Order</option> 
                                    <option value="delivered">Delivered</option> 
                                    <option value="cancelled">Cancelled</option>  </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="border-bottom-0">ID</th>
                                <th class="border-bottom-0">Ref Number</th>
                                <th class="border-bottom-0">Stock Type</th>
                                <th class="border-bottom-0">Warehouse</th>
                                <th class="border-bottom-0">From Warehouse</th>
                                <th class="border-bottom-0">No.of Product</th>
                                <th class="border-bottom-0">Sent Date</th>
                                <th class="border-bottom-0">Received Date</th>
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
                    url: '{!! route('admin.fetch.stock-history.data') !!}',
                    data: function (d) {
                        d.reference_number = $('input[name=reference_number]').val();      
                        d.warehouse = $('#warehouse').val();         
                        d.stock_type = $('#stock_type').val();         
                        d.status = $('#status').val();         
                    }
                }, columns: [
                    {data: 'id', name: 'id'},
                    {data: 'reference_number', name: 'reference_number'},
                    {data: 'stock_type', name: 'stock_type'},
                    {data: 'warehouse_to', name: 'warehouse_to'},
                    {data: 'warehouse_from', name: 'warehouse_from'},
                    {data: 'noof_product', name: 'noof_product'},
                    {data: 'sent_date', name: 'sent_date'},
                    {data: 'received_date', name: 'received_date'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
            $('#reference_number').on('keyup', function (e) {
                dataTable.draw();
                e.preventDefault();
                updateChart();
            });
            $('#status, #warehouse, #stock_type').on('change', function (e) {
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
          
        </script>
    </div>
</x-admin.app-layout>