{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */
 --}}
@extends('supplier.layouts.main')
@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{__('Home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{$pageTitle}}</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')

<div class="col-12">
    @if (count($errors) > 0)
        <div class = "alert alert-danger">
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

            @if(isset($product->id))
            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#basic_details" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Basic</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#add_variants" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Add Variants</a>
                    </li>
                </ul>
            </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="basic_details" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">

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
                                        <!-- <hr> -->
                                    </div>
                                </div>
                                <div class="row collapse show"
                                     id="productBasicDetails"
                                     aria-expanded="true">
                                    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text("name",null,["class"=>"form-control".($errors->has('name')?" is-invalid":""),"autofocus",'placeholder'=>'Name']) !!}

                                                @if ($errors->has('name'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text("slug",null,["class"=>"form-control".($errors->has('slug')?" is-invalid":""),"autofocus",'placeholder'=>'Slug']) !!}

                                                @if ($errors->has('slug'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('slug') }}</strong>
                                                    </small>
                                                @endif
                                            </div>

                                            {!! Form::hidden("status", "INACTIVE") !!}

                                            {{--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                                {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('status'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('status') }}</strong>
                                                    </small>
                                                @endif
                                            </div>--}}
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select("brand_id",$brands, null,["class"=>"form-control".($errors->has('brand_id')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('brand_id'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('brand_id') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('arrival_type', 'Arrival Type', ['class' => 'form-label label-required']) !!}
                                                <select name="arrival_type" id="arrival_type" class="form-control">
                                                    <?php foreach ($arrival as $key => $value) { ?>
                                                    <option value="<?php echo $value->id; ?>"  {{ (isset($product->arrival_type) && ($product->arrival_type == $value->id)) ? 'selected':'' }}><?php echo $value->name; ?></option>
                                                    <?php }?>
                                                </select>

                                                @if ($errors->has('arrival_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('arrival_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>

                                                @php $variantNumber = mt_rand(1000000000, 9999999999); @endphp
                                                {!! Form::hidden("variant_id",$variantNumber,["class"=>"form-control".($errors->has('variant_id')?" is-invalid":""),"autofocus",'placeholder'=>'Variant id']) !!}

                                        </div>
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
                                        <!-- <hr> -->
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
                                        <!-- <hr> -->
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
                                        <!-- <hr> -->
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
                                                {!! Form::label('meta_keyword', 'Meta keywords', ['class' => 'form-label']) !!}
                                                {!! Form::textarea("meta_keyword",null,["class"=>"form-control".($errors->has('meta_keyword')?" is-invalid":""),"autofocus",'placeholder'=>'Meta keywords', 'rows' => 4]) !!}

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


                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
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


                        <div class="tab-pane fade" id="add_variants" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">



                            {!!Form::model($product, ['route' => ["$route.storedata"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true])!!}
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <h3>{{__('Add Variants')}}</h3>
                                        <hr>

                                        <input type="hidden" name="variant_id" value="{{$product->variant_id}}">
                                        <input type="hidden" name="parent_id" value="{{$product->uuid}}">
                                        <input type="hidden" name="name" value="{{$product->name}}">
                                        <input type="hidden" name="slug" value="{{$product->slug}}">
                                        <input type="hidden" name="status" value="{{$product->status}}">
                                        <input type="hidden" name="brand_id" value="{{$product->brand_id}}">
                                        <input type="hidden" name="arrival_type" value="{{$product->arrival_type}}">
                                        <input type="hidden" name="description" value="{{$product->description}}">
                                        <input type="hidden" name="short_description" value="{{$product->short_description}}">
                                        <input type="hidden" name="image" value="{{$product->base_image}}">
                                        <input type="hidden" name="meta_description" value="{{$product->meta_description}}">
                                        <input type="hidden" name="meta_keyword" value="{{$product->meta_keyword}}">
                                        <input type="hidden" name="meta_title" value="{{$product->meta_title}}">
                                        <input type="hidden" name="search_keyword" value="{{$product->search_keyword}}">

                                        


                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('barcode', 'Barcode', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text("barcode",null,["class"=>"form-control".($errors->has('barcode')?" is-invalid":""),"autofocus",'placeholder'=>'Barcode']) !!}

                                                @if ($errors->has('barcode'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('barcode') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('base_price', 'Base price', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text("base_price", null, ["class"=>"form-control".($errors->has('base_price')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('base_price'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('base_price') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_type', 'Stock Type', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select("stock_type",$productAttribute, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('stock_type'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('stock_type') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_of', 'Pack Size', ['class' => 'form-label label-required ']) !!}
                                                {!! Form::text("stock_of", null, ["class"=>"form-control".($errors->has('base_price')?" is-invalid":""),"autofocus"]) !!}

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
                                                {!! Form::select("default_stock_type",$defaultStockType, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stoc_wt', 'Stock Weight', ['class' => 'form-label label-required']) !!}
                                                {!! Form::text("stoc_wt", null, ["class"=>"form-control".($errors->has('stoc_wt')?" is-invalid":""),"autofocus"]) !!}

                                                @if ($errors->has('stoc_wt'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('stoc_wt') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('stock_gst', 'Stock VAT', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select("stock_gst",$productgst, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                                {!! Form::label('tax_id', 'Tax', ['class' => 'form-label label-required']) !!}
                                                {!! Form::select("tax_id",$taxes, null,["class"=>"form-control".($errors->has('tax_id')?" is-invalid":""),"autofocus"]) !!}

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
                                                        {!! Form::text("unit_value",null,["class"=>"form-control product_unit_value".($errors->has('unit_value')?" is-invalid":""),"autofocus",'placeholder'=>'Value']) !!}

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

                                        <hr/>

                                        {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-success", 'name' => 'save'])!!}
                                        {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}

                                    </div>
                                </div>

                            {!! Form::close() !!}

                        </div>



                    </div>



                </div>

                <!-- card-body2 starts -->
            </div>
        </div>
    </div>

@endsection
