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
            <p class="text-white mt-0 mb-5">{{__('Country: :country | Province: :state', ['country' => $state->country->country_name, 'state' => $state->state_name])}}</p>
            <a href="{{ route("$route.index", $state_uuid) }}" class="btn btn-info">{{__('Back')}}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($city->id) && (isset($copy) && !$copy))
                        {!! Form::model($city, ['route' => ["$route.update", $state_uuid, $city->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($city, ['route' => ["$route.store", $state_uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($city->id))
                            {!! Form::hidden('uuid', $city->uuid) !!}
                        @endif
                            {!! Form::hidden('country_id', $state->country_id) !!}
                            {!! Form::hidden('state_id', $state_uuid) !!}

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('city_name', 'Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("city_name",null,["class"=>"form-control".($errors->has('city_name')?" is-invalid":""),"autofocus",'placeholder'=>'Name']) !!}

                            @if ($errors->has('city_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('city_name') }}</strong>
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

                        <div class="row">
                            <div class="col-xs-12 col-lg-8 col-md-8 mb-4">
                                <input id="pac-input" class="controls form-control map-location-search-box" type="text" placeholder="Search location">
                                <div id="map-canvas" style="height: 300px"></div>
                            </div>
                            <div class="col-xs-12 col-lg-4 col-md-4">
                                {!! Form::label('latitude', 'Latitude', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("latitude",null,["class"=>"form-control".($errors->has('latitude')?" is-invalid":""),'placeholder'=>'Latitude', 'id' => 'default_latitude']) !!}
                                @if ($errors->has('latitude'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('latitude') }}</strong>
                                    </small>
                                @endif
                                <br>
                                {!! Form::label('longitude', 'Longitude', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("longitude",null,["class"=>"form-control".($errors->has('longitude')?" is-invalid":""),'placeholder'=>'Longitude', 'id' => 'default_longitude']) !!}
                                @if ($errors->has('longitude'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('longitude') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($city->id))
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
@section("footerData")
    @include("utils.map")
@endsection