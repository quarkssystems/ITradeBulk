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
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('packing', 'Packing', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    <select name="packing" id="packing" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach ($product->getStockProductAttribute() as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ $product->packing == $key ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- {!! Form::text('packing', null, [
                                        'class' => 'form-control' . ($errors->has('packing') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Packing',
                                    ]) !!} --}}

                                    @if ($errors->has('packing'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('packing') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('units_per_packing', 'Units Per Packing', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('units_per_packing', null, [
                                        'class' => 'form-control' . ($errors->has('units_per_packing') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Units Per Packing',
                                    ]) !!}

                                    @if ($errors->has('units_per_packing'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('units_per_packing') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('size', 'Size', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('size', null, [
                                        'class' => 'form-control' . ($errors->has('size') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Size',
                                    ]) !!}

                                    @if ($errors->has('size'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('size') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('unit_of_measure', 'Unit Of Measure', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    <select name="unit_of_measure" id="unit_of_measure" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach ($productUnit as $key => $type)
                                            <option value="{{ $type->unit }}"
                                                {{ $product->unit_of_measure == $type->unit ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- {!! Form::text('unit_of_measure', null, [
                                        'class' => 'form-control' . ($errors->has('unit_of_measure') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Unit Of Measure',
                                    ]) !!} --}}

                                    @if ($errors->has('unit_of_measure'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('unit_of_measure') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('size_description', 'Size Description', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('size_description', null, [
                                        'class' => 'form-control' . ($errors->has('size_description') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Size Description',
                                    ]) !!}

                                    @if ($errors->has('size_description'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('size_description') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('height', 'Height', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('height', null, [
                                        'class' => 'form-control' . ($errors->has('height') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Height',
                                    ]) !!}

                                    @if ($errors->has('height'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('height') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('width', 'Width', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('width', null, [
                                        'class' => 'form-control' . ($errors->has('width') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Width',
                                    ]) !!}

                                    @if ($errors->has('width'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('width') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('depth', 'Depth', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('depth', null, [
                                        'class' => 'form-control' . ($errors->has('depth') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Depth',
                                    ]) !!}

                                    @if ($errors->has('depth'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('depth') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('weight', 'Weight', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('weight', null, [
                                        'class' => 'form-control' . ($errors->has('weight') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Weight',
                                    ]) !!}

                                    @if ($errors->has('weight'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('weight') }}</strong>
                                        </small>
                                    @endif
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
