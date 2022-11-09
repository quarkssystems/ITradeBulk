@extends('frontend.layouts.main')

@section('content')
    <section class="spacer">
        <div class="search-base">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{ __('Select Products') }}</b></h3>
                </div>
            </div>

            <div class="mb-15 br_category_sidebar">
                <div class="br_category_sidebar_inner col-lg-2" style="float: left">
                    <i class="fa fa-filter product_sidebar_filter btn"></i>
                    <div>
                        @include('frontend.catalog.product.sidebar')
                    </div>
                </div>


                @if (sizeof($_GET))
                    <div class="row">
                        <div class="col-md-12 search-tags">
                            <a href="/products">Clear</a>
                            @if (isset($_GET['category']))
                                <a href="">{{ str_replace('-', ' ', $_GET['category']) }}</a>
                            @endif
                            @if (isset($_GET['brand']))
                                <?php $a_links = explode('|', $_GET['brand']); ?>
                                @foreach ($a_links as $a_link)
                                    @if ($a_link != '')
                                        <a href="">{{ str_replace('-', ' ', $a_link) }}</a>
                                    @endif
                                @endforeach
                            @endif

                            @if (isset($_GET['supplier']))
                                <?php $a_links = explode('|', $_GET['supplier']); ?>
                                @foreach ($a_links as $uuid)
                                    @if ($uuid != '')
                                        <a href="">{{ getSupplierName($uuid) }}</a>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>
                @endif

                <div class="row">
                    @foreach ($mainProducts as $product)
                        <div class="col-6 col-md-4 col-lg-2 mb-15">
                            @include('frontend.catalog.product.list-item')
                        </div>
                    @endforeach

                    @if ($products->count() == 0)
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-15">
                            <div class="alert alert-warning">
                                {{ __('No matching product found') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerScript')
    <script type="text/javascript">
        // function addproducttocart(product_id, id) {

        //     var arrID = id.split('_');
        //     var parent_product_id = arrID[1];
        //     $('#add-product-'+parent_product_id).val(product_id);
        //     alert(arrID[1]);
        //     // alert(product_id, parent_product_id);

        //     // $('#testing').attr('id', product_id);

        // }

        // $(document).on('click','.popover-external-html1', function(e){
        // // $('.popover-external-html1').on("click", function(e){
        //     // alert('teet');

        //     let datatproductid = $(this).attr('data-product');
        //     var arrID = datatproductid.split('-');
        //     var parent_product_id = arrID[2];

        //     let selectboxid = $(this).attr('data-select');
        //     var productid = $('#'+selectboxid).val();

        //     $(datatproductid+ ' input[name = product_id]').val(productid);
        //     alert(datatproductid);
        //     $(datatproductid).submit();


        //     // alert(parentProductid +'-------'+  productid);
        // });


        $(document).ready(function($) {
            $('#seemore, #div-to-toggle').click(function(e) {
                if ($(e.target).attr('id') != 'close-btn') {
                    $('.allcategory').slideToggle("slow");
                    event.stopPropagation();
                }
            });

            $('#close-btn').click(function() {
                $('.allcategory').hide("slow");
                event.stopPropagation();
            })
        });
    </script>
    <script>
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        function UpdateQueryString(key, value, url) {
            if (!url) url = window.location.href;
            var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
                hash;

            if (re.test(url)) {
                if (typeof value !== 'undefined' && value !== null) {
                    return url.replace(re, '$1' + key + "=" + value + '$2$3');
                } else {
                    hash = url.split('#');
                    url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                    if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                        url += '#' + hash[1];
                    }
                    return url;
                }
            } else {
                if (typeof value !== 'undefined' && value !== null) {
                    var separator = url.indexOf('?') !== -1 ? '&' : '?';
                    hash = url.split('#');
                    url = hash[0] + separator + key + '=' + value;
                    if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                        url += '#' + hash[1];
                    }
                    return url;
                } else {
                    return url;
                }
            }
        }
    </script>
@endsection
