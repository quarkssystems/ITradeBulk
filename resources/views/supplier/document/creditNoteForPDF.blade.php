<html>

<head>
    <style>
        /* Invoice formate */
        .invoice-box {
            max-width: 100%;
            width: 1000px;

            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            margin: auto;
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
            border-collapse: unset;
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

    {{-- <section class="spacer" id="invoice-box" style="padding: 50px 0;"> --}}

    <div class="container" style="max-width: 1349px;">

        <div class="row" style="margin-left: -15px;margin-right: -15px;">

            <div class="col-md-12 text-center" style="text-align: center!important;">

                <h3 class="mb-4" style="margin-bottom: 1.5rem!important;"><b>{{ __('Proforma Credit Note') }}</b></h3>

            </div>

        </div>

        <div class='row' style="margin-left: -15px;margin-right: -15px;">




            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0" style="width: 100%;">

                    <tr class="top">
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td class="title">


                                        @if (is_null($supplierData->image))
                                            <img src='{{ asset('images/supplier.png') }}'
                                                style="width:100px; max-width:200px;" />
                                        @else
                                            <img src='{{ asset($supplierData->image) }}'
                                                style="width:200px; max-width:300px;" />
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
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td>
                                        {!! $supplierAddress !!}
                                        {{-- {{ $supplierData->company->trading_name }}<br>
                                        {{ $supplierData->company->address1 }},
                                        {{ $supplierData->company->address2 }},
                                        {{ $supplierData->company->city_name }},
                                        {{ $supplierData->company->state_name }},
                                        {{ $supplierData->company->country_name }},<br>
                                        {{ $supplierData->company->zipcode_name }},
                                        {{ $supplierData->company->zipcode_code }}. --}}
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


                    </tr>

                    <?php
                    $totalPrice = 0;
                    $paidPrice = 0;
                    $refundPrice = 0;
                    $pendingPrice = 0;
                    ?>
                    @foreach ($pickingData as $data)
                        <?php
                        // dd($data['old_final_total']);
                        $qnty = $data['qnty'];
                        // $qnty = $data['old_qnty'] - $data['single_qty'];
                        if ($qnty < 0) {
                            $qnty = $qnty * -1;
                            $qnty = '+' . $qnty;
                        }
                        ?>
                        <tr>
                            <td> {{ $data['product_name'] }}</td>
                            <td> {{ $qnty }}</td>
                            <td> R {{ $data['product_price'] }}</td>
                            <?php
                            $totalPrice = $data['final_total'];
                            $paidPrice = $data['paid'];
                            // $paidPrice = $data['old_final_total'];
                            $refundPrice = $data['refundPrice'];
                            // $refundPrice = $data['old_final_total'] - $data['final_total'];
                            if ($refundPrice < 0) {
                                $pendingPrice = $refundPrice * -1;
                            }
                            ?>
                        </tr>
                    @endforeach

                    <tr class="font-weight-bold">

                        <td></td>
                        <td>{{ __('Paid amount: ') }}</td>
                        <td> R {{ number_format((float) $paidPrice, 2, '.', '') }}</td>
                    </tr>


                    <tr class="font-weight-bold">

                        <td></td>
                        <td>{{ __('Refund amount: ') }}</td>
                        <td>R {{ number_format((float) $refundPrice, 2, '.', '') }}</td>
                    </tr>
                    @if ($pendingPrice != 0)
                        <tr class="font-weight-bold">

                            <td></td>
                            <td>{{ __('Pending amount: ') }}</td>
                            <td>R {{ number_format((float) $pendingPrice, 2, '.', '') }}</td>
                        </tr>
                    @endif


                </table>
            </div>


        </div>


    </div>

    {{-- </section> --}}



    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
        $(document).ready(function() {
            $('.edit-cart-item').on('click', function() {
                console.log($(this).data('id'));
                let productId = $(this).data('id');

                if ($(`.showEdit${productId}`).attr('style') === 'display: none') {
                    $(`.showEdit${productId}`).attr('style', '');
                } else {
                    $(`.showEdit${productId}`).attr('style', 'display: none');
                }
                console.log('hii', $(`.showEdit${productId}`).attr('style'));
            })
        })

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

</body>

</html>
