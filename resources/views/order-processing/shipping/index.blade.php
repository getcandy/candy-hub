@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Management</small>
    <h1>Shipping</h1>
@endsection

@section('header_actions')
    <candy-shipping-create style="display: inline-block;"></candy-shipping-create>
@endsection

@section('content')
    <candy-shipping-table></candy-shipping-table>
@endsection