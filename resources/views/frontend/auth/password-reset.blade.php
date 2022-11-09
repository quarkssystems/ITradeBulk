@extends('frontend.layouts.main')
@section('content')
    <div class="main-content">
        <section class="banners">

            <div class="container">


                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card login-card forgot-password-card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-5 login-card-left-content">
                                        <h2>{{$pageTitle}}</h2>
                                        <p>{{$pageSubTitle}}</p>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="login-card-right-content">
                                            <div class="myform form ">
                                                {!! Form::open(['url' => route('password.email') , 'method' => 'POST', 'role' => 'form','class'=>'theme-form']) !!}
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


                                                <div class="text-center">

                                                    <input type="submit" class="btn btn-primary btn-sm btn-block text-uppercase" value="Send reset link">

                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>


@endsection