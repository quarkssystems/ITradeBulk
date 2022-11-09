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
                    @if(isset($permission->id) && (isset($copy) && !$copy))
                        {!! Form::model($permission, ['route' => ["$route.update", $permission->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($permission, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($permission->id))
                            {!! Form::hidden('uuid', $permission->uuid) !!}
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
                            {!! Form::label('module', 'Module', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("module",null,["class"=>"form-control".($errors->has('module')?" is-invalid":""),'placeholder'=>'Module']) !!}
                            @if ($errors->has('module'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('module') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('module_group', 'Module group', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("module_group",null,["class"=>"form-control".($errors->has('module_group')?" is-invalid":""),'placeholder'=>'Module group']) !!}
                            @if ($errors->has('module_group'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('module_group') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>

                    <div class="row">

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
                            {!! Form::label('routes', 'Routes', ['class' => 'form-label']) !!}
                            {!! Form::textarea("routes", isset($permission->id) ? implode(PHP_EOL, $permission->routes) : null,["class"=>"form-control".($errors->has('routes')?" is-invalid":""),'placeholder'=>'Routes', 'rows' => 3]) !!}
                            <small><i>{{__('Note: Enter new route on new line')}}</i></small>
                            @if ($errors->has('routes'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('routes') }}</strong>
                                </small>
                            @endif
                        </div>



                    </div>



                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($permission->id))
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
