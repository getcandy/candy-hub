@extends('hub::layout', [
    'title' => 'Shipping Method',
])

@section('side_menu')
    @include('hub::order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Processing</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;" event="save-shipping-method">Save Shipping Method</candy-button>
@endsection

@section('content')
    <candy-shipping-method-edit id="{{ $id }}"></candy-shipping-method-edit>
@endsection