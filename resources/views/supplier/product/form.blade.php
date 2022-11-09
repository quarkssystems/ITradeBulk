@extends('supplier.layouts.main')

@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{ $pageTitle }}</h4>
            </div>
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
            <div class="card">
                <div class="card-body">
                    <div id="basic_details">

                        @if (isset($product->id) && (isset($copy) && !$copy))
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

                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <a class="" href="#productBasicDetails" data-toggle="collapse"
                                    data-target="#productBasicDetails" aria-expanded="true">
                                    {{ __('BASIC DETAILS') }}
                                </a>
                                <!-- <hr> -->
                            </div>
                        </div>

                        <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text('name', null, [
                                            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                            'autofocus',
                                            'placeholder' => 'Name',
                                            isset($product->id) ? 'readonly' : '',
                                        ]) !!}

                                        @if ($errors->has('name'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text('slug', null, [
                                            'class' => 'form-control' . ($errors->has('slug') ? ' is-invalid' : ''),
                                            'autofocus',
                                            'placeholder' => 'Slug',
                                            isset($product->id) ? 'readonly' : '',
                                        ]) !!}

                                        @if ($errors->has('slug'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('slug') }}</strong>
                                            </small>
                                        @endif
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('brand_id', 'Brand', ['class' => 'form-label label-required']) !!}
                                        {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label label-required']) !!} --}}
                                        {!! Form::select('brand_id', $brands, null, [
                                            'class' => 'form-control' . ($errors->has('brand_id') ? ' is-invalid' : ''),
                                            'autofocus',
                                            isset($product->id) ? 'disabled' : '',
                                        ]) !!}

                                        @if ($errors->has('brand_id'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('brand_id') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('arrival_type', 'Arrival Type', ['class' => 'form-label label-required']) !!}
                                        <select name="arrival_type" id="arrival_type" class="form-control"
                                            @if ($product->id) 'disabled' @endif>
                                            <?php foreach ($arrival as $key => $value) { ?>
                                            <option value="<?php echo $value->id; ?>"
                                                {{ isset($product->arrival_type) && $product->arrival_type == $value->id ? 'selected' : '' }}>
                                                <?php echo $value->name; ?></option>
                                            <?php }?>
                                        </select>

                                        @if ($errors->has('arrival_type'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('arrival_type') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <hr>


                                <div class="row">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#productCatrgory" data-toggle="collapse"
                                            data-target="#productCatrgory" aria-expanded="false">
                                            {{ __('CATEGORIES') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row collapse" id="productCatrgory" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <ul>
                                                    @foreach ($categories as $category)
                                                        {!! $category->getCategoryHierarchy($category, $selectedCategories) !!}
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#productMediaDetails" data-toggle="collapse"
                                            data-target="#productMediaDetails" aria-expanded="false">
                                            {{ __('MEDIA') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row collapse" id="productMediaDetails" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                                {!! Form::file('base_image_file', [
                                                    'class' => 'form-control dropify ' . ($errors->has('base_image_file') ? ' is-invalid' : ''),
                                                    'data-default-file' => isset($product->id) && (isset($copy) && !$copy) ? $product->base_image : '',
                                                ]) !!}
                                                <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                                @if ($errors->has('base_image_file'))
                                                    <br>
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('base_image_file') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>


                                <div class="row">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#variants" data-toggle="collapse" data-target="#variants"
                                            aria-expanded="false"> {{ __('ADD VARIANTS') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>

                                <div class="row" id="variants">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#single" data-toggle="collapse" data-target="#single"
                                            aria-expanded="false"> {{ __('SINGLE') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row" id="single" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'single_barcode',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['barcode'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_barcode') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Barcode',
                                                        isset($product->id) && (isset($single) && !empty($single)) ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_barcode'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_barcode') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_price', 'Base price', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'single_base_price',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['base_price'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_base_price') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_base_price'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_base_price') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('single_stock_type', 'single', [
                                                    'class' => 'form-control' . ($errors->has('single_stock_type') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'readonly',
                                                ]) !!}

                                                @if ($errors->has('single_stock_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_stock_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                                {!! Form::text(
                                                    'single_stock_of',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['stock_of'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_stock_of') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_stock_of'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_stock_of') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('default_stock_type', 'Default Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'single_default_stock_type',
                                                    $defaultStockType,
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['default_stock_type'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_default_stock_type') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'single_stoc_wt',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['stoc_wt'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_stoc_wt') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_stoc_wt'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_stoc_wt') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'single_stock_gst',
                                                    $productgst,
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['stock_gst'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_stock_gst') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'single_tax_id',
                                                    $taxes,
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['tax_id'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_tax_id') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_tax_id'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_tax_id') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit', 'Unit', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::text(
                                                            'single_unit_value',
                                                            isset($product->id) && (isset($single) && !empty($single)) ? $single['unit_value'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_value' . ($errors->has('single_unit_value') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                'placeholder' => 'Value',
                                                                isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('single_unit_value'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('single_unit_value') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit_data', 'UOM', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::select(
                                                            'single_unit_data',
                                                            $productUnits,
                                                            isset($product->id) && (isset($single) && !empty($single)) ? $single['unit'] . '|' . $single['unit_name'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_name ' . ($errors->has('single_unit_data') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'disabled' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('single_unit_data'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('single_unit_data') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                                {!! Form::select("single_status",$statuses, null,["class"=>"form-control".($errors->has('single_status')?" is-invalid":""),"autofocus","disabled"])!!}

                                                @if ($errors->has('single_status'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_status') }}</strong>
                                                    </small>
                                                @endif
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'single_description',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'single_short_description',
                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['short_description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('single_short_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Short description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('single_short_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('single_short_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <a class="collapsed" href="#single_productMediaDetails"
                                                    data-toggle="collapse" data-target="#single_productMediaDetails"
                                                    aria-expanded="false"> {{ __('MEDIA') }}</a>
                                                <!-- <hr> -->
                                            </div>
                                        </div>
                                        <div class="row" id="single_productMediaDetails" aria-expanded="false">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                        {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                                        {!! Form::file('single_base_image_file', [
                                                            'class' => 'form-control dropify ' . ($errors->has('single_base_image_file') ? ' is-invalid' : ''),
                                                            'data-default-file' => isset($product->id) && (isset($single) && !empty($single)) ? $single['base_image'] : '',
                                                        ]) !!}
                                                        <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                                        @if ($errors->has('single_base_image_file'))
                                                            <br>
                                                            <span class="help-block text-danger">
                                                                <strong>{{ $errors->first('single_base_image_file') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <a class="collapsed" href="#productSeoDetailsSingle"
                                                            data-toggle="collapse" data-target="#productSeoDetailsSingle"
                                                            aria-expanded="false"> {{ __('SEO') }} </a>
                                                    </div>
                                                </div>
                                                <div class="row" id="productSeoDetailsSingle" aria-expanded="false">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'single_meta_description',
                                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['meta_description'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('single_meta_description') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta description',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('single_meta_description'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('single_meta_description') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'single_meta_keyword',
                                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['meta_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('single_meta_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta keywords',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('single_meta_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('single_meta_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'single_meta_title',
                                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['meta_title'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('single_meta_title') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta title',
                                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('single_meta_title'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('single_meta_title') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                                                {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'single_search_keyword',
                                                                    isset($product->id) && (isset($single) && !empty($single)) ? $single['search_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('single_search_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Search keyword',
                                                                        isset($product->id) && (isset($single) && !empty($single) && $single->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('single_search_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('single_search_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr>

                                <div class="row" id="variants">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#Shrink" data-toggle="collapse" data-target="#Shrink"
                                            aria-expanded="false"> {{ __('SHRINK') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row" id="shrink" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'shrink_barcode',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['barcode'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_barcode') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Barcode',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink)) ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_barcode'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_barcode') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_price', 'Base price', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'shrink_base_price',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['base_price'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_base_price') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_base_price'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_base_price') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('shrink_stock_type', 'shrink', [
                                                    'class' => 'form-control' . ($errors->has('shrink_stock_type') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'readonly',
                                                ]) !!}

                                                @if ($errors->has('shrink_stock_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_stock_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                                {!! Form::text(
                                                    'shrink_stock_of',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['stock_of'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_stock_of') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_stock_of'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_stock_of') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('default_stock_type', 'Default Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'shrink_default_stock_type',
                                                    $defaultStockType,
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['default_stock_type'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_default_stock_type') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'shrink_stoc_wt',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['stoc_wt'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_stoc_wt') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_stoc_wt'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_stoc_wt') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'shrink_stock_gst',
                                                    $productgst,
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['stock_gst'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_stock_gst') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'shrink_tax_id',
                                                    $taxes,
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['tax_id'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_tax_id') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_tax_id'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_tax_id') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit', 'Unit', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::text(
                                                            'shrink_unit_value',
                                                            isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['unit_value'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_value' . ($errors->has('shrink_unit_value') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                'placeholder' => 'Value',
                                                                isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('shrink_unit_value'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('shrink_unit_value') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit_data', 'UOM', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::select(
                                                            'shrink_unit_data',
                                                            $productUnits,
                                                            isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['unit'] . '|' . $shrink['unit_name'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_name ' . ($errors->has('shrink_unit_data') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'disabled' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('shrink_unit_data'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('shrink_unit_data') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                                {!! Form::select("shrink_status",$statuses, null,["class"=>"form-control".($errors->has('shrink_status')?" is-invalid":""),"autofocus","disabled"])!!}

                                                @if ($errors->has('shrink_status'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_status') }}</strong>
                                                    </small>
                                                @endif
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'shrink_description',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'shrink_short_description',
                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['short_description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('shrink_short_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Short description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('shrink_short_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('shrink_short_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <a class="collapsed" href="#shrink_productMediaDetails"
                                                    data-toggle="collapse" data-target="#shrink_productMediaDetails"
                                                    aria-expanded="false"> {{ __('MEDIA') }}</a>
                                                <!-- <hr> -->
                                            </div>
                                        </div>
                                        <div class="row" id="shrink_productMediaDetails" aria-expanded="false">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                        {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                                        {!! Form::file('shrink_base_image_file', [
                                                            'class' => 'form-control dropify ' . ($errors->has('shrink_base_image_file') ? ' is-invalid' : ''),
                                                            'data-default-file' => isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['base_image'] : '',
                                                        ]) !!}
                                                        <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                                        @if ($errors->has('shrink_base_image_file'))
                                                            <br>
                                                            <span class="help-block text-danger">
                                                                <strong>{{ $errors->first('shrink_base_image_file') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <a class="collapsed" href="#productSeoDetailsSingle"
                                                            data-toggle="collapse" data-target="#productSeoDetailsSingle"
                                                            aria-expanded="false"> {{ __('SEO') }} </a>
                                                    </div>
                                                </div>
                                                <div class="row" id="productSeoDetailsSingle" aria-expanded="false">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'shrink_meta_description',
                                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['meta_description'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('shrink_meta_description') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta description',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('shrink_meta_description'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('shrink_meta_description') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'shrink_meta_keyword',
                                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['meta_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('shrink_meta_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta keywords',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('shrink_meta_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('shrink_meta_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'shrink_meta_title',
                                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['meta_title'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('shrink_meta_title') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta title',
                                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('shrink_meta_title'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('shrink_meta_title') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                                                {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'shrink_search_keyword',
                                                                    isset($product->id) && (isset($shrink) && !empty($shrink)) ? $shrink['search_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('shrink_search_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Search keyword',
                                                                        isset($product->id) && (isset($shrink) && !empty($shrink) && $shrink->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('shrink_search_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('shrink_search_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr>

                                <div class="row" id="variants">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#case" data-toggle="collapse" data-target="#case"
                                            aria-expanded="false"> {{ __('CASE') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row" id="case" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('case_barcode', isset($product->id) && (isset($case) && !empty($case)) ? $case['barcode'] : '', [
                                                    'class' => 'form-control' . ($errors->has('case_barcode') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'placeholder' => 'Barcode',
                                                    isset($product->id) && (isset($case) && !empty($case)) ? 'readonly' : '',
                                                ]) !!}

                                                @if ($errors->has('case_barcode'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_barcode') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_price', 'Base price', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'case_base_price',
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['base_price'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_base_price') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('case_base_price'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_base_price') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('case_stock_type', 'case', [
                                                    'class' => 'form-control' . ($errors->has('case_stock_type') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'readonly',
                                                ]) !!}

                                                @if ($errors->has('case_stock_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_stock_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                                {!! Form::text(
                                                    'case_stock_of',
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['stock_of'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_stock_of') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('case_stock_of'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_stock_of') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('default_stock_type', 'Default Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'case_default_stock_type',
                                                    $defaultStockType,
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['default_stock_type'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_default_stock_type') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('case_stoc_wt', isset($product->id) && (isset($case) && !empty($case)) ? $case['stoc_wt'] : '', [
                                                    'class' => 'form-control' . ($errors->has('case_stoc_wt') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                ]) !!}

                                                @if ($errors->has('case_stoc_wt'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_stoc_wt') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'case_stock_gst',
                                                    $productgst,
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['stock_gst'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_stock_gst') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'case_tax_id',
                                                    $taxes,
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['tax_id'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_tax_id') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('case_tax_id'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_tax_id') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit', 'Unit', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::text(
                                                            'case_unit_value',
                                                            isset($product->id) && (isset($case) && !empty($case)) ? $case['unit_value'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_value' . ($errors->has('case_unit_value') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                'placeholder' => 'Value',
                                                                isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('case_unit_value'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('case_unit_value') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit_data', 'UOM', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::select(
                                                            'case_unit_data',
                                                            $productUnits,
                                                            isset($product->id) && (isset($case) && !empty($case)) ? $case['unit'] . '|' . $case['unit_name'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_name ' . ($errors->has('case_unit_data') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'disabled' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('case_unit_data'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('case_unit_data') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                                {!! Form::select("case_status",$statuses, null,["class"=>"form-control".($errors->has('case_status')?" is-invalid":""),"autofocus","disabled"])!!}

                                                @if ($errors->has('case_status'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_status') }}</strong>
                                                    </small>
                                                @endif
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'case_description',
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('case_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'case_short_description',
                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['short_description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('case_short_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Short description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('case_short_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('case_short_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <a class="collapsed" href="#case_productMediaDetails"
                                                    data-toggle="collapse" data-target="#case_productMediaDetails"
                                                    aria-expanded="false"> {{ __('MEDIA') }}</a>
                                                <!-- <hr> -->
                                            </div>
                                        </div>
                                        <div class="row" id="case_productMediaDetails" aria-expanded="false">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                        {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                                        {!! Form::file('case_base_image_file', [
                                                            'class' => 'form-control dropify ' . ($errors->has('case_base_image_file') ? ' is-invalid' : ''),
                                                            'data-default-file' => isset($product->id) && (isset($case) && !empty($case)) ? $case['base_image'] : '',
                                                        ]) !!}
                                                        <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                                        @if ($errors->has('case_base_image_file'))
                                                            <br>
                                                            <span class="help-block text-danger">
                                                                <strong>{{ $errors->first('case_base_image_file') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <a class="collapsed" href="#productSeoDetailsSingle"
                                                            data-toggle="collapse" data-target="#productSeoDetailsSingle"
                                                            aria-expanded="false"> {{ __('SEO') }} </a>
                                                    </div>
                                                </div>
                                                <div class="row" id="productSeoDetailsSingle" aria-expanded="false">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'case_meta_description',
                                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['meta_description'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('case_meta_description') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta description',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('case_meta_description'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('case_meta_description') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'case_meta_keyword',
                                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['meta_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('case_meta_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta keywords',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('case_meta_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('case_meta_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'case_meta_title',
                                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['meta_title'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('case_meta_title') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta title',
                                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('case_meta_title'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('case_meta_title') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                                                {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'case_search_keyword',
                                                                    isset($product->id) && (isset($case) && !empty($case)) ? $case['search_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('case_search_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Search keyword',
                                                                        isset($product->id) && (isset($case) && !empty($case) && $case->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('case_search_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('case_search_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr>

                                <div class="row" id="variants">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <a class="collapsed" href="#pallets" data-toggle="collapse"
                                            data-target="#pallets" aria-expanded="false"> {{ __('PALLETS') }}</a>
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row" id="pallets" aria-expanded="false">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'pallets_barcode',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['barcode'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_barcode') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Barcode',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets)) ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_barcode'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_barcode') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_price', 'Base price', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'pallets_base_price',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['base_price'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_base_price') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_base_price'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_base_price') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text('pallets_stock_type', 'pallets', [
                                                    'class' => 'form-control' . ($errors->has('pallets_stock_type') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'readonly',
                                                ]) !!}

                                                @if ($errors->has('pallets_stock_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_stock_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                                {!! Form::text(
                                                    'pallets_stock_of',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['stock_of'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_stock_of') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_stock_of'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_stock_of') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('default_stock_type', 'Default Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'pallets_default_stock_type',
                                                    $defaultStockType,
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['default_stock_type'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_default_stock_type') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text(
                                                    'pallets_stoc_wt',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['stoc_wt'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_stoc_wt') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_stoc_wt'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_stoc_wt') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label']) !!}
                                                {!! Form::select(
                                                    'pallets_stock_gst',
                                                    $productgst,
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['stock_gst'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_stock_gst') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select(
                                                    'pallets_tax_id',
                                                    $taxes,
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['tax_id'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_tax_id') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'disabled' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_tax_id'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_tax_id') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit', 'Unit', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::text(
                                                            'pallets_unit_value',
                                                            isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['unit_value'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_value' . ($errors->has('pallets_unit_value') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                'placeholder' => 'Value',
                                                                isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('pallets_unit_value'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('pallets_unit_value') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('unit_data', 'UOM', ['class' => 'form-label label-required']) !!}
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        {!! Form::select(
                                                            'pallets_unit_data',
                                                            $productUnits,
                                                            isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['unit'] . '|' . $pallets['unit_name'] : '',
                                                            [
                                                                'class' => 'form-control product_unit_name ' . ($errors->has('pallets_unit_data') ? ' is-invalid' : ''),
                                                                'autofocus',
                                                                isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'disabled' : '',
                                                            ],
                                                        ) !!}

                                                        @if ($errors->has('pallets_unit_data'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('pallets_unit_data') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                                {!! Form::select("pallets_status",$statuses, null,["class"=>"form-control".($errors->has('pallets_status')?" is-invalid":""),"autofocus","disabled"])!!}

                                                @if ($errors->has('pallets_status'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_status') }}</strong>
                                                    </small>
                                                @endif
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'pallets_description',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                                {!! Form::textarea(
                                                    'pallets_short_description',
                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['short_description'] : '',
                                                    [
                                                        'class' => 'form-control' . ($errors->has('pallets_short_description') ? ' is-invalid' : ''),
                                                        'autofocus',
                                                        'placeholder' => 'Short description',
                                                        'rows' => 4,
                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('pallets_short_description'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('pallets_short_description') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <a class="collapsed" href="#pallets_productMediaDetails"
                                                    data-toggle="collapse" data-target="#pallets_productMediaDetails"
                                                    aria-expanded="false"> {{ __('MEDIA') }}</a>
                                                <!-- <hr> -->
                                            </div>
                                        </div>
                                        <div class="row" id="pallets_productMediaDetails" aria-expanded="false">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                        {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                                        {!! Form::file('pallets_base_image_file', [
                                                            'class' => 'form-control dropify ' . ($errors->has('pallets_base_image_file') ? ' is-invalid' : ''),
                                                            'data-default-file' => isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['base_image'] : '',
                                                        ]) !!}
                                                        <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                                        @if ($errors->has('pallets_base_image_file'))
                                                            <br>
                                                            <span class="help-block text-danger">
                                                                <strong>{{ $errors->first('pallets_base_image_file') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <a class="collapsed" href="#productSeoDetailsSingle"
                                                            data-toggle="collapse" data-target="#productSeoDetailsSingle"
                                                            aria-expanded="false"> {{ __('SEO') }} </a>
                                                    </div>
                                                </div>
                                                <div class="row" id="productSeoDetailsSingle" aria-expanded="false">
                                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'pallets_meta_description',
                                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['meta_description'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('pallets_meta_description') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta description',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('pallets_meta_description'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('pallets_meta_description') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                                                {!! Form::textarea(
                                                                    'pallets_meta_keyword',
                                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['meta_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('pallets_meta_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta keywords',
                                                                        'rows' => 4,
                                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('pallets_meta_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('pallets_meta_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                                {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'pallets_meta_title',
                                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['meta_title'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('pallets_meta_title') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Meta title',
                                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('pallets_meta_title'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('pallets_meta_title') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                                                {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                                                {!! Form::text(
                                                                    'pallets_search_keyword',
                                                                    isset($product->id) && (isset($pallets) && !empty($pallets)) ? $pallets['search_keyword'] : '',
                                                                    [
                                                                        'class' => 'form-control' . ($errors->has('pallets_search_keyword') ? ' is-invalid' : ''),
                                                                        'autofocus',
                                                                        'placeholder' => 'Search keyword',
                                                                        isset($product->id) && (isset($pallets) && !empty($pallets) && $pallets->status == 'ACTIVE') ? 'readonly' : '',
                                                                    ],
                                                                ) !!}

                                                                @if ($errors->has('pallets_search_keyword'))
                                                                    <small class="text-danger">
                                                                        <strong>{{ $errors->first('pallets_search_keyword') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            @if (!isset($product->id))
                                {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection
