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
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($delivery_vehicle_master->id) && (isset($copy) && !$copy))
                        {!! Form::model($delivery_vehicle_master, [
                            'route' => ["$route.update", $delivery_vehicle_master->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::model($delivery_vehicle_master, [
                            'route' => ["$route.store"],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if (isset($delivery_vehicle_master->id))
                        {!! Form::hidden('uuid', $delivery_vehicle_master->uuid) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('transport_type', 'Transport Type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('transport_type', $transportTypes, null, [
                                'class' => 'transport_vehicle form-control' . ($errors->has('transport_type') ? ' is-invalid' : ''),
                            ]) !!}
                            @if ($errors->has('transport_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('transport_type') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vehicle_capacity_id', 'Body Type', ['class' => 'form-label']) !!}
                            {!! Form::select('vehicle_capacity_id', $bodyTypes, null, [
                                'class' => 'form-control' . ($errors->has('vehicle_capacity_id') ? ' is-invalid' : ''),
                            ]) !!}
                            @if ($errors->has('vehicle_capacity_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle_capacity_id') }}</strong>
                                </small>
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
                            {!! Form::label('capacity', 'Capacity in TON', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('capacity', null, [
                                'class' => 'form-control' . ($errors->has('capacity') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Capacity',
                            ]) !!}

                            @if ($errors->has('capacity'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('capacity') }}</strong>
                                </small>
                            @endif
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('price_per_km', 'Price per KM', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('price_per_km', null, [
                                'class' => 'form-control' . ($errors->has('price_per_km') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Price per KM',
                            ]) !!}

                            @if ($errors->has('price_per_km'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('price_per_km') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('pallet_capacity_standard', 'Pallet Capacity Standard (1200 X 1000)', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::text('pallet_capacity_standard', null, [
                                'class' => 'form-control' . ($errors->has('pallet_capacity_standard') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Pallet Capacity',
                            ]) !!}

                            @if ($errors->has('pallet_capacity_standard'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('pallet_capacity_standard') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
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
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            {!! Form::submit('Save & Continue', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'name' => 'save_continue',
                            ]) !!}
                            @if (!isset($delivery_vehicle_master->id))
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
    <script src="{{ asset('assets/admin/js/logistic/driver.js?v=1.0.0') }}"></script>
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
