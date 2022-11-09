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
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            {{--            <a href="{{ route($redirectBackRoute) }}" class="btn btn-info">{{__('Back')}}</a> --}}
        </div>
    </div>
@endsection

@section('content')
    @include($navTab)


    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($logistic_detail->id))
                        {!! Form::model($logistic_detail, [
                            'route' => ["$route.update", $user->uuid, $logistic_detail->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                        ]) !!}
                    @else
                        {!! Form::model($logistic_detail, [
                            'route' => ["$route.store", $user->uuid],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if (isset($logistic_detail->id))
                        {!! Form::hidden('uuid', $logistic_detail->uuid) !!}
                    @endif
                    {!! Form::hidden('user_id', $user->uuid) !!}
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
                                'data-area-holder' => 'admin-ajax-capacity-area',
                                'data-ajax-url' => route('admin.ajax.postGetCapacityData'),
                                'data-view-file' => 'admin.helpers.ajax.CapacityDropDown',
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
                                    'data-area-holder' => 'admin-ajax-capacity-area',
                                    'data-ajax-url' => route('admin.ajax.postGetCapacity'),
                                    'data-view-file' => 'admin.helpers.ajax.CapacityDropDown',
                                ]) !!}
                            </div>

                            @if ($errors->has('vehicle_capacity_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('vehicle_capacity_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('admin.helpers.ajax.CapacityDropDown')
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
                                'placeholder' => 'Transport Capacity',
                            ]) !!}

                            @if ($errors->has('transport_capacity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('transport_capacity') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_type', 'Vehicle name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('vehicle_type', null, [
                                'id' => 'vehicle_type_admin',
                                'class' => 'form-control' . ($errors->has('vehicle_type') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Vehicle name',
                            ]) !!}

                            @if ($errors->has('vehicle_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_type') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('pallet_capacity_standard', 'Pallet Capacity Standard (1200 X 1000)', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::text('pallet_capacity_standard', null, [
                                'class' => 'form-control pallet_capacity_class' . ($errors->has('pallet_capacity_standard') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Pallet Capacity Standard (1200 X 1000)',
                            ]) !!}
                            <span class="badge badge-success"> Pallet only accepted "Blue Chep"</span>
                            @if ($errors->has('pallet_capacity'))
                                <span class="badge badge-success"> Pallet only accepted "Blue Chep"</span>
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('pallet_capacity_standard') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="row">
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
                    </div> --}}
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
                    {{--             <div class="row">

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
                    </div>
--}}


                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Length</label>
                            <input type="text" name="truck_length"
                                value="{{ $delivery_vehicle_master->truck_length != null ? $delivery_vehicle_master->truck_length : '' }}"
                                class="form-control truck_length" placeholder="Truck Length">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Width</label>
                            <input type="text" name="truck_width"
                                value="{{ $delivery_vehicle_master->truck_width != null ? $delivery_vehicle_master->truck_width : '' }}"
                                class="form-control truck_width" placeholder="Truck Width">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Height</label>
                            <input type="text" name="truck_height"
                                value="{{ $delivery_vehicle_master->truck_height != null ? $delivery_vehicle_master->truck_height : '' }}"
                                class="form-control truck_height" placeholder="Truck Height">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Payload(Kg)</label>
                            <input type="text" name="truck_payload"
                                value="{{ $delivery_vehicle_master->truck_payload != null ? $delivery_vehicle_master->truck_payload : '' }}"
                                class="form-control" placeholder="Truck Payload(Kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required truckwithtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Truck Max Pallets</label>
                            <input type="text" name="truck_max_pallets"
                                value="{{ $delivery_vehicle_master->truck_max_pallets != null ? $delivery_vehicle_master->truck_max_pallets : '' }}"
                                class="form-control" placeholder="Truck Max Pallets">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Length</label>
                            <input type="text" name="trailer_length"
                                value="{{ $delivery_vehicle_master->trailer_length != null ? $delivery_vehicle_master->trailer_length : '' }}"
                                class="form-control trailer_length" placeholder="Trailer Length">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Width</label>
                            <input type="text" name="trailer_width"
                                value="{{ $delivery_vehicle_master->trailer_width != null ? $delivery_vehicle_master->trailer_width : '' }}"
                                class="form-control trailer_width" placeholder="Trailer Width">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Height</label>
                            <input type="text" name="trailer_height"
                                value="{{ $delivery_vehicle_master->trailer_height != null ? $delivery_vehicle_master->trailer_height : '' }}"
                                class="form-control trailer_height" placeholder="Trailer Height">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Payload(Kg)</label>
                            <input type="text" name="trailer_payload"
                                value="{{ $delivery_vehicle_master->trailer_payload != null ? $delivery_vehicle_master->trailer_payload : '' }}"
                                class="form-control" placeholder="Trailer Payload(Kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required withtrailer"
                            @if (isset($logistic_detail->transport_type) && $logistic_detail->transport_type != 'Truck') { style ='display:none;' } @endif>
                            <label for="" class="form-label label-required">Trailer Max Pallets</label>
                            <input type="text" name="trailer_max_pallets"
                                value="{{ $delivery_vehicle_master->trailer_max_pallets != null ? $delivery_vehicle_master->trailer_max_pallets : '' }}"
                                class="form-control" placeholder="Trailer Max Pallets">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Body Volumn M3</label>
                            <input type="text" name="body_volumn"
                                value="{{ $delivery_vehicle_master->body_volumn != null ? $delivery_vehicle_master->body_volumn : '' }}"
                                class="form-control body_volumn" placeholder="Body Volumn M3">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Payload (kg)</label>
                            <input type="text" name="combine_payload"
                                value="{{ $delivery_vehicle_master->combine_payload != null ? $delivery_vehicle_master->combine_payload : '' }}"
                                class="form-control" placeholder="Payload (kg)">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <label for="" class="form-label label-required">Pallets</label>
                            <input type="text" name="combine_pallets"
                                value="{{ $delivery_vehicle_master->combine_pallets != null ? $delivery_vehicle_master->combine_pallets : '' }}"
                                class="form-control" placeholder="Pallets">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('address1', 'Address line 1', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('address1', null, [
                                'class' => 'form-control' . ($errors->has('address1') ? ' is-invalid' : ''),
                                'placeholder' => 'Address line 1',
                            ]) !!}
                            @if ($errors->has('address1'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('address1') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('address2', 'Address line 2', ['class' => 'form-label']) !!}
                            {!! Form::text('address2', null, [
                                'class' => 'form-control' . ($errors->has('address2') ? ' is-invalid' : ''),
                                'placeholder' => 'Address line 2',
                            ]) !!}
                            @if ($errors->has('address2'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('address2') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('admin.helpers.ajax.locationCountryDropdown')
                            @if ($errors->has('country_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('admin.helpers.ajax.locationStateDropdown')
                            @if ($errors->has('state_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('admin.helpers.ajax.locationCityDropdown')

                            @if ($errors->has('city_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('admin.helpers.ajax.locationZipcodeDropdown')
                            @if ($errors->has('zipcode_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>




                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            {!! Form::submit('Save & Continue', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'name' => 'save_continue',
                            ]) !!}
                            @if (!isset($logistic_detail->id))
                                {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.truck_length').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let truck_length = $('.truck_length').val();
                let truck_width = $('.truck_width').val();
                let truck_height = $('.truck_height').val();
                console.log('fghjkl;', transport_type);
                if (transport_type == 'Truck') {
                    $('.body_volumn').val(truck_length * truck_width * truck_height)
                }
            })
            $('.truck_width').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let truck_length = $('.truck_length').val();
                let truck_width = $('.truck_width').val();
                let truck_height = $('.truck_height').val();
                if (transport_type == 'Truck') {
                    $('.body_volumn').val(truck_length * truck_width * truck_height)
                }
            })
            $('.truck_height').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let truck_length = $('.truck_length').val();
                let truck_width = $('.truck_width').val();
                let truck_height = $('.truck_height').val();
                if (transport_type == 'Truck') {
                    $('.body_volumn').val(truck_length * truck_width * truck_height)
                }
            })

            $('.trailer_length').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let trailer_length = $('.trailer_length').val();
                let trailer_width = $('.trailer_width').val();
                let trailer_height = $('.trailer_height').val();
                if (transport_type == 'Truck with trailer') {
                    $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
                }

            })
            $('.trailer_width').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let trailer_length = $('.trailer_length').val();
                let trailer_width = $('.trailer_width').val();
                let trailer_height = $('.trailer_height').val();
                if (transport_type == 'Truck with trailer') {
                    $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
                }
            })
            $('.trailer_height').on('keyup', function() {
                let transport_type = $('.transport_vehicle  :selected').val();
                let trailer_length = $('.trailer_length').val();
                let trailer_width = $('.trailer_width').val();
                let trailer_height = $('.trailer_height').val();
                if (transport_type == 'Truck with trailer') {
                    $('.body_volumn').val(trailer_length * trailer_width * trailer_height)
                }

            })
        })
    </script>
@endsection
