@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;" event="save-product">Save Product</candy-button>
    <candy-disabled pos="bottom">
      <button class="btn btn-default white product-menu btn-pop-over"><span class="hamburger"></span></button>
    </candy-disabled>
    <!-- Menu Pop Over -->
    <div class="pop-over">
      <ul class="menu">
        <li><a href="#" title="Duplicate product">Duplicate</a></li>
        <li><a href="#" title="View product on live site">View</a></li>
      </ul>
    </div>
    <candy-delete
      element="product"
      endpoint="/products/{{ $id }}"
      id="{{ $id }}"
      redirect="/catalogue-manager/products"
      style="display: inline-block;"
    ></candy-delete>
@endsection

@section('content')
  <candy-product-edit product-id="{{ $id }}"></candy-product-edit>
@endsection