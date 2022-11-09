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
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>
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
                                    'route' => ["$route.update", $product->uuid],
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
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('has_upc', 'Has UPC', ['class' => 'form-label ']) !!}
                                            {!! Form::text('has_upc', null, [
                                                'class' => 'form-control' . ($errors->has('has_upc') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Has UPC',
                                            ]) !!}

                                            @if ($errors->has('has_upc'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('has_upc') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('barcode', 'Barcode', ['class' => 'form-label ']) !!}
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

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('product_code', 'Product Code', ['class' => 'form-label ']) !!}
                                            {!! Form::text('product_code', null, [
                                                'class' => 'form-control' . ($errors->has('product_code') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Product Code',
                                            ]) !!}

                                            @if ($errors->has('product_code'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('product_code') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('store_item_code', 'Store Item Code', ['class' => 'form-label ']) !!}
                                            {!! Form::text('store_item_code', null, [
                                                'class' => 'form-control' . ($errors->has('store_item_code') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Store Item Code',
                                            ]) !!}

                                            @if ($errors->has('store_item_code'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('store_item_code') }}</strong>
                                                </small>
                                            @endif
                                        </div> --}}

                                    </div>

                                </div>
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
