@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Collections</h1>
@endsection

@section('header_actions')
    <candy-disabled pos="bottom">
      <button class="btn btn-default white">Export</button>
      <button class="btn btn-default white">Import</button>
    </candy-disabled>
    <candy-collection-create style="display: inline-block;"></candy-collection-create>
@endsection

@section('content')

        <candy-collections-table></candy-collections-table>

@endsection