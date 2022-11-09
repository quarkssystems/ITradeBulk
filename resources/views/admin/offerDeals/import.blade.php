{{-- /**
 * Created by PhpStorm.
 * User: Haiyu
 * Date: 13/11/19
 * Time: 10:24 AM
 */ --}}
@extends('admin.layouts.main')
@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            <a href="" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">

                    {!! Form::open([
                        'route' => ['admin.importmafparse'],
                        'id' => 'form',
                        'autocomplete' => 'off',
                        'name' => 'importForm',
                        'files' => true,
                    ]) !!}
                    @csrf

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('Select CSV File', 'Select CSV File', ['class' => 'form-label label-required']) !!}
                            {!! Form::file('csv_import', null, [
                                'class' => 'form-control' . ($errors->has('csv_import') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Csv Import',
                            ]) !!}
                            <br>
                            @if ($errors->has('csv_import'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('csv_import') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Import Brand', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            {{-- {!! Form::submit("Import Manufacturer",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!} --}}
                            {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <a href="{{ url('/') }}/uploads/import_maf_simple.csv" download="import_maf_simple.csv">Download
                        Simple File</a>

                </div>
            </div>
        </div>
    </div>
@endsection
