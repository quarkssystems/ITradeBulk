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
                    @if(isset($bank_branch->id) && (isset($copy) && !$copy))
                        {!! Form::model($bank_branch, ['route' => ["$route.update", $bank_branch->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($bank_branch, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($bank_branch->id))
                            {!! Form::hidden('uuid', $bank_branch->uuid) !!}
                        @endif
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('bank_master_id', 'Select bank', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("bank_master_id", $banks, null,["class"=>"form-control".($errors->has('bank_master_id')?" is-invalid":""),"autofocus",'placeholder'=>'Bank']) !!}

                            @if ($errors->has('bank_master_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('bank_master_id') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('branch_name', 'Branch name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("branch_name",null,["class"=>"form-control".($errors->has('branch_name')?" is-invalid":""),"autofocus",'placeholder'=>'Branch name']) !!}

                            @if ($errors->has('branch_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('branch_name') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('branch_code', 'Branch code', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("branch_code",null,["class"=>"form-control".($errors->has('branch_code')?" is-invalid":""),"autofocus",'placeholder'=>'Branch code']) !!}

                            @if ($errors->has('branch_code'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('branch_code') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('swift_code', 'Swift code', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("swift_code",null,["class"=>"form-control".($errors->has('swift_code')?" is-invalid":""),"autofocus",'placeholder'=>'Swift code']) !!}

                            @if ($errors->has('swift_code'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('swift_code') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>

                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('address1', 'Address line 1', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("address1",null,["class"=>"form-control".($errors->has('address1')?" is-invalid":""),'placeholder'=>'Address line 1']) !!}
                                @if ($errors->has('address1'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('address1') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('address2', 'Address line 2', ['class' => 'form-label']) !!}
                                {!! Form::text("address2",null,["class"=>"form-control".($errors->has('address2')?" is-invalid":""),'placeholder'=>'Address line 2']) !!}
                                @if ($errors->has('address2'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('address2') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                @include('admin.helpers.ajax.locationCountryDropdown')
                                @if ($errors->has('country_id'))
                                    <small class="help-block text-danger">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                @include('admin.helpers.ajax.locationStateDropdown')
                                @if ($errors->has('state_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                @include('admin.helpers.ajax.locationCityDropdown')

                                @if ($errors->has('city_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                @include('admin.helpers.ajax.locationZipcodeDropdown')
                                @if ($errors->has('zipcode_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </small>
                                @endif
                            </div>

                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($bank_branch->id))
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
