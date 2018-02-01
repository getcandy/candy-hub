@extends('layout')

@section('side_menu')
    @include('order-processing.partials.side-menu')
@endsection

@section('header_title')
    <small>Order Processing</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('content')
    <candy-customer-edit id="{{ $id }}"></candy-customer-edit>
@endsection