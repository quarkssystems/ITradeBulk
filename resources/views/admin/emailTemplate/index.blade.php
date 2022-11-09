{{--
/**
 * Created by PhpStorm.
 * User: Mohit
 */
 --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{__('Email Template')}}</h1>
            {{--<p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>--}}
            <a href="{{ route("$route.create") }}" class="btn btn-info">{{__('ADD Email Template')}}</a>
            @if($data->count() > 0)
                <!-- <a href="{{ route("$route.index") }}?export_data" class="btn btn-success float-right">{{__('Export')}}</a> -->
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.emailTemplate.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


