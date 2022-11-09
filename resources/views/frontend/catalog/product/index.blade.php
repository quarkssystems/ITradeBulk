@extends('frontend.layout')

@section('style')
<style type="text/css">
    .br_category_sidebar_inner {
        float: left
    }

    .prod-title {
        margin-bottom: 30px;
    }

    .prod-price {
        position: absolute;
        bottom: 5px;
    }
</style>
@endsection

@section('content')

<section class="m-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h3 class="mb-4"><b>{{__('Select Products')}}</b></h3>
        </div>
    </div>
</section>

<!-- Category Sidebar -->
<div class="br_category_sidebar">
    <div class="br_category_sidebar_inner col-md-2">
        @include('frontend.catalog.product.sidebar')
    </div>

    <div class="row col-md-10">

        @foreach($products as $product)
            @include('frontend.catalog.product.card')
            <!-- <div class="col-md-2 mb-15">
                @include("frontend.catalog.product.list-item")
            </div> -->
        @endforeach

        @if($products->count() == 0)
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-15">
                <div class="alert alert-warning">
                    {{__("No matching product found")}}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Category Sidebar / -->
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function($) {
        $('#seemore, #div-to-toggle').click(function (e) {
            if ($(e.target).attr('id') != 'close-btn') {
                $('.allcategory').slideToggle("slow");
                event.stopPropagation();
            }
        });

        $('#close-btn').click(function () {
            $('.allcategory').hide("slow");
            event.stopPropagation();
        })
    });
</script>
@endsection
