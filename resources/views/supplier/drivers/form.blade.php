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

     @if(isset($driver->id))
     <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="btn-group mb-3" role="group" aria-label="Basic example">
            <a class="btn {{request()->route()->named('supplier.drivers.*') ? 'btn-primary' : 'btn-secondary-1'}} " href="{{ route('supplier.drivers.edit', ['user_uuid' => $driver->uuid ])}}">{{__('Basic Detail')}} </a>

            <a class="btn {{request()->route()->named('user.vehicle.*') ? 'btn-primary' : 'btn-secondary-1'}} " href="{{
             route('user.vehicle.edit', ['user_uuid' => $driver->uuid ]) }}">{{__('Vehicle details')}}</a> 
  
        </div>
  </div>
  @endif

@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        @include('frontend.helpers.globalMessage.message')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($driver->id))
                        {!! Form::model($driver, ['route' => ["$route.update", $driver->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', "files" => true]) !!}
                    @else
                        {!! Form::model($driver, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', "files" => true]) !!}
                    @endif
                        
                        {!! Form::hidden('user_type', $role) !!}
                        @if(isset($driver->id))
                            {!! Form::hidden('uuid', $driver->uuid) !!}
                        @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("title",$title, null,["class"=>"form-control".($errors->has('title')?" is-invalid":""),'placeholder'=>'Select title']) !!}
                            @if ($errors->has('title'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('first_name', 'First name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("first_name",null,["class"=>"form-control".($errors->has('first_name')?" is-invalid":""),"autofocus",'placeholder'=>'First name']) !!}

                            @if ($errors->has('first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("last_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),'placeholder'=>'Last name']) !!}
                            @if ($errors->has('last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email(
                            "email",null,[ "class"=>"form-control ".($errors->has('email')?" is-invalid":""),
                                'placeholder'=>'Email'])
                            !!}

                            @if ($errors->has('email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password",["class"=>"form-control".($errors->has('password')?" is-invalid":""),'placeholder'=>'Password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password_confirmation",["class"=>"form-control".($errors->has('password_confirmation')?" is-invalid":""),'placeholder'=>'Confirm password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password_confirmation'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('gender', 'Gender', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("gender",$gender, null,["class"=>"form-control".($errors->has('gender')?" is-invalid":""),'placeholder'=>'Select gender']) !!}

                            @if ($errors->has('gender'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </small>
                            @endif
                        </div>
                          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}
                                {!! Form::select("status",$status, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),'placeholder'=>'Select status']) !!}

                               @if ($errors->has('status'))
                                   <small class="text-danger">
                                      <strong>{{ $errors->first('status') }}</strong>
                                  </small> 
                               @endif
                          </div>

                           <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                               {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}
                              {!! Form::textarea("remarks", null,["class"=>"form-control".($errors->has('remarks')?" is-invalid":""),'placeholder'=>'Remarks', 'rows' => 1]) !!}

                                @if ($errors->has('remarks'))
                                    <small class="text-danger">
                                       <strong>{{ $errors->first('remarks') }}</strong>
                                   </small>
                               @endif
                            </div>
                    </div>

           
                   
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_exit'])!!}
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

@endsection
@section("footerScript")
    @include("utils.map")
@endsection