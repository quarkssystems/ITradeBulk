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
            {{--            <a href="{{ route("$route.index") }}" class="btn btn-info">{{__('Back')}}</a> --}}
        </div>
    </div>
@endsection

@section('content')
    @if (isset($user->id))
        @include($navTab)
    @endif
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($user->id) && (isset($copy) && !$copy))
                        {!! Form::model($user, [
                            'route' => ["$route.update", $user->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::model($user, [
                            'route' => ["$route.store"],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">
                    {!! Form::hidden('user_type', $role) !!}
                    @if (isset($user->id))
                        {!! Form::hidden('uuid', $user->uuid) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('image_file', 'Profile image', ['class' => 'form-label label-required']) !!}
                            {!! Form::file('image_file', [
                                'class' => 'form-control dropify ' . ($errors->has('image_file') ? ' is-invalid' : ''),
                                'data-default-file' => isset($user->id) && (isset($copy) && !$copy) ? $user->image : '',
                            ]) !!}
                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                            @if ($errors->has('image_file'))
                                <br><span class="help-block text-danger">
                                    <strong>{{ $errors->first('image_file') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            <div class="form-group">
                                {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                                {!! Form::select('title', $title, null, [
                                    'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                                    'placeholder' => 'Select title',
                                ]) !!}
                                @if ($errors->has('title'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                                {!! Form::email('email', isset($user->id) ? $user->email : '', [
                                    'class' => 'form-control ' . ($errors->has('email') ? ' is-invalid' : ''),
                                    'placeholder' => 'Email',
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
                            <div class="form-group">
                                {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                                {!! Form::password('password', [
                                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                    'placeholder' => 'Password',
                                    'autocomplete' => 'new-password',
                                ]) !!}

                                @if ($errors->has('password'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            <div class="form-group">
                                {!! Form::label('first_name', 'First name', ['class' => 'form-label label-required']) !!}
                                {!! Form::text('first_name', null, [
                                    'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'First name',
                                ]) !!}

                                @if ($errors->has('first_name'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                                {!! Form::text('last_name', null, [
                                    'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),
                                    'placeholder' => 'Last name',
                                ]) !!}
                                @if ($errors->has('last_name'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                                {!! Form::password('password_confirmation', [
                                    'class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''),
                                    'placeholder' => 'Confirm password',
                                    'autocomplete' => 'new-password',
                                ]) !!}

                                @if ($errors->has('password_confirmation'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if ($route == 'admin.manage-supplier')
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('facebook_url', 'Facebook Url', ['class' => 'form-label label-required']) !!}
                                {!! Form::text('facebook_url', null, [
                                    'class' => 'form-control' . ($errors->has('facebook_url') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'Facebook Url',
                                ]) !!}

                                @if ($errors->has('facebook_url'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('facebook_url') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('twitter_url', 'Twitter Url', ['class' => 'form-label label-required']) !!}
                                {!! Form::text('twitter_url', null, [
                                    'class' => 'form-control' . ($errors->has('twitter_url') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'Twitter Url',
                                ]) !!}

                                @if ($errors->has('twitter_url'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('twitter_url') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('insta_url', 'Instagram Url', ['class' => 'form-label']) !!}
                                {!! Form::text('insta_url', null, [
                                    'class' => 'form-control' . ($errors->has('insta_url') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'Instagram Url',
                                ]) !!}

                                @if ($errors->has('insta_url'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('insta_url') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('gender', 'Gender', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('gender', $gender, null, [
                                'class' => 'form-control' . ($errors->has('gender') ? ' is-invalid' : ''),
                                'placeholder' => 'Select gender',
                            ]) !!}

                            @if ($errors->has('gender'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}
                            {!! Form::select('status', $status, null, [
                                'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                'placeholder' => 'Select status',
                            ]) !!}

                            @if ($errors->has('status'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}
                            {!! Form::textarea('remarks', null, [
                                'class' => 'form-control' . ($errors->has('remarks') ? ' is-invalid' : ''),
                                'placeholder' => 'Remarks',
                                'rows' => 1,
                            ]) !!}

                            @if ($errors->has('remarks'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('remarks') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>


                    @if ($role == 'COMPANY')
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('transporter_name', 'Company Name', ['class' => 'form-label label-required']) !!}
                                {!! Form::text('transporter_name', null, [
                                    'class' => 'form-control' . ($errors->has('transporter_name') ? ' is-invalid' : ''),
                                    'placeholder' => 'Company Name',
                                ]) !!}

                                @if ($errors->has('transporter_name'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('transporter_name') }}</strong>
                                    </small>
                                @endif
                            </div>

                        </div>
                    @endif

                    @if ($role == 'DRIVER')
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group"
                                style="{{ isset($user->logistic_company_id) && $user->logistic_company_id != '' ? 'display:none' : '' }}">
                                {!! Form::label('logistic_type', 'Logistic type', ['class' => 'form-label label-required']) !!}
                                @foreach ($user->getLogisticTypes() as $logisticType)
                                    <div class="radio">
                                        <label>{!! Form::radio('logistic_type', $logisticType, null, ['class' => 'logistic-type-input']) !!} {{ $logisticType }}</label>
                                    </div>
                                @endforeach

                                @if ($errors->has('logistic_type'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('logistic_type') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required transporter_name_input"
                                style="{{ isset($user->logistic_type) && $user->logistic_type == 'COMPANY' ? '' : 'display:none' }}">
                                {!! Form::label('logistic_company_id', 'Company Transporter ', ['class' => 'form-label label-required']) !!}

                                {!! Form::select('logistic_company_id', $logisticCompanies, null, [
                                    'class' => 'form-control' . ($errors->has('logistic_company_id') ? ' is-invalid' : ''),
                                    'placeholder' => 'Transporter name',
                                    'data-ajax-url' => route('admin.ajax.getAddress'),
                                ]) !!}
                                @if ($errors->has('logistic_company_id'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('logistic_company_id') }}</strong>
                                    </small>
                                @endif

                            </div>

                        </div>
                    @endif

                    <div class="row">
                        <div class="col-xs-12 col-lg-8 col-md-8 mb-4">
                            <input id="pac-input" class="controls form-control map-location-search-box" type="text"
                                placeholder="Search location">
                            <div id="map-canvas" style="height: 300px"></div>
                        </div>
                        <div class="col-xs-12 col-lg-4 col-md-4">
                            {!! Form::label('latitude', 'Latitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('latitude', null, [
                                'class' => 'form-control' . ($errors->has('latitude') ? ' is-invalid' : ''),
                                'placeholder' => 'Latitude',
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
                                'placeholder' => 'Longitude',
                                'id' => 'default_longitude',
                            ]) !!}
                            @if ($errors->has('longitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('longitude') }}</strong>
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
                            @if (!isset($user->id))
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
@section('footerData')
    @include('utils.map')
@endsection
