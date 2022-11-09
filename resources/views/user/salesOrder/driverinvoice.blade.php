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

                        

                         <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="card" style="min-height:221px;">
                                <div class="card-body">
                                    <h3>{{__("Trader")}}</h3>
                                    <h6>{{$sales_order->user_name}}</h6>
                                    <address>{{implode(", ", $sales_order->user_address)}}</address>
                                </div>
                            </div>
                        </div>

                       <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="card" style="min-height:221px;">
                                <div class="card-body">
                                    <h3>{{__("Transporter")}}</h3>
                                    <h6>{{$sales_order->logistic_name}}</h6>
                                    <address>{{implode(", ", $sales_order->logistic_address)}}</address> 
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="card" style="min-height:221px;">
                                <div class="card-body">
                            <h3>{{__("Supplier")}}</h3>
                            <h6>{{$sales_order->supplier_name}}</h6>
                            <address>{{implode(", ", $sales_order->supplier_address)}}</address>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                            <h3>{{__("Details")}}</h3>
                            <h6>Status: {{$sales_order->order_status}}</h6>
                            <h6>Delivery Status: {{ucfirst($sales_order->delivery_type)}}</h6>
                            <h6>Delivery Amount: {{$sales_order->shipment_amount}}</h6>
                           
                                </div>
                            </div>
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
                                        <td>{{ (isset($item->product)) ? $item->product->name : ''}}</td>
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
                 {{--   <div class="row" ><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                             {!! Form::label('Order Status', 'Order Status', ['class' => 'form-label']) !!}
                            {!! Form::select("status",$order_status,$sales_order->order_status,["class"=>"orderpayment_status","autofocus" ,"data-id"  => $sales_order->uuid,"data-area-holder" => "status-".$sales_order->uuid  , "data-ajax-url" => route('frontend.ajax.updateOrderStatus')]) !!}
                           
                       </div> </div> --}}



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
