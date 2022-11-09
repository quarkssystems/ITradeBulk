{{--
/**
 * Created by PhpStorm.
 * User: Haiyu
 */
 --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{__('Settle Request')}}</h1></a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.withdrawalRequest.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



