@extends('frontend.layouts.main')
@section('content')
    <section class="banners">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 p-0">
                    <img src="{{ asset('assets/frontend/images/banners/become-driver-banner.png') }}" style="width:100%">
                </div>
            </div>
        </div>
    </section>
    @include('frontend.layouts.breadcrumb')
    <!-- @include('frontend.auth.register-head') -->
    @include('frontend.helpers.globalMessage.message')
    <section class="main-content">
        <div class="container">
            {!! Form::model($user, [
                'route' => ["$route.store"],
                'id' => 'form',
                'autocomplete' => 'off',
                'name' => 'usersForm',
                'class' => 'theme-form',
            ]) !!}
            <input autocomplete="off" name="hidden" type="text" style="display: none">
            {!! Form::hidden('user_type', $role) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    {{-- <h2>{{__('Become a supplier')}}</h2> --}}
                    {{-- <p>{{__('Thank you for your interest in becoming a supplier for us.')}}</p> --}}
                    {{-- <p>{{__('If you wish to apply for this status, please fill out this online Supplier Registration Form.')}}</p> --}}
                    {{-- @foreach ($errors->all() as $message) --}}
                    {{-- {{$message}} --}}
                    {{-- @endforeach --}}

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <h4 class="text-uppercase">{{ __('Basic details') }}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('title', $title, null, [
                                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                                'placeholder' => '*Select title',
                            ]) !!}
                            @if ($errors->has('title'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('first_name', 'First name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('first_name', null, [
                                'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => '*First name',
                            ]) !!}

                            @if ($errors->has('first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('last_name', null, [
                                'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),
                                'placeholder' => '*Last name',
                            ]) !!}
                            @if ($errors->has('last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('gender', 'Gender: ', ['class' => 'form-label label-required']) !!}
                            @foreach ($gender as $genderKey => $genderValue)
                                <label class="radio-inline">
                                    {!! Form::radio('gender', $genderKey, null, ['id' => $genderKey]) !!} {{ __($genderValue) }}
                                </label>
                            @endforeach
                            @if ($errors->has('gender'))
                                <br>
                                <small class="text-danger">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </small>
                            @endif
                        </div> --}}

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group" style="display: none">
                            {!! Form::label('logistic_type', 'Logistic type', ['class' => 'form-label label-required']) !!}
                            @foreach ($user->getLogisticTypes() as $logisticType)
                                <label class="radio-inline">{!! Form::radio('logistic_type', $logisticType, null, [
                                    'class' => 'logistic-type-input',
                                    'id' => $logisticType,
                                    'checked',
                                ]) !!} {{ $logisticType }}</label>
                            @endforeach

                            @if ($errors->has('logistic_type'))
                                <br>
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logistic_type') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email('email', isset($user->id) ? $user->email : '', [
                                'class' => 'form-control ' . ($errors->has('email') ? ' is-invalid' : ''),
                                'placeholder' => '*Email',
                                'autocomplete' => 'off',
                                'autofill' => 'off',
                                'data-old' => isset($user->id) ? $user->email : '',
                            ]) !!}

                            @if ($errors->has('email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required transporter_name_input"
                            style="{{ isset($user->logistic_type) && $user->logistic_type == 'COMPANY' ? '' : 'display:none' }}">
                            {!! Form::label('transporter_name', 'Transporter ', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('transporter_name', null, [
                                'class' => 'form-control' . ($errors->has('transporter_name') ? ' is-invalid' : ''),
                                'placeholder' => 'Enter Transport Company',
                            ]) !!}
                            @if ($errors->has('transporter_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('transporter_name') }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>


                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password('password', [
                                'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                'placeholder' => '*Password',
                                'autocomplete' => 'new-password',
                            ]) !!}

                            @if ($errors->has('password'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password('password_confirmation', [
                                'class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''),
                                'placeholder' => '*Confirm password',
                                'autocomplete' => 'new-password',
                            ]) !!}

                            @if ($errors->has('password_confirmation'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
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
                        
                    </div> --}}
                    {{-- <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <select name="vehicle" id="" class="form-control">
                                <option value="">Select Vehicle</option>
                                @foreach ($deliveryVehicleMaster as $vehicle)
                                    <option value="{{ $vehicle->uuid }}">{{ $vehicle->vehicle_type }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('vehicle'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vehicle') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            <select name="area" id="" class="form-control" multiple>
                                <option value="">Select area</option>
                                @foreach ($tradingArea as $area)
                                    <option value="{{ $area->area }}">{{ $area->area }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('area'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('area') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div> --}}

                    {{-- <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <h4 class="text-uppercase">{{__('Logistics details')}}</h4>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[phone]', 'Phone', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[phone]",null,[
                            "class"=>"form-control".($errors->has('logisticDetails.phone')?" is-invalid":""),
                            "autofocus",
                            'data-inputmask' => "'mask': '(999) 999 9999'",
                            'placeholder'=>'*Phone']) !!}

                            @if ($errors->has('logisticDetails.phone'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.phone') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[driving_licence]', 'Driving licence?', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("logisticDetails[driving_licence]", ['YES' => 'YES', 'NO' => 'NO'], null,["class"=>"form-control".($errors->has('logisticDetails.driving_licence')?" is-invalid":""), 'placeholder' => 'Driving licence?']) !!}
                            @if ($errors->has('logisticDetails.driving_licence'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.driving_licence') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[transport_type]', 'Transport type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("logisticDetails[transport_type]", $transportTypes, null,["class"=>"form-control".($errors->has('logisticDetails.transport_type')?" is-invalid":"")]) !!}
                            @if ($errors->has('logisticDetails.transport_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.transport_type') }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[transport_capacity]', 'Transport capacity in Ton', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[transport_capacity]",null,["class"=>"form-control".($errors->has('logisticDetails.transport_capacity')?" is-invalid":""),"autofocus",'placeholder'=>'Transport capacity in Ton']) !!}

                            @if ($errors->has('logisticDetails.transport_capacity'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.transport_capacity') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[availability]', 'Availability', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("logisticDetails[availability]", $availabilityTypes, null,["class"=>"form-control".($errors->has('logisticDetails.availability')?" is-invalid":""),'placeholder'=>'*Availability']) !!}

                            @if ($errors->has('logisticDetails.availability'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.availability') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[work_type]', 'Work type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("logisticDetails[work_type]", $workTypes, null,["class"=>"form-control".($errors->has('logisticDetails.work_type')?" is-invalid":""),'placeholder'=>'*Work type']) !!}

                            @if ($errors->has('logisticDetails.work_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.work_type') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[pallets_available]', 'Pallets available', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[pallets_available]",null,["class"=>"form-control".($errors->has('logisticDetails.pallets_available')?" is-invalid":""),'placeholder'=>'*Pallets available']) !!}

                            @if ($errors->has('logisticDetails.pallets_available'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.pallets_available') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[pallets_required]', 'Pallets required', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[pallets_required]",null,["class"=>"form-control".($errors->has('logisticDetails.pallets_required')?" is-invalid":""),'placeholder'=>'*Pallets required']) !!}

                            @if ($errors->has('logisticDetails.pallets_required'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.pallets_required') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[pallets_deposit]', 'Pallets deposit', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[pallets_deposit]",null,["class"=>"form-control".($errors->has('logisticDetails.pallets_deposit')?" is-invalid":""),'placeholder'=>'*Pallets deposit']) !!}

                            @if ($errors->has('logisticDetails.pallets_deposit'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.pallets_deposit') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div> --}}

                    {{-- <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[address1]', 'Address line 1', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('logisticDetails[address1]', null, [
                                'class' => 'form-control' . ($errors->has('address1') ? ' is-invalid' : ''),
                                'placeholder' => '*Address line 1',
                            ]) !!}
                            @if ($errors->has('logisticDetails.address1'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.address1') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[address2]', 'Address line 2', ['class' => 'form-label']) !!}
                            {!! Form::text('logisticDetails[address2]', null, [
                                'class' => 'form-control' . ($errors->has('address2') ? ' is-invalid' : ''),
                                'placeholder' => 'Address line 2',
                            ]) !!}
                            @if ($errors->has('logisticDetails.address2'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.address2') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationCountryDropdown')
                            @if ($errors->has('country_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('country_id') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationStateDropdown')
                            @if ($errors->has('state_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationCityDropdown')

                            @if ($errors->has('city_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationZipcodeDropdown')
                            @if ($errors->has('zipcode_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>



                    <div class="row form-group">
                        <div class="col-xs-12 col-lg-12 col-md-12 mb-12 form-group">
                            <input id="pac-input" class="controls form-control map-location-search-box" type="text"
                                placeholder="Search location">
                            <div id="map-canvas" style="height: 300px"></div>
                        </div>
                        <div class="col-xs-12 col-lg-12 col-md-12 form-group">
                            {!! Form::label('latitude', 'Latitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('latitude', null, [
                                'class' => 'form-control' . ($errors->has('latitude') ? ' is-invalid' : ''),
                                'placeholder' => '* Latitude',
                                'id' => 'default_latitude',
                            ]) !!}
                            @if ($errors->has('latitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </small>
                            @endif
                            <br>
                            {!! Form::label('longitude', 'Longitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('longitude', null, [
                                'class' => 'form-control' . ($errors->has('longitude') ? ' is-invalid' : ''),
                                'placeholder' => '* Longitude',
                                'id' => 'default_longitude',
                            ]) !!}
                            @if ($errors->has('longitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::label('Terms & Condtion', 'Terms & Condtion: ', ['class' => 'form-label label-required']) !!}
                            <label class="checkbox-inline">
                                {!! Form::checkbox('termscondition', 'true', null, ['id' => 'termscondition']) !!}
                                <a href="{{ route('cms-pages', 'terms-condition') }}">{{ __('Terms & Condition') }}</a>
                            </label>
                            @if ($errors->has('termscondition'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('termscondition') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Submit request to become Transporter', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger btn-sm btn-block text-uppercase',
                                'name' => 'save_continue',
                            ]) !!}

                            {{-- {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning btn-sm"])!!} --}}

                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <img src="{{ asset('assets/frontend/images/become-driver-side-image.png') }}">
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>

    {{-- <section class="main-content"> --}}
    {{-- <div class="container"> --}}
    {{-- <div class="row"> --}}
    {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> --}}
    {{-- <h4>{{__("FAQ")}}</h4> --}}
    {{-- @include('frontend.auth.register-faq') --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </section> --}}
    {{-- <section class="banners"> --}}
    {{-- <div class="container-fluid p-0"> --}}
    {{-- <div class="row m-0"> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </section> --}}
@endsection
@section('footerScript')
    @include('utils.map')
@endsection
