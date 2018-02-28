@extends('hub::layout')

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;">Save Product</candy-button>
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