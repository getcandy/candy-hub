@extends('layout')

@section('side_menu')
    @include('marketing-suite.partials.side-menu')
@endsection

@section('header_title')
    <small>Marketing Suite</small>
    <h1>Discounts</h1>
@endsection

@section('header_actions')
    <candy-create-discount style="display: inline-block;"></candy-create-discount>
@endsection

@section('content')
    <candy-discounts-table></candy-discounts-table>
@endsection