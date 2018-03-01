@extends('layout')

@section('side_menu')
    @include('hub::order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Processing</small>
    <h1>Shipping Zones</h1>
@endsection

@section('header_actions')
    <candy-shipping-zone-create style="display: inline-block;"></candy-shipping-create>
@endsection

@section('content')
    <candy-shipping-zones-table></candy-shipping-zones-table>
@endsection