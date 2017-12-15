@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Management</small>
    <h1>Orders</h1>
@endsection

@section('header_actions')
    <button class="btn btn-default white">Export</button>
    <button class="btn btn-default white">Import</button>
    <candy-product-create style="display: inline-block;"></candy-product-create>
@endsection

@section('content')
    <candy-orders-table></candy-orders-table>
@endsection