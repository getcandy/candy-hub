@extends('hub::layout', [
    'title' => 'Sales Report',
])

@section('side_menu')
    @include('hub::reports.partials.side-menu')
@endsection

@section('header_title')
    <small>Reports</small>
    <h1>Product Attribute Report</h1>
@endsection

@section('content')
  <candy-product-attributes-report from="{{ app()->request->from }}" to="{{ app()->request->to }}" />
@endsection