@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Management</small>
    <h1>Orders</h1>
@endsection

@section('content')
    <candy-orders-table></candy-orders-table>
@endsection