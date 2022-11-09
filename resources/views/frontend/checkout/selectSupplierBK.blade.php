@extends('frontend.layouts.main')
@section('content')
    <section class="spacer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{__('Select Supplier')}}</b></h3>
                </div>
            </div>
            <div class="row mb-15">
                @foreach($supplierData as $supplierDatam)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-15">
                        <div class="box-type-1">
                            <div class="box-icon min-height-200 line-height-200">
                                <img src="{{asset("assets/frontend/images/select-supplier-{$loop->iteration}.png")}}" style="max-width: 200px">
                            </div>
                            <div class="box-details">
                                <div class="box-title">{{$supplierDatam["supplier"]["display_name"]}}</div>
                            </div>
                            <div id="supplierProductDetail{{$supplierDatam["supplier"]["uuid"]}}" class="collapse">
                                <table class="table-striped table table-bordered">
                                    <tr>
                                        <th>{{__('Item')}}</th>
                                        <th>{{__('QTY')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Total')}}</th>
                                    </tr>
                                    @foreach($supplierDatam["products"] as $basketProduct)
                                        @if(count($basketProduct["stock"]) > 0)
                                            <tr>
                                                <td>{{$basketProduct['product_name']}}</td>
                                                <td>
                                                    @if(isset($basketProduct["stock"]["single"]))
                                                        {{__("Single: ")}} {{$basketProduct["stock"]["single"]["qty"]}}
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["shrink"]))
                                                        {{__("Shrink: ")}} {{$basketProduct["stock"]["shrink"]["qty"]}}
                                                    @endif

                                                        @if(isset($basketProduct["stock"]["case"]))
                                                        {{__("Case: ")}} {{$basketProduct["stock"]["case"]["qty"]}}
                                                    @endif

                                                        @if(isset($basketProduct["stock"]["pallet"]))
                                                        {{__("Pallet: ")}} {{$basketProduct["stock"]["pallet"]["qty"]}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($basketProduct["stock"]["single"]))
                                                        <div>{{$basketProduct["stock"]["single"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["shrink"]))
                                                        <div>{{$basketProduct["stock"]["shrink"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["case"]))
                                                        <div>{{$basketProduct["stock"]["case"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["pallet"]))
                                                        <div>{{$basketProduct["stock"]["pallet"]["price"]}}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($basketProduct["stock"]["single"]))
                                                        <div>{{$basketProduct["stock"]["single"]["qty"] * $basketProduct["stock"]["single"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["shrink"]))
                                                        <div>{{$basketProduct["stock"]["shrink"]["qty"] * $basketProduct["stock"]["shrink"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["case"]))
                                                        <div>{{$basketProduct["stock"]["case"]["qty"] * $basketProduct["stock"]["case"]["price"]}}</div>
                                                    @endif

                                                    @if(isset($basketProduct["stock"]["pallet"]))
                                                        <div>{{$basketProduct["stock"]["pallet"]["qty"] * $basketProduct["stock"]["pallet"]["price"]}}</div>
                                                    @endif

                                                        <hr>
                                                        <div><strong>R{{$basketProduct["row_total"]}}</strong></div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="table-warning">
                                                <td>{{$basketProduct['product_name']}}</td>
                                                <td>{{__("NA")}}</td>
                                                <td>{{__("NA")}}</td>
                                                <td>{{__("NA")}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pl-4 pb-2"><b>{{__("Total weight:")}} {{$supplierDatam["total_weight_unit"]["weight"]}}{{$supplierDatam["total_weight_unit"]["unit"]}}</b></div>
                                </div>
                            </div>
                            <div class="checkout-supplier">
                                <div class="row">
                                    @if(($supplierDatam["total_weight_unit"]["unit"] == 'ton' && $supplierDatam["total_weight_unit"]["weight"] < 1 ) || $supplierDatam["total_weight_unit"]["unit"] != 'ton')
                                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 text-right">
                                            <div class="text-danger"><small>{{__("Minimum order weight should be 1 ton")}}</small></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
{{--                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 checkout-total">R{{$supplierDatam["total"]}} <small class="{{$supplierDatam["total_weight_unit"]["unit"] == 'ton' && $supplierDatam["total_weight_unit"]["weight"] >= 1 ? "text-success" : "text-warning"}}"><i>({{$supplierDatam["total_weight_unit"]["weight"]}} {{$supplierDatam["total_weight_unit"]["unit"]}})</i></small></div>--}}
{{--                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
{{--                                            <a href="javascript:;" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#supplierProductDetail{{$supplierDatam["supplier"]["uuid"]}}" >--}}
{{--                                                {{__('Details')}}</a>--}}
{{--                                            @if($supplierDatam["total_weight_unit"]["unit"] == 'ton' && $supplierDatam["total_weight_unit"]["weight"] >= 1 )--}}
{{--                                                <a href="#selectDeliveryTypeModal" data-supplier-id="{{$supplier->uuid}}" data-toggle="modal" class="btn btn-sm btn-success selectDeliveryTypeModalButton" data-delivery-price="{{isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0}}" data-delivery-vehicle="{{isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null}}" data-distance="{{$distance['distance']}}" data-total-price="{{$total}}" >{{__('Checkout')}}</a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
                                    </div>
{{--                                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 ">--}}
{{--                                        <table class="table table-bordered">--}}
{{--                                            <tr>--}}
{{--                                                <th>{{__("Delivery")}}</th>--}}
{{--                                                <th>{{__("Pickup")}}</th>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <td width="50%">--}}
{{--                                                    <p>--}}
{{--                                                        <b>{{__("Total distance: ")}}</b> {{$distance['distance']}}<br>--}}
{{--                                                        <b>{{__("Delivery vehicle: ")}}</b> {{isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null}}<br>--}}
{{--                                                        <b>{{__("Delivery charge: ")}}</b> R{{isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0}}<br>--}}
{{--                                                        <b>{{__("Product charge: ")}}</b> R{{$total}}<br>--}}
{{--                                                        <strong>{{__("Total: ")}}</strong> R{{$total + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) }}--}}
{{--                                                    </p>--}}
{{--                                                </td>--}}
{{--                                                <td width="50%">--}}
{{--                                                    <p>--}}
{{--                                                        <b>{{__("Product charge: ")}}</b> R{{$total}}<br>--}}
{{--                                                        <strong>{{__("Total: ")}}</strong> R{{$total}}--}}
{{--                                                    </p>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach($suppliers as $supplier)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-15">
                        <div class="box-type-1">
                            <div class="box-icon min-height-200 line-height-200">
                                <img src="{{asset("assets/frontend/images/select-supplier-{$loop->iteration}.png")}}" style="max-width: 200px">
                            </div>
                            <div class="box-details">
                                <div class="box-title">{{__($supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name)}}</div>
                                @php($total = 0)
                                @php($totalWeight = 0)
                                @php($totalProducts = 0)
                                @php($totalAvailableProducts = 0)
                                <div id="supplierProductDetail{{$supplier->uuid}}" class="collapse">
                                    <table class="table-striped table table-bordered">
                                        <tr>
                                            <th>{{__('Item')}}</th>
                                            <th>{{__('QTY')}}</th>
                                            <th>{{__('Price')}}</th>
                                            <th>{{__('Total')}}</th>
                                        </tr>
                                        @foreach($basketProducts as $basketProduct)
                                            @php($totalProducts++)
                                            @if($supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid)->count() > 0)
                                                @php($supplierLatestRate = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid)->orderBy('id', 'DESC')->first())
                                                @php($singlePrice = $supplierLatestRate->single_price)
                                                @php($shrinkPrice = $supplierLatestRate->shrink_price)
                                                @php($casePrice = $supplierLatestRate->case_price)
                                                @php($palletPrice = $supplierLatestRate->pallet_price)
                                                @php($itemWeight = 0)
                                                @php($totalAvailableProducts++)
                                                <tr>
                                                    <td>{{$supplierLatestRate->product->name}}</td>
                                                    <td>
                                                        @if($basketProduct->single_qty > 0)
                                                            <div>Single: {{$basketProduct->single_qty}}</div>
                                                            @php($itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty))
                                                        @endif
                                                            @if($basketProduct->shrink_qty > 0)
                                                                <div>Shrink: {{$basketProduct->shrink_qty}}</div>
                                                                @php($itemWeight += $basketProduct->product->getCalculatedWeight("shrink", $basketProduct->shrink_qty))
                                                        @endif
                                                            @if($basketProduct->case_qty > 0)
                                                                <div>Case: {{$basketProduct->case_qty}}</div>
                                                                @php($itemWeight += $basketProduct->product->getCalculatedWeight("case", $basketProduct->case_qty))
                                                        @endif
                                                            @if($basketProduct->pallet_qty > 0)
                                                            <div>Pallet: {{$basketProduct->pallet_qty}}</div>
                                                                @php($itemWeight += $basketProduct->product->getCalculatedWeight("pallet", $basketProduct->pallet_qty))
                                                        @endif
                                                        @php($totalWeight += $itemWeight)
                                                    </td>
                                                    <td>
                                                        @if($basketProduct->single_qty > 0)
                                                            <div>R{{$singlePrice}}</div>
                                                        @endif
                                                        @if($basketProduct->shrink_qty > 0)
                                                                <div>R{{$shrinkPrice}}</div>
                                                        @endif
                                                        @if($basketProduct->case_qty > 0)
                                                                <div>R{{$casePrice}}</div>
                                                        @endif
                                                        @if($basketProduct->pallet_qty > 0)
                                                                <div>R{{$palletPrice}}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php($rowTotal = 0)
                                                        @if($basketProduct->single_qty > 0)
                                                            <div>R{{$singlePrice * $basketProduct->single_qty}}</div>
                                                            @php($rowTotal += ($singlePrice * $basketProduct->single_qty))
                                                        @endif
                                                        @if($basketProduct->shrink_qty > 0)
                                                            <div>R{{$shrinkPrice * $basketProduct->shrink_qty}}</div>
                                                            @php($rowTotal += ($shrinkPrice * $basketProduct->shrink_qty))
                                                        @endif
                                                        @if($basketProduct->case_qty > 0)
                                                            <div>R{{$casePrice * $basketProduct->case_qty}}</div>
                                                            @php($rowTotal += ($casePrice * $basketProduct->case_qty))
                                                        @endif
                                                        @if($basketProduct->pallet_qty > 0)
                                                            <div>R{{$palletPrice * $basketProduct->pallet_qty}}</div>
                                                            @php($rowTotal += ($palletPrice * $basketProduct->pallet_qty))
                                                        @endif
                                                        @php($total += $rowTotal)
                                                        <hr>
                                                        <div><strong>R{{$rowTotal}}</strong></div>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr class="table-warning">
                                                    <td>{{$basketProduct->product->name}}</td>
                                                    <td>{{__("NA")}}</td>
                                                    <td>{{__("NA")}}</td>
                                                    <td>{{__("NA")}}</td>
                                                </tr>
                                            @endif

                                        @endforeach
                                    </table>
                                    <div class="row">
                                        @php($totalWeightUnit = $supplier->kgToUnit($totalWeight))
                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pl-4 pb-2"><b>{{__("Total weight:")}} {{$totalWeightUnit["weight"]}}{{$totalWeightUnit["unit"]}}</b></div>
                                    </div>
                                </div>
                                <div class="checkout-supplier">

                                    @php($distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude))
                                    @php($distanceValue = isset($distance['distance']) ? $distance['distance'] : 0 )
                                    @php($deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($totalWeight, $distanceValue))
                                    <div class="row">

                                        @if(($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] < 1 ) || $totalWeightUnit["unit"] != 'ton')
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 text-right">
                                                <div class="text-danger"><small>{{__("Minimum order weight should be 1 ton")}}</small></div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 checkout-total">R{{$total}} <small class="{{$totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] >= 1 ? "text-success" : "text-warning"}}"><i>({{$totalWeightUnit["weight"]}} {{$totalWeightUnit["unit"]}})</i></small></div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <a href="javascript:;" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#supplierProductDetail{{$supplier->uuid}}" >
                                                    {{__('Details')}}</a>
                                                @if($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] >= 1 )
                                                    <a href="#selectDeliveryTypeModal" data-supplier-id="{{$supplier->uuid}}" data-toggle="modal" class="btn btn-sm btn-success selectDeliveryTypeModalButton" data-delivery-price="{{isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0}}" data-delivery-vehicle="{{isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null}}" data-distance="{{$distance['distance']}}" data-total-price="{{$total}}" >{{__('Checkout')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 ">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>{{__("Delivery")}}</th>
                                                    <th>{{__("Pickup")}}</th>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <p>
                                                            <b>{{__("Total distance: ")}}</b> {{$distance['distance']}}<br>
                                                            <b>{{__("Delivery vehicle: ")}}</b> {{isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null}}<br>
                                                            <b>{{__("Delivery charge: ")}}</b> R{{isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0}}<br>
                                                            <b>{{__("Product charge: ")}}</b> R{{$total}}<br>
                                                            <strong>{{__("Total: ")}}</strong> R{{$total + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) }}
                                                        </p>
                                                    </td>
                                                    <td width="50%">
                                                        <p>
                                                            <b>{{__("Product charge: ")}}</b> R{{$total}}<br>
                                                            <strong>{{__("Total: ")}}</strong> R{{$total}}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="supplier-selection-badge">
                                    <span class="badge-title">{{$totalAvailableProducts}}/{{$totalProducts}}</span>
                                    <span class="badge-sub-title">{{__("Available")}}</span>
                                </div>

                                <div class="supplier-selection-badge percentage-badge-left">
                                    @php($availablePercentage = intval((100 * $totalAvailableProducts) / $totalProducts))
                                    <span class="badge-title">{{$availablePercentage}}%</span>
{{--                                    <span class="badge-sub-title">{{__("Available")}}</span>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- The Modal -->
    <div class="modal" id="selectDeliveryTypeModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Select Delivery Type')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        {!! Form::open(['route' => "make-payment"]) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="pickup" checked name="delivery_type">Pickup
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="delivery" name="delivery_type">Delivery
                                    <span class="checkout-delivery-charge"></span>
                                    <span class="checkout-delivery-approx-distance"></span>
                                    <span class="checkout-delivery-vehicle"></span>
                                </label>
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                            <input type="hidden" name="product_total" class="product-total-input">
                            <input type="hidden" name="shipping_total" class="shipping-total-input">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success"><i class="far fa-money-bill-alt"></i> Pay</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection