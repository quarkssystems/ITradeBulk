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

                            @if ($adminDetails == null || is_null($adminDetails->icon))
                                {{-- @if (is_null($supplierData->image)) --}}
                                <img src='{{ asset('images/supplier.png') }}' style="width:100px; max-width:200px;">
                            @else
                                <img src='{{ asset($adminDetails->icon) }}' style="width:200px; max-width:300px;">
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

                        <td class="text-right txtalign" style="white-space: pre-wrap;">
                            @if ($adminDetails != null && $adminDetails->address != null)
                                {!! $adminDetails->address !!}
                            @endif
                            {{-- ITB Admin<br>South Africa --}}
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
