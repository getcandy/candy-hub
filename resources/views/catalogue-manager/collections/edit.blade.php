@extends('hub::layout')

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-disabled :inline="true" pos="bottom">
      <button class="btn btn-default white product-menu btn-pop-over"><span class="hamburger"></span></button>
      <!-- Menu Pop Over -->
      <div class="pop-over">
        <ul class="menu">
          <li><a href="#" title="Duplicate product">Duplicate</a></li>
          <li><a href="#" title="View product on live site">View</a></li>
        </ul>
      </div>
    </candy-disabled>
    <candy-delete
      element="collection"
      endpoint="/collections/{{ $id }}"
      id="{{ $id }}"
      redirect="/catalogue-manager/collections"
      style="display: inline-block;"
    ></candy-delete>

    <candy-button style="display: inline-block;" event="save-collection">Save Collection</candy-button>

@endsection

@section('content')
  <candy-collection-edit id="{{ $id }}"></candy-collection-edit>
@endsection