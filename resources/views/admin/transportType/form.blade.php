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
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($transportTypes->id) && (isset($copy) && !$copy))
                        {!! Form::model($transportTypes, [
                            'route' => ["$route.update", $transportTypes->id],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::model($transportTypes, [
                            'route' => ["$route.store"],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if (isset($transportTypes->id))
                        {!! Form::hidden('id', $transportTypes->id) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('type', 'Vehicle Type', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('type', isset($transportTypes->id) ? $transportTypes->type : '', [
                                'class' => 'form-control' . ($errors->has('type') ? ' is-invalid' : ''),
                                'placeholder' => 'Vehicle Type',
                            ]) !!}

                            {{-- <input type="text" name="type" id="" class="form-control"
                                value="{{ $transportTypes->type }}"> --}}
                            @if ($errors->has('type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('type') }}</strong>
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
                            @if (!isset($transportTypes->id))
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
@endsection
