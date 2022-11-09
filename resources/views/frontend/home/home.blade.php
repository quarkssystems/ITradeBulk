@extends('frontend.layouts.main')
@section('content')

    <!-- <section id="hero" class="animated  fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s"  style="background-image:url({{ asset($banner) }}) !important">
                                                                                                                                                                                                                <div class="container animated wow fadeIn" data-wow-delay="0.7s" data-wow-duration="0.5s">
                                                                                                                                                                                                                    <div class="row">
                                                                                                                                                                                                                        <div class="col-12 text-center">
                                                                                                                                                                                                                            <span class="hero-title">Retail Trade Made Easy</span>
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </section> -->

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach ($banners as $key => $row)
                <section id="hero"
                    class="animated  fadeIn carousel-item @if ($key == 0) active @endif"
                    data-wow-delay="0.5s" data-wow-duration="0.5s"
                    style="background-image:url({{ asset($row->image) }}) !important">
                    <div class="row">
                        <div class="col-12 text-center">
                            <span class="hero-title">THE FUTURE OF BULK BUYING!</span>
                            {{-- <span class="hero-title">Retail Trade Made Easy</span> --}}
                        </div>
                    </div>
                </section>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            @endforeach
        </div>
    </div>

    <div id="icn-slider">
        <div class="container">
            <div class="row icn-inner text-center">
                <div class="title">Product Categories</div>
                <div class="owl-carousel owl-theme animated wow bounceInUp home-category-slider" data-wow-delay="0.5s"
                    data-wow-duration="0.5s">
                    @foreach ($categories as $category)
                        <div class="img-wrap col-sm-12">
                            <div class="explore-prod">
                                <a href="{{ route('products') }}?category={{ $category->slug }}">
                                    <span class="exp-title">
                                        <img src="{{ asset($category->thumb_image_file) }}" class="img-fluid" width=""
                                            height="" alt="">
                                    </span>
                                    <div class="clearfix"></div>
                                    <span>{{ $category->name }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <section id="explore-prod">
        <div class="container">
            <div class="row">
                <div class="title col-12">Explore Products from premium brands</div>
                @foreach ($brands as $brand)
                    <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-6 animated wow fadeInLeftBig" data-wow-delay="0.2s"
                        data-wow-duration="0.2s">
                        <div class="explore-prod">
                            <a href="{{ route('products') }}?brand={{ $brand->slug }}">
                                <span class="exp-title"><img class="img-fluid" src="{{ asset($brand->icon_file) }}"
                                        width="" height="" alt=""></span>
                                <div class="clearfix"></div>
                                <span>{{ $brand->name }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
                <hr />
            </div>
        </div>
    </section>

    <section class="new-ariv ">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">New Arrivals</div>
                    <a class="view-all-link" href="{{ route('products') }}">View All</a>
                </div>
                @if (count($newarrivalsProducts) != 0)
                    @foreach ($newarrivalsProducts as $product)
                        <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6">
                            {{-- <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6" style=" display: flex;"> --}}
                            {{-- <div class="prod-main"> --}}
                            {{-- <!-- <div class="off-price"><strong>30%</strong> OFF</div> --> --}}
                            {{-- <img class="img-fluid" src="{{asset($arrival->base_image)}}" width="" height="" alt=""> --}}
                            {{-- <div class="clearfix"></div> --}}
                            {{-- <div class="prod-title">{{$arrival->name}}</div> --}}
                            {{-- <div class="prod-cont">{{$arrival->description}}</div> --}}
                            {{-- <div class="prod-price"> --}}
                            {{-- <span class="orig-price"><strong>{{$arrival->min_price}}</strong></span> --}}
                            {{-- <span class="old-price">{{$arrival->max_price}}</span> --}}
                            {{-- <span class="prod-wight"><strong>{{$arrival->unit_value .' '. $arrival->unit_name}}</strong></span> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                            @include('frontend.catalog.product.list-item')
                        </div>
                    @endforeach
                @else
                    <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6" style=" display: flex;">
                        No new arrival available
                    </div>
                @endif

                <div class="col-xl-4 col-lg-8 col-md-8 col-sm-12">
                    <div id="carousel-new-arrival" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="w-100" src="{{ asset('assets/frontend/images/New-Arrivals.png') }}"
                                    width="" height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="w-100" src="{{ asset('assets/frontend/images/New-Arrivals.png') }}"
                                    width="" height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="w-100" src="{{ asset('assets/frontend/images/New-Arrivals.png') }}"
                                    width="" height="" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-new-arrival" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-new-arrival" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <hr />
            </div>
        </div>
    </section>

    <section class="new-ariv">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">BEST SALES</div>
                    <a class="view-all-link" href="{{ route('products') }}">View All</a>
                </div>
                <div class="col-xl-4 col-lg-8 col-md-8 col-sm-6">
                    <div id="carousel-best-sales" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-fluid w-100" src="{{ asset('assets/frontend/images/Best-Seller.png') }}"
                                    width="" height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100" src="{{ asset('assets/frontend/images/Best-Seller.png') }}"
                                    width="" height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100" src="{{ asset('assets/frontend/images/Best-Seller.png') }}"
                                    width="" height="" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-best-sales" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-best-sales" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
                @if (count($bestsalesProducts) != 0)
                    @foreach ($bestsalesProducts as $product)
                        <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6">
                            {{-- <div class="prod-main"> --}}
                            {{-- <!-- <div class="off-price"><strong>30%</strong> OFF</div> --> --}}
                            {{-- <img class="img-fluid" src="{{asset($bestsale->base_image)}}" width="" height="" alt=""> --}}
                            {{-- <div class="clearfix"></div> --}}
                            {{-- <div class="prod-title">{{$bestsale->name}}</div> --}}
                            {{-- <div class="prod-cont">{{$bestsale->description}}</div> --}}
                            {{-- <div class="prod-price"> --}}
                            {{-- <span class="orig-price"><strong>{{$arrival->min_price}}</strong></span> --}}
                            {{-- <span class="old-price">{{$bestsale->max_price}}</span> --}}
                            {{-- <span class="prod-wight"><strong>{{$bestsale->unit_value . ' ' . $bestsale->unit_name}}</strong></span> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                            @include('frontend.catalog.product.list-item')
                        </div>
                    @endforeach
                @else
                    <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6" style=" display: flex;">
                        No best sale available
                    </div>
                @endif
                <hr />
            </div>
        </div>
    </section>

    {{-- <section class="new-ariv">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">DEALS OF THE DAY</div>
                    <a class="view-all-link" href="{{ route('products') }}">View All</a>
                </div>
                @foreach ($dealofthedaysProducts as $product)
                    <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6">
                        @include('frontend.catalog.product.list-item')
                    </div>
                @endforeach
                <div class="col-xl-4 col-lg-8 col-md-8 col-sm-12">
                    <div id="carousel-deals-of-day" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-deals-of-day" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-deals-of-day" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
                <hr />
            </div>
        </div>
    </section> --}}
    <section class="new-ariv">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">On Promotion</div>
                    <a class="view-all-link" href="{{ route('offers') }}">View All</a>
                </div>
                @if (count($dealofthedays) != 0)
                    @foreach ($dealofthedays as $product)
                        <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6">
                            <img src="{{ asset('assets/frontend/images/on-promotion.png') }}" alt=""
                                class="promotionset">
                            @include('frontend.catalog.product.list-item')

                        </div>
                    @endforeach
                @else
                    <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6" style=" display: flex;">
                        No Promotions available
                    </div>
                @endif {{-- <div class="col-xl-4 col-lg-8 col-md-8 col-sm-12">
                    <div id="carousel-deals-of-day" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Deal-of-the-day.png') }}" width=""
                                    height="" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-deals-of-day" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-deals-of-day" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div> --}}
                <hr />
            </div>
        </div>
    </section>

    {{-- <section class="new-ariv">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">BEST OF THIS WEEK</div>
                    <a class="view-all-link" href="{{ route('products') }}">View All</a>
                </div>
                <div class="col-xl-4 col-lg-8 col-md-8 col-sm-12">
                    <div id="carousel-best-of-week" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Best-of-This-week.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Best-of-This-week.png') }}" width=""
                                    height="" alt="">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid w-100"
                                    src="{{ asset('assets/frontend/images/Best-of-This-week.png') }}" width=""
                                    height="" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-best-of-week" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-best-of-week" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                @foreach ($bestofthisweeksProducts as $product)
                    <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-6">
                        @include('frontend.catalog.product.list-item')
                    </div>
                @endforeach

                <hr />
            </div>
        </div>
    </section> --}}
    <section class="new-ariv">
        <div class="container animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="row">
                <div class="col-12">
                    <div class="title text-left">RECOMMENDATIONS FOR YOU</div>
                    <a class="view-all-link" href="{{ route('products') }}">View All</a>
                </div>
                @if (count($recommendationsProducts) != 0)
                    @foreach ($recommendationsProducts as $product)
                        <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-6">
                            {{-- <div class="prod-main"> --}}
                            {{-- <!-- <div class="off-price"><strong>30%</strong> OFF</div> --> --}}
                            {{-- <img class="img-fluid" src="{{asset($recommendation->base_image)}}" width="" height="" alt=""> --}}
                            {{-- <div class="clearfix"></div> --}}
                            {{-- <div class="prod-title">{{$recommendation->name}}</div> --}}
                            {{-- <div class="prod-cont">{{$recommendation->description}}</div> --}}
                            {{-- <div class="prod-price"> --}}
                            {{-- <span class="orig-price"><strong>{{$arrival->min_price}}</strong></span> --}}
                            {{-- <span class="old-price">{{$recommendation->max_price}}</span> --}}
                            {{-- <span class="prod-wight"><strong>{{$recommendation->unit_value . ' ' . $recommendation->unit_name}}</strong></span> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                            @include('frontend.catalog.product.list-item')
                        </div>
                    @endforeach
                @else
                    <div class="col-6  col-xl-2 col-lg-4 col-md-4 col-sm-6" style=" display: flex;">
                        {{-- No product available --}}
                    </div>
                @endif
                <hr />
            </div>
        </div>
    </section>

    <section id="supplier">
        <div class="container">
            @if (count($suppliers) > 6)
                <div class="row">
                    <div class="col-12 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
                        <div class="title text-left">OUR SUPPLIERS</div>
                    </div>
                    @foreach ($suppliers as $supplier)
                        <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-4 animated wow fadeInDown"
                            data-wow-delay="0.5s" data-wow-duration="0.7s">
                            <div class="icon-box" style="margin-bottom:0px !important">
                                <div class="sup-left">
                                    <!-- <div class="sup-name">S</div> -->
                                    <img src="{{ checkImageExists($supplier->image, 'user') }}"
                                        style="border: 1px solid black;border-radius: 120px;width: 100px;height: 80px;">
                                </div>

                            </div>
                            <div class="">
                                <!-- <h4><a href="#!">{{ $supplier->company_name }}</a></h4> -->
                                <h4><a
                                        href="{{ route('supplier-detail', $supplier->uuid) }}">{{ isset($supplier->company->legal_name) ? $supplier->company->legal_name : $supplier->first_name . ' ' . $supplier->last_name }}</a>
                                </h4>
                                {{-- <p class="description">{{$supplier->gender}}</p> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 text-center"><a class="view-all-link" href="{{ route('supplier') }}">View All</a>
                </div>
                <hr />
            @else
                <div class="row suppliersNearYou">
                    <div class="col-12 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
                        <div class="title text-left"> OUR SUPPLIERS</div>
                    </div>
                    @foreach ($suppliers as $supplier)
                        <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-12 animated wow fadeInDown"
                            data-wow-delay="0.5s" data-wow-duration="0.7s">
                            <div class="icon-box" style="margin-bottom:0px !important">
                                <div class="sup-left">
                                    <!-- <div class="sup-name">{{ substr($supplier->company_name, 0, 1) }}</div> -->
                                    <img src="{{ checkImageExists($supplier->image, 'user') }}"
                                        style="border: 1px solid black;border-radius: 120px;width: 100px;height: 80px;">
                                </div>

                            </div>
                            <div class="">
                                <h4><a
                                        href="{{ route('supplier-detail', $supplier->uuid) }}">{{ isset($supplier->company->legal_name) ? $supplier->company->legal_name : $supplier->first_name . ' ' . $supplier->last_name }}</a>
                                </h4>
                                {{-- <p class="description">{{$supplier->gender}}</p> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="clearfix">&nbsp;</div>
    </section>
    @if (auth()->check())
        <section id="request-for-quotation" class="animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s">
            <div class="container animated wow fadeIn" data-wow-delay="0.7s" data-wow-duration="0.5s">
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="{{ route('request-quote') }}">Request for Quotation</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if (auth()->guest())
        <section id="foote-banner">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.7s"
                        data-wow-duration="0.5s"><a href="{{ route('become-supplier') }}"><img class="img-fluid"
                                src="{{ asset('assets/frontend/images/Become-Supplier.png') }}" width=""
                                height="" alt=""></a></div>
                    <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.9s"
                        data-wow-duration="0.5s"><a href="{{ route('become-driver') }}"><img class="img-fluid"
                                src="{{ asset('assets/frontend/images/TRANSPORTER.jpg') }}" width=""
                                height="" alt=""></a></div>
                    <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s"
                        data-wow-duration="0.5s"><a href="{{ route('become-vendor') }}"><img class="img-fluid"
                                src="{{ asset('assets/frontend/images/Become-Vender.png') }}" width=""
                                height="" alt=""></a></div>
                </div>
            </div>
        </section>
    @endif

    {{-- <section class="banners"> --}}
    {{-- <div class="container-fluid p-0"> --}}
    {{-- <div class="row m-0"> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-2.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- <div class="col-md-4 p-0"> --}}
    {{-- <a href="#"> --}}
    {{-- <img src="{{asset('assets/frontend/images/banner-3.jpg')}}" class="img-fluid"> --}}
    {{-- </a> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </section> --}}
@endsection


@section('footerScript')
    @if (is_null(session()->get('checkout_location')) || empty(session()->get('checkout_location')))
        <script>
            $(document).ready(function() {
                //$('#selectLocationModal').modal('show');
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {


            $(".home-zipcode-input").on("focus", function(e) {
                //e.preventDefault();
                //$('#selectLocationModal').modal('show');
                //$(".location-selector").focus();
                //return false;
            });

            $(".home-zipcode-input-button").on("click", function(e) {
                //e.preventDefault();
                //$('#selectLocationModal').modal('show');
                //$(".location-selector").focus();
                //return false;
            });

            setTimeout(function() {
                $('#hero').css('background-image',
                    'background-image:url({{ asset($banner) }}) !important');
            }, 5000);

        });
    </script>
@endsection
