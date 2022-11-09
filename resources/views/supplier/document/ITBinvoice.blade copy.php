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
                                <b>ITB Invoice</b><br>
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
                                {{ $supplierData->company->trading_name }}<br>
                                {{ $supplierData->company->address1 }}, {{ $supplierData->company->address2 }},
                                {{ $supplierData->company->city_name }}, {{ $supplierData->company->state_name }},
                                {{ $supplierData->company->country_name }},<br>
                                {{ $supplierData->company->zipcode_name }}, {{ $supplierData->company->zipcode_code }}.
                            </td>

                            <td class="text-right txtalign">
                                ITB Admin<br>South Africa
                                {{-- <b>{{ $supplierData->first_name }}
                                    {{ $supplierData->last_name }}</b><br>{{ $supplierData->email }} --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Name</td>
                <td>Price</td>



            </tr>
            <tr>
                <td>Total Amount</td>
                <td>{{ 'R ' . $salesData->final_total }}</td>
            </tr>
            <tr>
                <td>Charge Amount</td>
                <td>{{ $tax->admin_charge . '%' }}</td>
            </tr>
            <tr>
                <td>ITB Amount</td>
                <td>{{ 'R ' . $tax->credit_amount }}</td>
            </tr>


        </table>
    </div>

</body>

</html>
