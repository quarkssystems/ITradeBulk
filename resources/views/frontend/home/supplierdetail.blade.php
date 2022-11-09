@extends('frontend.layouts.main')
@section('content')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/supplier.css') }}" />

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <!-- Slide One - Set the background image for this slide in the line below -->
            @foreach ($banners as $banner)
                <?php
                $a = '';
                $b = $banner->image ?? '';
                $a = asset($b);
                ?>
                <div class="carousel-item active" style="background-image: url('<?php echo $a; ?>')">
                    <div class="carousel-caption d-none d-md-block">EXPLORE PRODUCTS FROM PREMIUM BRANDS
                        <h2 class="display-4">{{ $banner->name }}</h2>
                        <p class="lead">{{ $banner->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    </header>
    <section>
        <div id="suppliers_detail">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div class="suppliers_img">
                            <img src="{{ asset(!empty($supplier->image) ? $supplier->image : 'uploads/user.png') }}">
                        </div>
                        <ul class="social_list">
                            @if (isset($suppliers->facebook_url) && !empty($suppliers->facebook_url))
                                <li><a href="{{ $suppliers->facebook_url }}"><i class="fa fa-facebook"
                                            aria-hidden="true"></i></a></li>
                            @else
                                <li><a href="javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            @endif
                            @if (isset($suppliers->twitter_url) && !empty($suppliers->twitter_url))
                                <li><a href="{{ $suppliers->twitter_url }}"><i class="fa fa-twitter"
                                            aria-hidden="true"></i></a></li>
                            @else
                                <li><a href="javascript:void(0)"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            @endif
                            @if (isset($suppliers->insta_url) && !empty($suppliers->insta_url))
                                <li><a href="{{ $suppliers->insta_url }}"><i class="fa fa-instagram"
                                            aria-hidden="true"></i></a></li>
                            @else
                                <li><a href="javascript:void(0)"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            @endif
                            <!--<li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>-->

                        </ul>
                    </div>
                    <div class="col-md-8">

                        <div class="suppliers_info">
                            <span class="subheading"></span>
                            <h1>
                                @if (isset($suppliers->company->legal_name))
                                    {{ $suppliers->company->legal_name }}
                                @endif
                                <span>
                                    @if (isset($suppliers->company->business_type))
                                        {{ $suppliers->company->business_type }}
                                    @endif
                                </span>
                            </h1>
                            <h3>@php isset($successStory->title) ? $successStory->title :  'Success story' @endphp </h3>
                            <h4>{{ $successStory->title ?? '' }}</h4>
                            {{ $successStory->description ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cls-both">&nbsp;</div>
        <p>&nbsp;</p>
        </div>
    </section>
    <section class="">
        <div class="container-fluid">
            <div class="row supplier-row p-3 mb-5">
                <div class="col-md-3 col-sm-3 col-xs-12 col-lg-3 text-center">
                    <h1>Are you a supplier?</h1>
                </div>
                @php
                    $adminEmail = 'mailto:' . $admin_email;
                @endphp
                <div class="col-md-9 col-sm-9 col-xs-12 col-lg-9">
                    <p class="join-us-coa">Join {{ env('WEBSITE') }} as a verified supplier and connect with traders around
                        the world Any questions, email us at <a href="{{ $adminEmail }}">{{ $admin_email }}</a>
                    <p>
                        <a href="{{ route('become-supplier') }}" class="btn btn-warning">Join us</a>
                </div>
            </div>
        </div>
    </section>
@endsection
