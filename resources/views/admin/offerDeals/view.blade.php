{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */
 --}}
@extends('supplier.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{$pageTitle}}</h1>
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{__('Back')}}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                                    <h3>{{__("Vendor")}}</h3>
                                    <div>{{$sales_order->user_name}}</div>
                                    <div>{{implode(", ", $sales_order->user_address)}}</div>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                            <h3>{{__("Supplier")}}</h3>
                            <div>{{$sales_order->supplier_name}}</div>
                            <div>{{implode(", ", $sales_order->supplier_address)}}</div>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                            <h3>{{__("Details")}}</h3>
                            <div>Status: {{$sales_order->order_status}}</div>
                                    <div><b>Total: R {{$sales_order->final_total}}</b></div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h3>{{__("Items")}}</h3>
                            <table class="table table-striped">
                                <tr>
                                    <th>{{__("No")}}</th>
                                    <th>{{__("Product")}}</th>
                                    <th>{{__("Qty")}}</th>
{{--                                    <th>{{__("Price")}}</th>--}}
                                </tr>

                                @foreach($sales_order->basket_items as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->product->name}}</td>
                                        <td>
                                            @if($item->single_qty > 0)
                                                {{__("Single: ")}} {{$item->single_qty}}<br>
                                            @endif
                                                @if($item->shrink_qty > 0)
                                                {{__("Shrink: ")}} {{$item->shrink_qty}}<br>
                                            @endif
                                                @if($item->case_qty > 0)
                                                {{__("Case: ")}} {{$item->case_qty}}<br>
                                            @endif
                                                @if($item->pallet_qty > 0)
                                                {{__("Pallet: ")}} {{$item->pallet_qty}}<br>
                                            @endif
                                        </td>
{{--                                        <td></td>--}}
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
{{--                            <h3>{{__("Logistics")}}</h3>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
