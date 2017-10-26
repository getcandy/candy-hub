@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Categories</h1>
@endsection

@section('header_actions')
    <button class="btn btn-default white">Export</button>
    <button class="btn btn-default white">Import</button>
    <candy-category-create style="display: inline-block;"></candy-category-create>
@endsection

@section('content')

    <candy-categories-list></candy-categories-list>

@endsection