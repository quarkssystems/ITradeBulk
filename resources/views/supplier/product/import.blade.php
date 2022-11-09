{{-- /**
 * Created by PhpStorm.
 * User: Haiyu
 * Date: 13/11/19
 * Time: 10:24 AM
 */ --}}
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
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif

        </div>
    </div>
@endsection
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                @if (auth()->user()->fact_access == '1')
                <div class="card-body">
                    <h1>Import Facts</h1>
                    {!! Form::open([
                        'route' => ['supplier.import_parse'],
                        'id' => 'form',
                        'autocomplete' => 'off',
                        'name' => 'importForm',
                        'files' => true,
                    ]) !!}
                    @csrf

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('Select CSV File', 'Select CSV File', ['class' => 'form-label label-required']) !!}
                            {!! Form::file('product_csv_file', null, [
                                'class' => 'form-control' . ($errors->has('product_csv_file') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Csv Import',
                            ]) !!}
                            <br>
                            @if ($errors->has('product_csv_file'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('product_csv_file') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Import Product Facts', [
                                'type' => 'submit',
                                'class' => 'btn btn-primary',
                                'name' => 'save_exit',
                            ]) !!}
                            {{-- {!! Form::submit('Import Product', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!} --}}
                            {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <a href="{{ url('/') }}/uploads/Supplier_Stock_update.xlsx"
                        download="Supplier_Stock_update.xlsx">Download Sample File For Product Facts Update</a><br>
                </div>
                @endif

                @if (auth()->user()->product_access == '1')
                <hr>
                <div class="card-body">
                    <h1>Import Products</h1>

                    {!! Form::open([
                        'route' => ['supplier.import_product_parse'],
                        'id' => 'formproduct',
                        'autocomplete' => 'off',
                        'name' => 'importFormproduct',
                        'files' => true,
                    ]) !!}
                    @csrf

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('Select CSV File', 'Select CSV File', ['class' => 'form-label label-required']) !!}
                            {!! Form::file('real_product_csv_file', null, [
                                'class' => 'form-control' . ($errors->has('real_product_csv_file') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Csv Import',
                            ]) !!}
                            <br>
                            @if ($errors->has('real_product_csv_file'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('real_product_csv_file') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Import Products', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <a href="{{ url('/') }}/uploads/Import_Products_supplier.xlsx"
                        download="Import_Products_supplier.xlsx">Download
                        Sample File For Products Import</a>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection
