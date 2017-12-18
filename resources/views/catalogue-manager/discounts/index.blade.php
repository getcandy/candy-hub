@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Discounts</h1>
@endsection

@section('header_actions')
    <candy-create-discount style="display: inline-block;"></candy-create-discount>
@endsection

@section('content')
    <candy-discounts-table></candy-discounts-table>
@endsection