{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */ --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <!-- <ul class="order_tracking">
                                                                                                                                        <li class="normal"><span class="dot"></span>Order Placed</li>
                                                                                                                                        <li class="normal"><span class="dot"></span>Order Prepared</li>
                                                                                                                                        <li class="normal"><span class="dot"></span>Dispatched</li>
                                                                                                                                        <li class="normal"><span class="dot"></span>Delivered</li>
                                                                                                                                    </ul> -->
                            <ul class="order_tracking">
                                @inject('constants', 'App\Models\SalesOrder')
                                <li class="@if ($sales_order->order_status == $constants::ORDERPLACED ||
                                    $sales_order->order_status == 'PACKED' ||
                                    $sales_order->order_status == $constants::DISPATCH ||
                                    $sales_order->order_status == 'DELIVERED' ||
                                    $sales_order->order_status == 'ACCEPT ORDER' ||
                                    $sales_order->order_status == 'PICKING STARTED' ||
                                    $sales_order->order_status == 'ORDER COMPLETE' ||
                                    $sales_order->order_status == 'ORDER COLLECTED' ||
                                    $sales_order->order_status == 'ACCEPT DELIVERY') success @else normal @endif"><span
                                        class="dot"></span>Order Placed</li>
                                <li class="@if ($sales_order->order_status == 'PACKED' ||
                                    $sales_order->order_status == $constants::DISPATCH ||
                                    $sales_order->order_status == 'DELIVERED' ||
                                    $sales_order->order_status == 'ORDER COMPLETE' ||
                                    $sales_order->order_status == 'ORDER COLLECTED') success @else normal @endif"><span
                                        class="dot"></span>Order Prepared</li>
                                <li class="@if ($sales_order->order_status == $constants::DISPATCH ||
                                    $sales_order->order_status == 'DELIVERED' ||
                                    $sales_order->order_status == 'ORDER COMPLETE' ||
                                    $sales_order->order_status == 'ORDER COLLECTED') success @else normal @endif"><span
                                        class="dot"></span>Dispatch</li>
                                <li class="@if ($sales_order->order_status == 'DELIVERED' || $sales_order->order_status == 'ORDER COMPLETE') success @else normal @endif"><span
                                        class="dot"></span>Delivered</li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3>{{ __('Trader') }}</h3>
                                    <p>{{ $sales_order->user_name }}</p>
                                    <p>{{ implode(', ', $sales_order->user_address) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3>{{ __('Supplier') }}</h3>
                                    <p>{{ $sales_order->supplier_name }}</p>
                                    <p>{{ implode(', ', $sales_order->supplier_address) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3>{{ __('Details') }}</h3>
                                    <p>Status: {{ $sales_order->order_status }}</p>
                                    <p>Delivery Status: {{ ucfirst($sales_order->delivery_type) }}</p>
                                    <p>Discount Amount: {{ ucfirst($sales_order->discount_amount) }}</p>
                                    <p>Tax Amount: {{ ucfirst($sales_order->tax_amount ?? 0) }}</p>
                                    <p><b>Total: R {{ $sales_order->final_total }}</b></p>
                                    <div>
                                        @if ($proformaInvoice != null)
                                            <a href="{{ asset($proformaInvoice) }}" target="_blank">Proforma Invoice <i
                                                    class="fa fa-file-pdf"></i> </a><br>
                                        @endif
                                        @if ($supplierOwnInvoice != null)
                                            <a href="{{ $supplierOwnInvoice }}" target="_blank">Own Invoice <i
                                                    class="fa fa-file-pdf"></i> </a>
                                            <br>
                                            {{-- <a href="{{ route('credit-note', [$sales_order->uuid]) }}"
                                                target="_blank">Credit Note <i class="fa fa-file-pdf"></i> </a><br> --}}
                                        @endif
                                        @if ($suppliertaxInvoice != null)
                                            <a href="{{ $suppliertaxInvoice }}" target="_blank">Tax Invoice <i
                                                    class="fa fa-file-pdf"></i> </a>
                                            <br>
                                            {{-- <a href="{{ route('credit-note', [$sales_order->uuid]) }}"
                                            target="_blank">Credit Note <i class="fa fa-file-pdf"></i> </a><br> --}}
                                        @endif
                                        <a href="{{ $itbInvoice }}" target="_blank">ITB Invoice <i
                                                class="fa fa-file-pdf"></i> </a>
                                        <br>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h3>{{ __('Items') }}</h3>
                            <table class="table table-striped">
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    {{-- <th>{{__("Price")}}</th> --}}
                                </tr>

                                @foreach ($sales_order->basket_items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product != null ? $item->product->name : '' }}</td>

                                        <td>{{ $item->single_qty }}</td>

                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    @if ($sales_order->delivery_type != 'pickup')
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>{{ __('Logistics') }}</h3>
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('Driver Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Address') }}</th>
                                    </tr>
                                    @foreach ($Drivers as $driver)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->email }}</td>
                                            <td>{{ $driver->user_address }} </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
