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
            <p class="text-white mt-0 mb-5">{{__('Country: :country', ['country' => $country->country_name])}}</p>
            <a href="{{ route("$route.index", $country_uuid) }}" class="btn btn-info">{{__('Back')}}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($state->id) && (isset($copy) && !$copy))
                        {!! Form::model($state, ['route' => ["$route.update", $country_uuid, $state->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($state, ['route' => ["$route.store", $country_uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($state->id))
                            {!! Form::hidden('uuid', $state->uuid) !!}
                        @endif
                            {!! Form::hidden('country_id', $country_uuid) !!}

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('state_name', 'Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("state_name",null,["class"=>"form-control".($errors->has('state_name')?" is-invalid":""),"autofocus",'placeholder'=>'Name']) !!}

                            @if ($errors->has('state_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('state_name') }}</strong>
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
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($state->id))
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
