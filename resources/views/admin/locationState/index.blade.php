{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-10">
            <h1 class="display-2 text-white">{{__('MANAGE PROVINCE')}}</h1>
            <p class="text-white mt-0 mb-5">{{__('Country: :country', ['country' => $country->country_name])}}</p>
            <a href="{{ route("$route.create", $country_uuid) }}" class="btn btn-info">{{__('ADD PROVINCE')}}</a>
            <a href="{{ route("admin.country.index") }}" class="btn btn-warning float-right">{{__('BACK TO COUNTRIES')}}</a>
            @if($data->count() > 0)
                <a href="{{ route("$route.index", $country_uuid) }}?export_data" class="btn btn-success float-right">{{__('Export')}}</a>
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
                        @include('admin.locationState.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


