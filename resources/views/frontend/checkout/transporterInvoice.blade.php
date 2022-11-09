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
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                @if (!isset($removebtn))
                                    <img src="{{ asset($transporterDetail->image) }}"
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
                <td colspan="4">
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
                                <b>{{ $transporterDetail->first_name }} {{ $transporterDetail->last_name }}</b><br>
                                {{ $transporterDetail->email }}<br>
                                {{ $logisticDetail->address1 }} {{ $logisticDetail->address2 }},<br>
                                {{ $logisticDetail->city_name }} , {{ $logisticDetail->state_name }}<br>
                                {{ $logisticDetail->country_name }}.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Order No</td>
                <td>Weight / Distance</td>
                <td>Price</td>
                <td>Total Price</td>
            </tr>

            <tr class="details">
                <td>#{{ $orderDetail->order_number }}</td>
                <td>{{ $orderDetail->total_weight }} / {{ $orderDetail->distance }}</td>
                <td>R {{ number_format($orderDetail->shipment_amount, 2, '.', ',') }}</td>
                <td>R {{ number_format($orderDetail->shipment_amount, 2, '.', ',') }}</td>
            </tr>

            {{-- <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td>{{__("Shipping: ")}}</td>
                        <td>R {{number_format($orderDetail->shipment_amount, 2, '.', ',')}}</td>
                    </tr> --}}

            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('VAT: ') }}</td>
                <td>{{ $orderDetail->total_weight }}</td>
            </tr>
            <!--
                    <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td>{{ __('iTradezon charge: ') }}</td>
                        <td>R {{ number_format($adminCharge, 2, '.', ',') }}</td>
                    </tr> -->

            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td>{{ __('Total amount: ') }}</td>
                <td>R {{ number_format($orderDetail->shipment_amount, 2, '.', ',') }}</td>
            </tr>


        </table>
    </div>

</body>

</html>
