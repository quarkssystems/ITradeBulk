<div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 mb-3">
    <div class="prod-main">
        <a href="{{ route('productdetail', $product->uuid) }}">
            <img class="img-fluid" src="{{ asset($product->base_image) }}">
        </a>

        <div class="clearfix"></div>

        <a href="{{ route('productdetail', $product->uuid) }}">
            <div class="prod-title">{{ $product->name }}</div>
        </a>

        <div class="prod-price">
            <span class="orig-price"><strong>{{ $product->unit_value }}{{ $product->unit_name }}</strong></span>
            <span class="old-price">
                <a href="javascript:;" data-product="#add-product-{{$product->uuid}}"  class="add-to-cart popover-external-html">
                    <img src="{{asset('assets/frontend/images/addtocart.png')}}" class="img-fluid">
                </a>
                {!! Form::open(['route' => "checkout.add-to-cart",'id' => "add-product-".$product->uuid ]) !!}
                {!! Form::hidden('product_id', $product->uuid) !!}
                {!! Form::close() !!}
            </span>
        </div>
    </div>
</div>
