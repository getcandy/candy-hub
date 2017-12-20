@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Processing</small>
    <h1>Shipping Methods</h1>
@endsection

@section('header_actions')
    <candy-shipping-method-create style="display: inline-block;"></candy-shipping-method-create>
@endsection

@section('content')
    <candy-shipping-methods-table></candy-shipping-methods-table>
@endsection