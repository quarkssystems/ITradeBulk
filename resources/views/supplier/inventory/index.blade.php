@extends('supplier.layouts.main')
@section('page-header')

    <div class="container-fluid">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item"><a href="/">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
            </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{$pageTitle}}</h4>
            </div>
        </div>

    </div>
@endsection
@section('content')
    @php($supplierTotalStock = auth()->user()->supplier_total_stock)
{{--    <div class="row">--}}
{{--        <div class="content-header">--}}
{{--            <div class="content-title">--}}
{{--                <h3>Total Stock</h3>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-3 mb-15">--}}
{{--            <div class="card card-body card-border-radius">--}}
{{--                <div class="content">--}}
{{--                    <h5 class="text-uppercase font-15">{{__('single')}} : {{$supplierTotalStock['single']}}</h5>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-3 mb-15">--}}
{{--            <div class="card card-body">--}}
{{--                <div class="content">--}}
{{--                    <h5 class="text-uppercase font-15">{{__('shrink')}} : {{$supplierTotalStock['shrink']}}</h5>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-3 mb-15">--}}
{{--            <div class="card card-body">--}}
{{--                <div class="content">--}}
{{--                    <h5 class="text-uppercase font-15">{{__('Case')}} : {{$supplierTotalStock['case']}}</h5>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-3 mb-15">--}}
{{--            <div class="card card-body">--}}
{{--                <div class="content">--}}
{{--                    <h5 class="text-uppercase font-15">{{__('Pallet')}} : {{$supplierTotalStock['pallet']}}</h5>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-md-12">
            @include('frontend.helpers.globalMessage.message')
        </div>
        <div class="content-header">
            <div class="content-title">
                <h3>Products</h3> 
                @if (auth()->user()->fact_access == '1' || auth()->user()->product_access == '1')
                    <div class=""><a href="{{ route("supplier.productimport") }}" class="btn btn-info float-right">{{__('IMPORT STOCK')}}</a></div>
                @endif       

            </div>
        </div>
    </div>


    <div class="data-grid">
              @include('supplier.inventory.grid')
    </div>
@endsection