@extends('frontend.layouts.main')

@section('content')

    <section class="inner-banner before primary-bg bg-cover bg-bottom text-center about-page-banner" style="background-image:url({{asset($banner)}}) !important">

        <div class="container-fluid text-white">

            <div class="row">

                <div class="col-md-12">

                    <h1 class="text-uppercase font-weight-bold" style="color: #ffffff;">About Us</h1>

                    {{--  <p class="text-white">

                        Exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br>

						Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.

                    </p>  --}}

                </div>

            </div>

        </div>

    </section>

    <section class="spacer pt-4">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12 mb-4">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb pt-0">

                            <li class="breadcrumb-item"><a href="#">Home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">About Us</li>

                        </ol>

                    </nav>  

                </div>

            </div>

            <div class="row">

                <div class="col-md-12 csaboutblock">

                    {!! $cmsblock ? $cmsblock->content : "" !!}

                </div>

            </div>

        </div>

    </section> 

<section class="spacer bg-gray whyuscmsblock" style="background-image: url('/assets/frontend/images/about-2.jpg');background-repeat: no-repeat;background-position: right top;background-size: 470px;">
    <div class="container-fluid aboutUsContent">
    {!! $whyuscmsblock->content !!}
    </div>
</section>

    <section class="spacer our-team">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12 text-center">

                    <h2 class="mb-5"><b>Our Team</b></h2>

                </div>

                <div class="col-md-12">

                    <div class="row">

                    @foreach($teams as $team)
                        <div class="col-6 col-md-3 team">

                            <div class="image">

                                <img src="{{asset($team->coloured_image)}}" class="img-fluid">

                            </div>

                            <div class="text">

                                <div class="title">{{$team->first_name .' '.$team->last_name}}</div>
                                
                                <p>{{$team->designation}}</p>

                            </div>

                        </div>
                    @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="spacer ">

        <div class="container-fluid">

            <div class="row">

                @foreach($brands as $brand)
                    <div class="col-6 col-xl-2 col-lg-4 col-md-4 col-sm-6 animated wow fadeInLeftBig" data-wow-delay="0.2s" data-wow-duration="0.2s">
                        <div class="explore-prod">
                            <a href="#!">
                                <span class="exp-title"><img class="img-fluid" src="{{asset($brand->icon_file)}}" width="" height="" alt=""></span><div class="clearfix"></div>
                                <span class="text-center brandTitle">{{$brand->name}}</span >
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </section>

    @include('frontend.home.clienttestimonialsslider')

@endsection