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
        @include('frontend.helpers.globalMessage.message')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h4><i class="fa fa-wallet"></i> Balance: R{{$walletBalance}} 
                    @if($role == 'DRIVER' || $role == 'COMPANY' || $role == 'SUPPLIER' || $role == 'VENDOR')   
                     <a  class="btn btn-info" href="{{route($route.".create")}}">Add Settle Request<i class="fa fa-money"></i></a></h4> 
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <table class="table ">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{__("No")}}</th>
                                    <th>{{__("Wallet Reference")}}</th>
                                    <th>{{__("Withdrawal Amount")}}</th>
                                    <th>{{__("Status")}}</th>
                                    <th>{{__("Time")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($transactions as $datum)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>W-{{$datum->id}}</td>
                                        <td>{{$datum->amount}}</td>
                                        <td>{{$datum->status}}</td>
                                        <td>{{$datum->created_at->format('d-m-Y h:i a')}}</td>
                                    </tr>
                                @endforeach
                                @if($transactions->count() == 0)
                                    <tr>
                                        <td colspan="11">
                                            <div class="alert alert-primary">{{__('No data found')}}</div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection