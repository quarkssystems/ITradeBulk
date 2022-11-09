@extends('frontend.layouts.main')
@section('content')
    <div class="main-content">
        <section class="banners">

            <div class="container">


                <div class="row">
                    <div class="col-md-8 offset-md-2">
                           <div class="card login-card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-5 login-card-left-content">
                                            <h2>{{$pageTitle}}</h2>
                                            <p>{{$pageSubTitle}}</p>

                                        </div>
                                        <div class="col-md-7">
                                            <div class="login-card-right-content">
                                                <div class="myform form ">
                                                    {!! Form::open(['url' => route('login'), 'method' => 'POST', 'role' => 'form','class'=>'theme-form']) !!}
                                                    <div class="form-group mb-3">
                                                        <div class="input-group">
                                                            <input id="email" placeholder="{{__('Email')}}" type="email"
                                                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                   name="email" value="{{ old('email') }}" required autofocus>
                                                        </div>
                                                        @if ($errors->has('email'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input id="password" type="password" placeholder="{{__('Password')}}"
                                                                   class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                   name="password" required>

                                                        </div>
                                                        @if ($errors->has('password'))
                                                            <small class="text-danger">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </small>
                                                        @endif
                                                    </div>
                                                            <div class="custom-control custom-control-alternative custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" name="remember"
                                                                       id="customCheckLogin" {{ old('remember') ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="customCheckLogin">
                                                                    <span class="text-muted">{{ __('Remember Me') }}</span>
                                                                </label>
                                                            </div>

                                                    <div class="text-center">

                                                        <input type="submit" class="btn btn-primary btn-sm btn-block text-uppercase" value="Login">
                                                       <hr>
                                                        @if (Route::has('user.password.email'))
                                                            <a class="forgot-password" href="{{ route('user.password.email') }}">
                                                                {{ __('Forgot Your Password?') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6 offset-md-3">

                                {{--<div class="myform form ">--}}
                                    {{--{!! Form::open(['url' => route('login'), 'method' => 'POST', 'role' => 'form','class'=>'theme-form']) !!}--}}
                                    {{--<div class="form-group mb-3">--}}
                                        {{--<div class="input-group">--}}
                                            {{--<input id="email" placeholder="{{__('Email')}}" type="email"--}}
                                                   {{--class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"--}}
                                                   {{--name="email" value="{{ old('email') }}" required autofocus>--}}
                                        {{--</div>--}}
                                        {{--@if ($errors->has('email'))--}}
                                            {{--<small class="text-danger">--}}
                                                {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                            {{--</small>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}

                                    {{--<div class="form-group">--}}
                                        {{--<div class="input-group">--}}
                                            {{--<input id="password" type="password" placeholder="{{__('Password')}}"--}}
                                                   {{--class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"--}}
                                                   {{--name="password" required>--}}

                                        {{--</div>--}}
                                        {{--@if ($errors->has('password'))--}}
                                            {{--<small class="text-danger">--}}
                                                {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                            {{--</small>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}

                                    {{--<div class="custom-control custom-control-alternative custom-checkbox">--}}
                                        {{--<input class="custom-control-input" type="checkbox" name="remember"--}}
                                               {{--id="customCheckLogin" {{ old('remember') ? 'checked' : '' }}>--}}
                                        {{--<label class="custom-control-label" for="customCheckLogin">--}}
                                            {{--<span class="text-muted">{{ __('Remember Me') }}</span>--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                    {{--<div class="text-center">--}}

                                        {{--<input type="submit" class="btn btn-danger btn-sm btn-block text-uppercase" value="Login">--}}
                                    {{--</div>--}}
                                    {{--{!! Form::close() !!}--}}
                                {{--</div>--}}

                    </div>

                </div>
            </div>
        </section>

    </div>


@endsection