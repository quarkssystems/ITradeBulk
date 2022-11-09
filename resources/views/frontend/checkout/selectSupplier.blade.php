@extends('frontend.layouts.main')

@section('content')
    <section class="spacer">

        <div class="container">

            <div class="row">

                <div class="col-md-12 text-center">

                    <h3 class="mb-4"><b>{{ __('Select Supplier') }}</b></h3>

                </div>

            </div>

            <?php
            $supplier_delivery = '';
            ?>
            <div class="row mb-15">
                @if (isset($suppliers) && count($suppliers) != 0)
                    @foreach ($suppliers as $supplier)
                        <?php
                        $supplier_delivery = $supplier->supplier_delivery;
                        ?>
                        <div class="col-md-6 mb-4">

                            <div class="box-type-1 supplier-selection-box">



                                <div class="box-icon">

                                    <!-- <img src='{{ asset("assets/frontend/images/select-supplier-{$loop->iteration}.png") }}' class="supplier-logo"> -->
                                    @if (is_null($supplier->image))
                                        <img src='{{ asset('images/supplier.png') }}' class="supplier-logo">
                                    @else
                                        <img src='{{ asset($supplier->image) }}' class="supplier-logo">
                                    @endif

                                </div>

                                <div class="box-details">

                                    <div class="box-title">
                                        @if ($supplier != null)
                                            {{-- {{ dd($supplier->company->trading_name) }} --}}

                                            {{ __($supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name) }}
                                        @endif
                                    </div>

                                    @php($todayDate = Carbon\Carbon::now()->format('Y-m-d H:i:s'))

                                    @php($offervalue = 0)



                                    @php($total = 0)

                                    @php($totalWeight = 0)

                                    @php($totalProducts = 0)

                                    @php($totalAvailableProducts = 0)

                                    @php($itemTotalTax = 0)

                                    <div class="supplierProductDetail{{ $supplier->uuid }} product_details">



                                        <table class="table-striped table table-bordered">

                                            <tr>

                                                <th>{{ __('Item') }}</th>

                                                <th>{{ __('QTY') }}</th>

                                                <th>{{ __('Price') }}</th>

                                                <th style="position:relative">{{ __('Total') }} <img class="close"
                                                        src="/assets/frontend/images/error.png"></th>

                                            </tr>

                                            @foreach ($arrBasketProducts as $basketProduct)
                                                <?php
                                                
                                                $single_qty_new = $basketProduct->single_qty;
                                                // $single_qty_new = $basketProduct->getOriginal()['single_qty'];
                                                ?>

                                                @php($totalProducts++)

                                                @if (!empty($single_qty_new) && $single_qty_new > 0)
                                                    {{-- @if (!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0) --}}


                                                    @php($supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid))

                                                    @php($supplierItemInventoryModel->where('single', '>', 0))

                                                    @php($supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0))

                                                    @php($supplierLatestRate = $supplierItemInventoryDataModel->first())
                                                    <?php
                                                    //   dd($supplierLatestRate->single_price);
                                                    if ($supplierLatestRate != null) {
                                                        $single_price_new = $supplierLatestRate->single_price;
                                                    } else {
                                                        $single_price_new = 0;
                                                    }
                                                    //  $single_price_new = $supplierLatestRate->getOriginal()['single_price'];
                                                    ?>
                                                    @if ($supplierLatestRate && !empty($single_price_new) && $single_price_new > 0)
                                                        {{-- @if ($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0) --}}


                                                        @php($singlePrice = $single_price_new)
                                                        {{-- @php($singlePrice = $supplierLatestRate->single_price) --}}
                                                        {{-- {{dd($supplierLatestRate)}} --}}

                                                        @if ($offerModel->where('user_id', $supplier->uuid)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0)
                                                            <?php
                                                            // $productOffer = $offerModel
                                                            //     ->where('user_id', $supplier->uuid)
                                                            //     ->where('products_id', $basketProduct->product_id)
                                                            //     ->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                            //     ->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                            //     ->orderBy('id', 'DESC')
                                                            //     ->first();
                                                            $productOffer = $offerModel
                                                                ->where('user_id', $supplier->uuid)
                                                                ->where('product_id', $basketProduct->product_id)
                                                                ->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                                ->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                                ->orderBy('id', 'DESC')
                                                                ->first();
                                                            
                                                            ?>
                                                            {{-- @php(
    $productOffer = $offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first(),
) --}}



                                                            @if ($productOffer->promotion_id != '')
                                                                {{-- @if ($productOffer->offer_type == 'RENT') --}}
                                                                @php($singlePrice = $productOffer->promotion_price)
                                                            @else
                                                                @php($singlePrice = $singlePrice - ($singlePrice * $productOffer->promotion_price) / 100)
                                                            @endif
                                                        @endif


                                                        @php($supplierQty = $supplierLatestRate->single)

                                                        @php($itemWeight = 0)

                                                        @php($productitemTax = 0)



                                                        @php($totalAvailableProducts++)

                                                        <tr>

                                                            <td>{{ $supplierLatestRate->product->name }}</td>

                                                            <td>

                                                                @if ($single_qty_new > 0 && $singlePrice > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0 && $singlePrice > 0) --}}

                                                                    @php($pqtyClass = '')

                                                                    @if ($supplierQty >= $single_qty_new)
                                                                        {{-- @if ($supplierQty >= $basketProduct->single_qty) --}}

                                                                        <div class="">
                                                                            {{ $supplierLatestRate->product->stock_type }}
                                                                            :
                                                                            {{ $single_qty_new }}</div>


                                                                        {{-- <div class="">{{$supplierLatestRate->product->stock_type}} : {{$basketProduct->single_qty}}</div> --}}
                                                                    @else
                                                                        @php($pqtyClass = 'badge badge-warning')

                                                                        @php($single_qty_new = $supplierQty)
                                                                        {{-- @php($basketProduct->single_qty = $supplierQty) --}}

                                                                        <div><span
                                                                                class="{{ $pqtyClass }}">{{ $supplierLatestRate->product->stock_type }}
                                                                                : {{ $single_qty_new }}</span> <span
                                                                                class="badge badge-info">AVAILABEL
                                                                                STOCK</span>
                                                                        </div>
                                                                        {{-- <div><span class="{{$pqtyClass}}">{{$supplierLatestRate->product->stock_type}} : {{$basketProduct->single_qty}}</span> <span class="badge badge-info">AVAILABEL STOCK</span></div> --}}
                                                                    @endif



                                                                    @php($itemWeight += $basketProduct->product->getCalculatedWeight('single', $single_qty_new))
                                                                    {{-- @php($itemWeight += $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty)) --}}

                                                                    @php($productitemTax = $basketProduct->product->getCalculatedTax('single', $single_qty_new, $singlePrice))
                                                                    {{-- @php($productitemTax = $basketProduct->product->getCalculatedTax("single",$basketProduct->single_qty, $singlePrice)) --}}
                                                                @endif




                                                                @php($totalWeight += $itemWeight)

                                                                @php($itemTotalTax += $productitemTax)

                                                            </td>

                                                            <td>

                                                                @if ($single_qty_new > 0 && $singlePrice > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0 && $singlePrice > 0) --}}

                                                                    <div>R {{ $singlePrice }}</div>
                                                                    {{-- {{dd($singlePrice)}} --}}
                                                                    <?php
                                                                    //  print_r($singlePrice);
                                                                    ?>
                                                                @endif

                                                            </td>

                                                            <td>

                                                                @php($rowTotal = 0)

                                                                @if ($single_qty_new > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0) --}}



                                                                    @php($rowTotal += $singlePrice * $single_qty_new)
                                                                    {{-- {{dd($singlePrice , $single_qty_new)}} --}}
                                                                    {{-- @php($rowTotal += ($singlePrice * $basketProduct->single_qty)) --}}
                                                                @endif



                                                                @php($total += $rowTotal)



                                                                <div><strong>R {{ $rowTotal }}</strong></div>

                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                            <tr>

                                                <td colspan="4" class="pb-5"></td>

                                            </tr>

                                            <tr>

                                                <td><b>Total (R)</b></td>

                                                <td colspan="3" class="text-right">{{ $total ?? 0 }}</td>

                                            </tr>

                                            <tr>

                                                <td><b>Tax (R)</b></td>

                                                <td colspan="3" class="text-right">{{ $itemTotalTax ?? 0 }}</td>

                                            </tr>

                                            <tr>

                                                <td><b>Discount (R)</b></td>

                                                <td colspan="3" class="text-right discountPrice">0</td>

                                            </tr>

                                            <tr>

                                                <td><b>Delivery Charge (R)</b></td>

                                                <td colspan="3" class="text-right delivery_price">
                                                    {{ isset($deliveryDetails) && isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0 }}
                                                </td>

                                            </tr>


                                            <tr style="background-color: #ddd;">

                                                <td><b>Payable Amount (R)</b></td>

                                                <td colspan="3" class="text-right payblePrice">
                                                    <b>{{ $total + $itemTotalTax }}</b>
                                                </td>

                                            </tr>

                                        </table>

                                        <div class="row">


                                            @php($totalWeightUnit = $supplier->kgToUnit($totalWeight))

                                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pl-4 pb-2">
                                                <b>{{ __('Total weight:') }}
                                                    {{ $totalWeightUnit['weight'] }}{{ $totalWeightUnit['unit'] }}</b>
                                            </div>

                                        </div>

                                    </div>




                                    <div class="container-fluid">

                                        @if ($offerModel->where('user_id', $supplier->uuid)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0)
                                            <?php
                                            $offerLatestRate = $offerModel
                                                ->where('user_id', $supplier->uuid)
                                                ->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                ->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                ->orderBy('id', 'DESC')
                                                ->first();
                                            
                                            ?>
                                            {{-- @php(
    $offerLatestRate = $offerModel->where('user_id', $supplier->uuid)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first(),
) --}}

                                            @if ($offerLatestRate->offer_type == 'RENT')
                                                @php($offervalue = $offerLatestRate->offer_value)
                                            @else
                                                @php($offervalue = ($total * $offerLatestRate->offer_value) / 100)
                                            @endif
                                        @endif

                                        @php($distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude))

                                        @php($distanceValue = isset($distance['distance']) ? $distance['distance'] : 0)


                                        <div class="row">

                                            @if (($totalWeightUnit['unit'] == 'ton' && $totalWeightUnit['weight'] < 1) || $totalWeightUnit['unit'] != 'ton')
                                                <div class="col-xs-12 col-sm-12 text-left">
                                                    </1>

                                                    <div class="text-danger">
                                                        <small>{{ __('Please make an order of a minimum 1 Ton or you want to pay 1 Ton delivery charges.') }}</small>
                                                    </div>

                                                </div>

                                                @php($dWeight = 1000)
                                            @else
                                                @php($dWeight = $totalWeightUnit['weight'] * 1000)
                                            @endif

                                        </div>

                                        @php($deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($dWeight, $distanceValue))



                                        @php($available_list = $logisticModel->getVehicle($dWeight))

                                        @php($vehicle_list = [])

                                        @if ($available_list)
                                            @foreach ($available_list as $key1 => $value1)
                                                @php($vehicle_list[] = $value1->vehicle_type)
                                            @endforeach
                                        @endif



                                        <div class="row">

                                            <div class="col-xs-8 col-sm-8 text-left">

                                                <div class="checkout-total">R <span
                                                        class="strike-price-total-{{ $supplier->uuid }}">{{ $total }}</span>
                                                    &nbsp; <span class="offerwithtotal{{ $supplier->uuid }}"></span> <small
                                                        class="{{ $totalWeightUnit['unit'] == 'ton' && $totalWeightUnit['weight'] >= 1 ? 'text-success' : 'text-warning' }}"><i>({{ $totalWeightUnit['weight'] }}
                                                            {{ $totalWeightUnit['unit'] }})</i></small></div>

                                            </div>

                                            <div class="col-xs-4 col-sm-4 col-sm-push-3">
                                                <a type="button" href="{{ route('offers') }}" class="btn btn-sm btn-theme"
                                                    style="background: #fa6500;">{{ __('View Promotions') }}</a>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-xs-6 col-sm-6">

                                                <div class="supplier_selection_btns">
                                                    <a href="#selectDeliveryTypeModal" id="checkout{{ $supplier->uuid }}"
                                                        data-supplier-id="{{ $supplier->uuid }}" data-toggle="modal"
                                                        class="btn btn-sm btn-theme selectDeliveryTypeModalButton"
                                                        data-delivery-price="{{ isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0 }}"
                                                        data-delivery-vehicle="{{ isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null }}"
                                                        data-distance="{{ $distance['distance'] }}" data-offer-price="0"
                                                        data-total-price="{{ $total }}" data-offer-id=""
                                                        data-supplier-id="{{ $supplier->uuid }}"
                                                        data-item-tax="{{ $itemTotalTax }}"
                                                        data-amt-payble="{{ $total + $itemTotalTax }}"
                                                        data-pallets-capacity="{{ $deliveryDetails['palletCapacity'] }}"
                                                        data-total-weight='{{ $totalWeightUnit['weight'] }} {{ $totalWeightUnit['unit'] }}'>{{ __('Checkout') }}</a>

                                                    <!-- Button trigger modal -->

                                                    <a type="button" class="btn btn-sm btn-theme product_details_btn"
                                                        data-toggle="modal"
                                                        data-target="#supplierProductDetail{{ $supplier->uuid }}"
                                                        style="background: #fa6500;">{{ __('Order Details') }}</a>

                                                    <!-- <a href="#selectDeliveryTypeModal" id ="checkout{{ $supplier->uuid }}"  data-supplier-id="{{ $supplier->uuid }}" data-toggle="modal" class="btn btn-sm btn-success selectDeliveryTypeModalButton" data-delivery-price="{{ isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0 }}" data-delivery-vehicle="{{ isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null }}" data-distance="{{ $distance['distance'] }}" data-offer-price="0"   data-total-price="{{ $total }}"  data-offer-id=""

                                                                                                               data-supplier-id="{{ $supplier->uuid }}">{{ __('Checkout') }}</a> -->

                                                </div>

                                            </div>



                                            <div class="col-xs-6 col-sm-6">

                                                <div id="offercode{{ $supplier->uuid }}" class="">

                                                    <form class="form-inline promocode-form" action="/action_page.php">

                                                        <div class="form-group">

                                                            <input class="form-control" type="text" autocomplete="off"
                                                                id="txtcode_{{ $supplier->uuid }}"
                                                                placeholder="Promo Code" name="promocode">

                                                        </div>

                                                        <input type="button" class="supplier-offers-apply"
                                                            name="go" id="use_offer_code-{{ $supplier->uuid }}"
                                                            data-offer-amount="" data-total-amount="{{ $total }}"
                                                            data-payble-amount="{{ $total + $itemTotalTax }}"
                                                            data-tax-amount="{{ $itemTotalTax }}"
                                                            data-delivery-amount="{{ isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0 }}"
                                                            value="Apply"
                                                            data-ajax-url="{{ route('frontend.ajax.verifyPromoCode') }}"
                                                            data-supplier-id="{{ $supplier->uuid }}">

                                                        <small class="text-danger hidden"
                                                            id="promocode-error-{{ $supplier->uuid }}"></small>

                                                    </form>

                                                </div>

                                            </div>



                                        </div>


                                    </div>



                                    <table class="table supplier_summery" id='tabledetail{{ $supplier->uuid }}'>

                                        <tr>

                                            <th>{{ __('Delivery') }}</th>

                                            <th>{{ __('Pickup') }}</th>

                                        </tr>

                                        <tr>

                                            <td width="50%">



                                                <div class="delivery_summery">


                                                    <div class="text-right"><b>{{ __('Total distance: ') }}</b>
                                                        {{ $distance['distance'] }}</div>

                                                    <div class="text-right"><b>{{ __('Delivery vehicle: ') }}</b>
                                                        &nbsp;{{-- {{ implode(' OR ', array_unique($vehicle_list))}} --}}
                                                        {{ isset($deliveryDetails) && isset($deliveryDetails['vehicle']['vehicle_type']) ? $deliveryDetails['vehicle']['vehicle_type'] : null }}
                                                    </div>

                                                    <div class="text-right">
                                                        <b>{{ __('Approximate Pallet Capacity: ') }}</b>
                                                        {{ isset($deliveryDetails) ?? isset($deliveryDetails['palletCapacity']) ? $deliveryDetails['palletCapacity'] : null }}
                                                    </div>

                                                    <div class="text-right"><b>{{ __('Delivery charge: ') }}</b>R <span
                                                            class="delivery_price">
                                                            {{ isset($deliveryDetails) && isset($deliveryDetails['price']) ? number_format($deliveryDetails['price'], 2, '.', ',') : 0 }}</span>
                                                    </div>

                                                    <div class="text-right"><b>{{ __('Tax: ') }}</b> R
                                                        {{ number_format($itemTotalTax, 2, '.', ',') }}</div>



                                                    <div class="text-right"><b>{{ __('Total: ') }}</b> R <span
                                                            class="finaltotal_with_delivery">{{ number_format($total + $itemTotalTax + (isset($deliveryDetails) && isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0), 2, '.', ',') }}
                                                    </div>



                                                    <?php $lead = $supplier->company()->exists() ? ($supplier->company->lead_approximate_time != null ? $supplier->company->lead_approximate_time : 0) : 0; ?>

                                                    <div class="text-right">
                                                        <b>{{ __('Approximate Delivery Within: ') }}</b>
                                                        <span class="finaltotal_with_delivery">
                                                            @if ($lead != '')
                                                                @if ($lead == 1)
                                                                    {{ __($lead . ' Day') }}
                                                                @else
                                                                    {{ __($lead . ' Days') }}
                                                                @endif
                                                            @endif
                                                    </div>

                                                </div>

                                            </td>

                                            <td width="50%">

                                                <div class="product_summery">

                                                    <div class="text-right"><b>{{ __('Tax: ') }}</b> R
                                                        {{ number_format($itemTotalTax, 2, '.', ',') }}</div>

                                                    <div class="text-right"><b>{{ __('Total: ') }}</b> R <span
                                                            class="finaltotal">{{ number_format($total + $itemTotalTax, 2, '.', ',') }}</span>
                                                    </div>

                                                    <div class="text-right"><b>Items {{ $totalAvailableProducts }} of
                                                            {{ $totalProducts }} Available</b> <span class="finaltotal">
                                                        </span>
                                                    </div>
                                                </div>

                                            </td>

                                        </tr>

                                    </table>

                                </div>





                                <div class="ribbon-flat ribbon-flat-top-left"><span>

                                        @php($availablePercentage = intval((100 * $totalAvailableProducts) / $totalProducts))



                                        {{ $availablePercentage }}% {{ __('Products Available') }}

                                    </span></div>

                            </div>

                        </div>

                        <!-- Modal -->

                        <div class="modal fade " id="supplierProductDetail{{ $supplier->uuid }}" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                            <div class="modal-dialog modal-lg" role="document">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Products Details') }}</h5>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                            <span aria-hidden="true">&times;</span>

                                        </button>

                                    </div>

                                    @php($offervalue = 0)



                                    @php($total = 0)

                                    @php($totalWeight = 0)

                                    @php($totalProducts = 0)

                                    @php($totalAvailableProducts = 0)

                                    @php($itemTotalTax = 0)

                                    <div class="modal-body">

                                        <table class="table-striped table table-bordered">

                                            <tr>

                                                <th>{{ __('Item') }}</th>

                                                <th>{{ __('QTY') }}</th>

                                                <th>{{ __('Price') }}</th>
                                                {{-- <th>{{ __('Color') }}</th>
                                            <th>{{ __('Size') }}</th> --}}

                                                <th>{{ __('Offer') }}</th>

                                                <th>{{ __('Total') }}</th>
                                                {{-- new added --}}
                                                <th>{{ __('Competitor Supplier Price') }}</th>
                                                <th>{{ __('Competitor Supplier Name') }}</th>

                                            </tr>

                                            @foreach ($arrBasketProducts as $basketProduct)
                                                {{-- {{ dd($basketProduct) }} --}}
                                                <?php
                                                $single_qty_new = $basketProduct->getOriginal()['single_qty'];
                                                ?>
                                                @php($totalProducts++)
                                                <?php
                                                // echo "<pre>";
                                                // print_r($basketProduct);
                                                ?>

                                                @if (!empty($single_qty_new) && $single_qty_new > 0)
                                                    {{-- @if (!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0) --}}

                                                    @php($supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid))

                                                    @php($supplierItemInventoryModel->where('single', '>', 0))

                                                    @php($supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0))

                                                    @php($supplierLatestRate = $supplierItemInventoryDataModel->first())


                                                    @if ($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0)
                                                        <?php
                                                        $supplierLatestRate = $supplierItemInventoryModel
                                                            ->where('product_id', $basketProduct->product_id)
                                                            ->where('user_id', $supplier->uuid)
                                                            ->orderBy('id', 'DESC')
                                                            ->first();
                                                        ?>
                                                        {{-- @php() --}}

                                                        @php($singlePrice = $supplierLatestRate->single_price)
                                                        @php($productSinglePrice = $supplierLatestRate->single_price)


                                                        @if ($offerModel->where('user_id', $supplier->uuid)->where('product_id', $basketProduct->product_id)->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->count() > 0)
                                                            <?php
                                                            $productOffer = $offerModel
                                                                ->where('user_id', $supplier->uuid)
                                                                ->where('product_id', $basketProduct->product_id)
                                                                ->where(DB::raw("date_format(period_from, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                                ->where(DB::raw("date_format(period_to, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
                                                                ->orderBy('id', 'DESC')
                                                                ->first();
                                                            
                                                            ?>
                                                            {{-- @php(
    $productOffer = $offerModel->where('user_id', $supplier->uuid)->where('products_id', $basketProduct->product_id)->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))->orderBy('id', 'DESC')->first(),
) --}}

                                                            @if ($productOffer->promotion_id != '')
                                                                {{-- @if ($productOffer->offer_type == 'RENT') --}}
                                                                @php($singlePrice = $productOffer->promotion_price)
                                                            @else
                                                                @php($singlePrice = $singlePrice - ($singlePrice * $productOffer->promotion_price) / 100)
                                                            @endif
                                                        @endif




                                                        @php($itemWeight = 0)

                                                        @php($productitemTax = 0)

                                                        @php($totalAvailableProducts++)

                                                        <tr>

                                                            <td>{{ $supplierLatestRate->product->name }}</td>

                                                            <td>

                                                                @if ($single_qty_new > 0 && $singlePrice > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0 && $singlePrice > 0) --}}
                                                                    <div><span
                                                                            class="{{ $pqtyClass }}">{{ $supplierLatestRate->product->stock_type }}
                                                                            : {{ $single_qty_new }}</span> <span
                                                                            {{-- : {{ $basketProduct->single_qty }}</span> <span --}}
                                                                            class="badge badge-info">AVAILABEL STOCK</span>
                                                                        @if ($basketProduct->color != null)
                                                                            <span class="badge badge-info">Color:
                                                                                {{ $basketProduct->color }}</span>
                                                                        @endif
                                                                        @if ($basketProduct->size != null)
                                                                            <span class="badge badge-info">Size:
                                                                                {{ $basketProduct->size }}</span>
                                                                        @endif
                                                                        {{-- <a href=""><i class="fas fa-edit"></i></a> --}}
                                                                        <div class="showEdit" style="display: none">
                                                                            {!! Form::open(['route' => 'checkout.add-to-cart']) !!}
                                                                            {!! Form::hidden('product_id', $basketProduct->product_id) !!}

                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn">
                                                                                                <button type="button"
                                                                                                    class="btn btn-default btn-number btn-sm"
                                                                                                    data-type="minus"
                                                                                                    data-field="single_qty-{{ $basketProduct->product_id }}"><span
                                                                                                        class="fa fa-minus"></span></button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="single_qty"
                                                                                                class="form-control input-number form-control-sm single_qty-{{ $basketProduct->product_id }}"
                                                                                                value="{{ $single_qty_new }}"
                                                                                                {{-- value="{{ $basketProduct->single_qty }}" --}}
                                                                                                min="1"
                                                                                                max="100">
                                                                                            <span class="input-group-btn">
                                                                                                <button type="button"
                                                                                                    class="btn btn-default btn-number btn-sm"
                                                                                                    data-type="plus"
                                                                                                    data-field="single_qty-{{ $basketProduct->product_id }}"><span
                                                                                                        class="fa fa-plus"></span></button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </td>
                                                                                    {{-- </tr> --}}

                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2"><button
                                                                                            type="submit"
                                                                                            class="btn btn-sm btn-theme float-right">{{ __('Update') }}</button>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            {!! Form::close() !!}
                                                                            {{-- <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="minus" data-field="single_qty-{{$basketProduct->product_id}}"><span class="fa fa-minus"></span></button>
                                    </span>
                                                <input type="text" name="single_qty" class="form-control input-number form-control-sm single_qty-{{$basketProduct->product_id}}" value="{{$basketProduct->single_qty}}"
                                                       min="1" max="100">
                                                <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="plus" data-field="single_qty-{{$basketProduct->product_id}}"><span class="fa fa-plus"></span></button>
                                    </span>
                                    <button type="submit" class="btn btn-sm btn-theme float-right">{{__("Update")}}</button> --}}
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <a title="Edit product from cart"
                                                                                href="javascript:void(0)"
                                                                                class="text-theme edit-cart-item"><i
                                                                                    class="fa fa-edit fa-3"
                                                                                    style="font-size: 20px;"></i></a>
                                                                            <a title="Remove product from cart"
                                                                                href="{{ route('checkout.remove-product-from-cart', $basketProduct->product_id) }}"
                                                                                class="text-theme delete-cart-item"><i
                                                                                    class="fa fa-trash fa-3"
                                                                                    style="font-size: 20px;"></i></a>
                                                                        </div>
                                                                        {{-- <a href=""><i class="fas fa-trash"></i></a> --}}
                                                                    </div>



                                                                    @php($itemWeight += $basketProduct->product->getCalculatedWeight('single', $single_qty_new))
                                                                    {{-- @php($itemWeight += $basketProduct->product->getCalculatedWeight('single', $basketProduct->single_qty)) --}}

                                                                    @php($productitemTax = $basketProduct->product->getCalculatedTax('single', $single_qty_new, $singlePrice))
                                                                    {{-- @php($productitemTax = $basketProduct->product->getCalculatedTax('single', $basketProduct->single_qty, $singlePrice)) --}}
                                                                @endif



                                                                @php($totalWeight += $itemWeight)

                                                                @php($itemTotalTax += $productitemTax)

                                                            </td>

                                                            <?php
                                                            $totalP = 0.0;
                                                            ?>
                                                            <td>

                                                                @if ($single_qty_new > 0 && $singlePrice > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0 && $singlePrice > 0) --}}
                                                                    <div>R
                                                                        {{ number_format($productSinglePrice, 2, '.', ',') }}
                                                                    </div>
                                                                    <?php
                                                                    $totalP = $totalP + $productSinglePrice;
                                                                    ?>
                                                                @endif

                                                            </td>

                                                            {{-- <td>
                                                            {{ $basketProduct->color != null ? $basketProduct->color : '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $basketProduct->size ? $basketProduct->size : '-' }}

                                                        </td> --}}
                                                            <?php
                                                            $discountPrice = 0;
                                                            ?>
                                                            <td>

                                                                @if (isset($productOffer))
                                                                    @if ($supplierLatestRate->product_id == $productOffer->product_id)
                                                                        {{-- @if ($supplierLatestRate->product_id == $productOffer->products_id) --}}
                                                                        <div>

                                                                            {{ $productOffer->promotion_type }} <br>
                                                                            <p> R {{ $productOffer->promotion_price }}</p>
                                                                            <?php
                                                                            $discountPrice = $discountPrice + $productOffer->promotion_price;
                                                                            ?>
                                                                            {{-- @if ($productOffer->promotion_id != '')
                                                                                {{ $productOffer->promotion_price }} %
                                                                            @else
                                                                                {{ $productOffer->promotion_price }} Flate
                                                                                Free
                                                                            @endif --}}
                                                                        </div>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                @else
                                                                    -
                                                                @endif

                                                            </td>

                                                            <td>

                                                                @php($rowTotal = 0)

                                                                @if ($single_qty_new > 0)
                                                                    {{-- @if ($basketProduct->single_qty > 0) --}}
                                                                    @php($rowTotal += $singlePrice * $single_qty_new)
                                                                    {{-- @php($rowTotal += $singlePrice * $basketProduct->single_qty) --}}
                                                                @endif



                                                                @php($total += $rowTotal)



                                                                <div><strong>R
                                                                        {{ number_format($rowTotal, 2, '.', ',') }}</strong>
                                                                </div>

                                                            </td>

                                                            <?php $supplierItemInventoryDataModelMinValue = $supplierItemInventoryModel
                                                                ->where('product_id', $basketProduct->product_id)
                                                                ->where('single_price', '>=', 0)
                                                                ->min('single_price');
                                                            $checkValue = $supplierItemInventoryModel
                                                                ->where('single_price', $supplierItemInventoryDataModelMinValue)
                                                                ->selectRaw('min(single_price) as min_price, product_id,user_id')
                                                                ->first();
                                                            $min_price = $supplierItemInventoryDataModelMinValue;
                                                            $totalValue = number_format($rowTotal, 2, '.', ',');
                                                            ?>

                                                            <td>
                                                                @if ($totalValue != $min_price)
                                                                    R {{ $min_price }}
                                                                @endif

                                                            </td>


                                                            <td>
                                                                <?php
                                                                $traderData = $userModel->where('uuid', $checkValue->user_id)->first();
                                                                if ($traderData != null) {
                                                                    $traderData = $traderData->company()->exists() ? $traderData->company->trading_name : $traderData->name;
                                                                } else {
                                                                    $traderData = '';
                                                                }
                                                                
                                                                ?>
                                                                @if ($totalValue != $min_price)
                                                                    {{ $traderData }}
                                                                @endif

                                                            </td>


                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                            <tr>

                                                <td colspan="9" class="pb-5"></td>



                                                <div><strong>R {{ number_format($rowTotal, 2, '.', ',') }}</strong></div>

                                                </td>

                                            </tr>






                                            <tr>

                                                <td colspan="9" class="pb-5"></td>

                                            </tr>

                                            <tr>

                                                <td><b>Total (R)</b></td>

                                                <td colspan="9" class="text-right">
                                                    {{-- {{ number_format($totalP, 2, '.', ',') }} --}}
                                                    {{ number_format($total, 2, '.', ',') }}
                                                </td>

                                            </tr>
                                            <tr style="background-color: #ddd;">

                                                <td><b>Shipping Amount (R)</b></td>

                                                <td colspan="9" class="text-right payblePrice"><b>0.00</b></td>

                                                {{-- <td colspan="9" class="text-right payblePrice"><b>
                                                        {{ number_format($total + $itemTotalTax, 2, '.', ',') }} </b></td> --}}

                                            </tr>
                                            <tr>

                                                <td><b>Tax (R)</b></td>

                                                <td colspan="9" class="text-right">
                                                    {{ number_format($itemTotalTax, 2, '.', ',') }}</td>

                                            </tr>

                                            {{-- <tr>

                                                <td><b>Discount (R)</b></td>

                                                <td colspan="9" class="text-right discountPrice">
                                                    {{ number_format($discountPrice, 2, '.', ',') }}</td> --}}
                                            {{-- <td colspan="9" class="text-right discountPrice"> 0.00</td> --}}

                                            {{-- </tr> --}}

                                            {{-- <tr>

                                                <td><b>Delivery Charge (R)</b></td>

                                                <td colspan="9" class="text-right delivery_price">
                                                    {{ isset($deliveryDetails['price']) ? number_format($deliveryDetails['price'], 2, '.', ',') : 0 }}
                                                </td>

                                            </tr> --}}



                                            <tr style="background-color: #ddd;">

                                                <td><b>Payable Amount (R)</b></td>

                                                <td colspan="9" class="text-right payblePricewithdelivery"><b>

                                                        @php(isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0)

                                                        {{ number_format($total + $itemTotalTax + $deliveryDetails['price'], 2, '.', ',') }}
                                                    </b></td>

                                            </tr>

                                        </table>

                                        <div class="row">

                                            @php($totalWeightUnit = $supplier->kgToUnit($totalWeight))

                                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pl-4 pb-2">
                                                <b>{{ __('Total weight:') }}
                                                    {{ $totalWeightUnit['weight'] }}{{ $totalWeightUnit['unit'] }}</b>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <span class="topCartWalletAmount">Wallet
                                            R{{ bcdiv(auth()->user()->wallet_balance, 1, 2) }}</span>

                                        <a href="{{ route('user.wallet.index') }}"
                                            class="btn btn-theme">{{ __('Top Up') }}</a>
                                        {{-- <a class="btn btn-theme" href="">Checkout</a> --}}
                                        <a href="#selectDeliveryTypeModal" id="checkout{{ $supplier->uuid }}"
                                            data-supplier-id="{{ $supplier->uuid }}" data-toggle="modal"
                                            class="btn  btn-theme selectDeliveryTypeModalButton"
                                            data-delivery-price="{{ isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0 }}"
                                            data-delivery-vehicle="{{ isset($deliveryDetails['vehicle']->vehicle_type) ? $deliveryDetails['vehicle']->vehicle_type : null }}"
                                            data-distance="{{ $distance['distance'] }}" data-offer-price="0"
                                            data-total-price="{{ $total }}" data-offer-id=""
                                            data-supplier-id="{{ $supplier->uuid }}"
                                            data-item-tax="{{ $itemTotalTax }}"
                                            data-amt-payble="{{ $total + $itemTotalTax }}"
                                            data-pallets-capacity="{{ $deliveryDetails['palletCapacity'] }}"
                                            data-total-weight='{{ $totalWeightUnit['weight'] }} {{ $totalWeightUnit['unit'] }}'>{{ __('Checkout') }}</a>

                                        <button type="button" class="btn btn-theme" data-dismiss="modal">Back</button>
                                        {{-- <button type="button" class="btn btn-theme" data-dismiss="modal">Close</button> --}}

                                    </div>

                                </div>

                            </div>

                        </div>
                    @endforeach
                @else
                    <div class="row" style="margin: auto;">

                        <div class="col-md-12 text-center">

                            <h3 class="mb-4"><b>{{ __('No supplier available for this product') }}</b></h3>

                        </div>

                    </div>
                @endif


            </div>

        </div>

    </section>





    <!-- The Modal -->

    <div class="modal" id="selectDeliveryTypeModal">

        <div class="modal-dialog">

            <div class="modal-content">



                <!-- Modal Header -->

                <div class="modal-header">

                    <h4 class="modal-title">{{ __('Select Delivery Type') }}</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>



                <!-- Modal body -->

                <div class="modal-body">

                    <div class="row">

                        {!! Form::open(['route' => 'make-payment']) !!}

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                            <div class="form-check">

                                <label class="form-check-label">

                                    <input type="radio" class="form-check-input" value="courier" checked
                                        name="delivery_type">Courier

                                </label>

                            </div>

                            @if ($supplier_delivery == 'own_distributor')
                                <div class="form-check">

                                    <label class="form-check-label">

                                        <input type="radio" class="form-check-input" value="own_distributor" checked
                                            name="delivery_type">Own Distributor


                                    </label>

                                    <select name="delivery_status_own_distributor" class="delivery_status_own_distributor"
                                        style="display: none">
                                        <option value="">Select Status</option>
                                        <option value="Same Day">Same Day</option>
                                        <option value="Express">Express</option>
                                        <option value="Economy">Economy</option>
                                    </select>

                                </div>
                            @endif

                            <div class="form-check">

                                <label class="form-check-label">

                                    <input type="radio" class="form-check-input" value="pickup" checked
                                        name="delivery_type">Pickup (Collect)

                                </label>

                            </div>

                            <div class="form-check">

                                <label class="form-check-label">

                                    <input type="radio" class="form-check-input" value="delivery"
                                        name="delivery_type">Delivery (Tender)

                                    <span class="checkout-delivery-charge"></span>

                                    <span class="checkout-delivery-approx-distance"></span>

                                    <span class="checkout-delivery-vehicle"></span>

                                    <span class="checkout-delivery-pallet"></span>

                                </label>

                                <select name="delivery_status_delivery" class="delivery_status_delivery"
                                    style="display: none">
                                    <option value="">Select Status</option>
                                    <option value="Same Day">Same Day</option>
                                    <option value="Express">Express</option>
                                    <option value="Economy">Economy</option>
                                </select>
                            </div>



                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                            <input type="hidden" name="total_distance" class="total-distance">

                            <input type="hidden" name="total_weight" class="total-weight">

                            <input type="hidden" name="product_total" class="product-total-input">

                            <input type="hidden" name="shipping_total" class="shipping-total-input">

                            <input type="hidden" name="offer_total" class="offer-total-input">

                            <input type="hidden" name="supplier_id" class="supplier-id-input">

                            <input type="hidden" name="offer_id" class="offer-id-input">

                            <input type="hidden" name="paybel_amt_input" class="paybel-amt-input">

                            <input type="hidden" name="item_tax_input" class="item-tax-input">
                            <input type="hidden" name="delivery_status" class="delivery_status">


                            <button type="button" class="btn btn-theme" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-success"><i class="far fa-money-bill-alt"></i> Review
                                Order</button>

                        </div>

                        {!! Form::close() !!}

                    </div>

                </div>



            </div>

        </div>

    </div>

    <div class="modal" id="selectCourier">

        <div class="modal-dialog">

            <div class="modal-content">



                <!-- Modal Header -->

                <div class="modal-header">

                    <h4 class="modal-title">{{ __('Select Courier') }}</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>



                <!-- Modal body -->

                <div class="modal-body">

                    <div class="row">

                        {!! Form::open(['route' => 'make-payment']) !!}

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required showAllCourier">

                            {{-- <div class="form-check">

                                <label class="form-check-label">

                                    <input type="radio" class="courier_id" value="" checked
                                        name="courier_id">Courier

                                </label>

                            </div> --}}

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                            <input type="hidden" name="delivery_type" class="courier" value="courier">
                            {{-- <input type="hidden" name="courier" class="courier" value="courier"> --}}
                            <input type="hidden" name="total_distance" class="total-distance">

                            <input type="hidden" name="total_weight" class="total-weight">

                            <input type="hidden" name="product_total" class="product-total-input">

                            <input type="hidden" name="shipping_total" class="shipping-total-input">

                            <input type="hidden" name="offer_total" class="offer-total-input">

                            <input type="hidden" name="supplier_id" class="supplier-id-input">

                            <input type="hidden" name="offer_id" class="offer-id-input">

                            <input type="hidden" name="paybel_amt_input" class="paybel-amt-input">

                            <input type="hidden" name="item_tax_input" class="item-tax-input">
                            <input type="hidden" name="delivery_status" class="delivery_status">

                            <button type="button" class="btn btn-theme" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-success"><i class="far fa-money-bill-alt"></i> Review
                                Order</button>

                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footerScript')
    <script>
        $(document).ready(function() {
            $(document).on('change', '.form-check-input', function() {
                console.log($(this).val());


                if ($(this).val() == 'own_distributor') {
                    $('.delivery_status_own_distributor').show();
                } else {
                    $('.delivery_status_own_distributor').hide();
                    // $('.delivery_status_delivery').hide();

                }
                if ($(this).val() == 'delivery') {
                    $('.delivery_status_delivery').show();
                } else {
                    // $('.delivery_status_own_distributor').hide();
                    $('.delivery_status_delivery').hide();
                }
                if ($(this).val() == 'courier') {
                    $('#selectCourier').modal('show');

                    $.get("getCourierData", function(data, status) {

                        let allData = data.length;
                        console.log('allData: ', allData);
                        if (allData != 0) {
                            console.log('name1: ', data);
                            $('.showAllCourier').empty();
                            $.each(data, function(key, value) {
                                console.log('value: ', value);
                                // data = JSON.parse(data);
                                console.log('name2: ', data);

                                // console.log('name: ', value);
                                let name = value.name;
                                let id = value.id;

                                let string = `<div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="courier_id" value="${id}" checked
                                        name="courier_id"> ${name}
                                </label>
                                </div>`;
                                $('.showAllCourier').append(string);
                                // console.log(key, value);
                            });
                        }
                        console.log('data: ', data);
                        // alert("Data: " + data + "\nStatus: " + status);
                    });
                }
            });
            $('.edit-cart-item').on('click', function() {
                if ($('.showEdit').attr('style') === 'display: none') {
                    $('.showEdit').attr('style', '');
                } else {
                    $('.showEdit').attr('style', 'display: none');
                }
                console.log('hii', $('.showEdit').attr('style'));
            })
            $('.delivery_status_own_distributor').on('change', function() {
                $('.delivery_status').val($(this).val());
            })
            $('.delivery_status_delivery').on('change', function() {
                $('.delivery_status').val($(this).val());
            })
            // delivery_status
        })
    </script>
@endsection
