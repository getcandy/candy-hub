@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>GetCandy</small>
    <h1>Dashboard</h1>
@endsection

@section('header_actions')
    <button class="btn btn-success"><i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Product</button>
    <button class="btn btn-default white product-menu btn-pop-over"><span class="hamburger"></span></button>
    <!-- Menu Pop Over -->
    <div class="pop-over">
      <ul class="menu">
        <li><a href="#" title="Duplicate product">Duplicate</a></li>
        <li><a href="#" title="View product on live site">View</a></li>
      </ul>
    </div>
    <button class="btn btn-default white"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
@endsection

@section('content')

    <div class="row">
    <div class="col-md-12 text-center">
        <div class="alert alert-warning">
            Test Data
        </div>
    </div>
        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Sales this week</h3>
                </header>
                <div class="panel-body">
                    <span class="dashboard-figure">&pound;{{ number_format($sales_total, 2) }}</span>
                </div>
                <footer class="panel-footer">
                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup> 81.54%</span> from last week
                </footer>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Total Orders</h3>
                </header>
                <div class="panel-body">
                    <span class="dashboard-figure">{{ $order_count }}</span>
                </div>
                <footer class="panel-footer">
                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup> 10%</span> from last week
                </footer>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Taxes</h3>
                </header>
                <div class="panel-body">
                    <span class="dashboard-figure">&pound;{{ number_format($tax_total, 2) }}</span>
                </div>
                <footer class="panel-footer">
                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup> 80.42%</span> from last week
                </footer>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Abandoned / Live Carts</h3>
                </header>
                <div class="panel-body">
                    <span class="dashboard-figure">{{ $basket_count }}</span>
                </div>
                <footer class="panel-footer">
                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup> 296.67%</span> from last week
                </footer>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Recent Orders</h3>
                </header>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders as $order)
                            <tr>
                                <td><span class="label label-success">Complete</span></td>
                                <td><a href="#">#GWf42dGS</a></td>
                                <td>Guest</td>
                                <td>23/12/2017</td>
                                <td>&pound;2,300.00</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Orders / Sales</h3>
                </header>
                <div class="panel-body">
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Active products</h3>
                </header>
                <div class="panel-body">
                    <h4 class="no-mar">{{ $product_count }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Out of stock</h3>
                </header>
                <div class="panel-body">
                    <h4 class="no-mar">92</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Registered Customers</h3>
                </header>
                <div class="panel-body">
                    <h4 class="no-mar">{{ $user_count }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Categories</h3>
                </header>
                <div class="panel-body">
                    <h4 class="no-mar">{{ $category_count }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Channels</h3>
                </header>
                <div class="panel-body">
                    <h4 class="no-mar">{{ $channel_count }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script>
    var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [{
                    label: "Orders",
                    backgroundColor: '#E7028C',
                    borderColor: '#E7028C',
                    data: [
                        10, 3, 10, 20, 15, 36, 40, 16, 10, 5, 15, 0
                    ],
                    fill: false,
                },
                {
                    label: "Baskets",
                    backgroundColor: '#333A8F',
                    borderColor: '#333A8F',
                    data: [
                        25, 18, 23, 50, 30, 45, 50, 40, 15, 12, 19, 10
                    ],
                    fill: false,
                },
                {
                    label: "Transactions",
                    backgroundColor: '#961B8E',
                    borderColor: '#961B8E',
                    data: [
                        10, 2, 10, 20, 14, 36, 40, 16, 10, 5, 0, 0
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

        document.getElementById('randomizeData').addEventListener('click', function() {
            config.data.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });

            });

            window.myLine.update();
        });

        var colorNames = Object.keys(window.chartColors);
        document.getElementById('addDataset').addEventListener('click', function() {
            var colorName = colorNames[config.data.datasets.length % colorNames.length];
            var newColor = window.chartColors[colorName];
            var newDataset = {
                label: 'Dataset ' + config.data.datasets.length,
                backgroundColor: newColor,
                borderColor: newColor,
                data: [],
                fill: false
            };

            for (var index = 0; index < config.data.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());
            }

            config.data.datasets.push(newDataset);
            window.myLine.update();
        });

        document.getElementById('addData').addEventListener('click', function() {
            if (config.data.datasets.length > 0) {
                var month = MONTHS[config.data.labels.length % MONTHS.length];
                config.data.labels.push(month);

                config.data.datasets.forEach(function(dataset) {
                    dataset.data.push(randomScalingFactor());
                });

                window.myLine.update();
            }
        });

        document.getElementById('removeDataset').addEventListener('click', function() {
            config.data.datasets.splice(0, 1);
            window.myLine.update();
        });

        document.getElementById('removeData').addEventListener('click', function() {
            config.data.labels.splice(-1, 1); // remove the label first

            config.data.datasets.forEach(function(dataset, datasetIndex) {
                dataset.data.pop();
            });

            window.myLine.update();
        });
</script>
@endsection