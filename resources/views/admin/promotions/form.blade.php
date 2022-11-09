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
                                            {!! Form::label('promotion_type', 'Promotion Type', ['class' => 'form-label label-required']) !!}
                                            <select name="promotion_type" id="promotion_type" class="form-control">
                                                <option value="">Select Promo Type</option>
                                                @foreach ($promoType as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $product->promotion_type == $key ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            {{-- {!! Form::select('promotion_type', $promoType, null, ['class' => 'form-control', 'autofocus']) !!} --}}

                                            {{-- {!! Form::label('promotion_type', 'Promotion Type', ['class' => 'form-label ']) !!}
                                            {!! Form::text('promotion_type', null, [
                                                'class' => 'form-control' . ($errors->has('promotion_type') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Promotion Type',
                                            ]) !!} --}}

                                            @if ($errors->has('promotion_type'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('promotion_type') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('promotion_id', 'Promotion ID', ['class' => 'form-label ']) !!}
                                            {!! Form::text('promotion_id', null, [
                                                'class' => 'form-control' . ($errors->has('promotion_id') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Promotion ID',
                                                'readonly',
                                            ]) !!}

                                            @if ($errors->has('promotion_id'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('promotion_id') }}</strong>
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('period_from', 'Period From', ['class' => 'form-label ']) !!}
                                            {!! Form::date('period_from', null, [
                                                'class' => 'form-control' . ($errors->has('period_from') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Period From',
                                            ]) !!}

                                            @if ($errors->has('period_from'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('period_from') }}</strong>
                                                </small>
                                            @endif
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('period_to', 'Period To', ['class' => 'form-label ']) !!}
                                            {!! Form::date('period_to', null, [
                                                'class' => 'form-control' . ($errors->has('period_to') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Period To',
                                            ]) !!}

                                            @if ($errors->has('period_to'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('period_to') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('promotion_price', 'Promotion Price', ['class' => 'form-label ']) !!}
                                            {!! Form::text('promotion_price', null, [
                                                'class' => 'form-control' . ($errors->has('promotion_price') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Promotion Price',
                                            ]) !!}

                                            @if ($errors->has('promotion_price'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('promotion_price') }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('store_item_code', 'Store Item Code', ['class' => 'form-label ']) !!}
                                            {!! Form::text('store_item_code', null, [
                                                'class' => 'form-control' . ($errors->has('store_item_code') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Promotion Price',
                                            ]) !!}

                                            @if ($errors->has('store_item_code'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('store_item_code') }}</strong>
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                            {!! Form::label('base_price', 'Current Price', ['class' => 'form-label ']) !!}
                                            {!! Form::text('base_price', null, [
                                                'class' => 'form-control' . ($errors->has('base_price') ? ' is-invalid' : ''),
                                                'autofocus',
                                                'placeholder' => 'Current Price',
                                                'disabled',
                                            ]) !!}

                                            @if ($errors->has('base_price'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('base_price') }}</strong>
                                                </small>
                                            @endif
                                        </div>

                                        @if ($product->stock_expiry_date != null)
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required ">
                                                {!! Form::label('stock_expiry_date', 'Stock Expiry Date', ['class' => 'form-label ']) !!}
                                                {!! Form::date('stock_expiry_date', null, [
                                                    'class' => 'form-control' . ($errors->has('stock_expiry_date') ? ' is-invalid' : ''),
                                                    'autofocus',
                                                    'placeholder' => 'Stock Expiry Date',
                                                    'disabled',
                                                ]) !!}

                                                @if ($errors->has('stock_expiry_date'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('stock_expiry_date') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
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
            </div>
        </div>
    </div>
@endsection
{{-- {{ dd($subCategoryId) }} --}}
@section('footerData')
    <script>
        $("#product_id").on('change', function() {
            let product_id = $('#product_id :selected').val();
            // console.log(product_id);

            $.ajax({
                url: "/admin/productData/" + product_id,
                type: 'GET',
                success: function(data, status) {
                    if (data != null) {
                        let barcode = (data.barcode != null) ? 'b' + data.barcode : 'b0';
                        let store_id = (data.store_id != null) ? 's' + data.store_id : 's0';
                        // let id = store_id+'_'+barcode;
                        let promotion_id = $('#promotion_id').val();

                        if (data.stock_expiry_date !== null) {
                            $('.stock_expiry_date_show').show();
                        } else {
                            $('.stock_expiry_date_show').hide();
                        }
                        let check = promotion_id.includes('s');
                        console.log('check: ', check);

                        let id = '';
                        if (check) {
                            var mySubString = promotion_id.split('s').pop().split('_f')[0];
                            console.log('+mySubString', 's' + mySubString);
                            id = promotion_id.replace('s' + mySubString, store_id + '_' + barcode);
                        } else {
                            id = store_id + '_' + barcode;
                        }
                        $('#promotion_id').val(id);
                    } else {
                        $('#promotion_id').val('');
                        $('#current_price').val('');
                        $('#stock_expiry_date').val('');
                        $('.stock_expiry_date_show').hide();
                    }
                    console.log(data);
                    // location.reload();
                },
                error: function(data) {
                    $('#promotion_id').val('');
                    $('#current_price').val('');
                    $('#stock_expiry_date').val('');
                    $('.stock_expiry_date_show').hide();
                }
            });

            // $.get("/admin/productData/" +
            //     product_id,
            //     function(data, status) {

            //     });

        });

        $("#period_from").on('change', function() {
            // console.log(this);
            var formattedDate = new Date($(this).val());
            var d = formattedDate.getDate();
            var m = formattedDate.getMonth();
            m += 1; // JavaScript months are 0-11
            var y = formattedDate.getFullYear();

            let date = d + '' + m + '' + y;

            let promotion_id = $('#promotion_id').val();

            let check = promotion_id.includes('_f');
            let id = '';
            if (check) {
                var mySubString = promotion_id.split('_f').pop().split('_')[0];
                id = promotion_id.replace('_f' + mySubString, '_f' + date);
            } else {
                id = promotion_id + '_f' + date;
            }
            $('#promotion_id').val(id);
        });

        $("#period_to").on('change', function() {
            // console.log(this);
            var formattedDate = new Date($(this).val());
            var d = formattedDate.getDate();
            var m = formattedDate.getMonth();
            m += 1; // JavaScript months are 0-11
            var y = formattedDate.getFullYear();

            let date = d + '' + m + '' + y;

            let promotion_id = $('#promotion_id').val();

            let check = promotion_id.includes('_e');
            let id = '';
            if (check) {
                var mySubString = promotion_id.split('_e').pop().split('_')[0];
                id = promotion_id.replace('_e' + mySubString, '_e' + date);
            } else {
                id = promotion_id + '_e' + date;
            }
            $('#promotion_id').val(id);
        });
    </script>
@endsection
