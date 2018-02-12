@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Management</small>
    <h1>Customers</h1>
@endsection

@section('header_actions')
    <candy-customer-create style="display: inline-block;"></candy-customer-create>
@endsection


@section('content')
    <candy-customers-table></candy-customers-table>
@endsection