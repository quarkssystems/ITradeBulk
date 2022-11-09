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
                    @if(isset($role->id) && (isset($copy) && !$copy))
                        {!! Form::model($role, ['route' => ["$route.update", $role->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($role, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($role->id))
                            {!! Form::hidden('uuid', $role->uuid) !!}
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
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('permissions', 'Select permissions', ['class' => 'form-label label-required']) !!}
                            @if ($errors->has('permissions.*'))
                                <span class="help-block text-danger">
                                        <strong>{{ $errors->first('permissions.*') }}</strong>
                                    </span>
                            @endif

                            @if ($errors->has('permissions'))
                                <span class="help-block text-danger">
                                        <strong>{{ $errors->first('permissions') }}</strong>
                                    </span>
                            @endif

                            <div class="row permissions-container">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a href="javascript:;"><i><small class="select-deselect-roles" >{{__('Select all')}}</small></i></a>
                                </div>

                                @foreach($permissions as $permission)
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                        <label class="switch">
                                            <input name="permissions[]" type="checkbox" class="form-check-input" value="{{$permission->uuid}}" {{!is_null($role->permissions) && in_array($permission->uuid, $role->permissions) ? 'checked' : ''}}>
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="custom-checkbox-switch-description">{{$permission->name}}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($role->id))
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
