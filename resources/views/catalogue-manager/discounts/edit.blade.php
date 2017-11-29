@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Create Discount</h1>
@endsection

@section('content')
    <candy-edit-discount id="{{ $id }}"></candy-edit-discount>
@stop

