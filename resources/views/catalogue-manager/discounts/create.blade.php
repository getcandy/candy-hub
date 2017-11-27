@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Create Discount</h1>
@endsection

@section('content')
<candy-tabs initial="productdetails">
    <candy-tab name="Create discount" :selected="true" dispatch="product-details">
        <div class="tab-content sub-content section block" style="width:1500px;">
            <div class="row">
                <div class="col-md-12">
                    <candy-create-discount></candy-create-discount>
                </div>
            </div>
        </div>
    </candy-tab>
</candy-tabs>
@stop

