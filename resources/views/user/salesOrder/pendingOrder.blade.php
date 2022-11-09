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

                   

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <table class="table ">

                                <thead class="thead-light">

                                <tr>

                                    <th>{{__("No")}}</th>

                                    <th>{{__("Order ID")}}</th>

                                    <th>{{__("Trader")}}</th>

                                    <th>{{__("Supplier")}}</th>  

                                    <th>{{__("Driver")}}</th>

                                    <th>{{__("Amount")}}</th>

                                    <th>{{__("Status")}}</th>

                                    

                                </tr>

                                </thead>

                                <tbody>

                                @foreach ($orders as $datum)

                                    <tr>

                                        <td>{{$loop->iteration}}</td>

                                        <td>{{$datum->order_number}}</td>

                                        <td>{{$datum->user_name}}</td>

                                        <td>{{$datum->supplier_name}}</td>

                                        <td>{{$datum->logistic_name}}</td>

                                        <td>{{$datum->shipment_amount}}</td>

                                        <td>{{$datum->order_status}}</td>


                                    </tr>

                                @endforeach

                                @if($orders->count() == 0)

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