@extends('hub::layout')

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;" event="save-attribute">Save Product Family</candy-button>
@stop


@section('content')
    <candy-product-family-edit id="{{ $id }}"></candy-product-family-edit>
@endsection