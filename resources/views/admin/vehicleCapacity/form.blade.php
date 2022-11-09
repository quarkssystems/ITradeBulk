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
                    @if(isset($vehicle_capacity->uuid) && (isset($copy) && !$copy))
                        {!! Form::model($vehicle_capacity, ['route' => ["$route.update", $vehicle_capacity->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($vehicle_capacity, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($vehicle_capacity->uuid))
                            {!! Form::hidden('id', $vehicle_capacity->uuid) !!}
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
                            {!! Form::label('max_weight', 'Maximum Carry Weight (in Kg)', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("max_weight",null,["class"=>"form-control numericInput".($errors->has('max_weight')?" is-invalid":""),'placeholder'=>'Maximum Carry Weight']) !!}

                            @if ($errors->has('max_weight'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('max_weight') }}</strong>
                                </small>
                            @endif
                        </div>

                      
                    </div>
                        {{--
                        <div class="form-group row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('load_space_volume', 'Loadspace Volume (in m3 cube)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("load_space_volume",null,["class"=>"form-control numericInput".($errors->has('load_space_volume')?" is-invalid":""),'placeholder'=>'Loadspace Volume']) !!}

                                @if ($errors->has('load_space_volume'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('load_space_volume') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('load_floor_length', 'Loadfloor Length (in meter)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("load_floor_length",null,["class"=>"form-control numericInput".($errors->has('load_floor_length')?" is-invalid":""),'placeholder'=>'Loadfloor Length']) !!}

                                @if ($errors->has('load_floor_length'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('load_floor_length') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('load_floor_width', 'Loadfloor Width (in meter)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("load_floor_width",null,["class"=>"form-control numericInput".($errors->has('load_floor_width')?" is-invalid":""),'placeholder'=>'Loadfloor Width']) !!}

                                @if ($errors->has('load_floor_width'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('load_floor_width') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Side loading (Curtains Only)</b></div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('side_load_height', 'Height (in meter)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("side_load_height",null,["class"=>"form-control numericInput".($errors->has('side_load_height')?" is-invalid":""),'placeholder'=>'Height']) !!}

                                @if ($errors->has('side_load_height'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('side_load_height') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('side_load_length', 'Length (in meter)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("side_load_length",null,["class"=>"form-control numericInput".($errors->has('side_load_length')?" is-invalid":""),'placeholder'=>'Length']) !!}

                                @if ($errors->has('side_load_length'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('side_load_length') }}</strong>
                                    </small>
                                @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Pallet Capacity</b></div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('pallet_capacity_standard', 'Standard (1200 X 1000)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("pallet_capacity_standard",null,["class"=>"form-control numericInput".($errors->has('pallet_capacity_standard')?" is-invalid":""),'placeholder'=>'']) !!}

                                @if ($errors->has('pallet_capacity_standard'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('pallet_capacity_standard') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('pallet_capacity_euro', 'Euro (800 X 1000)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("pallet_capacity_euro",null,["class"=>"form-control numericInput".($errors->has('pallet_capacity_euro')?" is-invalid":""),'placeholder'=>'']) !!}

                                @if ($errors->has('pallet_capacity_euro'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('pallet_capacity_euro') }}</strong>
                                    </small>
                                @endif
                            </div>

                        </div>
                        --}}
                        {{-- 
                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Full pallet dimension</b></div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('full_pallet_dimension_height', 'Height (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("full_pallet_dimension_height",null,["class"=>"form-control numericInput".($errors->has('full_pallet_dimension_height')?" is-invalid":""),'placeholder'=>'Height']) !!}

                                @if ($errors->has('full_pallet_dimension_height'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('full_pallet_dimension_height') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('full_pallet_dimension_width', 'Width (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("full_pallet_dimension_width",null,["class"=>"form-control numericInput".($errors->has('full_pallet_dimension_width')?" is-invalid":""),'placeholder'=>'Width']) !!}

                                @if ($errors->has('full_pallet_dimension_width'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('full_pallet_dimension_width') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('full_pallet_dimension_depth', 'Depth (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("full_pallet_dimension_depth",null,["class"=>"form-control numericInput".($errors->has('full_pallet_dimension_depth')?" is-invalid":""),'placeholder'=>'Depth']) !!}

                                @if ($errors->has('full_pallet_dimension_depth'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('full_pallet_dimension_depth') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('full_pallet_dimension_max_weight', 'Max weight (in Kg)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("full_pallet_dimension_max_weight",null,["class"=>"form-control numericInput".($errors->has('full_pallet_dimension_max_weight')?" is-invalid":""),'placeholder'=>'Max weight']) !!}

                                @if ($errors->has('full_pallet_dimension_max_weight'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('full_pallet_dimension_max_weight') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Half pallet dimension</b></div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('half_pallet_dimension_height', 'Height (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("half_pallet_dimension_height",null,["class"=>"form-control numericInput".($errors->has('half_pallet_dimension_height')?" is-invalid":""),'placeholder'=>'Height']) !!}

                                @if ($errors->has('half_pallet_dimension_height'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('half_pallet_dimension_height') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('half_pallet_dimension_width', 'Width (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("half_pallet_dimension_width",null,["class"=>"form-control numericInput".($errors->has('half_pallet_dimension_width')?" is-invalid":""),'placeholder'=>'Width']) !!}

                                @if ($errors->has('half_pallet_dimension_width'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('half_pallet_dimension_width') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('half_pallet_dimension_depth', 'Depth (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("half_pallet_dimension_depth",null,["class"=>"form-control numericInput".($errors->has('half_pallet_dimension_depth')?" is-invalid":""),'placeholder'=>'Depth']) !!}

                                @if ($errors->has('half_pallet_dimension_depth'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('half_pallet_dimension_depth') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('half_pallet_dimension_max_weight', 'Max weight (in Kg)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("half_pallet_dimension_max_weight",null,["class"=>"form-control numericInput".($errors->has('half_pallet_dimension_max_weight')?" is-invalid":""),'placeholder'=>'Max weight']) !!}

                                @if ($errors->has('half_pallet_dimension_max_weight'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('half_pallet_dimension_max_weight') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Quarter pallet dimension</b></div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('quarter_pallet_dimension_height', 'Height (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("quarter_pallet_dimension_height",null,["class"=>"form-control numericInput".($errors->has('quarter_pallet_dimension_height')?" is-invalid":""),'placeholder'=>'Height']) !!}

                                @if ($errors->has('quarter_pallet_dimension_height'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('quarter_pallet_dimension_height') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('quarter_pallet_dimension_width', 'Width (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("quarter_pallet_dimension_width",null,["class"=>"form-control numericInput".($errors->has('quarter_pallet_dimension_width')?" is-invalid":""),'placeholder'=>'Width']) !!}

                                @if ($errors->has('quarter_pallet_dimension_width'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('quarter_pallet_dimension_width') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('quarter_pallet_dimension_depth', 'Depth (in cm)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("quarter_pallet_dimension_depth",null,["class"=>"form-control numericInput".($errors->has('quarter_pallet_dimension_depth')?" is-invalid":""),'placeholder'=>'Depth']) !!}

                                @if ($errors->has('quarter_pallet_dimension_depth'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('quarter_pallet_dimension_depth') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('quarter_pallet_dimension_max_weight', 'Max weight (in Kg)', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("quarter_pallet_dimension_max_weight",null,["class"=>"form-control numericInput".($errors->has('quarter_pallet_dimension_max_weight')?" is-invalid":""),'placeholder'=>'Max weight']) !!}

                                @if ($errors->has('quarter_pallet_dimension_max_weight'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('quarter_pallet_dimension_max_weight') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>
                        --}}

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($vehicle_capacity->uuid))
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
