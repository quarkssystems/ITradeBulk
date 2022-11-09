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
            <h1 class="display-2 text-white">{{__('MANAGE CITIES')}}</h1>
            <p class="text-white mt-0 mb-5">{{__('Country: :country | Province: :state', ['country' => $state->country->country_name, 'state' => $state->state_name])}}</p>
            <a href="{{ route("$route.create", $state_uuid) }}" class="btn btn-info">{{__('ADD CITY')}}</a>
            <a href="{{ route("admin.state.index", $state->country_id) }}" class="btn btn-warning float-right">{{__('BACK TO PROVINCE')}}</a>
            @if($data->count() > 0)
                <a href="{{ route("$route.index", $state_uuid) }}?export_data" class="btn btn-success float-right">{{__('Export')}}</a>
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
                        @include('admin.locationCity.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


