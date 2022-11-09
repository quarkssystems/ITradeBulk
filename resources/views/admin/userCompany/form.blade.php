{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */ --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            {{-- <a href="{{ route($redirectBackRoute) }}" class="btn btn-info">{{__('Back')}}</a> --}}
        </div>
    </div>
@endsection

@section('content')

    @include($navTab)


    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($user_company->id))
                        {!! Form::model($user_company, [
                            'route' => ["$route.update", $user->uuid, $user_company->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                        ]) !!}
                    @else
                        {!! Form::model($user_company, [
                            'route' => ["$route.store", $user->uuid],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if (isset($user_company->id))
                        {!! Form::hidden('uuid', $user_company->uuid) !!}
                    @endif
                    {!! Form::hidden('owner_user_id', $user->uuid) !!}
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('legal_name', 'Legal name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('legal_name', null, [
                                'class' => 'form-control' . ($errors->has('legal_name') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Legal name',
                            ]) !!}

                            @if ($errors->has('legal_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('legal_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('trading_name', 'Trading name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('trading_name', null, [
                                'class' => 'form-control' . ($errors->has('trading_name') ? ' is-invalid' : ''),
                                'placeholder' => 'Trading name',
                            ]) !!}
                            @if ($errors->has('trading_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('trading_name') }}</strong>
                                </small>
                            @endif

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('business_type', 'Business type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('business_type', $businessType, null, [
                                'class' => 'form-control' . ($errors->has('business_type') ? ' is-invalid' : ''),
                                'placeholder' => 'Business type',
                            ]) !!}
                            @if ($errors->has('business_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('business_type') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('representative_first_name', 'Representative first name', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::text('representative_first_name', null, [
                                'class' => 'form-control' . ($errors->has('representative_first_name') ? ' is-invalid' : ''),
                                'placeholder' => 'Representative first name',
                            ]) !!}

                            @if ($errors->has('representative_first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('representative_first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('representative_last_name', 'Representative last name', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::text('representative_last_name', null, [
                                'class' => 'form-control' . ($errors->has('representative_last_name') ? ' is-invalid' : ''),
                                'placeholder' => 'Representative last name',
                            ]) !!}

                            @if ($errors->has('representative_last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('representative_last_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('geographical_target', 'Geographical target', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('geographical_target', null, [
                                        'class' => 'form-control' . ($errors->has('geographical_target') ? ' is-invalid' : ''),
                                        'placeholder' => 'Geographical target',
                                    ]) !!}

                                    @if ($errors->has('geographical_target'))
    <small class="text-danger">
                                            <strong>{{ $errors->first('geographical_target') }}</strong>
                                        </small>
    @endif
                                </div> -->
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('founding_year', 'Founding year', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('founding_year', $foundingYears, null, [
                                'class' => 'form-control' . ($errors->has('founding_year') ? ' is-invalid' : ''),
                                'placeholder' => 'Founding year',
                            ]) !!}

                            @if ($errors->has('founding_year'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('founding_year') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email('email', null, [
                                'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                                'placeholder' => 'Email',
                            ]) !!}

                            @if ($errors->has('email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('phone', 'Phone', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('phone', null, [
                                'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
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
                            {!! Form::label('website', 'Website', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('website', null, [
                                'class' => 'form-control' . ($errors->has('website') ? ' is-invalid' : ''),
                                'placeholder' => 'Website',
                            ]) !!}

                            @if ($errors->has('website'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('website') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('company_size', 'Company size', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('company_size', null, [
                                        'class' => 'form-control' . ($errors->has('company_size') ? ' is-invalid' : ''),
                                        'placeholder' => 'Company size',
                                    ]) !!}

                                    @if ($errors->has('company_size'))
    <small class="text-danger">
                                            <strong>{{ $errors->first('company_size') }}</strong>
                                        </small>
    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('audience', 'Audience', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('audience', null, [
                                        'class' => 'form-control' . ($errors->has('audience') ? ' is-invalid' : ''),
                                        'placeholder' => 'Audience',
                                    ]) !!}

                                    @if ($errors->has('audience'))
    <small class="text-danger">
                                            <strong>{{ $errors->first('audience') }}</strong>
                                        </small>
    @endif
                                </div>
                        </div> -->

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
                        @if (isset($user_company->owner->role))
                            @if ($user_company->owner->role == 'SUPPLIER')
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('lead_approximate_time', 'Approximate Lead Time (Days)', [
                                        'class' => 'form-label label-required',
                                    ]) !!}
                                    {!! Form::text('lead_approximate_time', null, [
                                        'class' => 'form-control' . ($errors->has('lead_approximate_time') ? ' is-invalid' : ''),
                                        'placeholder' => 'Approximate Lead Time',
                                        'rows' => 3,
                                    ]) !!}
                                    @if ($errors->has('lead_approximate_time'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('lead_approximate_time') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            @endif
                        @endif

                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('product_service_offered', 'What kind of products / services does your company offer?', [
                                'class' => 'form-label label-required',
                            ]) !!}
                            {!! Form::textarea('product_service_offered', null, [
                                'class' => 'form-control' . ($errors->has('product_service_offered') ? ' is-invalid' : ''),
                                'placeholder' => 'What kind of products / services does your company offer?',
                                'rows' => 3,
                            ]) !!}
                            @if ($errors->has('product_service_offered'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('product_service_offered') }}</strong>
                                </small>
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
                            @if (!isset($user_company->id))
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
