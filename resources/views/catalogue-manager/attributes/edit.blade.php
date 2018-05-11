@extends('hub::layout')

@section('side_menu')
    @include('hub::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Settings</small>
    <h1>@verbatim<template v-if="title">{{ title }}</template>@endverbatim</h1>
@endsection

@section('header_actions')
    <candy-button style="display: inline-block;" event="save-attribute">Save Attribute</candy-button>
@stop


@section('content')
    <candy-attribute-edit id="{{ $id }}"></candy-attribute-edit>
@endsection