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
                    @if(isset($tax->id) && (isset($copy) && !$copy))
                        {!! Form::model($tax, ['route' => ["$route.update", $tax->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($tax, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($tax->id))
                            {!! Form::hidden('uuid', $tax->uuid) !!}
                        @endif
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
                            {!! Form::label('value', 'Tax', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("value",null,["class"=>"form-control".($errors->has('value')?" is-invalid":""),"autofocus",'placeholder'=>'Tax']) !!}

                            @if ($errors->has('value'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('value') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                            {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                            @if ($errors->has('status'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($tax->id))
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
