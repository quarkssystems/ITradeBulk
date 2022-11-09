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
                    @if(isset($team->uuid) && (isset($copy) && !$copy))
                        {!! Form::model($team, ['route' => ["$route.update", $team->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($team, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($team->uuid))
                            {!! Form::hidden('id', $team->uuid) !!}
                        @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('first_name', 'First Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("first_name",null,["class"=>"form-control".($errors->has('first_name')?" is-invalid":""),"autofocus",'placeholder'=>'First Name']) !!}

                            @if ($errors->has('first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('last_name', 'Last Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("last_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),"autofocus",'placeholder'=>'Last Name']) !!}

                            @if ($errors->has('last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('designation', 'Designation', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("designation",null,["class"=>"form-control".($errors->has('designation')?" is-invalid":""),"autofocus",'placeholder'=>'Designation']) !!}

                            @if ($errors->has('designation'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('designation') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                            @if ($errors->has('status'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </small>
                            @endif
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('coloured_image', 'Coloured Image', ['class' => 'form-label label-required']) !!}
                            {!! Form::file("coloured_image", [
                                            "class"=>"form-control dropify ".($errors->has('coloured_image')?" is-invalid":""),
                                            'data-default-file' => (isset($team->uuid) && (isset($copy) && !$copy)) ? $team->coloured_image : ''
                                            ]) !!}
                            <small><i>{{__('Only JPG and PNG supported')}}</i></small>
                            @if ($errors->has('coloured_image'))
                                <br>
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('coloured_image') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('black_white_image', 'Black & White Image', ['class' => 'form-label label-required']) !!}
                            {!! Form::file("black_white_image", [
                                            "class"=>"form-control dropify ".($errors->has('black_white_image')?" is-invalid":""),
                                            'data-default-file' => (isset($team->uuid) && (isset($copy) && !$copy)) ? $team->black_white_image : ''
                                            ]) !!}
                            <small><i>{{__('Only JPG and PNG supported')}}</i></small>
                            @if ($errors->has('black_white_image'))
                                <br>
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('black_white_image') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                            {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($team->uuid))
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
