{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */ --}}
@extends('supplier.layouts.main')

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
                    <div class="row mb-5">

                        <div class="col-xs-12 col-sm-12">
                            <!-- <ul class="order_tracking">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <li class="normal"><span class="dot"></span>Order Placed</li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <li class="normal"><span class="dot"></span>Order Packed</li>
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
                            <div class="card" style="min-height:221px;">
                                <div class="card-body">
                                    <h3>{{ __('Trader') }}</h3>
                                    <h6>{{ $sales_order->user_name }}</h6>
                                    <address>{{ implode(', ', $sales_order->user_address) }}</address>
                                    <h6>{{ $sales_order->user_details->phone }}</h6>
                                    <h6> {{ $sales_order->delivery_requested != null ? 'Delivery Requested: ' . $sales_order->delivery_requested : '' }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card" style="min-height:221px;">
                                <div class="card-body">
                                    <h3>{{ __('Supplier') }}</h3>
                                    <h6>{{ $sales_order->supplier_name }}</h6>
                                    <address>{{ implode(', ', $sales_order->supplier_address) }}</address>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card" style="min-height:221px;">
                                <div class="card-body" style="line-height:21px;">
                                    <h3>{{ __('Details') }}</h3>
                                    <div>Status: {{ $sales_order->order_status }}</div>
                                    <div>Delivery Status:
                                        {{ ucfirst(str_replace('_', ' ', $sales_order->delivery_type)) }}</div>
                                    <div>Discount Amount: {{ ucfirst($sales_order->discount_amount) }}</div>
                                    <div>Tax Amount: {{ ucfirst($sales_order->tax_amount ?? 0) }}</div>
                                    <div>Delivery Amount: {{ $sales_order->shipment_amount }}</div>
                                    <div><b>Total: R {{ $sales_order->final_total }}</b></div>
                                    <div>
                                        @if ($proformaInvoice != null)
                                            <a href="{{ asset($proformaInvoice) }}" target="_blank">Proforma Invoice <i
                                                    class="fa fa-file-pdf"></i> </a>
                                        @endif
                                    </div>
                                    @if (Auth::user()->role == 'SUPPLIER' || Auth::user()->role == 'PICKER' || Auth::user()->role == 'DISPATCHER')
                                        @if ($sales_order->order_status == 'PICKING STARTED' || $sales_order->order_status == 'PACKED')
                                            <div>
                                                <a href="{{ route('picking-document', [$sales_order->uuid]) }}"
                                                    target="_blank">Picking document edit <i class="fa fa-file-pdf"></i>
                                                </a><br>
                                                <a href="{{ route('credit-note', [$sales_order->uuid]) }}"
                                                    target="_blank">Proforma Credit Note <i class="fa fa-file-pdf"></i>
                                                </a><br>

                                                {{-- {{ dd($supplierOwnInvoice) }} --}}

                                                <br><br>
                                                <form action="{{ route('supplierOwnInvoice') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                                                    <input type="hidden" name="order_number"
                                                        value="{{ $sales_order->order_number }}">
                                                    <label for="">Upload Your Own Invoice</label>
                                                    <input type="file" name="file"
                                                        accept="image/jpeg,image/jpg,image/png,application/pdf" required />
                                                    <button type="submit">Save</button>
                                                </form>
                                            </div>
                                        @endif
                                        @if ($supplierOwnInvoice != null)
                                            <a href="{{ $supplierOwnInvoice }}" target="_blank">Own invoice <i
                                                    class="fa fa-file-pdf"></i> </a>
                                            <br>
                                        @endif

                                        {{-- <div>
                                
                                <a href="{{$supplierOwnInvoice}}" target="_blank">Own invoice <i class="fa fa-file-pdf"></i> </a><br><br>
                                <form action="{{route('supplierOwnInvoice')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{$sales_order->uuid}}">
                                    <input type="hidden" name="order_number" value="{{$sales_order->order_number}}">
                                    <input type="file" name="file" required/>
                                    <button type="submit">Save</button>
                                </form>
                            </div> --}}
                                    @endif
                                    @if (Auth::user()->role == 'SUPPLIER' ||
                                        Auth::user()->role == 'PICKER' ||
                                        Auth::user()->role == 'DISPATCHER' ||
                                        Auth::user()->role == 'VENDOR' ||
                                        Auth::user()->role == 'DRIVER')
                                        @if ($sales_order->order_status == 'DISPATCH')
                                            <a href="{{ route('credit-note', [$sales_order->uuid]) }}"
                                                target="_blank">Proforma Credit Note <i class="fa fa-file-pdf"></i> </a><br>
                                            <a href="{{ route('dispatch-document', [$sales_order->uuid]) }}"
                                                target="_blank">Dispatch document edit <i class="fa fa-file-pdf"></i>
                                            </a><br>

                                            <div>
                                                {{-- {{ dd($supplierOwnInvoice) }} --}}

                                                <br><br>
                                                <form action="{{ route('supplierOwnInvoice') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                                                    <input type="hidden" name="order_number"
                                                        value="{{ $sales_order->order_number }}">
                                                    <label for="">Upload Your Own Invoice</label>
                                                    <input type="file" name="file"
                                                        accept="image/jpeg,image/jpg,image/png,application/pdf" required />
                                                    <button type="submit">Save</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                    <span style="font-size: 16px;">
                                        <a href="{{ route('user.itbinvoice', [$sales_order->uuid]) }}" target="_blank">ITB
                                            invoice</a>
                                    </span>
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
                                    {{-- <th>{{ __('Color') }}</th>
                                    <th>{{ __('Size') }}</th> --}}
                                    {{-- <th>{{__("Price")}}</th> --}}
                                </tr>

                                @foreach ($sales_order->basket_items as $item)
                                    <?php
                                    $color = '';
                                    if ($item['color']) {
                                        $color = 'Color: ' . $item['color'] . ',';
                                    }
                                    $size = '';
                                    if ($item['size']) {
                                        $size = 'Size: ' . $item['size'];
                                    }
                                    ?>

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product != null ? $item->product->name : '' }}
                                            @if ($color != '' || $size != '')
                                                ({{ $color }} {{ $size }})
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @if ($item->single_qty > 0) --}}
                                            {{ $item->single_qty }}<br>
                                            {{-- @endif --}}
                                            {{-- @if ($item->single_qty > 0) --}}
                                            {{-- {{__("Single: ")}} --}} <br>
                                            {{-- @endif --}}
                                            {{-- @if ($item->shrink_qty > 0)
                                                {{ __('Shrink: ') }} {{ $item->shrink_qty }}<br>
                                            @endif
                                            @if ($item->case_qty > 0)
                                                {{ __('Case: ') }} {{ $item->case_qty }}<br>
                                            @endif
                                            @if ($item->pallet_qty > 0)
                                                {{ __('Pallet: ') }} {{ $item->pallet_qty }}<br>
                                            @endif --}}
                                        </td>
                                        {{-- <td>{{ $item->color }}</td>
                                        <td>{{ $item->size }}</td> --}}

                                        {{-- <td></td> --}}
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                    </div>



                    @if (auth()->user()->role == 'VENDOR')
                        @if ($sales_order->order_status != 'DELIVERED')
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    {!! Form::label('Order Status', 'Order Status', ['class' => 'form-label']) !!}
                                    {!! Form::select('status', $order_status, $sales_order->order_status, [
                                        'class' => 'orderpayment_status',
                                        'autofocus',
                                        'data-id' => $sales_order->uuid,
                                        'data-area-holder' => 'status-' . $sales_order->uuid,
                                        'data-ajax-url' => route('frontend.ajax.updateOrderStatus'),
                                    ]) !!}
                                    <span class="waitProcess"></span>

                                    <span style="font-size: 19px">
                                        {{ $sales_order->delivery_requested != null ? 'Delivery Requested: ' . $sales_order->delivery_requested : '' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @else
                        @if ($sales_order->order_status != 'DELIVERED')
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    {!! Form::label('Order Status', 'Order Status', ['class' => 'form-label']) !!}
                                    {!! Form::select('status', $order_status, $sales_order->order_status, [
                                        'class' => 'orderpayment_status',
                                        'autofocus',
                                        'data-id' => $sales_order->uuid,
                                        'data-area-holder' => 'status-' . $sales_order->uuid,
                                        'data-ajax-url' => route('frontend.ajax.updateOrderStatus'),
                                    ]) !!}
                                    <span class="waitProcess" style="font-size: 23px"></span>
                                    <span style="font-size: 19px">
                                        {{ $sales_order->delivery_requested != null ? 'Delivery Requested: ' . $sales_order->delivery_requested : '' }}
                                    </span>
                                </div>

                            </div>
                        @endif
                    @endif
                    <div class="statusAcceptOrder" style="display: none">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="card" style="min-height:221px;">

                                    <form action="{{ route('frontend.ajax.updateOrderStatus') }}" method="POST">
                                        <div class="row">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="col-md-12">
                                                {{-- <label for="">Enter Order Lead Time</label><br> --}}
                                                <label for="">Date</label>
                                                {{-- <input type="date" name="order_lead_time" required> --}}
                                                {{ date('Y-m-d') }}
                                                <input type="hidden" name="order_lead_time"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>

                                            <input type="hidden" name="order_lead_time_clock" value="1">

                                            <div class="col-md-12 ">
                                                <label for="">Standard Lead Time</label>
                                                <select name="order_lead_time_to_clock" id="order_lead_time_to_clock">
                                                    <option value="24">24</option>
                                                    <option value="48">48</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="order_status" value="ACCEPT ORDER">
                                            <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                                            {{-- <div class="col-md-12 ">
                                    <label for="">Delivery requested</label><br>
                                    <select name="delivery_requested" id="delivery_requested">
                                        <option value="Same day">Same day</option>
                                        <option value="Next Day">Next Day</option>
                                        <option value="Flexible">Flexible</option>
                                    </select>
                                </div> --}}
                                            {{-- <div class="col-md-12">
                                    <label for="">From Time</label>
                                    <input type="time" name="order_lead_time_clock" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="">To Time</label>
                                    <input type="time" name="order_lead_time_to_clock" required>
                                    <input type="hidden" name="order_status" value="ACCEPT ORDER">
                                    <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                                </div> --}}
                                            <div class="col-md-12">
                                                <label for="">Choose Picker</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                {!! Form::select('picker_name', $picker) !!}
                                                <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                                            </div>
                                        </div>

                                        <button type="submit">Save</button>

                                    </form>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="card" style="min-height:221px;">
                                    <div class="card-body">
                                        <h3>{{ __('Trader Details') }}</h3>
                                        <h6>{{ $sales_order->user_name }}</h6>
                                        <address>{{ implode(', ', $sales_order->user_address) }}</address>
                                        <h6>{{ $sales_order->user_details->phone }}</h6>
                                        <h6> {{ $sales_order->delivery_requested != null ? 'Delivery Requested: ' . $sales_order->delivery_requested : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="statusChoosePicker" style="display: none">
                        <form action="{{ route('frontend.ajax.updateOrderStatus') }}" method="POST">
                            <label for="">Choose Picker</label>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {!! Form::select('picker_name', $picker) !!}
                            <input type="hidden" name="order_status" value="CHOOSE PICKER">
                            <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                            <button type="submit">Save</button>
                        </form>
                    </div> --}}

                    <div class="statusChooseDispatcher" style="display: none">
                        <form action="{{ route('frontend.ajax.updateOrderStatus') }}" method="POST">
                            <label for="">Choose Dispatcher</label>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {!! Form::select('dispatcher_name', $dispatcher) !!}
                            <input type="hidden" name="order_status" value="PACKED">
                            <input type="hidden" name="order_id" value="{{ $sales_order->uuid }}">
                            <button type="submit">Save</button>
                        </form>
                    </div>


                    {{-- <div class="row"> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> --}}
                    {{-- <h3>{{__("Logistics")}}</h3> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
