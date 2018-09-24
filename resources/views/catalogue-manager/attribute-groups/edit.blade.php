@extends('hub::layout', [
    'title' => 'Edit Attribute Group',
])

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
    <candy-attribute-groups-edit id="{{ $id }}"></candy-attribute-groups-edit>
@endsection