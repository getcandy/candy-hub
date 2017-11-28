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
    <candy-tab name="Basic information" :selected="true" dispatch="product-details">
        <div class="tab-content sub-content section block">
            <div class="row">
                <div class="col-md-12">
                    <candy-create-discount></candy-create-discount>
                </div>
            </div>
        </div>
    </candy-tab>
    <candy-tab name="Conditions">
        <div class="tab-content sub-content section block">
            <div class="row">
                <div class="col-md-12">
                    Conditions
                </div>
            </div>
        </div>
    </candy-tab>
    <candy-tab name="Rewards">
        <div class="tab-content sub-content section block">
            <div class="row">
                <div class="col-md-12">
                    Rewards
                </div>
            </div>
        </div>
    </candy-tab>
</candy-tabs>
@stop

