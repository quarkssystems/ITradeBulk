<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Invoice formate */
        .invoice-box {
            max-width: 100%;
            width: 1000px;

            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
            text-align: left;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }


        .invoice-box tr.total {
            border-top: 1px solid #ddd;
        }

        .invoice-box .text-right b {
            font-weight: bold;
        }

        .checkout-total span {
            font-size: 14px;
            font-weight: bold;
        }

        .txtalign {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0" style="width: 100%;">

            <tr class="top">
                <td colspan="13">
                    <table>
                        <tr>
                            <td class="title">
                                {{-- @if (!isset($removebtn))
                                        <img src="{{asset('assets/frontend/images/logo.png')}}" style="width:200px; max-width:300px;">
                                    @else
                                        <img src="{{public_path('assets/frontend/images/logo.png')}}" style="width:200px; max-width:300px;">
                                    @endif --}}

                                @if (is_null($supplierData->image))
                                    <img src='{{ asset('images/supplier.png') }}' style="width:100px; max-width:200px;">
                                @else
                                    <img src='{{ asset($supplierData->image) }}' style="width:200px; max-width:300px;">
                                @endif


                            </td>

                            <td class="text-right" style="text-align: right;">
                                <b>Invoice</b><br>
                                Date: {{ date('M d, Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="13">
                    <table>
                        <tr>
                            <td>
                                {!! $supplierAddress !!}
                                {{-- {{ $supplierData->company->trading_name }}<br>
                                {{ $supplierData->company->address1 }}, {{ $supplierData->company->address2 }},
                                {{ $supplierData->company->city_name }}, {{ $supplierData->company->state_name }},
                                {{ $supplierData->company->country_name }},<br>
                                {{ $supplierData->company->zipcode_name }}, {{ $supplierData->company->zipcode_code }}. --}}
                            </td>

                            <td class="text-right txtalign">
                                {!! $traderAddress !!}
                                {{-- <b>{{ $supplierData->first_name }}
                                    {{ $supplierData->last_name }}</b><br>{{ $supplierData->email }} --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Name</td>
                <td style="width: 112px;">Qty</td>
                <td>Price</td>
                <td>Offer</td>
                <td>Total Price</td>

                {{-- new added --}}
                <td>Barcode</td>
                <td>Product Code</td>
                <td>Store Item Code</td>
                <td>Product Image</td>
                {{-- <td>Price</td> --}}
                <td>Category</td>
                <td>Description</td>
                <td>Orderd Qty</td>
                {{-- <td>Units Ordered</td> --}}










            </tr>

            @foreach ($supplierLoopData['products'] as $key => $product_info)
                <?php
                $color = '';
                if ($product_info['color']) {
                    $color = 'Color: ' . $product_info['color'] . ',';
                }
                $size = '';
                if ($product_info['size']) {
                    $size = 'Size: ' . $product_info['size'];
                }
                ?>
                <?php
                $offerPrice = 0.0;
                $productOffer = $product_info['productOffer'] ?? null;
                \Log::info('-----1');
                \Log::info($productOffer);
                \Log::info('-----2');
                ?>
                {{-- {{dd($product_info)}} --}}
                <tr class="details">
                    <td> {{ $product_info['product_name'] }}
                        @if ($color != '' || $size != '')
                            ({{ $color }} {{ $size }})
                        @endif
                    </td>
                    <td> {{ $product_info['qty'] }}
                        <div class="showEdit{{ $product_info['product_id'] }}" style="display: none">
                            {!! Form::open(['route' => 'dispatch-doc-Update']) !!}
                            {!! Form::hidden('product_id', $product_info['product_id']) !!}
                            {!! Form::hidden('basket_products_id', $product_info['basket_product_id']) !!}
                            {!! Form::hidden('basket_id', $product_info['basket_id']) !!}
                            {!! Form::hidden('product_price', $product_info['productSinglePrice']) !!}
                            {!! Form::hidden('order_id', $product_info['order_id']) !!}
                            {!! Form::hidden('offer_price', $product_info['offer_price']) !!}
                            {!! Form::hidden('offer_id', $product_info['offer_id']) !!}

                            <table>
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number btn-sm"
                                                    data-type="minus"
                                                    data-field="single_qty-{{ $product_info['product_id'] }}"><span
                                                        class="fa fa-minus"></span></button>
                                            </span>
                                            <input type="text" name="single_qty"
                                                class="form-control input-number form-control-sm single_qty-{{ $product_info['product_id'] }}"
                                                value="{{ $product_info['qty'] }}" min="1" max="100">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number btn-sm"
                                                    data-type="plus"
                                                    data-field="single_qty-{{ $product_info['product_id'] }}"><span
                                                        class="fa fa-plus"></span></button>
                                            </span>
                                        </div>
                                    </td>
                                    {{-- </tr> --}}

                                </tr>
                                <tr>
                                    <td colspan="2"><button type="submit"
                                            class="btn btn-sm btn-theme float-right">{{ __('Update') }}</button>
                                    </td>
                                </tr>
                            </table>
                            {!! Form::close() !!}
                        </div>
                        @if (Auth::user()->role != 'DRIVER')
                            <div class="mt-2">
                                <a title="Edit product from cart" href="javascript:void(0)"
                                    class="text-theme edit-cart-item" data-id="{{ $product_info['product_id'] }}"><i
                                        class="fa fa-edit fa-3" style="font-size: 20px;"></i></a>
                                <a title="Remove product from cart"
                                    href="{{ route('remove-product-doc', [$product_info['order_id'], $product_info['product_id']]) }}"
                                    class="text-theme delete-cart-item" data-id="{{ $product_info['product_id'] }}"><i
                                        class="fa fa-trash fa-3" style="font-size: 20px;"></i></a>
                            </div>
                        @endif
                    </td>
                    <td> R {{ number_format($product_info['productSinglePrice'], 2, '.', ',') }}</td>
                    <td>
                        @if (isset($productOffer) && $productOffer != '')
                            @if ($product_info['product_id'] == $productOffer->product_id)
                                <div>
                                    {{ $productOffer->promotion_type }} <br>
                                    <p> R {{ $productOffer->promotion_price }}</p>
                                    <?php
                                    $offerPrice = $offerPrice + $productOffer->promotion_price;
                                    ?>
                                    {{-- @if ($productOffer->offer_type == 'PERCENTAGE')
                                    {{ $productOffer->offer_value }} %
                                @else
                                    {{ $productOffer->offer_value }} Flate Free
                                @endif --}}
                                </div>
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                        {{-- @if (isset($productOffer) && $productOffer != '')
                            @if ($product_info['product_id'] == $productOffer->products_id)
                                <div>
                                    {{ $productOffer->title }} <br>
                                    @if ($productOffer->offer_type == 'PERCENTAGE')
                                        {{ $productOffer->offer_value }} %
                                    @else
                                        {{ $productOffer->offer_value }} Flate Free
                                    @endif
                                </div>
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif --}}
                    </td>
                    <td> R {{ number_format($product_info['totalprice'], 2, '.', ',') }}</td>
                    <td>{{ $product_info['barcode'] != '' ? $product_info['barcode'] : 'NA' }}</td>
                    <td>{{ $product_info['product_code'] != '' ? $product_info['product_code'] : 'NA' }}</td>
                    <td>{{ $product_info['Store_item_code'] != '' ? $product_info['Store_item_code'] : 'NA' }}</td>
                    @if (isset($product_info['product_image']) && file_exists(url($product_info['product_image'])))
                        <td><img src="{{ url($product_info['product_image']) }}" /> </td>
                    @else
                        <td>NA</td>
                    @endif
                    {{-- <td>{{$product_info['price']}}</td> --}}
                    <td>{{ $product_info['category'] != '' ? $product_info['category'] : 'NA' }}</td>
                    <td>{{ $product_info['description'] != '' ? $product_info['description'] : 'NA' }}</td>
                    <td>{{ $product_info['units_ordered'] != '' ? $product_info['units_ordered'] : 'NA' }}</td>
                </tr>
            @endforeach


            <tr class="total font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ __('Total: ') }}</td>
                <td></td>
                <td>R {{ number_format($productTotal, 2, '.', ',') }}</td>
            </tr>

            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ __('Shipping: ') }}</td>
                <td></td>
                <td>R {{ number_format($shippingTotal, 2, '.', ',') }}
                    {{-- <td>R {{ $shippingMethod == 'delivery' ? number_format($shippingTotal, 2, '.', ',') : 0 }} --}}
                    ({{ strtoupper($shippingMethod) }})</td>
            </tr>


            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ __('Tax : ') }}</td>
                <td></td>
                <td>R {{ number_format($item_tax_input, 2, '.', ',') }}</td>
            </tr>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ __('Offer Total: ') }}</td>
                <td></td>
                <td>R {{ number_format($offerTotal, 2, '.', ',') }}</td>
            </tr>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ __('Payable amount: ') }}</td>
                <td></td>
                <td>@php($grandTotal = $paybel_amt_input) R {{ number_format($grandTotal, 2, '.', ',') }}</td>
                {{-- <td>@php($grandTotal = $paybel_amt_input + $shippingTotal) R {{ number_format($grandTotal, 2, '.', ',') }}</td> --}}
                {{-- <td>@php($grandTotal = $paybel_amt_input + ($shippingMethod == 'delivery' ? $shippingTotal : 0)) R {{ number_format($grandTotal, 2, '.', ',') }}</td> --}}
            </tr>

            {{-- <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{__("Wallet Amount: ")}}</td>
                        <td></td>
                        <td>R {{ number_format($walletBalance, 2, '.', ',') }}</td>
                    </tr> --}}

            {{-- <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{__("Credit Note: ")}}</td>
                        <td></td>
                        <td>R {{ $creditNote   }}</td>
                    </tr> --}}
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><input type="button" onclick="printDiv('invoice-box')" class="btn btn-sm btn-warning float-right"
                        value="Print" /></td>
                @if (Auth::user()->role != 'DRIVER')
                    <td><a href="{{ route('reset_dispatch_doc', [$product_info['order_id']]) }}"
                            class="btn btn-sm btn-warning float-right">Reset</a></td>
                @endif
            </tr>
        </table>
    </div>

</body>

</html>
