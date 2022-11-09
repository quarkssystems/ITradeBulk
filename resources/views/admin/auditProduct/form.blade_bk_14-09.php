{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */
 --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{$pageTitle}}</h1>
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{__('Back')}}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($product->id) && (isset($copy) && !$copy))
                        {!! Form::model($product, ['route' => ["$route.update", $product->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($product, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($product->id))
                            {!! Form::hidden('uuid', $product->uuid) !!}
                        @endif
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <a
                                        class=""
                                        href="#productBasicDetails"
                                        data-toggle="collapse"
                                        data-target="#productBasicDetails"
                                        aria-expanded="true">
                                    {{__('BASIC DETAILS')}}
                                </a>
                                <hr>
                            </div>
                        </div>
                        <div class="row collapse show"
                             id="productBasicDetails"
                             aria-expanded="true">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text("name",null,["class"=>"form-control".($errors->has('name')?" is-invalid":""),"autofocus",'placeholder'=>'Name']) !!}

                                        @if ($errors->has('name'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text("slug",null,["class"=>"form-control".($errors->has('slug')?" is-invalid":""),"autofocus",'placeholder'=>'Slug']) !!}

                                        @if ($errors->has('slug'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('slug') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text("barcode",null,["class"=>"form-control".($errors->has('barcode')?" is-invalid":""),"autofocus",'placeholder'=>'Barcode']) !!}

                                        @if ($errors->has('barcode'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('barcode') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                        {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                                        @if ($errors->has('status'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('base_price', 'Base price', ['class' => 'form-label']) !!}
                                        {!! Form::text("base_price", null, ["class"=>"form-control".($errors->has('base_price')?" is-invalid":""),"autofocus"]) !!}

                                        @if ($errors->has('base_price'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('base_price') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                        {!! Form::select("tax_id",$taxes, null,["class"=>"form-control".($errors->has('tax_id')?" is-invalid":""),"autofocus"]) !!}

                                        @if ($errors->has('tax_id'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('tax_id') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('brand_id', 'Brand', ['class' => 'form-label label-required']) !!}
                                        {!! Form::select("brand_id",$brands, null,["class"=>"form-control".($errors->has('brand_id')?" is-invalid":""),"autofocus"]) !!}

                                        @if ($errors->has('brand_id'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('brand_id') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('unit', 'Unit', ['class' => 'form-label']) !!}
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                {!! Form::text("unit_value",null,["class"=>"form-control product_unit_value".($errors->has('unit_value')?" is-invalid":""),"autofocus",'placeholder'=>'Value']) !!}

                                                @if ($errors->has('unit_value'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('unit_value') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                {!! Form::select("unit_data",$productUnits, isset($product->unit_value) ? "{$product->unit}|{$product->unit_name}" : null,["class"=>"form-control product_unit_name ".($errors->has('unit_data')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('unit_data'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('unit_data') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
{{--                                <div class="row">--}}
{{--                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">--}}
{{--                                        {!! Form::label('min_price', 'Min price', ['class' => 'form-label label-required']) !!}--}}
{{--                                        {!! Form::text("min_price",null,["class"=>"form-control".($errors->has('min_price')?" is-invalid":""),"autofocus",'placeholder'=>'Min price']) !!}--}}

{{--                                        @if ($errors->has('min_price'))--}}
{{--                                            <small class="text-danger">--}}
{{--                                                <strong>{{ $errors->first('min_price') }}</strong>--}}
{{--                                            </small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">--}}
{{--                                        {!! Form::label('max_price', 'Max price', ['class' => 'form-label label-required']) !!}--}}
{{--                                        {!! Form::text("max_price",null,["class"=>"form-control".($errors->has('short_description')?" is-invalid":""),"autofocus",'placeholder'=>'Max price']) !!}--}}

{{--                                        @if ($errors->has('max_price'))--}}
{{--                                            <small class="text-danger">--}}
{{--                                                <strong>{{ $errors->first('max_price') }}</strong>--}}
{{--                                            </small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                        {!! Form::textarea("description",null,["class"=>"form-control".($errors->has('description')?" is-invalid":""),"autofocus",'placeholder'=>'Description', 'rows' => 4]) !!}

                                        @if ($errors->has('description'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('short_description', 'Short description', ['class' => 'form-label label-required']) !!}
                                        {!! Form::textarea("short_description",null,["class"=>"form-control".($errors->has('short_description')?" is-invalid":""),"autofocus",'placeholder'=>'Short description', 'rows' => 4]) !!}

                                        @if ($errors->has('short_description'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('short_description') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>{{__("Define weight")}}</label><br>
                                        <small><i>{{__("Note: Please mention net weight with packaging in kg")}}</i></small>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>{{__('Qty')}}</th>
                                                <th>{{__('Weight(kg)')}}</th>
                                                <th width="100">{{__('Single Qty')}}</th>
                                                <th width="100">{{__('Shrink Qty')}}</th>
                                                <th width="100">{{__('Case Qty')}}</th>
                                                <th colspan="3">{{__('Dimension in cm')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__("Single")}}</td>
                                                <td>
                                                    {!! Form::text("single_qty",(isset($product->id) ? $product->single_qty : 1),["class"=>"form-control product-single-qty-input".($errors->has('single_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Single qty', 'readonly']) !!}

                                                    @if ($errors->has('single_qty'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('single_qty') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Form::text("single_weight",null,["class"=>"form-control product-single-weight-input".($errors->has('single_weight')?" is-invalid":""),"autofocus",'placeholder'=>'Single weight in gm']) !!}

                                                    @if ($errors->has('single_weight'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('single_weight') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{{__("NA")}}</td>
                                                <td>{{__("NA")}}</td>
                                                <td>{{__("NA")}}</td>
{{--                                                <td>{!! Form::hidden("single_bundle_of", "single", ["class"=>"form-control single-product-bundle-of-input"]) !!}{{__("NA")}}</td>--}}

                                                <td>
                                                    {!! Form::text("single_height", null,["class"=>"form-control".($errors->has('single_height')?" is-invalid":""),"autofocus",'placeholder'=>'Height']) !!}
                                                </td>

                                                <td>
                                                    {!! Form::text("single_width", null,["class"=>"form-control".($errors->has('single_width')?" is-invalid":""),"autofocus",'placeholder'=>'Width']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("single_length", null,["class"=>"form-control".($errors->has('single_length')?" is-invalid":""),"autofocus",'placeholder'=>'Length']) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__("Shrink")}}</td>
                                                <td>
                                                    {!! Form::text("shrink_qty", 1, ["class"=>"form-control product-shrink-qty-input".($errors->has('shrink_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Shrink qty', 'readonly']) !!}

                                                    @if ($errors->has('shrink_qty'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('shrink_qty') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Form::text("shrink_weight", null, ["class"=>"form-control product-shrink-weight-input ".($errors->has('shrink_weight')?" is-invalid":""),"autofocus",'placeholder'=>'Shrink weight in gm']) !!}

                                                    @if ($errors->has('shrink_weight'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('shrink_weight') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{!! Form::text("shrink_single_qty", null, ["class"=>"form-control shrink_single_qty ".($errors->has('shrink_single_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Single Qty']) !!}</td>
                                                <td>{{__("NA")}}</td>
                                                <td>{{__("NA")}}</td>
                                                <td>
                                                    {!! Form::text("shrink_height", null, ["class"=>"form-control".($errors->has('shrink_height')?" is-invalid":""),"autofocus",'placeholder'=>'Height']) !!}
                                                </td>

                                                <td>
                                                    {!! Form::text("shrink_width", null, ["class"=>"form-control".($errors->has('shrink_width')?" is-invalid":""),"autofocus",'placeholder'=>'Width']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("shrink_length", null, ["class"=>"form-control".($errors->has('shrink_length')?" is-invalid":""),"autofocus",'placeholder'=>'Length']) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__("Case")}}</td>
                                                <td>
                                                    {!! Form::text("case_qty",1,["class"=>"form-control product-case-qty-input".($errors->has('case_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Case qty', 'readonly']) !!}

                                                    @if ($errors->has('case_qty'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('case_qty') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Form::text("case_weight",null,["class"=>"form-control product-case-weight-input".($errors->has('case_weight')?" is-invalid":""),"autofocus",'placeholder'=>'Case weight in gm']) !!}

                                                    @if ($errors->has('case_weight'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('case_weight') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{!! Form::text("case_single_qty", null, ["class"=>"form-control case_single_qty ".($errors->has('case_single_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Single Qty']) !!}</td>
                                                <td>{!! Form::text("case_shrink_qty", null, ["class"=>"form-control case_shrink_qty ".($errors->has('case_shrink_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Shrink Qty']) !!}</td>
                                                <td>{{__("NA")}}</td>
{{--                                                <td>{!! Form::select("case_bundle_of", ["single" => "single", "shrink" => "shrink"], null,["class"=>"form-control case-product-bundle-of-input"]) !!}</td>--}}
                                                <td>
                                                    {!! Form::text("case_height",null,["class"=>"form-control".($errors->has('case_height')?" is-invalid":""),"autofocus",'placeholder'=>'Height']) !!}
                                                </td>

                                                <td>
                                                    {!! Form::text("case_width",null,["class"=>"form-control".($errors->has('case_width')?" is-invalid":""),"autofocus",'placeholder'=>'Width']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("case_length",null,["class"=>"form-control".($errors->has('case_length')?" is-invalid":""),"autofocus",'placeholder'=>'Length']) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__("Pallet")}}</td>
                                                <td>
                                                    {!! Form::text("pallet_qty",1,["class"=>"form-control product-pallet-qty-input".($errors->has('pallet_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Pallet qty', 'readonly']) !!}

                                                    @if ($errors->has('pallet_qty'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('pallet_qty') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Form::text("pallet_weight",null,["class"=>"form-control product-pallet-weight-input ".($errors->has('pallet_weight')?" is-invalid":""),"autofocus",'placeholder'=>'Pallet weight in gm']) !!}

                                                    @if ($errors->has('pallet_weight'))
                                                        <small class="text-danger">
                                                            <strong>{{ $errors->first('pallet_weight') }}</strong>
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{!! Form::text("pallet_single_qty", null, ["class"=>"form-control pallet_single_qty ".($errors->has('pallet_single_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Single Qty']) !!}</td>
                                                <td>{!! Form::text("pallet_shrink_qty", null, ["class"=>"form-control pallet_shrink_qty ".($errors->has('pallet_single_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Shrink Qty']) !!}</td>
                                                <td>{!! Form::text("pallet_case_qty", null, ["class"=>"form-control pallet_case_qty ".($errors->has('pallet_case_qty')?" is-invalid":""),"autofocus",'placeholder'=>'Case Qty']) !!}</td>
{{--                                                <td>{!! Form::select("pallet_bundle_of", ["single" => "single", "shrink" => "shrink", "case" => "case"], null,["class"=>"form-control pallet-product-bundle-of-input"]) !!}</td>--}}
                                                <td>
                                                    {!! Form::text("pallet_height",null,["class"=>"form-control".($errors->has('pallet_height')?" is-invalid":""),"autofocus",'placeholder'=>'Height']) !!}
                                                </td>

                                                <td>
                                                    {!! Form::text("pallet_width",null,["class"=>"form-control".($errors->has('pallet_width')?" is-invalid":""),"autofocus",'placeholder'=>'Width']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("pallet_length",null,["class"=>"form-control".($errors->has('pallet_length')?" is-invalid":""),"autofocus",'placeholder'=>'Length']) !!}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <a
                                        class="collapsed"
                                        href="#productCatrgory"
                                        data-toggle="collapse"
                                        data-target="#productCatrgory"
                                        aria-expanded="false">
                                    {{__('CATEGORIES')}}
                                </a>
                                <hr>
                            </div>
                        </div>
                        <div class="row collapse"
                             id="productCatrgory"
                             aria-expanded="false">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group row">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <ul>
                                            @foreach($categories as $category)
                                                {!! $category->getCategoryHierarchy($category, $selectedCategories) !!}
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <a
                                        class="collapsed"
                                        href="#productMediaDetails"
                                        data-toggle="collapse"
                                        data-target="#productMediaDetails"
                                        aria-expanded="false">
                                    {{__('MEDIA')}}
                                </a>
                                <hr>
                            </div>
                        </div>
                        <div class="row collapse"
                             id="productMediaDetails"
                             aria-expanded="false">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('base_image_file', 'Base image', ['class' => 'form-label label-required']) !!}
                                        {!! Form::file("base_image_file", [
                                                        "class"=>"form-control dropify ".($errors->has('base_image_file')?" is-invalid":""),
                                                        'data-default-file' => (isset($product->id) && (isset($copy) && !$copy)) ? $product->base_image : ''
                                                        ]) !!}
                                        <small><i>{{__('Only JPG and PNG supported')}}</i></small>
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
                                <a
                                        class="collapsed"
                                        href="#productSeoDetails"
                                        data-toggle="collapse"
                                        data-target="#productSeoDetails"
                                        aria-expanded="false">
                                    {{__('SEO')}}
                                </a>
                                <hr>
                            </div>
                        </div>

                        <div class="row collapse"
                             id="productSeoDetails"
                             aria-expanded="false">
                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label']) !!}
                                        {!! Form::textarea("meta_description",null,["class"=>"form-control".($errors->has('meta_description')?" is-invalid":""),"autofocus",'placeholder'=>'Meta description', 'rows' => 4]) !!}

                                        @if ($errors->has('meta_description'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('meta_description') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('meta_keywords', 'Meta keywords', ['class' => 'form-label']) !!}
                                        {!! Form::textarea("meta_keywords",null,["class"=>"form-control".($errors->has('meta_keywords')?" is-invalid":""),"autofocus",'placeholder'=>'Meta keywords', 'rows' => 4]) !!}

                                        @if ($errors->has('meta_keywords'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('meta_keywords') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                        {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label']) !!}
                                        {!! Form::text("meta_title",null,["class"=>"form-control".($errors->has('meta_title')?" is-invalid":""),"autofocus",'placeholder'=>'Meta title']) !!}

                                        @if ($errors->has('meta_title'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('meta_title') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                        {!! Form::label('search_keyword', 'Search keyword', ['class' => 'form-label']) !!}
                                        {!! Form::text("search_keyword",null,["class"=>"form-control".($errors->has('search_keyword')?" is-invalid":""),"autofocus",'placeholder'=>'Search keyword']) !!}

                                        @if ($errors->has('search_keyword'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('search_keyword') }}</strong>
                                            </small>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

{{--                        <div class="row">--}}
{{--                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">--}}
{{--                                <a--}}
{{--                                        class="collapsed"--}}
{{--                                        href="#productLogisticsDetails"--}}
{{--                                        data-toggle="collapse"--}}
{{--                                        data-target="#productLogisticsDetails"--}}
{{--                                        aria-expanded="false">--}}
{{--                                    {{__('Logistics')}}--}}
{{--                                </a>--}}
{{--                                <hr>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="row collapse"--}}
{{--                             id="productLogisticsDetails"--}}
{{--                             aria-expanded="false">--}}
{{--                            <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                                        <small><i>{{__("Note: Please mention net weight with packaging")}}</i></small>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                                        --}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($product->id))
                                {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
