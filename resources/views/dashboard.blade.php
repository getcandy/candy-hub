@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>GetCandy</small>
    <h1>Dashboard</h1>
@endsection

@section('header_actions')
    <button class="btn btn-success"><i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Product</button>
    <button class="btn btn-default white product-menu btn-pop-over"><span class="hamburger"></span></button>
    <!-- Menu Pop Over -->
    <div class="pop-over">
      <ul class="menu">
        <li><a href="#" title="Duplicate product">Duplicate</a></li>
        <li><a href="#" title="View product on live site">View</a></li>
      </ul>
    </div>
    <button class="btn btn-default white"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
@endsection

@section('content')

    <h1>Dashboard</h1>

@endsection 