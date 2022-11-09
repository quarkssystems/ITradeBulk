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
            <a href="{{ route("$route.variantsindex") }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($product, [
                        'route' => ["$route.variantsupdate"],
                        'id' => 'form',
                        'autocomplete' => 'off',
                        'name' => 'usersForm',
                        'files' => true,
                    ]) !!}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <h3>{{ __('Edit Variants') }}</h3>
                            <hr />


                            <input type="hidden" name="product_id" value="{{ $id }}">

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('barcode', $productData->barcode, [
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
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('base_price', 'Base price', ['class' => 'form-label']) !!}
                                    {!! Form::text('base_price', $productData->base_price, [
                                        'class' => 'form-control' . ($errors->has('base_price') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('base_price'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('base_price') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('stock_type', $productAttribute, $productData->stock_type, [
                                        'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('stock_type'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('stock_type') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                    {!! Form::text('stock_of', $productData->stock_of, [
                                        'class' => 'form-control' . ($errors->has('base_price') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('stock_of'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('stock_of') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('default_stock_type', 'Default Stock Type', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('default_stock_type', $defaultStockType, $productData->default_stock_type, [
                                        'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('stoc_wt', $productData->stoc_wt, [
                                        'class' => 'form-control' . ($errors->has('stoc_wt') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('stoc_wt'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('stoc_wt') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label ']) !!}
                                    {!! Form::select('stock_gst', $productgst, $productData->stock_gst, [
                                        'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('tax_id', $taxes, $productData->tax_id, [
                                        'class' => 'form-control' . ($errors->has('tax_id') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('tax_id'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('tax_id') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('unit', 'Unit', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('unit_value', $productData->unit_value, [
                                                'class' => 'form-control product_unit_value' . ($errors->has('unit_value') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                            ]) !!}

                                            @if ($errors->has('unit_value'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('unit_value') }}</strong>
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
                                                'unit_data',
                                                $productUnits,
                                                isset($productData->unit_value) ? "{$productData->unit}|{$productData->unit_name}" : null,
                                                ['class' => 'form-control product_unit_name ' . ($errors->has('unit_data') ? ' is-invalid' : ''), 'autofocus'],
                                            ) !!}

                                            @if ($errors->has('unit_data'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('unit_data') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('status', $statuses, $productData->status, [
                                        'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                    @if ($errors->has('status'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('parent_id', 'Parent ID', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('parent_id', $productData->parent_id, [
                                                'class' => 'form-control product_parent_id' . ($errors->has('parent_id') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                                'disabled',
                                            ]) !!}

                                            @if ($errors->has('parent_id'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('parent_id') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('variant_id', 'Variant ID', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('variant_id', $productData->variant_id, [
                                                'class' => 'form-control product_variant_id' . ($errors->has('variant_id') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                            ]) !!}

                                            @if ($errors->has('variant_id'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('variant_id') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('colour', 'Colour', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('colour', $productData->colour, [
                                                'class' => 'form-control product_colour' . ($errors->has('colour') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                            ]) !!}

                                            @if ($errors->has('colour'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('colour') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('colour_variants', 'Colour Variants', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('colour_variants', $productData->colour_variants, [
                                                'class' => 'form-control product_colour_variants' . ($errors->has('colour_variants') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                            ]) !!}

                                            @if ($errors->has('colour_variants'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('colour_variants') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('size_variants', 'Size Variants', ['class' => 'form-label label-required']) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            {!! Form::text('size_variants', $productData->size_variants, [
                                                'class' => 'form-control product_size_variants' . ($errors->has('size_variants') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Value',
                                            ]) !!}

                                            @if ($errors->has('size_variants'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('size_variants') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                    {!! Form::textarea('description', $productData->description, [
                                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Description',
                                        'rows' => 4,
                                    ]) !!}

                                    @if ($errors->has('description'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                    {!! Form::textarea('short_description', $productData->short_description, [
                                        'class' => 'form-control' . ($errors->has('short_description') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Short description',
                                        'rows' => 4,
                                    ]) !!}

                                    @if ($errors->has('short_description'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('short_description') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <a class="collapsed" href="#productMediaDetails" data-toggle="collapse"
                                        data-target="#productMediaDetails" aria-expanded="false">
                                        {{ __('MEDIA') }}
                                    </a>
                                    <hr>
                                </div>
                            </div>

                            <div class="row collapse" id="productMediaDetails" aria-expanded="false">
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                            {!! Form::file('base_image_file', [
                                                'class' => 'form-control dropify ' . ($errors->has('base_image_file') ? ' is-invalid' : ''),
                                                'data-default-file' => isset($productData->base_image) ? $productData->base_image : '',
                                            ]) !!}
                                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                            @if ($errors->has('base_image_file'))
                                                <br><span class="help-block text-danger">
                                                    <strong>{{ $errors->first('base_image_file') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <a class="collapsed" href="#productSeoDetails" data-toggle="collapse"
                                        data-target="#productSeoDetails" aria-expanded="false">
                                        {{ __('SEO') }}
                                    </a>
                                    <hr>
                                </div>
                            </div>

                            <div class="row collapse" id="productSeoDetails" aria-expanded="false">
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                            {!! Form::textarea('meta_description', $productData->meta_description, [
                                                'class' => 'form-control' . ($errors->has('meta_description') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Meta description',
                                                'rows' => 4,
                                            ]) !!}

                                            @if ($errors->has('meta_description'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('meta_description') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                            {!! Form::textarea('meta_keyword', $productData->meta_keyword, [
                                                'class' => 'form-control' . ($errors->has('meta_keyword') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Meta keywords',
                                                'rows' => 4,
                                            ]) !!}

                                            @if ($errors->has('meta_keyword'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('meta_keyword') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                            {!! Form::text('meta_title', $productData->meta_title, [
                                                'class' => 'form-control' . ($errors->has('meta_title') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Meta title',
                                            ]) !!}

                                            @if ($errors->has('meta_title'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('meta_title') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                            {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                            {!! Form::text('search_keyword', $productData->search_keyword, [
                                                'class' => 'form-control' . ($errors->has('search_keyword') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Search keyword',
                                            ]) !!}

                                            @if ($errors->has('search_keyword'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('search_keyword') }}</strong>
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- <hr/> -->

                            {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save']) !!}
                            {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}

                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
