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
                                    {!! Form::label('category_group', 'Category Group', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('category_group', null, [
                                        'class' => 'form-control' . ($errors->has('category_group') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Category Group',
                                        'readonly',
                                    ]) !!}

                                    @if ($errors->has('category_group'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('category_group') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('department', 'Department', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('department', null, [
                                        'class' => 'form-control' . ($errors->has('department') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Department',
                                    ]) !!}

                                    @if ($errors->has('department'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('department') }}</strong>
                                        </small>
                                    @endif
                                </div>

                            </div>
                            <?php $subCategoryId = ''; ?>
                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                {{-- <div class="row collapse" id="productCatrgory" aria-expanded="false"> --}}
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    <div class="admin-ajax-location-categories">
                                        {!! Form::label('category_id', 'Category', ['class' => 'form-label ']) !!}
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Select category</option>
                                            @foreach ($categories as $category)
                                                @if (in_array($category->uuid, $selectedCategories))
                                                    <option value="{{ $category->uuid }}" data-ajax-url="/postGetCategory"
                                                        data-ajax-category="category" selected>
                                                        {{ $category->name }}</option>
                                                    <?php
                                                    unset($selectedCategories[array_search($category->uuid, $selectedCategories)]);
                                                    
                                                    if (count($selectedCategories) > 0) {
                                                        $subCategoryId = [...$selectedCategories];
                                                        $subCategoryId = $subCategoryId[0];
                                                    }
                                                    ?>
                                                @else
                                                    <option value="{{ $category->uuid }}" data-ajax-url="/postGetCategory"
                                                        data-ajax-category="category">
                                                        {{ $category->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($errors->has('category_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- {{ dd($subCategoryId) }} --}}
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required sub_category_id_show"
                                    {{-- style="display: none" --}}>
                                    <div class="admin-ajax-location-sub-categories">
                                        {!! Form::label('sub_category_id', 'Sub Category', ['class' => 'form-label']) !!}
                                        <select name="sub_category_id" id="sub_category_id" class="form-control">
                                            <option value="">Select sub category</option>
                                            {{-- @foreach ($categories as $category)
                                            <option value="{{ $category->uuid }}">{{ $category->name }}</option>
                                        @endforeach --}}
                                        </select>
                                    </div>

                                    @if ($errors->has('sub_category_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('sub_category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>



                            <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('segment', 'Segment', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('segment', null, [
                                        'class' => 'form-control' . ($errors->has('segment') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Segment',
                                    ]) !!}

                                    @if ($errors->has('segment'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('segment') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('subsegment', 'Sub Segment', ['class' => 'form-label ']) !!}
                                    {{-- {!! Form::label('brand_id', 'Manufacturer', ['class' => 'form-label ']) !!} --}}
                                    {!! Form::text('subsegment', null, [
                                        'class' => 'form-control' . ($errors->has('subsegment') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Sub Segment',
                                    ]) !!}

                                    @if ($errors->has('subsegment'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('subsegment') }}</strong>
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
            <script>
                // var app = @json($selectedCategories);
                // var jobs = "{!! $subCategoryId !!}";
                var editSubCategoryId = '<?php echo $subCategoryId; ?>';
                // alert(baz);
                // alert(editSubCategoryId);
                // let selectedData = '{{ '+$selectedCategories+' }}'
                // console.log('12312', selectedData);
                // in_array($category->uuid, $selectedCategories)
                $(document).on('change', '#category_id', function() { // get log nad lat of company when company driver add 

                    let ajaxUrl = $(this).find(':selected').data('ajax-url');
                    let category = $(this).find(':selected').data('ajax-category');
                    let category_id = $(this).find(':selected').val();

                    console.log('123: ', category_id, ajaxUrl);

                    if (category_id != "") {

                        $.ajax({
                            type: 'POST',
                            data: {
                                _token: TOKEN,
                                category_id: category_id
                            },
                            url: ajaxUrl,
                            success: function(data) {

                                // var data1 = $.parseJSON(data);
                                var data1 = data;
                                if (data1.length == 0) {
                                    // $('.sub_category_id_show').hide();
                                }
                                if (category === 'category') {
                                    if (data1.length !== 0) {
                                        // $('.sub_category_id_show').show();
                                    }
                                }

                                $('#sub_category_id').empty();
                                $('#sub_category_id').append(`<option value="">Select sub category</option>`);
                                $.each(data1, function(i, val) {
                                    console.log('data1: ', val);
                                    $('#sub_category_id').append(
                                        `<option value="${val.uuid}">${val.name}</option>`);
                                });


                            },
                            error: function(xhr, status, error) {

                                alert(xhr.responseText);

                            }
                        });
                    } else {
                        // $('.sub_category_id_show').hide();
                    }




                    // call_ajax(ajaxUrl, category_id,category)
                });

                setTimeout(() => {
                    let ajaxUrlNew = $('#category_id').find(':selected').data('ajax-url');
                    let categoryNew = $('#category_id').find(':selected').data('ajax-category');
                    let category_idNew = $('#category_id').find(':selected').val();

                    console.log('fggd: ', ajaxUrlNew, categoryNew, category_idNew);
                    call_ajax(ajaxUrlNew, category_idNew, categoryNew, editSubCategoryId)
                }, 1000);

                function call_ajax(ajaxUrl, category_id, category, editSubCategoryId) {
                    if (category_id) {
                        $.ajax({
                            type: 'POST',
                            data: {
                                _token: TOKEN,
                                category_id: category_id
                            },
                            url: ajaxUrl,
                            success: function(data) {

                                // var data1 = $.parseJSON(data);
                                var data1 = data;
                                if (data1.length == 0) {
                                    // $('.sub_category_id_show').hide();
                                }
                                if (category === 'category') {
                                    if (data1.length !== 0) {
                                        // $('.sub_category_id_show').show();
                                    }
                                }

                                $('#sub_category_id').empty();
                                $('#sub_category_id').append(
                                    `<option value="">Select sub category</option>`);
                                $.each(data1, function(i, val) {
                                    if (val.uuid == editSubCategoryId) {
                                        $('#sub_category_id').append(
                                            `<option value="${val.uuid}" selected>${val.name}</option>`);
                                    } else {
                                        $('#sub_category_id').append(
                                            `<option value="${val.uuid}">${val.name}</option>`);
                                    }

                                });


                            },
                            error: function(xhr, status, error) {

                                alert(xhr.responseText);

                            }
                        });
                    } else {
                        // $('.sub_category_id_show').hide();
                    }
                }

                $(document).on('change', '.varients', function() {
                    var url = window.location.pathname;
                    // var id = url.substring(url.lastIndexOf('/') + 1);
                    var res = url.split("/")[3];
                    console.log($(this).val(), res);


                    $('#status').val('');
                    $('#tax_id').val('');
                    $('#stock_type').val('');
                    // $('#base_image_file').val('');
                    html =
                        `<input type="file" id="base_image_file_class" name="base_image_file" class="dropify form-control base_image_file_class" data-default-file="" required />`;
                    $('.imageShow').html(html);
                    $('#base_image_file_class').dropify();

                    $('#varient').val('');
                    $('#barcode').val('');
                    $('#base_price').val('');
                    $('#stock_of').val('');
                    $('#default_stock_type').val('');
                    $('#stoc_wt').val('');
                    $('#stock_gst').val('');
                    $('#description').val('');
                    $('#short_description').val('');
                    $('#meta_description').val('');
                    $('#meta_keyword').val('');
                    $('#meta_title').val('');
                    $('#search_keyword').val('');
                    $('.product_unit_value').val('');
                    $('.product_unit_name').val('');
                    let productId = res;

                    let type = $(this).find(":selected").val();
                    $('#varient').val(type);

                    // let type = $(this).val();
                    if (type != '') {
                        $('.showData').show();

                        $.post('/admin/products/getProductVarient', {
                            product_id: productId,
                            type: type,
                            _token: TOKEN
                        }, function(data, status) {
                            if (data != "null") {
                                data = JSON.parse(data);
                                $('#status').val(data.status);
                                $('#tax_id').val(data.tax_id);
                                $('#stock_type').val(data.stock_type);
                                // $('#base_image_file').val(data.base_image);
                                // $('.base_image_file_class').attr('data-default-file', data.base_image);
                                // $('.base_image_file_class').dropify();
                                // $('#base_image_file').attr('data-default-file', data.base_image);
                                html =
                                    `<input type="file" id="base_image_file_class" name="base_image_file" class="dropify form-control base_image_file_class" value="${data.base_image}" data-default-file="${data.base_image}"  />`;
                                $('.imageShow').html(html);
                                $('#base_image_file_class').dropify();
                                // $('.imageShow').html();

                                $('#varient').val(data.stock_type);
                                $('#barcode').val(data.barcode);
                                $('#base_price').val(data.price);
                                $('#stock_of').val(data.stock_of);
                                $('#default_stock_type').val(data.default_stock_type);
                                $('#stoc_wt').val(data.stoc_wt);
                                $('#stock_gst').val(data.stock_gst);
                                $('#description').val(data.description);
                                $('#short_description').val(data.short_description);
                                $('#meta_description').val(data.meta_description);
                                $('#meta_keyword').val(data.meta_keyword);
                                $('#meta_title').val(data.meta_title);
                                $('#search_keyword').val(data.search_keyword);
                                $('.product_unit_value').val(data.unit_value);
                                $('.product_unit_name').val(data.unit + '|' + data.unit_name);
                                console.log(data);
                            }
                            // alert("Data: " + data + "\nStatus: " + status);
                        });
                    } else {
                        $('.showData').hide();
                    }

                    // $.get('/admin/products/getProductVarient',function)
                });
            </script>
        @endsection
