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

        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">


                    <form method="post" action="{{ route('admin.admin-details-store') }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        {{-- {!! csrf_field() !!} --}}

                        @csrf

                        <input type="hidden" name="id" value="{{ isset($data->id) ? $data->id : '' }}">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group required">
                            {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                            {!! Form::textarea('address', isset($data->id) ? $data->address : '', [
                                'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Address',
                            ]) !!}
                            @if ($errors->has('address'))
                                <br><span class="help-block text-danger">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group required">
                            {!! Form::label('icon', 'icon', ['class' => 'form-label ']) !!}
                            {!! Form::file('icon', [
                                'class' => 'form-control dropify ' . ($errors->has('icon') ? ' is-invalid' : ''),
                                'data-default-file' => isset($data->id) ? $data->icon : '',
                            ]) !!}
                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                            @if ($errors->has('icon'))
                                <br><span class="help-block text-danger">
                                    <strong>{{ $errors->first('icon') }}</strong>
                                </span>
                            @endif

                        </div>

                        <div class="form-group row">
                            <div class="col-xs-12 col-lg-12">
                                {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}

                            </div>

                        </div>
                        {{-- {!! Form::close() !!} --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
