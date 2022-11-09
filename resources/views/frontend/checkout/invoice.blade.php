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
                <td colspan="5">
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
                <td colspan="5">
                    <table>
                        <tr>
                            <td>
                                {{ $supplierData->company->trading_name }}<br>
                                {{ $supplierData->company->address1 }}, {{ $supplierData->company->address2 }},
                                {{ $supplierData->company->city_name }}, {{ $supplierData->company->state_name }},
                                {{ $supplierData->company->country_name }},<br>
                                {{ $supplierData->company->zipcode_name }},
                                {{ $supplierData->company->zipcode_code }}.
                            </td>

                            <td style="text-align: right;">
                                <?php
                                $currentUser = auth()->user();
                                ?>
                                {{ $currentUser->title }} {{ $currentUser->first_name }}
                                {{ $currentUser->last_name }}<br>
                                {{ $currentUser->company->address1 }}, {{ $currentUser->company->address2 }},
                                {{ $currentUser->company->city_name }}, {{ $currentUser->company->state_name }},
                                {{ $currentUser->company->country_name }},<br>
                                {{ $currentUser->company->zipcode_name }},
                                {{ $currentUser->company->zipcode_code }}.
                            </td>
                            {{-- <td>
                                {{ $supplierData->company->trading_name }}<br>
                                {{ $supplierData->company->address1 }}, {{ $supplierData->company->address2 }},
                                {{ $supplierData->company->city_name }}, {{ $supplierData->company->state_name }},
                                {{ $supplierData->company->country_name }},<br>
                                {{ $supplierData->company->zipcode_name }}, {{ $supplierData->company->zipcode_code }}.
                            </td>

                            <td class="text-right txtalign">
                                <b>{{ $supplierData->first_name }}
                                    {{ $supplierData->last_name }}</b><br>{{ $supplierData->email }}
                            </td> --}}
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Name</td>
                <td>Qty</td>
                <td>Price</td>
                <td>Offer</td>
                <td>Total Price</td>
            </tr>
            <?php
            $offerPrice = 0.0;
            ?>
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
                <tr class="details">
                    <td> {{ $product_info['product_name'] }} @if ($color != '' || $size != '')
                            ({{ $color }} {{ $size }})
                        @endif
                    </td>
                    <td> {{ $product_info['qty'] }}</td>
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
                    </td>
                    <td> R {{ number_format($product_info['totalprice'], 2, '.', ',') }}</td>
                </tr>
            @endforeach


            <tr class="total font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Total: ') }}</td>
                <td></td>
                <td>R {{ number_format($productTotal, 2, '.', ',') }}</td>
            </tr>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Shipping: ') }}</td>
                <td></td>
                <td>R {{ number_format($shippingTotal, 2, '.', ',') }}
                    {{-- <td>R
                    {{ $shippingMethod == 'delivery' ? number_format($shippingTotal, 2, '.', ',') : 0 }} --}}
                    ({{ strtoupper(str_replace('_', ' ', $shippingMethod)) }})</td>
            </tr>


            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Tax : ') }}</td>
                <td></td>
                <td>R {{ number_format($item_tax_input, 2, '.', ',') }}</td>
            </tr>
            {{-- <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Offer Total: ') }}</td>
                <td></td>
                <td>R {{ number_format($offerPrice, 2, '.', ',') }}</td> --}}
            {{-- <td>R {{ number_format($offerTotal, 2, '.', ',') }}</td> --}}
            {{-- </tr> --}}
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Payable amount: ') }}</td>
                <td></td>
                <td>@php($grandTotal = $paybel_amt_input + $shippingTotal) R {{ number_format($grandTotal, 2, '.', ',') }}</td>
                {{-- <td>@php($grandTotal = $paybel_amt_input + ($shippingMethod == 'delivery' ? $shippingTotal : 0)) R {{ number_format($grandTotal, 2, '.', ',') }}</td> --}}
            </tr>
            @if ($delivery_status != '')
                <tr class="font-weight-bold">
                    <td></td>
                    <td></td>
                    <td>{{ __('Delivery Type: ') }}</td>
                    <td></td>
                    <td>{{ strtoupper($delivery_status) }}</td>
                    {{-- <td>@php($grandTotal = $paybel_amt_input + ($shippingMethod == 'delivery' ? $shippingTotal : 0)) R {{ number_format($grandTotal, 2, '.', ',') }}</td> --}}
                </tr>
            @endif

            {{-- <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Wallet Amount: ') }}</td>
                <td></td>
                <td>R {{ number_format($walletBalance, 2, '.', ',') }}</td>
            </tr> --}}
            @if (!isset($removebtn))
                <tr class="make_payment_row">
                    <td colspan="5" class="text-right">
                        <a href="{{ url('/checkout/select-supplier') }}"
                            class="btn btn-sm btn-warning">{{ __('Back') }}</a>
                        {{-- <a href="" class="btn btn-sm btn-warning" >{{__('Print')}}</a> --}}
                        <input type="button" onclick="printDiv('invoice-box')" class="btn btn-sm btn-warning"
                            value="Print" />

                        <a href="{{ route('user.wallet.index') }}"
                            class="btn btn-sm btn-warning">{{ __('Topup') }}</a>

                        @if ($grandTotal < $walletBalance)
                            <a href="#paymentLoaderModal" data-order-amount="{{ $productTotal }}"
                                data-shipping-amount="{{ $shippingTotal }}" data-delivery-type="{{ $shippingMethod }}"
                                data-offer-amount="{{ $offerTotal }}" data-offer-id="{{ $offerId }}"
                                data-supplier-id="{{ $supplierId }}" data-item-tax="{{ $item_tax_input }}"
                                data-amt-payble="{{ $grandTotal }}" data-toggle="modal"
                                data-distance="{{ $distance }}" data-weight="{{ $weight }}"
                                data-delivery_status="{{ $delivery_status }}"
                                class="btn btn-sm btn-theme payment-order-button"
                                data-target="#paymentLoaderModal">{{ __('Pay using Wallet') }}</a>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Insufficient wallet amount. Please add amount to your wallet and complete payment. <a
                                    href="{{ route('user.wallet.index') }}"
                                    class="btn btn-sm btn-warning">{{ __('Credit to wallet') }}</a>
                            </div>
                        @endif
                    </td>
                </tr>
            @elseif(isset($orderID))
            @endif
        </table>
        <div
            style="margin: auto;
        width: 100%;
        text-align: center;
        margin-top: 23px;
        color: blue;">
            ® Facilitate by iTradebulk</div>
    </div>

</body>

</html>
