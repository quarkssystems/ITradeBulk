@extends('frontend.layouts.main')

@section('content')
    <section class="inner-banner before primary-bg bg-cover bg-bottom text-center contact-page-banner" style="background-image:url({{asset($banner)}}) !important">

        <div class="container-fluid text-white">

            <div class="row">

                <div class="col-md-12">

                    <h1 class="text-uppercase font-weight-bold" style="color: #ffffff">Feedback</h1>

                    <p class="text-white">

                        Support is the sound of the future.

                    </p>

                </div>

            </div>

        </div>

    </section>



    <section class="pt-4">

        <div class="container-fluid" >

            <div class="row">

                <div class="col-md-12 mb-2">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb pt-0 pb-0">

                            <li class="breadcrumb-item"><a href="#">Home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Feedback</li>

                        </ol>

                    </nav> 

                </div>

            </div> 

        </div> 
        <div class="container-fluid  contact-section-3" style="background-color: #EAEAEA">

            <div class="row row-eq-height">

                <div class="col-md-12 col-lg-7  form" >

                    <h2 class="mb-4"><b>FEEL FREE TO DROP US A FEEDBACK</b></h2>

                    {{--  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>  --}}



                    <form class="mt-5" id="contact-form" action="{{route("feedback-submit")}}" method="post">
                    @csrf
                        <div class="container-fluid no-padding">

                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <input type="text" class="form-control" name="first_name" placeholder="First name">
                                    @if ($errors->has('first_name'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </small>
                                    @endif

                                </div>

                                <div class="col-md-6">

                                    <input type="text" class="form-control" name="last_name" placeholder="Last name">
                                    @if ($errors->has('last_name'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </small>
                                    @endif
                                </div>

                            </div>

                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <input type="text" class="form-control" name="email" placeholder="Email">
                                    @if ($errors->has('email'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject">
                                    @if ($errors->has('subject'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" placeholder="Message"></textarea>
                                    @if ($errors->has('message'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-theme my-2 my-sm-0 " type="submit">Submit Feedback</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="col-md-12 col-lg-5 " style="background-color:#D6DCE4">
                    <div class="row">
                        {!! $cmsblock ? $cmsblock->content : "" !!}
                    </div>
                </div> --}}
            </div>
        </div>

    </section>

      @include('frontend.home.clienttestimonialsslider')

@endsection
