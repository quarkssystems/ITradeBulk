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
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a href="{{ route('user.vehicle.index') }}" class="btn btn-primary">Back</a>

    </div>
@endsection
@section('content')
    <div class="col-md-12">
        @include('frontend.helpers.globalMessage.message')
    </div>

    {{-- {{ dd($logisticDetails) }} --}}
    @if (isset($logisticDetails->id))
        {!! Form::model($logisticDetails, [
            'route' => ["$route.update", $logisticDetails->uuid],
            'method' => 'PUT',
            'id' => 'form',
            'autocomplete' => 'off',
            'name' => 'usersForm',
            'files' => true,
        ]) !!}
    @else
        {!! Form::model($logisticDetails, [
            'route' => ["$route.store"],
            'id' => 'form',
            'autocomplete' => 'off',
            'name' => 'usersForm',
            'files' => true,
        ]) !!}
    @endif
    <input autocomplete="off" name="hidden" type="text" style="display: none">

    @if (isset($logisticDetails->id))
        {!! Form::hidden('uuid', $logisticDetails->uuid) !!}
    @endif
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <a class="" href="#productBasicDetails" data-toggle="collapse" data-target="#productBasicDetails"
                aria-expanded="true">
                {{ __('BASIC DETAILS') }}
            </a>


        </div>
    </div>
    <div class="row collapse show" id="productBasicDetails" aria-expanded="true">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 form-group required">
                    {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}
                    {!! Form::text('name', null, [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'autofocus',
                        'placeholder' => 'Name',
                    ]) !!}

                    @if ($errors->has('name'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('name') }}</strong>
                        </small>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 form-group required">
                    {!! Form::label('phone', 'Phone', ['class' => 'form-label label-required']) !!}
                    {!! Form::text('phone', null, [
                        'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                        'autofocus',
                        'placeholder' => 'Phone',
                    ]) !!}

                    @if ($errors->has('phone'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </small>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 form-group required">
                    {!! Form::label('driving_licence', 'Driving Licence', ['class' => 'form-label label-required']) !!}
                    {!! Form::text('driving_licence', null, [
                        'class' => 'form-control' . ($errors->has('driving_licence') ? ' is-invalid' : ''),
                        'autofocus',
                        'placeholder' => 'Driving Licence',
                    ]) !!}

                    @if ($errors->has('driving_licence'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('driving_licence') }}</strong>
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <a class="" href="#vehicleBasicDetails" data-toggle="collapse" data-target="#vehicleBasicDetails"
                aria-expanded="true">
                {{ __('VEHICLE DETAILS') }}
            </a>


        </div>
    </div>
    <div class="row collapse show" id="vehicleBasicDetails" aria-expanded="true">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
            <div class="row">
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
            </div>
            <div class="row">

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
            Note: If you could not find your vehicle in the list please contact our <a
                href="{{ route('contact') }}">customer support</a>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <a class="" href="#areaBasicDetails" data-toggle="collapse" data-target="#areaBasicDetails"
                aria-expanded="true">
                {{ __('SELECT AREA') }}
            </a>


        </div>
    </div>
    <div class="row collapse show" id="areaBasicDetails" aria-expanded="true">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                    {{-- <label for="">Area</label> --}}
                    <label for=""></label>

                    <select name="trading_area" id="trading_area" class="form-control ">
                        {{-- <select name="trading_area" id="trading_area" class="form-control " multiple> --}}
                        <option value="">Select area</option>
                        @foreach ($tradingArea as $area)
                            <option value="{{ $area->area }}"
                                {{ $area->area == $logisticDetails->trading_area ? 'selected' : '' }}>
                                {{ ucfirst($area->area) }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('area'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('area') }}</strong>
                        </small>
                    @endif
                    Note: if you could not find your town in the list please contact our <a
                        href="{{ route('contact') }}">customer support</a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                    <label for="">Country</label>
                    @if (isset($logisticDetails->id))
                        <select name="country_id[]" id="country_id" class="form-control">
                            {{-- <select name="country_id[]" id="country_id" class="form-control" multiple> --}}
                            <option value="">Select Country</option>
                            {{-- countriesAll
                            statesAll
                            citiesAll --}}
                            {{-- countries --}}
                            @foreach ($countriesAll as $key => $country)
                                <option value="{{ $country->uuid }}"
                                    {{ in_array($country->uuid, (array) $countries) ? 'selected' : '' }}>
                                    {{ $country->country_name }}</option>
                            @endforeach

                        </select>
                        {{-- Note: If you can not find country you want please contact admin --}}
                    @else
                        <select name="country_id[]" id="country_id" class="form-control">
                            <option value="">Select Country</option>


                            @foreach ($countriesAll as $key => $country)
                                <option value="{{ $country->uuid }}"
                                    {{ in_array($country->uuid, (array) $countries) ? 'selected' : '' }}>
                                    {{ $country->country_name }}</option>
                            @endforeach
                            {{-- @foreach ($countries as $key => $country)
                                <option value="{{ $country->uuid }}">
                                    {{ $country->country_name }}</option>
                            @endforeach --}}
                        </select>
                        {{-- Note: If you can not find country you want please contact admin --}}
                    @endif
                    @if ($errors->has('country_id'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('country_id') }}</strong>
                        </small>
                    @endif

                </div>
                <input type="hidden" name="transporterTrading" id="transporterTrading"
                    value="{{ $transporterTrading }}" disabled>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                    <label for="">Province</label>
                    {{-- @if (count($transporterTrading) == 0) --}}
                    <div>
                        @if (isset($logisticDetails->id))
                            <select name="state_id[]" id="state_id" class="form-control 123" multiple>
                                {{-- <option value="">Select Province</option> --}}
                                @foreach ($statesAll as $key => $state)
                                    <option value="{{ $state->uuid }}"
                                        {{ in_array($state->uuid, (array) $states) ? 'selected' : '' }}>
                                        {{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            {{-- Note: If you can not find province you want please contact admin --}}
                        @else
                            <select name="state_id[]" id="state_id" class="form-control 456" multiple>
                                {{-- <option value="">Select Province</option> --}}

                            </select>
                            {{-- Note: If you can not find province you want please contact admin --}}
                        @endif
                    </div>
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
                    <div>
                        @if (isset($logisticDetails->id))
                            <select name="city_id[]" id="city_id" class="form-control" multiple>
                                {{-- <option value="">Select Town</option> --}}
                                @foreach ($citiesAll as $key => $country)
                                    <option value="{{ $country->uuid }}"
                                        {{ in_array($country->uuid, (array) $cities) ? 'selected' : '' }}>
                                        {{ $country->city_name }}</option>
                                @endforeach
                            </select>
                            {{-- Note: If you can not find city you want please contact admin --}}
                        @else
                            <select name="city_id[]" id="city_id" class="form-control" multiple>
                                {{-- <option value="">Select Town</option> --}}

                            </select>
                            {{-- Note: If you can not find city you want please contact admin --}}
                        @endif
                    </div>
                    @if ($errors->has('city_id'))
                        <small class="text-danger">
                            <strong>{{ $errors->first('city_id') }}</strong>
                        </small>
                    @endif

                </div>
            </div>

            {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save']) !!}
            {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footerScript')
    @include('utils.map')
    <script>
        $(document).ready(function() {

            let transporterTrading = $('#transporterTrading').val();
            console.log('transporterTrading: ', transporterTrading);
            if (transporterTrading != "") {
                transporterTrading = JSON.parse(transporterTrading);
            }
            setTimeout(() => {
                let trading_area = $('#trading_area').val();
                console.log('trading_area: ', trading_area);
                // trading_area.forEach(function(val){
                //     // console.log("trading_area: ",val);
                // })

                data = trading_area;
                // $(trading_area).each(function(i, data) {
                if (data == 'country') {
                    $('#state_id').parent().parent().hide();
                    $('#city_id').parent().parent().hide();
                    $('#country_id').parent().show();
                } else if (data == 'province') {
                    $('#country_id').parent().show();
                    $('#state_id').parent().parent().show();
                    $('#city_id').parent().parent().hide();
                } else if (data == 'town') {
                    $('#country_id').parent().show();
                    $('#state_id').parent().parent().show();
                    $('#city_id').parent().parent().show();
                }
                console.log(data);
                // })
            })

            //     $('#country_id :selected').each(function() {
            //         console.log('fdgjhk', $(this).val());
            //         $.ajax({
            //             type: 'GET',
            //             url: "{{ route('getProvince') }}",
            //             data: {
            //                 "country_id[]": $(this).val()
            //             },
            //             success: function(data) {
            //                 console.log('data: ', data);
            //                 // $('#state_id').parent().show();
            //                 // $('#state_id').empty();
            //                 // $('#state_id').append(
            //                 //     `<option value="">Select province</option>`);
            //                 data.forEach(element => {
            //                     // console.log('datajgkhj: ',transporterTrading,element);
            //                     if (transporterTrading.includes(element.uuid)) {

            //                         $('#state_id').append(
            //                             `<option value="${element.uuid}" selected>${element.state_name}</option>`
            //                         );
            //                     } else {

            //                         $('#state_id').append(
            //                             `<option value="${element.uuid}">${element.state_name}</option>`
            //                         );
            //                     }

            //                 });
            //             }
            //         });
            //     })
            //     $("#country_id :selected").each(function() {
            //         // alert(this.value);
            //         let countryId = [];
            //         countryId.push(this.value);
            //         console.log('countryId: ', countryId);

            //         $.ajax({
            //             type: 'GET',
            //             url: "{{ route('getProvince') }}",
            //             data: {
            //                 "country_id[]": countryId
            //             },
            //             success: function(data) {
            //                 console.log('data: ', data);
            //                 $('#state_id').parent().show();
            //                 $('#state_id').empty();
            //                 $('#state_id').append(
            //                     `<option value="">Select province</option>`);
            //                 data.forEach(element => {
            //                     // console.log('datajgkhj: ',transporterTrading,element);
            //                     if (transporterTrading.includes(element.uuid)) {

            //                         $('#state_id').append(
            //                             `<option value="${element.uuid}" selected>${element.state_name}</option>`
            //                         );
            //                     } else {

            //                         $('#state_id').append(
            //                             `<option value="${element.uuid}">${element.state_name}</option>`
            //                         );
            //                     }

            //                 });
            //             }
            //         });

            //     });

            //     $("#state_id :selected").each(function() {
            //         // $('#state_id').on('change',function(){
            //         let state_id = [];
            //         state_id.push(this.value);
            //         console.log('state_id: ', state_id);
            //         // let state_id = $(this).val();
            //         $.ajax({
            //             type: 'GET',
            //             url: "{{ route('getProvince') }}",
            //             data: {
            //                 state_id: state_id
            //             },
            //             success: function(data) {
            //                 console.log('data: ', data);
            //                 $('#city_id').parent().show();
            //                 $('#city_id').empty();
            //                 $('#city_id').append(
            //                     `<option value="">Select Town</option>`);
            //                 data.forEach(element => {
            //                     // console.log('data: ', element);
            //                     $('#city_id').append(
            //                         `<option value="${element.uuid}">${element.city_name}</option>`
            //                     );

            //                 });
            //             }
            //         });
            //     })

        }, 1000);



        $('#state_id').multiselect({});
        $('#city_id').multiselect({});
        let area = '';
        $('#country_id').on('change', function() {
            let countryId = $(this).val();
            let area = $('#trading_area').val();
            console.log('area: ', area);
            // if (area.includes("province") || area.includes("town")) {

            // if(area.includes("country","province","town"))
            $.ajax({
                type: 'GET',
                url: "{{ route('getProvince') }}",
                data: {
                    country_id: countryId
                },
                success: function(data) {
                    console.log('data: ', data);
                    $('#state_id').parent().parent().show();
                    $('#state_id').empty();
                    $('#state_id').append(`<option value="">Select Province</option>`);

                    if (data != "") {
                        data.forEach(element => {
                            // console.log('data: ', element);
                            $('#state_id').append(
                                `<option value="${element.uuid}">${element.state_name}</option>`
                            );
                            console.log('element: ', element);

                            // $('#state_id').multiselect('refresh');
                        });
                        setTimeout(function() {
                            $('#state_id').multiselect('rebuild');
                            // $('#state_id').multiselect('refresh');

                            $("#state_id :selected").each(function() {
                                console.log($(this).val());
                                let trading_area = $('#trading_area').val();
                                if (trading_area == 'town') {
                                    getCity($(this).val());
                                }

                                // selectedValues.push($(this).val());
                            });
                        }, 1000);
                    }

                    // $('#state_id').multiselect('deselectAll', false);
                    // $('#city_id').multiselect('refresh');
                    // $('#state_id').multiselect({});
                    // $('#city_id').multiselect({});
                }
            });
            // }

        })


        function getCity(state_id) {
            $.ajax({
                type: 'GET',
                url: "{{ route('getProvince') }}",
                data: {
                    state_id: state_id
                },
                success: function(data) {
                    console.log('data: ', data);
                    $('#city_id').parent().parent().show();
                    $('#city_id').empty();
                    $('#city_id').append(`<option value="">Select Town</option>`);
                    data.forEach(element => {
                        console.log('data: ', element);
                        $('#city_id').append(
                            `<option value="${element.uuid}">${element.city_name}</option>`
                        );

                    });
                    // setTimeout(function() {
                    $('#city_id').multiselect('rebuild');
                    // $('#city_id').multiselect('refresh');
                    // }, 1000);
                    // $('#city_id').multiselect('refresh');
                    // $('#city_id').multiselect({});
                }
            });
        }

        $('#state_id').on('change', function() {
            // $('#state_id').on('change', function() {
            console.log('dfgh: ');
            let state_id = $(this).val();
            let area = $('#trading_area').val();
            let trading_area = $('#trading_area').val();
            if (trading_area == 'town') {
                // if (area.includes("town")) {
                getCity(state_id);
                // }
            }
        })

        $('#country_id').parent().hide();
        $('#state_id').parent().parent().hide();
        $('#city_id').parent().parent().hide();
        $('#trading_area').on('change', function() {
            // $('#country_id').parent().hide();
            // $('#state_id').parent().hide();
            // $('#city_id').parent().hide();
            let areaVal = $(this).val();
            console.log('areaVal: ', areaVal);
            area = areaVal;
            data = areaVal;
            // $(areaVal).each(function(i, data) {
            if (data == 'country') {
                $('#state_id').parent().parent().hide();
                $('#city_id').parent().parent().hide();
                $('#country_id').parent().show();
            } else if (data == 'province') {
                $('#country_id').parent().show();
                $('#state_id').parent().parent().show();
                $('#city_id').parent().parent().hide();
            } else if (data == 'town') {
                $('#country_id').parent().show();
                $('#state_id').parent().parent().show();
                $('#city_id').parent().parent().show();
            }
            console.log(data);
            // })
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
