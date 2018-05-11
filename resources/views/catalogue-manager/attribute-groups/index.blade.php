@extends('hub::layout')

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Attribute Groups</h1>
@endsection

@section('header_actions')
    <candy-attribute-group-create></candy-attribute-group-create>
@stop

@section('content')
    <candy-attribute-groups-table></candy-attribute-groups-table>
@endsection