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

                    <form method="post" action="{{ route('admin.settings.store') }}" class="form-horizontal" role="form">
                        {!! csrf_field() !!}

                        @if (count(config('setting_fields', [])))
                            @foreach (config('setting_fields') as $section => $fields)
                                @foreach ($fields['elements'] as $field)
                                    @include('admin.setting.fields')
                                @endforeach
                            @endforeach
                        @endif

                        <div class="form-group row">
                            <div class="col-xs-12 col-lg-12">
                                {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}

                            </div>

                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
