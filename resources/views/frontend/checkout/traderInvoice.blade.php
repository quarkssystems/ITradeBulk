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
                                @if (!isset($removebtn))
                                    <img src="{{ asset($supplierDetail->image) }}"
                                        style="width:100px; max-width:200px;">
                                @else
                                    <img src="{{ public_path('assets/frontend/images/logo.png') }}"
                                        style="width:200px; max-width:300px;">
                                @endif
                            </td>

                            <td class="text-right" style="text-align: right;">
                                <b>Proforma Invoice: #{{ $orderDetail->order_number }}</b><br>
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
                                <b>{{ $userDetail->title }} {{ $userDetail->first_name }}
                                    {{ $userDetail->last_name }}</b><br>
                                {{ $userDetail->email }}<br>
                                {{ $userCompanyData->address1 }} {{ $userCompanyData->address2 }},<br>
                                {{ $userCompanyData->zipcode_code }} - {{ $userCompanyData->city_name }},<br>
                                {{ $userCompanyData->state_name }} , {{ $userCompanyData->country_name }}.
                            </td>

                            <td class="text-right txtalign">
                                <b>{{ $supplierCompanyData->trading_name }}</b><br>
                                {{ $supplierCompanyData->email }}<br>
                                {{ $supplierCompanyData->address1 }} {{ $supplierCompanyData->address2 }},<br>
                                {{ $supplierCompanyData->zipcode_code }} - {{ $supplierCompanyData->city_name }},<br>
                                {{ $supplierCompanyData->state_name }} , {{ $supplierCompanyData->country_name }}.
                            </td>
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
            @foreach ($supplierLoopData['products'] as $key => $product_info)
                <tr class="details">
                    <td> {{ $product_info['product_name'] }}</td>
                    <td> {{ $product_info['qty'] }}</td>
                    <td> R {{ number_format($product_info['productSinglePrice'], 2, '.', ',') }}</td>
                    <td>
                        @if (isset($productOffer) && $productOffer != '')
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
                <td>R {{ number_format($orderDetail->cart_amount, 2, '.', ',') }}</td>
            </tr>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Tax : ') }}</td>
                <td></td>
                <td>R {{ number_format($orderDetail->tax_amount, 2, '.', ',') }}</td>
            </tr>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Promo Disc Value: ') }}</td>
                <td></td>
                <td>R {{ number_format($orderDetail->discount_amount, 2, '.', ',') }}</td>
            </tr>
            <!--   <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td>{{ __('iTradezon charge: ') }}</td>
                        <td>R {{ number_format($supplierAdminCharge, 2, '.', ',') }}</td>
                    </tr> -->
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Payable amount: ') }}</td>
                <td></td>
                <td>R {{ number_format($orderDetail->final_total - $orderDetail->shipment_amount, 2, '.', ',') }}</td>
            </tr>


        </table>
    </div>

</body>

</html>
