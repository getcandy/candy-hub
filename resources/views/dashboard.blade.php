@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>GetCandy</small>
    <h1>Dashboard</h1>
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
                    <div class="dashboard-figure">
                        &pound;{{ number_format($sales_this_week, 2) }}<br>
                        <section style="margin-top:10px;font-size:.75em">
                            @if($sales_this_week - $sales_last_week >= 0)
                                <span class="text-success"><sup><fa icon="caret-up"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 2) }}</span>
                            @else
                                <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 2) }}</span>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Orders this week</h3>
                </header>
                <div class="panel-body">
                    <div class="dashboard-figure">
                        {{ $orders_this_week }} <br>
                        <section style="margin-top:10px;font-size:.75em">
                            @if($orders_this_week - $orders_last_week >= 0)
                                <span class="text-success"><sup><fa icon="caret-up"></fa></sup>{{ $orders_this_week - $orders_last_week }}</span>
                            @else
                                <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>{{ $orders_this_week - $orders_last_week }}</span>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Sales this month</h3>
                </header>
                <div class="panel-body">
                    <div class="dashboard-figure">
                        &pound;{{ number_format($sales_this_month,2 ) }} <br>
                        <section style="margin-top:10px;font-size:.75em">
                            @if($sales_this_month - $sales_last_month >= 0)
                                <span class="text-success"><sup><fa icon="caret-up"></fa></sup>&pound;{{ number_format($sales_this_month - $sales_last_month, 2) }}</span>
                            @else
                                <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>&pound;{{ number_format($sales_this_month - $sales_last_month, 2) }}</span>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Orders this month</h3>
                </header>
                <div class="panel-body">
                    <div class="dashboard-figure">
                        {{ $orders_this_month }} <br>
                        <section style="margin-top:10px;font-size:.75em">
                            @if($orders_this_month - $orders_last_month >= 0)
                                <span class="text-success"><sup><fa icon="caret-up"></fa></sup>{{ $orders_this_month - $orders_last_month }}</span>
                            @else
                                <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>{{ $orders_this_month - $orders_last_month }}</span>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Orders for the previous 8 weeks</h3>
                </header>
                <div class="panel-body">
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Sales for the previous 8 weeks</h3>
                </header>
                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($sales_data as $label => $data)
                        <li class="list-group-item clearfix">
                            <div class="pull-left">
                                {{ $label }}
                            </div>
                            <div class="pull-right text-right">
                                &pound;{{ number_format($data['total'], 2) }}

                                <br>
                                <small class="{{ $data['diff'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fa {{ $data['diff'] >= 0 ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                    &pound;{{ number_format($data['diff'], 2) }}
                                </small>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Recent Orders</h3>
                </header>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders as $order)
                            <tr>
                                <td><a href="#">{{ $order->ref }}</a></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>&pound;{{ number_format($order->total, 2) }}</td>
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
    </div>
    <hr>
    <!--  <div class="row">
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
    </div>-->
@endsection

@section('scripts')

<script>
        var config = {
            type: 'bar',
            data: {!! json_encode($graph_data) !!},
            options: {
                multiTooltipTemplate: "<%= datasetLabel %> - <%= value %> foo",
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
                            labelString: 'Week'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
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
</script>
@endsection