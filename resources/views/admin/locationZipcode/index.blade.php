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
            <h1 class="display-2 text-white">{{__('MANAGE POSTAL CODE')}}</h1>
            <p class="text-white mt-0 mb-5">{{__('COUNTRY: :country | PROVINCE: :state | CITY: :city', ['country' => $city->country->country_name, 'state' => $city->state->state_name, 'city' => $city->city_name])}}</p>
            <a href="{{ route("$route.create", $city_uuid) }}" class="btn btn-info">{{__('ADD POSTAL CODE')}}</a>
            <a href="{{ route("admin.city.index", $city->state_id) }}" class="btn btn-warning float-right">{{__('BACK TO CITIES')}}</a>
            @if($data->count() > 0)
                <a href="{{ route("$route.index", $city_uuid) }}?export_data" class="btn btn-success float-right">{{__('Export')}}</a>
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
                        @include('admin.locationZipcode.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


