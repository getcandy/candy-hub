@extends('hub::layout', [
    'title' => 'Order',
])

@section('side_menu')
    @include('hub::order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Processing</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('content')
    <candy-order-edit id="{{ $id }}"></candy-order-edit>
@endsection