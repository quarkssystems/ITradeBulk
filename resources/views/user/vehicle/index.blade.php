@extends('supplier.layouts.main')
@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{ $pageTitle }}</h4>
            </div>
        </div>
    </div>
    @if (isset($user->id) && auth()->user()->role == 'COMPANY')
        <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a class="btn {{ request()->route()->named('supplier.drivers.*')? 'btn-primary': 'btn-secondary-1' }} "
                    href="{{ route('supplier.drivers.edit', ['user_uuid' => $user->uuid]) }}">{{ __('Basic Detail') }} </a>

                <a class="btn {{ request()->route()->named('user.vehicle.*')? 'btn-primary': 'btn-secondary-1' }} "
                    href="{{ route('user.vehicle.edit', ['user_uuid' => $user->uuid]) }}">{{ __('Vehicle details') }}</a>

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

                    {!! Form::model($logistic_detail, [
                        'route' => ["$route.update", $user->uuid],
                        'method' => 'PUT',
                        'id' => 'form',
                        'autocomplete' => 'off',
                        'name' => 'usersForm',
                    ]) !!}

                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    {!! Form::hidden('user_id', $user->uuid) !!}
                    <input type="hidden" name="transporterTrading" id="transporterTrading"
                        value="{{ $transporterTrading }}">
                    {{-- {!! Form::hidden('transporterTrading', $transporterTrading) !!} --}}
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('phone', 'Phone', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('phone', null, [
                                'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                                'autofocus',
                                'data-inputmask' => "'mask': '(999) 999 9999'",
                                'placeholder' => 'Phone',
                            ]) !!}

                            @if ($errors->has('phone'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('driving_licence', 'Driving licence?', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('driving_licence', ['YES' => 'YES', 'NO' => 'NO'], null, [
                                'class' => 'form-control' . ($errors->has('driving_licence') ? ' is-invalid' : ''),
                            ]) !!}
                            @if ($errors->has('driving_licence'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('driving_licence') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('transport_type', 'Transport type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('transport_type', $transportTypes, null, [
                                'class' => 'transport_type  transport_vehicle form-control' . ($errors->has('transport_type') ? ' is-invalid' : ''),
                                'data-area-holder' => 'frontend-ajax-capacity-area',
                                'data-ajax-url' => route('frontend.ajax.postGetCapacityData'),
                                'data-view-file' => 'frontend.helpers.ajax.CapacityDropDown',
                            ]) !!}
                            @if ($errors->has('transport_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('transport_type') }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <div class="">

                                {!! Form::label('vehicle_capacity_id', 'Body Type', ['class' => 'form-label']) !!}
                                {!! Form::select('vehicle_capacity_id', $bodyTypes, null, [
                                    'class' => 'form-control load-capacity-on-change' . ($errors->has('vehicle_capacity_id') ? ' is-invalid' : ''),
                                    'placeholder' => 'Select body type',
                                    'data-area-holder' => 'frontend-ajax-capacity-area',
                                    'data-ajax-url' => route('frontend.ajax.postGetCapacity'),
                                    'data-view-file' => 'frontend.helpers.ajax.CapacityDropDown',
                                ]) !!}

                            </div>

                            @if ($errors->has('vehicle_capacity_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('vehicle_capacity_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.CapacityDropDown')
                            @if ($errors->has('vehicle_type'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('vehicle_type') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('transport_capacity', 'Transport Capacity in Ton', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('transport_capacity', null, [
                                'class' => 'form-control capacity_class' . ($errors->has('transport_capacity') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Transport Capacity in Ton',
                            ]) !!}

                            @if ($errors->has('transport_capacity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('transport_capacity') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('pallet_capacity_standard', 'Pallet Capacity Standard (200x120x100cm)', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::text('pallet_capacity_standard', null, [
                                'class' => 'form-control pallet_capacity_class' . ($errors->has('pallet_capacity_standard') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Pallet Capacity Standard (200x120x100cm)',
                            ]) !!}
                            <span class="badge badge-success"> Pallet only accepted "Blue Chep"</span>
                            @if ($errors->has('pallet_capacity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('pallet_capacity_standard') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Length</label>
                            <input type="text" name="truck_length" class="form-control truck_length"
                                value="{{ $logistic_detail->truck_length }}" placeholder="Truck Length">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Width</label>
                            <input type="text" name="truck_width" class="form-control truck_width"
                                value="{{ $logistic_detail->truck_width }}" placeholder="Truck Width">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Height</label>
                            <input type="text" name="truck_height" class="form-control truck_height"
                                value="{{ $logistic_detail->truck_height }}" placeholder="Truck Height">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Payload(Kg)</label>
                            <input type="text" name="truck_payload" class="form-control"
                                value="{{ $logistic_detail->truck_payload }}" placeholder="Truck Payload(Kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Max Pallets</label>
                            <input type="text" name="truck_max_pallets" class="form-control"
                                value="{{ $logistic_detail->truck_max_pallets }}" placeholder="Truck Max Pallets">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Length</label>
                            <input type="text" name="trailer_length" class="form-control trailer_length"
                                value="{{ $logistic_detail->trailer_length }}" placeholder="Trailer Length">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Width</label>
                            <input type="text" name="trailer_width" class="form-control trailer_width"
                                value="{{ $logistic_detail->trailer_width }}" placeholder="Trailer Width">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Height</label>
                            <input type="text" name="trailer_height" class="form-control trailer_height"
                                value="{{ $logistic_detail->trailer_height }}" placeholder="Trailer Height">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Payload(Kg)</label>
                            <input type="text" name="trailer_payload" class="form-control"
                                value="{{ $logistic_detail->trailer_payload }}" placeholder="Trailer Payload(Kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Max Pallets</label>
                            <input type="text" name="trailer_max_pallets" class="form-control"
                                value="{{ $logistic_detail->trailer_max_pallets }}" placeholder="Trailer Max Pallets">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Body Volumn M3</label>
                            <input type="text" name="body_volumn" class="form-control body_volumn"
                                value="{{ $logistic_detail->body_volumn }}" placeholder="Body Volumn M3">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Payload (kg)</label>
                            <input type="text" name="combine_payload" class="form-control"
                                value="{{ $logistic_detail->combine_payload }}" placeholder="Payload (kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Pallets</label>
                            <input type="text" name="combine_pallets" class="form-control"
                                value="{{ $logistic_detail->combine_pallets }}" placeholder="Pallets">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('availability', 'Availability', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('availability', $availabilityTypes, null, [
                                'class' => 'form-control' . ($errors->has('availability') ? ' is-invalid' : ''),
                                'placeholder' => 'Availability',
                            ]) !!}

                            @if ($errors->has('availability'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('availability') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('work_type', 'Work type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('work_type', $workTypes, null, [
                                'class' => 'form-control' . ($errors->has('work_type') ? ' is-invalid' : ''),
                                'placeholder' => 'Work type',
                            ]) !!}

                            @if ($errors->has('work_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('work_type') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="">Area</label>
                            <select name="trading_area" id="trading_area" class="form-control" multiple>
                                <option value="">Select area</option>
                                @foreach ($tradingArea as $area)
                                    <option value="{{ $area->area }}"
                                        {{ $area->area == $logistic_detail->trading_area ? 'selected' : '' }}>
                                        {{ $area->area }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('area'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('area') }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="">Country</label>
                            <select name="country_id[]" id="country_id" class="form-control" multiple>
                                <option value="">Select Country</option>
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $key }}"
                                        {{ !in_array($key, (array) $transporterTrading) ? 'selected' : '' }}>
                                        {{ $country }}</option>
                                @endforeach

                            </select>
                            @if ($errors->has('country_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('country_id') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="">Province</label>
                            {{-- @if (count($transporterTrading) == 0) --}}
                            <select name="state_id[]" id="state_id" class="form-control" multiple>
                                <option value="">Select Province</option>

                            </select>
                            {{-- @else 
                            <select name="state_id[]" id="state_id" class="form-control" multiple>
                                <option value="">Select Province</option>
                            
                            </select>

                            @endif --}}
                            @if ($errors->has('state_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="">Town</label>
                            <select name="city_id[]" id="city_id" class="form-control" multiple>
                                <option value="">Select Town</option>

                            </select>
                            @if ($errors->has('city_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_make', 'Vehicle Make', ['class' => 'form-label']) !!}
                            {!! Form::text('vehicle_make', null, [
                                'class' => 'form-control' . ($errors->has('vehicle_make') ? ' is-invalid' : ''),
                                'placeholder' => 'Vehicle Make',
                            ]) !!}

                            @if ($errors->has('vehicle_make'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_make') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_registration_number', 'Vehicle Registration Number', ['class' => 'form-label']) !!}
                            {!! Form::text('vehicle_registration_number', null, [
                                'class' => 'form-control' . ($errors->has('vehicle_registration_number') ? ' is-invalid' : ''),
                                'placeholder' => 'Vehicle Registration Number',
                            ]) !!}

                            @if ($errors->has('vehicle_registration_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_registration_number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_model', 'Vehicle Model', ['class' => 'form-label']) !!}
                            {!! Form::text('vehicle_model', null, [
                                'class' => 'form-control' . ($errors->has('vehicle_model') ? ' is-invalid' : ''),
                                'placeholder' => 'Vehicle Model',
                            ]) !!}

                            @if ($errors->has('vehicle_model'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_model') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vin_number', 'Vin Number', ['class' => 'form-label']) !!}
                            {!! Form::text('vin_number', null, [
                                'class' => 'form-control' . ($errors->has('vin_number') ? ' is-invalid' : ''),
                                'placeholder' => 'Vin Number',
                            ]) !!}

                            @if ($errors->has('vin_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vin_number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_color', 'Vehicle Color', ['class' => 'form-label']) !!}
                            {!! Form::text('vehicle_color', null, [
                                'class' => 'form-control' . ($errors->has('vehicle_color') ? ' is-invalid' : ''),
                                'placeholder' => 'Vehicle Color',
                            ]) !!}

                            @if ($errors->has('vehicle_color'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_color') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('pallets_available', 'Pallets available', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("pallets_available",null,["class"=>"form-control".($errors->has('pallets_available')?" is-invalid":""),'placeholder'=>'Pallets available']) !!}

                                @if ($errors->has('pallets_available'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('pallets_available') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('pallets_required', 'Pallets required', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("pallets_required",null,["class"=>"form-control".($errors->has('pallets_required')?" is-invalid":""),'placeholder'=>'Pallets required']) !!}

                                @if ($errors->has('pallets_required'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('pallets_required') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('pallets_deposit', 'Pallets deposit', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("pallets_deposit",null,["class"=>"form-control".($errors->has('pallets_deposit')?" is-invalid":""),'placeholder'=>'Pallets deposit']) !!}

                                @if ($errors->has('pallets_deposit'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('pallets_deposit') }}</strong>
                                    </small>
                                @endif
                            </div>
                    </div> --}}

                    {{-- <div class="row">

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
                                @include('frontend.helpers.ajax.locationCountryDropdown')
                                @if ($errors->has('country_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationStateDropdown')
                            @if ($errors->has('state_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationCityDropdown')

                            @if ($errors->has('city_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationZipcodeDropdown')
                            @if ($errors->has('zipcode_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div> --}}

                    {{-- @if (auth()->check() && auth()->user()->role == 'DRIVER' && auth()->user()->logistic_type == 'INDIVIDUAL') --}}
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {{-- @elseif(auth()->check() && auth()->user()->role == "COMPANY")
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>

                    @endif --}}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerScript')
    @include('utils.map')
    <script>
        $(document).ready(function() {

            let transporterTrading = $('#transporterTrading').val();
            transporterTrading = JSON.parse(transporterTrading);
            console.log('transporterTrading: ', transporterTrading);
            // let transporterTrading = "{{ $transporterTrading }}";
            // console.log('transporterTrading: ', JSON.stringify(transporterTrading));
            setTimeout(() => {
                let trading_area = $('#trading_area').val();
                // trading_area.forEach(function(val){
                //     // console.log("trading_area: ",val);
                // })

                $(trading_area).each(function(i, data) {
                    if (data == 'country') {
                        $('#state_id').parent().hide();
                        $('#city_id').parent().hide();
                        $('#country_id').parent().show();
                    } else if (data == 'province') {
                        $('#country_id').parent().show();
                        $('#state_id').parent().show();
                        $('#city_id').parent().hide();
                    } else if (data == 'town') {
                        $('#country_id').parent().show();
                        $('#state_id').parent().show();
                        $('#city_id').parent().show();
                    }
                    console.log(data);
                })

                // $('#country_id').each(function(){
                //     console.log('fdgjhk',$(this));
                // })
                $("#country_id :selected").each(function() {
                    // alert(this.value);
                    let countryId = [];
                    countryId.push(this.value);
                    console.log('countryId: ', countryId);

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('getProvince') }}",
                        data: {
                            "country_id[]": countryId
                        },
                        success: function(data) {
                            console.log('data: ', data);
                            $('#state_id').parent().show();
                            $('#state_id').empty();
                            $('#state_id').append(
                                `<option value="">Select province</option>`);
                            data.forEach(element => {
                                // console.log('datajgkhj: ',transporterTrading,element);
                                if (transporterTrading.includes(element.uuid)) {

                                    $('#state_id').append(
                                        `<option value="${element.uuid}" selected>${element.state_name}</option>`
                                        );
                                } else {

                                    $('#state_id').append(
                                        `<option value="${element.uuid}">${element.state_name}</option>`
                                        );
                                }

                            });
                        }
                    });

                });

                $("#state_id :selected").each(function() {
                    // $('#state_id').on('change',function(){
                    let state_id = [];
                    state_id.push(this.value);
                    console.log('state_id: ', state_id);
                    // let state_id = $(this).val();
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('getProvince') }}",
                        data: {
                            state_id: state_id
                        },
                        success: function(data) {
                            console.log('data: ', data);
                            $('#city_id').parent().show();
                            $('#city_id').empty();
                            $('#city_id').append(
                                `<option value="">Select Town</option>`);
                            data.forEach(element => {
                                console.log('data: ', element);
                                $('#city_id').append(
                                    `<option value="${element.uuid}">${element.city_name}</option>`
                                    );

                            });
                        }
                    });
                })
                // if(area.includes("province") || area.includes("town")){
                //     $.ajax({
                //         type: 'GET',
                //         url: "{{ route('getProvince') }}",
                //         data:{
                //             country_id:countryId
                //         },
                //         success:function(data){
                //             console.log('data: ',data);
                //             $('#state_id').parent().show();
                //             $('#state_id').empty();
                //             $('#state_id').append(`<option value="">Select province</option>`);
                //             data.forEach(element => {
                //                 console.log('data: ',element);
                //                 $('#state_id').append(`<option value="${element.uuid}">${element.state_name}</option>`);

                //             });
                //         }
                //     });
                // }
            }, 1000);

            let area = '';
            $('#country_id').on('change', function() {
                let countryId = $(this).val();
                // let area = $('#trading_area').val();
                console.log('area: ', area);
                if (area.includes("province") || area.includes("town")) {

                    // if(area.includes("country","province","town"))
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('getProvince') }}",
                        data: {
                            country_id: countryId
                        },
                        success: function(data) {
                            console.log('data: ', data);
                            $('#state_id').parent().show();
                            $('#state_id').empty();
                            $('#state_id').append(`<option value="">Select province</option>`);
                            data.forEach(element => {
                                console.log('data: ', element);
                                $('#state_id').append(
                                    `<option value="${element.uuid}">${element.state_name}</option>`
                                    );

                            });
                        }
                    });
                }

            })

            $('#state_id').on('change', function() {
                let state_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('getProvince') }}",
                    data: {
                        state_id: state_id
                    },
                    success: function(data) {
                        console.log('data: ', data);
                        $('#city_id').parent().show();
                        $('#city_id').empty();
                        $('#city_id').append(`<option value="">Select Town</option>`);
                        data.forEach(element => {
                            console.log('data: ', element);
                            $('#city_id').append(
                                `<option value="${element.uuid}">${element.city_name}</option>`
                                );

                        });
                    }
                });
            })

            $('#country_id').parent().hide();
            $('#state_id').parent().hide();
            $('#city_id').parent().hide();
            $('#trading_area').on('change', function() {
                // $('#country_id').parent().hide();
                // $('#state_id').parent().hide();
                // $('#city_id').parent().hide();
                let areaVal = $(this).val();
                area = areaVal;
                $(areaVal).each(function(i, data) {
                    if (data == 'country') {
                        $('#state_id').parent().hide();
                        $('#city_id').parent().hide();
                        $('#country_id').parent().show();
                    } else if (data == 'province') {
                        $('#country_id').parent().show();
                        $('#state_id').parent().show();
                        $('#city_id').parent().hide();
                    } else if (data == 'town') {
                        $('#country_id').parent().show();
                        $('#state_id').parent().show();
                        $('#city_id').parent().show();
                    }
                    console.log(data);
                })
            })
        })


        $('.truck_length').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let truck_length = $('.truck_length').val();
            let truck_width = $('.truck_width').val();
            let truck_height = $('.truck_height').val();
            if (transport_type == 'Truck') {
                $('.body_volumn').val(truck_length * truck_width * truck_height)
            }
        })
        $('.truck_width').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let truck_length = $('.truck_length').val();
            let truck_width = $('.truck_width').val();
            let truck_height = $('.truck_height').val();
            if (transport_type == 'Truck') {
                $('.body_volumn').val(truck_length * truck_width * truck_height)
            }
        })
        $('.truck_height').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let truck_length = $('.truck_length').val();
            let truck_width = $('.truck_width').val();
            let truck_height = $('.truck_height').val();
            if (transport_type == 'Truck') {
                $('.body_volumn').val(truck_length * truck_width * truck_height)
            }
        })

        $('.trailer_length').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let trailer_length = $('.trailer_length').val();
            let trailer_width = $('.trailer_width').val();
            let trailer_height = $('.trailer_height').val();
            if (transport_type == 'Truck with trailer') {
                $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
            }

        })
        $('.trailer_width').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let trailer_length = $('.trailer_length').val();
            let trailer_width = $('.trailer_width').val();
            let trailer_height = $('.trailer_height').val();
            if (transport_type == 'Truck with trailer') {
                $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
            }
        })
        $('.trailer_height').on('keyup', function() {
            let transport_type = $('.transport_type').val();
            let trailer_length = $('.trailer_length').val();
            let trailer_width = $('.trailer_width').val();
            let trailer_height = $('.trailer_height').val();
            if (transport_type == 'Truck with trailer') {
                $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
            }

        })
    </script>
@endsection
