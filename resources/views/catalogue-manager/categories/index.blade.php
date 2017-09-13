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
    <button class="btn btn-success" data-toggle="modal" data-target="#createCategory"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Create Category</button>
@endsection

@section('content')

    <categories-list></categories-list>
    <candy-categories-modals></candy-categories-modals>

@endsection