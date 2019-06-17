@extends('hub::layout', [
    'title' => 'Dashboard',
])

@section('side_menu')
    @include('hub::order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>GetCandy</small>
    <h1>Dashboard</h1>
@endsection

@section('content')
    <candy-dashboard-metrics></candy-dashboard-metrics>
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title">Orders totals for last 6 months</h3>
                </header>
                <div class="panel-body">
                    <candy-orders-report initial-style="bar" from="{{ now()->subMonths(6) }}" to="{{ now() }}" :show-controls="false" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <header class="panel-heading">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 class="panel-title">Orders / Sales Report</h3>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('hub.reports.index') }}">View full report</a>
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <candy-sales-report :show-controls="false" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection