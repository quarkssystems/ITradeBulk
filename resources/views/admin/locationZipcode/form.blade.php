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
            <p class="text-white mt-0 mb-5">{{__('COUNTRY: :country | PROVINCE: :state | CITY: :city', ['country' => $city->country->country_name, 'state' => $city->state->state_name, 'city' => $city->city_name])}}</p>
            <a href="{{ route("$route.index", $city_uuid) }}" class="btn btn-info">{{__('Back')}}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($zipcode->id) && (isset($copy) && !$copy))
                        {!! Form::model($zipcode, ['route' => ["$route.update", $city_uuid, $zipcode->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @else
                        {!! Form::model($zipcode, ['route' => ["$route.store", $city_uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($zipcode->id))
                            {!! Form::hidden('uuid', $zipcode->uuid) !!}
                        @endif
                            {!! Form::hidden('country_id', $city->country_id) !!}
                            {!! Form::hidden('state_id', $city->state_id) !!}
                            {!! Form::hidden('city_id', $city_uuid) !!}

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('zipcode_name', 'Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("zipcode_name",null,["class"=>"form-control".($errors->has('zipcode_name')?" is-invalid":""),"autofocus",'placeholder'=>'Name']) !!}

                            @if ($errors->has('zipcode_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('zipcode_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('zipcode', 'Postal Code', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("zipcode",null,["class"=>"form-control".($errors->has('zipcode')?" is-invalid":""),"autofocus",'placeholder'=>'Postal Code']) !!}

                            @if ($errors->has('zipcode'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('zipcode') }}</strong>
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
                            @if(!isset($zipcode->id))
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
