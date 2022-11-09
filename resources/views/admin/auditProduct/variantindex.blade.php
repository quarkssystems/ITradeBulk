{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __($pageTitle) }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.product.variantgrid')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('footerData')

@stop
