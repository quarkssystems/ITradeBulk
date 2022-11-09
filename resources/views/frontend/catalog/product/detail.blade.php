@extends('frontend.layouts.main')
@section('content')

    <!-- Product Detail HTML -->
    <div id="product_detail">
        <div class="container" style="margin-top:50px;">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <!-- <div class="thumb-image"><img src="{{ asset($product_detail->base_image) }}" data-imagezoom="true"> -->

                    <div class="image-container">
                        <img class="image-zoom" src="{{ asset($product_detail->base_image) }}"
                            data-zoom="{{ asset($product_detail->base_image) }}">
                        <div class="image-details"></div>
                    </div>


                    <!-- <ul class="nav nav-pills">
                                                                                                                                                                            <li class="active"><a data-toggle="pill" href="#img1"><img src="images/prod-img.png" width="30px"></a></li>
                                                                                                                                                                            <li><a data-toggle="pill" href="#img2"><img src="images/fresh-meat-banner.jpg" width="30px"></a></li>
                                                                                                                                                                            <li><a data-toggle="pill" href="#img3"><img src="images/prod-img.png" width="30px"></a></li>
                                                                                                                                                                            <li><a data-toggle="pill" href="#img4"><img src="images/fresh-meat-banner.jpg" width="30px"></a></li>
                                                                                                                                                                        </ul> -->

                    <!-- <div class="tab-content">

                                                                                                                                                                            <div id="img1" class="tab-pane fade in active">
                                                                                                                                                                                <img src="images/prod-img.png" data-imagezoom="true">
                                                                                                                                                                            </div>
                                                                                                                                                                            <div id="img2" class="tab-pane fade">
                                                                                                                                                                                <img src="images/fresh-meat-banner.jpg" data-imagezoom="true">
                                                                                                                                                                            </div>
                                                                                                                                                                            <div id="img3" class="tab-pane fade">
                                                                                                                                                                                <img src="images/prod-img.png" data-imagezoom="true">
                                                                                                                                                                            </div>
                                                                                                                                                                            <div id="img4" class="tab-pane fade">
                                                                                                                                                                                <img src="images/fresh-meat-banner.jpg" data-imagezoom="true">
                                                                                                                                                                            </div>
                                                                                                                                                                        </div> -->
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="details product_detail">

                        <ul class="category_nav">
                            @php
                                $i = 1;
                                $c = count($cats);
                                $icon = '&raquo;';
                            @endphp
                            @foreach ($cats as $cat)
                                @php
                                    if ($i == $c) {
                                        $icon = '';
                                    }
                                    $i++;
                                @endphp
                                <li>
                                    <a href="{{ route('products') }}?category={{ $cat['slug'] }}">{{ $cat['name'] }}
                                        {!! $icon !!}</a>
                                </li>
                            @endforeach
                        </ul>
                        <h3 class="product-title">{{ $product_detail->name }}</h3>

                        <p class="product-description" style="margin-bottom:0px">{{ $product_detail->description }} </p>
                        <!-- <h4 class="price">Current Price: <span>R {{ $product_detail->base_price }}</span></h4> -->
                        @if ($avrgReviewRatings)
                            <span class="fa fa-star" @if ($avrgReviewRatings == 1 ||
                                $avrgReviewRatings == 2 ||
                                $avrgReviewRatings == 3 ||
                                $avrgReviewRatings == 4 ||
                                $avrgReviewRatings == 5) style="color: orange" @endif></span>
                            <span class="fa fa-star" @if ($avrgReviewRatings == 2 || $avrgReviewRatings == 3 || $avrgReviewRatings == 4 || $avrgReviewRatings == 5) style="color: orange" @endif></span>
                            <span class="fa fa-star" @if ($avrgReviewRatings == 3 || $avrgReviewRatings == 4 || $avrgReviewRatings == 5) style="color: orange" @endif></span>
                            <span class="fa fa-star" @if ($avrgReviewRatings == 4 || $avrgReviewRatings == 5) style="color: orange" @endif></span>
                            <span class="fa fa-star" @if ($avrgReviewRatings == 5) style="color: orange" @endif></span>
                        @endif
                        <h5 class="sizes">
                            {{ $product_detail->unit }}: {{ $product_detail->unit_value }}
                            {{ $product_detail->unit_name }}
                        </h5>
                        <h5 class="manufacturer">
                            Brand : <span>{{ $product_detail['brand']['name'] }}</span>
                            {{-- Manufacturer : <span>{{$product_detail['brand']['name']}}</span> --}}
                        </h5>
                        <h5 class="manufacturer">
                            Unit :
                            <span>{{ $product_detail['stock_of'] . 'x' . $product_detail['unit_value'] . '' . $product_detail['unit_name'] }}</span>
                        </h5>
                        <h5 class="manufacturer">
                            Stock Type : {{-- <span>$product_detail->stock_type</span> --}}

                            <span>

                                <!-- <select class="form-control-sm select-dropdown col-md-4" name="stock_type" id="stock_type">
                                                                                                                                                                                    @foreach ($childProducts as $cp)
    {{-- <option value="{{$cp->slug}}" @if ($cp->slug == $product_detail->slug) ? selected : null @endif>{{$cp->stock_type}}</option> --}}
                                                                                                                                                                                        <option value="{{ route('productdetail', $cp->slug) }}" @if ($cp->slug == $product_detail->slug) ? selected : null @endif>{{ $cp->stock_type }}</option>
    @endforeach
                                                                                                                                                                                </select> -->

                                @foreach ($childProducts as $cp)
                                    <button type="button"
                                        class="btn btn-outline-success @if ($cp->slug == $product_detail->slug) active @endif"
                                        value="{{ route('productdetail', $cp->slug) }}" id="stock_type1"
                                        onclick="addtocart(this.value)">{{ $cp->stock_type }}</button>
                                @endforeach

                            </span>

                        </h5>




                        @if (Auth::guest())
                            {!! Form::open(['route' => 'checkout.add-to-cart']) !!}
                            {!! Form::hidden('product_id', $product_detail->uuid) !!}
                            <div class="action">
                                <button type="submit"
                                    class="add-to-cart btn btn-default ">{{ __('Add to cart') }}</button>
                            </div>
                            {!! Form::close() !!}
                        @elseif(Auth::user()->role == 'VENDOR')
                            {!! Form::open(['route' => 'checkout.add-to-cart']) !!}
                            {!! Form::hidden('product_id', $product_detail->uuid) !!}
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4 no-padding">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number btn-sm"
                                                    data-type="minus"
                                                    data-field="single_qty-{{ $product_detail->uuid }}"><span
                                                        class="fa fa-minus"></span></button>
                                            </span>
                                            <input type="text" name="single_qty"
                                                class="form-control input-number form-control-sm single_qty-{{ $product_detail->uuid }}"
                                                value="{{ $product_detail->single_qty ?? 1 }}" min="0"
                                                max="100">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number btn-sm"
                                                    data-type="plus" data-field="single_qty-{{ $product_detail->uuid }}">
                                                    <span class="fa fa-plus"></span></button>
                                                <!-- <button type="submit" class="add-to-cart btn btn-default " >{{ __('Add to cart') }}</button> -->
                                            </span>
                                        </div>

                                        @if (count($getColorVariants) != 0)
                                            <div class="input-group">
                                                <select name="color" id=""
                                                    class="btn btn-default btn-number btn-sm" style="width: 100%;"
                                                    required>
                                                    <option value="">Select Color</option>
                                                    @foreach ($getColorVariants as $color)
                                                        <option value="{{ $color }}">{{ $color }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if (count($getSizeVariants) != 0)
                                            <div class="input-group">
                                                <select name="size" id=""
                                                    class="btn btn-default btn-number btn-sm" style="width: 100%;"
                                                    required>
                                                    <option value="">Select Size</option>
                                                    @foreach ($getSizeVariants as $size)
                                                        <option value="{{ $size }}">{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="action">
                                            <button type="submit"
                                                class="add-to-cart btn btn-default ">{{ __('Add to cart') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="product_tabs">
                <ul class="tabs">
                    <li class="tab-link current" data-tab="tab-2">Description</li>
                    <li class="tab-link" data-tab="tab-1">Reviews & Rating</li>
                    <!-- <li class="tab-link" data-tab="tab-3">Questions and Answers</li> -->
                </ul>

                <div id="tab-1" class="tab-content ratings">
                    @if ($user_uuid)
                        <form method="post" action="{{ url('user/review-rating') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    <ul class="list-inline rating-stars" onMouseLeave="mouseOutRating(2,0);"
                                        style="display:inline-flex">
                                        <li value="1" id="2_1" class="star" onclick="addRating(2,1);"
                                            onMouseOver="mouseOverRating(2,1);" style="font-size: xxx-large;">&#9733;</li>
                                        <li value="2" id="2_2" class="star" onclick="addRating(2,2);"
                                            onMouseOver="mouseOverRating(2,2);" style="font-size: xxx-large;">&#9733;</li>
                                        <li value="1" id="2_3" class="star" onclick="addRating(2,3);"
                                            onMouseOver="mouseOverRating(2,3);" style="font-size: xxx-large;">&#9733;</li>
                                        <li value="1" id="2_4" class="star" onclick="addRating(2,4);"
                                            onMouseOver="mouseOverRating(2,4);" style="font-size: xxx-large;">&#9733;</li>
                                        <li value="1" id="2_5" class="star" onclick="addRating(2,5);"
                                            onMouseOver="mouseOverRating(2,5);" style="font-size: xxx-large;">&#9733;</li>
                                    </ul>
                                </div>
                                <div class="col-xs-12 col-lg-12">
                                    <input type="hidden" id="rating" name="rating">
                                    <input type="hidden" id="user_uuid" name="user_uuid" value="{{ $user_uuid }}">
                                    <input type="hidden" id="productid" name="productid"
                                        value="{{ $product_detail->uuid }}">
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Enter your title">
                                    <textarea class="form-control" id="review" name="review" placeholder="Type your valuable reviews here."></textarea>
                                </div>

                                <div class="col-xs-12 col-lg-12 text-right">
                                    {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save_exit']) !!}
                                </div>

                            </div>
                        </form>
                    @else
                        <div class="no-review-form-message">
                            <b>Write a review. <a class="" href="{{ url('user/login') }}">Login<a></b>
                        </div>
                    @endif

                    @if ($reviewRatings)
                        @foreach ($reviewRatings as $reviewRating)
                            <div class="single-review">
                                <div class="stars">
                                    <span class="fa fa-star"
                                        @if ($reviewRating->rating == 1 ||
                                            $reviewRating->rating == 2 ||
                                            $reviewRating->rating == 3 ||
                                            $reviewRating->rating == 4 ||
                                            $reviewRating->rating == 5) style="color: orange" @endif></span>
                                    <span class="fa fa-star"
                                        @if ($reviewRating->rating == 2 ||
                                            $reviewRating->rating == 3 ||
                                            $reviewRating->rating == 4 ||
                                            $reviewRating->rating == 5) style="color: orange" @endif></span>
                                    <span class="fa fa-star"
                                        @if ($reviewRating->rating == 3 || $reviewRating->rating == 4 || $reviewRating->rating == 5) style="color: orange" @endif></span>
                                    <span class="fa fa-star"
                                        @if ($reviewRating->rating == 4 || $reviewRating->rating == 5) style="color: orange" @endif></span>
                                    <span class="fa fa-star"
                                        @if ($reviewRating->rating == 5) style="color: orange" @endif></span>
                                    <span class="comment-title">{{ $reviewRating->title }}</span>
                                </div>

                                <p class="review-detail">{{ ucfirst($reviewRating->review) }}</p>
                                <p class="reviewer">-By
                                    {{ ucfirst($reviewRating->user->first_name . ' ' . $reviewRating->user->last_name) }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <span class="review-no">No Reviews yet. Add your review.</span>
                    @endif

                </div>
                <div id="tab-2" class="product-description-tab tab-content current">
                    <p>{{ $product_detail->description }}</p>

                </div>
            </div>
        </div>
    </div>
    <!-- Product Detail HTML Close -->

@section('footerScript')

    <script type="text/javascript">
        // $('#stock_type').on('change', function (e) {
        //     var link = $("option:selected", this).val();
        //     if (link) {
        //         location.href = link;
        //     }
        // });

        function addtocart(link) {
            if (link) {
                location.href = link;
            }
        }

        function showRestaurantData(url) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("restaurant_list").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", url, true);
            xhttp.send();

        }

        function mouseOverRating(restaurantId, rating) {

            resetRatingStars(restaurantId)

            for (var i = 1; i <= rating; i++) {
                var ratingId = restaurantId + "_" + i;
                document.getElementById(ratingId).style.color = "#ff6e00";

            }
        }

        function resetRatingStars(restaurantId) {
            for (var i = 1; i <= 5; i++) {
                var ratingId = restaurantId + "_" + i;
                document.getElementById(ratingId).style.color = "#9E9E9E";
            }
        }

        function mouseOutRating(restaurantId, userRating) {
            //    var ratingId;
            //    if(userRating !=0) {
            // 	       for (var i = 1; i <= userRating; i++) {
            // 	    	      ratingId = restaurantId + "_" + i;
            // 	          document.getElementById(ratingId).style.color = "#ff6e00";
            // 	       }
            //    }
            //    if(userRating <= 5) {
            // 	       for (var i = (userRating+1); i <= 5; i++) {
            //     	      ratingId = restaurantId + "_" + i;
            //           document.getElementById(ratingId).style.color = "#9E9E9E";
            //        }
            //    }
        }

        function addRating(restaurantId, ratingValue) {
            var ratingId = restaurantId + '_' + ratingValue;
            document.getElementById(ratingId).style.color = "#ff6e00";
            $('#rating').val(ratingValue);
        }
    </script>
@stop
@endsection
