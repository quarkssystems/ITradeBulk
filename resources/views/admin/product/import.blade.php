{{--

/**

 * Created by PhpStorm.

 * User: Haiyu

 * Date: 13/11/19

 * Time: 10:24 AM

 */

 --}}

@extends('admin.layouts.main')

@section('header')

    <div class="row">

        <div class="col-lg-7 col-md-10">

            <h1 class="display-2 text-white">{{$pageTitle}}</h1>

            <a href="{{route('admin.products.index')}}" class="btn btn-info">{{__('Back')}}</a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">

                   

                        {!! Form::open(['route' => ["admin.import_parse"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'importForm', 'files' => true]) !!}    

                        @csrf



                         <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                                    {!! Form::label('Select File', 'Select File', ['class' => 'form-label label-required']) !!}

                                    {!! Form::file("file_import",null,["class"=>"form-control".($errors->has('file_import')?" is-invalid":""),"autofocus",'placeholder'=>'Csv Import']) !!}

                                    <br>

                                    @if ($errors->has('file_import'))

                                        <small class="text-danger">

                                            <strong>{{ $errors->first('file_import') }}</strong>

                                        </small>

                                    @endif

                                </div>

                        </div>

                         <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit("Import Product",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

                            {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}

                        </div>

                        <div class="col-xs-12 col-lg-12">

                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>

                        </div>

                    </div>

                    {!! Form::close() !!}

                    {{--  <a href="{{ url('/') }}/uploads/Import_Products.xlsx" download="Import_Products.xlsx" >Download Sample File</a>  --}}

                    <a href="{{ url('/') }}/uploads/Import_Products.xlsx" download="Import_Products.xlsx" >Download Sample File</a>

                     

                </div>

            </div>

        </div>

    </div>

@endsection

