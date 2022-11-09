@extends('frontend.layouts.main')
@section('content')
    <section class="banners">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 p-0">
                    <img src="{{asset('assets/frontend/images/banners/become-dispatcher-banner.png')}}" style="width:100%">
                </div>
            </div>
        </div>
    </section>
@include('frontend.layouts.breadcrumb')
{{--@include('frontend.auth.register-head')--}}
@include('frontend.helpers.globalMessage.message')

    <section class="main-content">
        <div class="container">
            {!! Form::model($user, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'class' => 'theme-form']) !!}
            <input autocomplete="off" name="hidden" type="text" style="display: none">
            {!! Form::hidden('user_type', $role) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
{{--                    <h2>{{__('Become a supplier')}}</h2>--}}
                    {{--<p>{{__('Thank you for your interest in becoming a supplier for us.')}}</p>--}}
                    {{--<p>{{__('If you wish to apply for this status, please fill out this online Supplier Registration Form.')}}</p>--}}
                    {{--@foreach ($errors->all() as $message)--}}
                    {{--{{$message}}--}}
                    {{--@endforeach--}}

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <h4 class="text-uppercase">{{__('Basic details')}}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("title",$title, null,["class"=>"form-control".($errors->has('title')?" is-invalid":""),'placeholder'=>'*Select title']) !!}
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
                            {!! Form::text("first_name",null,["class"=>"form-control".($errors->has('first_name')?" is-invalid":""),"autofocus",'placeholder'=>'*First name']) !!}

                            @if ($errors->has('first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("last_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),'placeholder'=>'*Last name']) !!}
                            @if ($errors->has('last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('gender', 'Gender: ', ['class' => 'form-label label-required']) !!}
                            @foreach($gender as $genderKey => $genderValue)
                                <label class="radio-inline">
                                    {!! Form::radio('gender', $genderKey, null, ['id' => $genderKey]) !!} {{__($genderValue)}}
                                </label>
                            @endforeach
                            @if ($errors->has('gender'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email(
                            "email",
                            isset($user->id) ? $user->email : '',
                            [
                                "class"=>"form-control ".($errors->has('email')?" is-invalid":""),
                                'placeholder'=>'*Email',
                                'autocomplete' => 'off',
                                'autofill' => 'off',
                                'data-old' => isset($user->id) ? $user->email : '',
                            ])
                            !!}

                            @if ($errors->has('email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password",["class"=>"form-control".($errors->has('password')?" is-invalid":""),'placeholder'=>'*Password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password_confirmation",["class"=>"form-control".($errors->has('password_confirmation')?" is-invalid":""),'placeholder'=>'*Confirm password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password_confirmation'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <h4 class="text-uppercase">{{__('Company details')}}</h4>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[legal_name]', 'Legal name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[legal_name]",null,["class"=>"form-control".($errors->has('company.legal_name')?" is-invalid":""),"autofocus",'placeholder'=>'*Legal name']) !!}

                            @if ($errors->has('company.legal_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.legal_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[trading_name]', 'Trading name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[trading_name]",null,["class"=>"form-control".($errors->has('company.trading_name')?" is-invalid":""),'placeholder'=>'*Trading name']) !!}
                            @if ($errors->has('company.trading_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.trading_name') }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[business_type]', 'Business type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("company[business_type]", $businessType, null,["class"=>"form-control".($errors->has('company.business_type')?" is-invalid":""),'placeholder'=>'*Business type']) !!}
                            @if ($errors->has('company.business_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.business_type') }}</strong>
                                </small>
                            @endif

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[founding_year]', 'Founding year', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("company[founding_year]",$foundingYears, null,["class"=>"form-control".($errors->has('company.founding_year')?" is-invalid":""),'placeholder'=>'Founding year']) !!}

                            @if ($errors->has('company.founding_year'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.founding_year') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[representative_first_name]', 'Representative first name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[representative_first_name]",null,["class"=>"form-control".($errors->has('company.representative_first_name')?" is-invalid":""),'placeholder'=>'*Representative first name']) !!}

                            @if ($errors->has('company.representative_first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.representative_first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[representative_last_name]', 'Representative last name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[representative_last_name]",null,["class"=>"form-control".($errors->has('company.representative_last_name')?" is-invalid":""),'placeholder'=>'*Representative last name']) !!}

                            @if ($errors->has('company.representative_last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.representative_last_name') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
{{--                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">--}}
{{--                            {!! Form::label('company[geographical_target]', 'Geographical target', ['class' => 'form-label label-required']) !!}--}}
{{--                            {!! Form::text("company[geographical_target]",null,["class"=>"form-control".($errors->has('company.geographical_target')?" is-invalid":""),'placeholder'=>'*Geographical target']) !!}--}}

{{--                            @if ($errors->has('company.geographical_target'))--}}
{{--                                <small class="text-danger">--}}
{{--                                    <strong>{{ $errors->first('company.geographical_target') }}</strong>--}}
{{--                                </small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[website]', 'Website', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[website]",null,["class"=>"form-control".($errors->has('company.website')?" is-invalid":""),'placeholder'=>'Website']) !!}

                            @if ($errors->has('company.website'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.website') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[email]', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email("company[email]",null,["class"=>"form-control".($errors->has('company.email')?" is-invalid":""),'placeholder'=>'*Company Email']) !!}

                            @if ($errors->has('company.email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.email') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[phone]', 'Phone', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[phone]",null,[
                            "class"=>"form-control".($errors->has('company.phone')?" is-invalid":""),
                            'data-inputmask' => "'mask': '(999) 999 9999'",
                            'placeholder'=>'*Phone']) !!}

                            @if ($errors->has('company.phone'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.phone') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>

                    <div class="row">
{{--                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">--}}
{{--                            {!! Form::label('company[company_size]', 'Company size', ['class' => 'form-label label-required']) !!}--}}
{{--                            {!! Form::text("company[company_size]",null,["class"=>"form-control".($errors->has('company.company_size')?" is-invalid":""),'placeholder'=>'*Company size']) !!}--}}

{{--                            @if ($errors->has('company.company_size'))--}}
{{--                                <small class="text-danger">--}}
{{--                                    <strong>{{ $errors->first('company.company_size') }}</strong>--}}
{{--                                </small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">--}}
{{--                            {!! Form::label('company[audience]', 'Audience', ['class' => 'form-label label-required']) !!}--}}
{{--                            {!! Form::text("company[audience]",null,["class"=>"form-control".($errors->has('company.audience')?" is-invalid":""),'placeholder'=>'*Audience']) !!}--}}

{{--                            @if ($errors->has('company.audience'))--}}
{{--                                <small class="text-danger">--}}
{{--                                    <strong>{{ $errors->first('company.audience') }}</strong>--}}
{{--                                </small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[address1]', 'Address line 1', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("company[address1]",null,["class"=>"form-control".($errors->has('company.address1')?" is-invalid":""),'placeholder'=>'*Address line 1']) !!}
                            @if ($errors->has('company.address1'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.address1') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('company[address2]', 'Address line 2', ['class' => 'form-label']) !!}
                            {!! Form::text("company[address2]",null,["class"=>"form-control".($errors->has('company.address2')?" is-invalid":""),'placeholder'=>'Address line 2']) !!}
                            @if ($errors->has('company.address2'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.address2') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationCountryDropdown')
                            @if ($errors->has('country_id'))
                                <span class="help-block text-danger">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationStateDropdown')
                            @if ($errors->has('state_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationCityDropdown')

                            @if ($errors->has('city_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            @include('frontend.helpers.ajax.locationZipcodeDropdown')
                            @if ($errors->has('zipcode_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('company[product_service_offered]', 'What kind of products / services does your company offer?', ['class' => 'form-label label-required']) !!}
                            {!! Form::textarea("company[product_service_offered]",null,["class"=>"form-control".($errors->has('company.product_service_offered')?" is-invalid":""),'placeholder'=>'* What kind of products / services does your company offer?', 'rows' => 3]) !!}
                            @if ($errors->has('company.product_service_offered'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('company.product_service_offered') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    {{--<div class="row">--}}
                        {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">--}}
                            {{--<h4 class="text-uppercase">{{__('Documents')}}</h4>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row form-group">
                        <div class="col-xs-12 col-lg-12 col-md-12 mb-12 form-group">
                            <input id="pac-input" class="controls form-control map-location-search-box" type="text" placeholder="Search location">
                            <div id="map-canvas" style="height: 300px"></div>
                        </div>
                        <div class="col-xs-12 col-lg-12 col-md-12 form-group">
                            {!! Form::label('latitude', 'Latitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("latitude",null,["class"=>"form-control".($errors->has('latitude')?" is-invalid":""),'placeholder'=>'* Latitude', 'id' => 'default_latitude']) !!}
                            @if ($errors->has('latitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </small>
                            @endif
                            <br>
                            {!! Form::label('longitude', 'Longitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("longitude",null,["class"=>"form-control".($errors->has('longitude')?" is-invalid":""),'placeholder'=>'* Longitude', 'id' => 'default_longitude']) !!}
                            @if ($errors->has('longitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                        {!! Form::label('Terms & Condtion', 'Terms & Condtion: ', ['class' => 'form-label label-required']) !!}
                        <label class="checkbox-inline">
                            {!! Form::checkbox('termscondition','true' , null, ['id' => 'termscondition']) !!} 
                            <a href="{{route('cms-pages','terms-condition')}}">{{__('Terms & Condition')}}</a>
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
                            {!! Form::submit("Submit request to become dispatcher",["type" => "submit","class"=>"btn btn-danger btn-sm btn-block text-uppercase", 'name' => 'save_continue'])!!}

{{--                            {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning btn-sm"])!!}--}}

                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <img src="{{asset('assets/frontend/images/become-supplier-side-image.png')}}">
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
{{--    <section class="main-content">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
{{--                    <h4>{{__("FAQ")}}</h4>--}}
{{--                    @include('frontend.auth.register-faq')--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section class="banners">--}}
{{--        <div class="container-fluid p-0">--}}
{{--            <div class="row m-0">--}}
{{--                <div class="col-md-4 p-0">--}}
{{--                    <a href="#">--}}
{{--                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 p-0">--}}
{{--                    <a href="#">--}}
{{--                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 p-0">--}}
{{--                    <a href="#">--}}
{{--                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
@endsection
@section("footerScript")
    @include("utils.map")
@endsection