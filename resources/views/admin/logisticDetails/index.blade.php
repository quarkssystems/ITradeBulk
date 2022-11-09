{{-- /**

 * Created by MANAN-S-MOZAR.

 * User: Manan

 */ --}}



@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <h1 class="display-2 text-white">{{ __('TRANSPORTER VEHICLE') }}</h1>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if ($data->count() > 0)
                <!-- <a href="{{ route("$route.index") }}?export_data" class="btn btn-success float-right">{{ __('Export') }}</a> -->
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
                        @include('admin.logisticDetails.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
