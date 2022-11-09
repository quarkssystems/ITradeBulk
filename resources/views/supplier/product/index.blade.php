{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}
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
    <div class="row">
        <div class="col-md-12">
            
            <a href="{{ route("$route.create") }}" class="btn btn-info float-right">{{__('REQUEST PRODUCT')}}</a>
        </div>
        <div class="col-md-12">
            <div class="data-grid">
                @include('supplier.product.grid')
            </div>
        </div>
    </div>
@endsection


