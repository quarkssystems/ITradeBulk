@extends('supplier.layouts.main')
@section('page-header')


    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{__('Home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{$pageTitle}}</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        @include('frontend.helpers.globalMessage.message')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">

                    {!! Form::model($user, ['route' => ["$route.update", $user->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', "files" => true]) !!}

                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if(isset($user->id))
                        {!! Form::hidden('uuid', $user->uuid) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('image_file', 'Profile image', ['class' => 'form-label label-required']) !!}
                            {!! Form::file("image_file", [
                                            "class"=>"form-control dropify ".($errors->has('image_file')?" is-invalid":""),
                                            'data-default-file' => (isset($user->id) && (isset($copy) && !$copy)) ? $user->image : ''
                                            ]) !!}
                            <small><i>{{__('Only JPG and PNG supported')}}</i></small>
                            @if ($errors->has('image_file'))
                                <br><span class="help-block text-danger"><strong>{{ $errors->first('image_file') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("title",$title, null,["class"=>"form-control".($errors->has('title')?" is-invalid":""),'placeholder'=>'Select title']) !!}
                            @if ($errors->has('title'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('first_name', 'First name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("first_name",null,["class"=>"form-control".($errors->has('first_name')?" is-invalid":""),"autofocus",'placeholder'=>'First name']) !!}

                            @if ($errors->has('first_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("last_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),'placeholder'=>'Last name']) !!}
                            @if ($errors->has('last_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </small>
                            @endif

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                            {!! Form::email(
                            "email",
                            isset($user->id) ? $user->email : '',
                            [
                                "class"=>"form-control ".($errors->has('email')?" is-invalid":""),
                                'placeholder'=>'Email',
                                'autocomplete' => 'off',
                                'autofill' => 'off',
                                'data-old' => isset($user->id) ? $user->email : '',
                                'readonly' => 'true'
                            ])
                            !!}

                            @if ($errors->has('email'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                            @endif

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password",["class"=>"form-control".($errors->has('password')?" is-invalid":""),'placeholder'=>'Password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                            {!! Form::password("password_confirmation",["class"=>"form-control".($errors->has('password_confirmation')?" is-invalid":""),'placeholder'=>'Confirm password', 'autocomplete' => 'new-password']) !!}

                            @if ($errors->has('password_confirmation'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>
                    
                    @if($user->role == 'SUPPLIER')

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('facebook_url', 'Facebook Url', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("facebook_url",null,["class"=>"form-control".($errors->has('facebook_url')?" is-invalid":""),"autofocus",'placeholder'=>'Facebook Url']) !!}

                            @if ($errors->has('facebook_url'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('facebook_url') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('twitter_url', 'Twitter Url', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("twitter_url",null,["class"=>"form-control".($errors->has('twitter_url')?" is-invalid":""),"autofocus",'placeholder'=>'Twitter Url']) !!}

                            @if ($errors->has('twitter_url'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('twitter_url') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('insta_url', 'Instagram Url', ['class' => 'form-label']) !!}
                            {!! Form::text("insta_url",null,["class"=>"form-control".($errors->has('insta_url')?" is-invalid":""),"autofocus",'placeholder'=>'Instagram Url']) !!}

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
                            {!! Form::select("gender",$gender, null,["class"=>"form-control".($errors->has('gender')?" is-invalid":""),'placeholder'=>'Select gender']) !!}

                            @if ($errors->has('gender'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </small>
                            @endif
                        </div>
{{--                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">--}}
{{--                                {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}--}}
{{--                                {!! Form::select("status",$status, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),'placeholder'=>'Select status']) !!}--}}

{{--                                @if ($errors->has('status'))--}}
{{--                                    <small class="text-danger">--}}
{{--                                        <strong>{{ $errors->first('status') }}</strong>--}}
{{--                                    </small>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">--}}
{{--                                {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}--}}
{{--                                {!! Form::textarea("remarks", null,["class"=>"form-control".($errors->has('remarks')?" is-invalid":""),'placeholder'=>'Remarks', 'rows' => 1]) !!}--}}

{{--                                @if ($errors->has('remarks'))--}}
{{--                                    <small class="text-danger">--}}
{{--                                        <strong>{{ $errors->first('remarks') }}</strong>--}}
{{--                                    </small>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                    </div>

            @if($role == 'COMPANY')
                    <div class="row">
                            
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required transporter_name_input" style="{{isset($user->logistic_type) && $user->logistic_type == 'COMPANY' ? '' : 'display:none'}}">
                                {!! Form::label('transporter_name', 'Company Transporter name', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("transporter_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),'placeholder'=>'Transporter name']) !!}
                                @if ($errors->has('transporter_name'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('transporter_name') }}</strong>
                                    </small>
                                @endif

                            </div>

                        </div> 
                 @endif
                    @if($role == 'DRIVER' || $role == 'COMPANY')
                   {{--     <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                {!! Form::label('logistic_type', 'Logistic type', ['class' => 'form-label label-required']) !!}
                                @foreach($user->getLogisticTypes() as $logisticType)
                                    <div class="radio">
                                        <label>{!! Form::radio('logistic_type', $logisticType, null, ['class' => 'logistic-type-input']) !!} {{$logisticType}}</label>
                                    </div>
                                @endforeach

                                @if ($errors->has('logistic_type'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('logistic_type') }}</strong>
                                    </small>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required transporter_name_input" style="{{isset($user->logistic_type) && $user->logistic_type == 'COMPANY' ? '' : 'display:none'}}">
                                {!! Form::label('transporter_name', 'Transporter name', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("transporter_name",null,["class"=>"form-control".($errors->has('last_name')?" is-invalid":""),'placeholder'=>'Transporter name']) !!}
                                @if ($errors->has('transporter_name'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('transporter_name') }}</strong>
                                    </small>
                                @endif

                            </div>

                        </div> --}}


                          <div class="row">
                        

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[address1]', 'Address line 1', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("logisticDetails[address1]",null,["class"=>"form-control".($errors->has('address1')?" is-invalid":""),'placeholder'=>'*Address line 1']) !!}
                            @if ($errors->has('logisticDetails.address1'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('logisticDetails.address1') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('logisticDetails[address2]', 'Address line 2', ['class' => 'form-label']) !!}
                            {!! Form::text("logisticDetails[address2]",null,["class"=>"form-control".($errors->has('address2')?" is-invalid":""),'placeholder'=>'Address line 2']) !!}
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
                    @endif

                    <div class="row">
                        <div class="col-xs-12 col-lg-8 col-md-8 mb-4">
                            <input id="pac-input" class="controls form-control map-location-search-box" type="text" placeholder="Search location">
                            <div id="map-canvas" style="height: 300px"></div>
                        </div>
                        <div class="col-xs-12 col-lg-4 col-md-4">
                            {!! Form::label('latitude', 'Latitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("latitude",null,["class"=>"form-control".($errors->has('latitude')?" is-invalid":""),'placeholder'=>'Latitude', 'id' => 'default_latitude']) !!}
                            @if ($errors->has('latitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </small>
                            @endif
                            <br>
                            {!! Form::label('longitude', 'Longitude', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("longitude",null,["class"=>"form-control".($errors->has('longitude')?" is-invalid":""),'placeholder'=>'Longitude', 'id' => 'default_longitude']) !!}
                            @if ($errors->has('longitude'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_exit'])!!}
{{--                                {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}--}}
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
@section("footerScript")
    @include("utils.map")
@endsection