@extends('frontend.layouts.main')
@section('content')
    <section id="hero" class="animated  fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s"
        style="background-image:url({{ asset($banner) }}) !important; padding: 210px 0; min-height: 480px; height: 480px;">
        <div class="container animated wow fadeIn" data-wow-delay="0.7s" data-wow-duration="0.5s">
            <div class="row">
                <div class="col-12 text-center">

                </div>
            </div>
        </div>
    </section>
    <section id="foote-banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.7s" data-wow-duration="0.5s"><a
                        href="{{ route('become-supplier') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/Become-Supplier.png') }}" width="" height=""
                            alt=""></a></div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.9s" data-wow-duration="0.5s"><a
                        href="{{ route('become-driver') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/Become-Driver-1.png') }}" width="" height=""
                            alt=""></a></div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s"><a
                        href="{{ route('become-vendor') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/Become-Vender.png') }}" width="" height=""
                            alt=""></a></div>
            </div>
            {{-- <div class="row">
                <div class="col-md-2 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s">
                </div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" placeholder="become-picker"
                    data-wow-duration="0.5s"><a href="{{ route('become-picker') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/Become-Picker.png') }}" width="" height=""
                            alt=""></a></div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s"><a
                        href="{{ route('become-dispatcher') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/Become-Dispatcher.png') }}" width="" height=""
                            alt=""></a></div>
                <div class="col-md-2 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s">
                </div>

            </div> --}}
    </section>
    <section class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-muted">
                        <h1 class="text-center">How it works</h1>
                        <div class="step-container three">
                            <div class="step">
                                <span class="label">1</span>
                                <div class="description">Register with {{ env('APP_NAME') }}</div>
                            </div>
                            <div class="step">
                                <span class="label">2</span>
                                <div class="description">Place an order</div>
                            </div>
                            <div class="step">
                                <span class="label">3</span>
                                <div class="description">Get your Goods delivered at your door step</div>
                            </div>
                        </div>
                        <!-- <div style="text-align: center; margin-top: 60px">
                                            <a href="{{ route('request-quote') }}" target="_blank" class="button">Get an instant online quote</a>
                                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <section class="main-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <h4>{{ __('FAQ') }}</h4>
                                    @include('frontend.auth.register-faq')
                                </div>
                            </div>
                        </div>
                    </section> -->
@endsection
@section('footerScript')
    @include('utils.map')
@endsection
