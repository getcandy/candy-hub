@extends('hub::layout', [
    'title' => 'Categories',
])

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Categories</h1>
@endsection

@section('header_actions')
    <candy-category-create style="display: inline-block;"></candy-category-create>
@endsection

@section('content')

    <candy-categories-list></candy-categories-list>

@endsection