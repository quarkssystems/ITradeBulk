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

<div class="row">
    <div class="col-md-12">
        @include('frontend.helpers.globalMessage.message')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {!! Form::model($withdrawal, ['route' => ["$route.store"], 'method'=>'POST','id'=>'form', 'autocomplete' => 'off', 'name' => 'walletForm']) !!}

                            <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('amount', 'Settle amount', ['class' => 'form-label label-required']) !!} (R)
                                    {!! Form::text("amount",null,["class"=>"form-control".($errors->has('amount')?" is-invalid":""),"autofocus",'placeholder'=>'Settle amount']) !!}

                                    @if ($errors->has('amount'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('remarks', 'Details', ['class' => 'form-label label-required']) !!}
                                    {!! Form::textarea("remarks", null, ["class"=>"form-control".($errors->has('remarks')?" is-invalid":""),"autofocus",'placeholder'=>'Details', 'rows' => 2]) !!}

                                    @if ($errors->has('remarks'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('remarks') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                         
                         <!--          <div class="form-group row required">
                                        {!! Form::label('receipt', 'Receipt', ['class' => 'form-label label-required']) !!}
                                        {!! Form::file("receipt", ["class"=>"form-control dropify ".($errors->has('receipt')?" is-invalid":"") ]) !!}
                                        
                                    @if ($errors->has('receipt'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('receipt') }}</strong>
                                        </small>
                                    @endif
                                    </div>
 -->                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    {!! Form::submit("Submit",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_exit'])!!}
                                    {{--                                {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}--}}
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
        </div>
</div>

@endsection