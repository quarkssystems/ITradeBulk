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

    <section class="spacer" id="invoice-box">
        <div class="container" style="max-width: 1349px;">

            <div class="row" style="margin-left: -15px;margin-right: -15px;">

                <div class="col-md-12 text-center" style="text-align: center!important;">

                    <h3 class="mb-4" style="margin-bottom: 1.5rem!important;"><b>{{ __('Disptached Document') }}</b>
                    </h3>

                </div>

            </div>

            <div class='row' style="margin-left: -15px;margin-right: -15px;">





                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">

                        <tr class="top">
                            <td colspan="13">
                                <table>
                                    <tr>
                                        <td class="title">


                                            @if (is_null($supplierData->image))
                                                <img src='{{ asset('images/supplier.png') }}'
                                                    style="width:100px; max-width:200px;">
                                            @else
                                                <img src='{{ asset($supplierData->image) }}'
                                                    style="width:200px; max-width:300px;">
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
                                            {{ $supplierData->company->trading_name }}<br>
                                            {{ $supplierData->company->address1 }},
                                            {{ $supplierData->company->address2 }},
                                            {{ $supplierData->company->city_name }},
                                            {{ $supplierData->company->state_name }},
                                            {{ $supplierData->company->country_name }},<br>
                                            {{ $supplierData->company->zipcode_name }},
                                            {{ $supplierData->company->zipcode_code }}.
                                        </td>

                                        <td class="text-right txtalign">
                                            <b>{{ $supplierData->first_name }}
                                                {{ $supplierData->last_name }}</b><br>{{ $supplierData->email }}
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
                            {{-- {{dd($product_info)}} --}}
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
                                <td> {{ $product_info['product_name'] }}
                                    @if ($color != '' || $size != '')
                                        ({{ $color }} {{ $size }})
                                    @endif
                                </td>
                                <td> {{ $product_info['qty'] }}

                                </td>
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
                                <td>{{ $product_info['barcode'] != '' ? $product_info['barcode'] : 'NA' }}</td>
                                <td>{{ $product_info['product_code'] != '' ? $product_info['product_code'] : 'NA' }}
                                </td>
                                <td>{{ $product_info['Store_item_code'] != '' ? $product_info['Store_item_code'] : 'NA' }}
                                </td>
                                @if (isset($product_info['product_image']) && file_exists(url($product_info['product_image'])))
                                    <td><img src="{{ url($product_info['product_image']) }}" /> </td>
                                @else
                                    <td>NA</td>
                                @endif
                                {{-- <td>{{$product_info['price']}}</td> --}}
                                <td>{{ $product_info['category'] != '' ? $product_info['category'] : 'NA' }}</td>
                                <td>{{ $product_info['description'] != '' ? $product_info['description'] : 'NA' }}
                                </td>
                                <td>{{ $product_info['units_ordered'] != '' ? $product_info['units_ordered'] : 'NA' }}
                                </td>
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
                            <td>@php($grandTotal = $paybel_amt_input + $shippingTotal) R {{ number_format($grandTotal, 2, '.', ',') }}</td>
                            {{-- <td>@php($grandTotal = $paybel_amt_input + ($shippingMethod == 'delivery' ? $shippingTotal : 0)) R {{ number_format($grandTotal, 2, '.', ',') }}</td> --}}
                        </tr>


                    </table>
                </div>






            </div>

        </div>

    </section>

    <script type="text/javascript">
        $(document).ready(function() {


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
        });
    </script>


</body>

</html>
