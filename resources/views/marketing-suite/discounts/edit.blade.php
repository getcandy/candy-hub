@extends('layout')

@section('side_menu')
    @include('marketing-suite.partials.side-menu')
@endsection

@section('header_title')
    <small>Marketing Suite</small>
    <h1>Create Discount</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;" event="save-discount">Save Discount</candy-button>
@stop

@section('content')
    <candy-edit-discount id="{{ $id }}"></candy-edit-discount>
@stop

