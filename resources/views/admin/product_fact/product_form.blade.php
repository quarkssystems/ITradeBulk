{{-- /**
 * Created by MMS.
 * User: manan
 * Date: 11/12/2020
 */ --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            <a href="{{ route("admin.product-list",[$user_id]) }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')

    <div class="col-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @if (isset($product->id))
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        {{-- <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab"
                                href="#basic_details" role="tab" aria-controls="tabs-icons-text-1"
                                aria-selected="true">Basic</a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab"
                                href="#add_variants" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">
                                Variants</a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab"
                                href="#combine_product" role="tab" aria-controls="tabs-icons-text-2"
                                aria-selected="false">Combine Product</a>
                        </li> --}}
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">


                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="basic_details" role="tabpanel"
                            aria-labelledby="tabs-icons-text-1-tab">

                            @if (isset($product->id))
                                {!! Form::model($product, [
                                    'route' => ["admin.supplier-product-update", $product->uuid],
                                    'method' => 'PUT',
                                    'id' => 'form',
                                    'autocomplete' => 'off',
                                    'name' => 'usersForm',
                                    'files' => true,
                                ]) !!}
                            @else
                                {!! Form::model($product, [
                                    'route' => ["$route.store"],
                                    'id' => 'form',
                                    'autocomplete' => 'off',
                                    'name' => 'usersForm',
                                    'files' => true,
                                ]) !!}
                            @endif
                            <input autocomplete="off" name="hidden" type="text" style="display: none">

                            @if (isset($product->id))
                                {!! Form::hidden('uuid', $product->uuid) !!}
                                {!! Form::hidden('user_id', $user_id) !!}
                                
                            @endif
                            {{-- <div class="row">
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <a class="" href="#productBasicDetails" data-toggle="collapse"
                                        data-target="#productBasicDetails" aria-expanded="true">
                                        {{ __('BASIC DETAILS') }}
                                    </a>
                                    <hr>
                                </div>
                                
                            </div> --}}


                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('name', 'Name', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('name', null, [
                                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Name',
                                    ]) !!}

                                    @if ($errors->has('name'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('slug', 'Slug', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('slug', null, [
                                        'class' => 'form-control' . ($errors->has('slug') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Slug',
                                    ]) !!}

                                    @if ($errors->has('slug'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('barcode', 'Barcode', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('barcode', null, [
                                        'class' => 'form-control' . ($errors->has('barcode') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Barcode',
                                    ]) !!}

                                    @if ($errors->has('barcode'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('barcode') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('brand_id', 'Brand', ['class' => 'form-label label-required']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label label-required']) !!} --}}
                                    {!! Form::select('brand_id', $brands, null, [
                                        'class' => 'form-control' . ($errors->has('brand_id') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('brand_id'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('brand_id') }}</strong>
                                        </small>
                                    @endif
                                </div>
                               
                                {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('autoprice', 'Autoprice', ['class' => 'form-label ']) !!}
                                    {!! Form::text('autoprice', null, [
                                        'class' => 'form-control' . ($errors->has('autoprice') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Autoprice',
                                    ]) !!}

                                    @if ($errors->has('autoprice'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('autoprice') }}</strong>
                                        </small>
                                    @endif
                                </div> --}}
                            </div>

                           <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('base_image', 'Image File Name', ['class' => 'form-label ']) !!}
                                {!! Form::file('base_image', [
                                    'class' => 'form-control dropify ' . ($errors->has('base_image') ? ' is-invalid' : ''),
                                    'data-default-file' => isset($product->id) ? $product->base_image : '',
                                ]) !!}
                                <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                @if ($errors->has('base_image'))
                                    <br><span class="help-block text-danger">
                                        <strong>{{ $errors->first('base_image') }}</strong>
                                    </span>
                                @endif
                            </div>
                                {{--  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('price', 'Price', ['class' => 'form-label ']) !!}
                                    {!! Form::text('price', null, [
                                        'class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Price',
                                    ]) !!}

                                    @if ($errors->has('price'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('quantity', 'Quantity', ['class' => 'form-label ']) !!}
                                    {!! Form::text('quantity', null, [
                                        'class' => 'form-control' . ($errors->has('quantity') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Quantity',
                                    ]) !!}

                                    @if ($errors->has('quantity'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('min_order_quantity', 'Min Order Quantity', ['class' => 'form-label ']) !!}
                                    {!! Form::text('min_order_quantity', null, [
                                        'class' => 'form-control' . ($errors->has('min_order_quantity') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Min Order Quantity',
                                    ]) !!}

                                    @if ($errors->has('min_order_quantity'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('min_order_quantity') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('stock_expiry_date', 'Stock Expiry Date', ['class' => 'form-label ']) !!}
                                    {!! Form::date('stock_expiry_date', null, [
                                        'class' => 'form-control' . ($errors->has('stock_expiry_date') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Stock Expiry Date',
                                    ]) !!}

                                    @if ($errors->has('stock_expiry_date'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('stock_expiry_date') }}</strong>
                                        </small>
                                    @endif
                                </div>--}}
                            </div> 

                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                                    {!! Form::submit('Save & Continue', [
                                        'type' => 'submit',
                                        'class' => 'btn btn-success',
                                        'name' => 'save_continue',
                                    ]) !!}
                                    @if (!isset($product->id))
                                        {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                                    @endif
                                </div>
                                <div class="col-xs-12 col-lg-12">
                                    <small><i><label class=""></label>
                                            {{ __('') }}</i></small>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>



                        <!-- card-body2 starts -->
                    </div>
                </div>
            </div>
        @endsection
        {{-- {{ dd($subCategoryId) }} --}}
        @section('footerData')
        @endsection