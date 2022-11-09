@extends('frontend.layouts.main')

@section('content')
    <section class="spacer" id="invoice-box">

        <div class="container">

            <div class="row">

                <div class="col-md-12 text-center">

                    <h3 class="mb-4"><b>{{ __('Picking Document') }}</b></h3>

                </div>

            </div>

            <div class='row'>



                @include('supplier.document.invoice')





            </div>

            {{-- <div class="row mb-15">



                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-15">

                    <div class="box-type-1">

                        <div class="box-icon line-height-100">

                            <i class="fa fa-wallet fa-2x"></i> R{{$walletBalance}}

                        </div>  

                        <div class="box-details">

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-15 mt-3">

                            <p>{{__("Total: ")}}R{{$productTotal}}</p>

                            <p>{{__("Shipping: ")}}R{{$shippingMethod == "delivery" ? $shippingTotal : 0}} ({{strtoupper($shippingMethod)}})</p>

                             @if ($offerTotal > 0)

                                 <p>{{__("Offer Total: ")}}R{{$offerTotal}}</p>

                             @endif

                            <p><b>Payable amount: R{{$grandTotal = $productTotal + $offerTotal + ($shippingMethod == "delivery" ? $shippingTotal : 0)}}</b></p>

                            </div>

                            <div class="payment-action-footer">

                                <div class="row">

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">

                                        @if ($grandTotal < $walletBalance)

                                            <a href="#paymentLoaderModal" data-order-amount="{{$productTotal}}" data-shipping-amount="{{$shippingTotal}}"  data-delivery-type="{{$shippingMethod}}" data-offer-amount="{{$offerTotal}}"  data-offer-id="{{$offerId}}" data-supplier-id="{{$supplierId}}" data-toggle="modal" data-distance="{{$distance}}" data-weight="{{$weight}}" class="btn btn-sm btn-success payment-order-button loading" >{{__('Pay using Wallet')}}</a>

                                        @else

                                            <small class="text-danger">Insufficient wallet amount. Please add amount to your wallet and complete payment.</small>

                                            <a href="{{route('user.wallet.index')}}" class="btn btn-sm btn-warning" >{{__('Credit to wallet')}}</a>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div> --}}

        </div>

    </section>



    <!-- The Modal -->

    <div class="modal" id="paymentLoaderModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- Modal body -->

                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required text-center">

                            <br>

                            <i class="fas fa-spinner fa-3x fa-spin"></i>

                            <p class="text-success text-center">Please wait...</p>

                        </div>

                    </div>

                </div>



            </div>

        </div>

    </div>
@endsection



@section('footerScript')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#paymentLoaderModal').on('shown.bs.modal', function(e) {

                let orderAmount = $(".payment-order-button").data('order-amount');

                let shippingAmount = $(".payment-order-button").data('shipping-amount');

                let offerAmount = $(".payment-order-button").data('offer-amount');

                let offerId = $(".payment-order-button").data('offer-id');

                let supplierId = $(".payment-order-button").data('supplier-id');

                let deliveryType = $(".payment-order-button").data('delivery-type');

                let amtPayble = $(".payment-order-button").data('amt-payble');

                let itemTax = $(".payment-order-button").data('item-tax');

                let distance = $(".payment-order-button").data('distance');

                let weight = $(".payment-order-button").data('weight');

                let ajaxUrl = "{{ route('make-payment-post') }}";


                $.ajax({

                    type: 'POST',

                    data: {
                        _token: TOKEN,
                        order_amount: orderAmount,
                        supplier_id: supplierId,
                        shipping_amount: shippingAmount,
                        discount_amount: offerAmount,
                        offer_id: offerId,
                        delivery_type: deliveryType,
                        amtPayble: amtPayble,
                        distance: distance,
                        weight: weight,
                        itemTax: itemTax
                    },

                    url: ajaxUrl,

                    success: function(data) {
                        // $("#paymentLoaderModal").model("show");

                        // $('.' + wrapperClass).replaceWith(data);

                        // $('.' + wrapperClass + ' select').trigger('change');

                        hideLoader()

                    },

                    error: function(xhr, status, error) {

                        //alert(xhr.responseText);

                        hideLoader()

                    }

                });

                setTimeout(function() {

                    window.location.href = "{{ route('success') }}";

                }, 5000);

            })

        });

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
@endsection
