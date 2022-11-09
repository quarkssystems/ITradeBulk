@extends('frontend.layouts.main')
@section('content')
    <section class="inner-banner big before bg-contain bg-right request-quote-banner supplier-banner"  style="background-image:url({{asset($banner)}}) !important">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3>Suppliers</h3>
                    <h3>FIND NEAR YOU</h3>
                    <a href="#supplierForm" class="btn btn-primary my-2 my-sm-0">GET A QUOTE</a>
                </div>
            </div>
        </div>
    </section>
    <section class="spacer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="image">
                            <img src="{{asset('assets/frontend/images/icon-5.png')}}">
                        </div>
                        <h2>
                        GET THE BEST QUOTE <b> ON YOUR REQUEST </b>
                        </h2>
                        <p>Connecting with thousands of top suppliers is simple!</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="image">
                            <img src="{{asset('assets/frontend/images/icon-6.png')}}">
                        </div>
                        <h2> FIND THE RIGHT SUPPLIER <b>NEAR YOU</b>
                        </h2>
                        <p>Quickly get quotes from multiple suppliers in one place</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="image">
                            <img src="{{asset('assets/frontend/images/icon-7.png')}}">
                        </div>
                        <h2>
                            Close deal with <b>One Click</b>
                        </h2>
                        <p>it's never been easier to select the right supplier</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="spacer bg-dark bottom-pull request-quote-section-2" id="supplierForm">
        <div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-white">
                        <h3>Tell suppliers what you need</h3>
                        <h5>Complete Your RFQ</h5>
                        <p>The more specific your information, the more accurately we can match your request to the right suppliers</p>
                    </div>
                </div>
                <form action="{{ action('FrontendHomeController@requestQuotePost') }}" method="POST" enctype="multipart/form-data">
                    {!! Form::token() !!}
                    <div class="row text-white">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                            @if ($errors->has('name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="number" id="number" placeholder="Phone number">
                            @if ($errors->has('number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                @if ($errors->has('email'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <textarea class="form-control" name="message" id="message" placeholder="Dear Sir/Mam &#10; I am looking for..."></textarea>
                            @if ($errors->has('message'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('message') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <input type="file" name="attachment" id="attachment">
                            @if ($errors->has('attachment'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('attachment') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="terms_condition" value="1">I have read, understood and agreed to abide by Terms and Conditions Governing RFQ
                                @if ($errors->has('terms_condition'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('terms_condition') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 op1">
                            <button class="btn btn-primary my-2 my-sm-0 btn-block " type="submit">Submit Request for Quotation</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section id="foote-banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.7s" data-wow-duration="0.5s"><a href="{{route("become-supplier")}}"><img class="img-fluid" src="{{asset("assets/frontend/images/Become-Supplier.png")}}" width="" height="" alt=""></a></div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.9s" data-wow-duration="0.5s"><a href="{{route("become-driver")}}"><img class="img-fluid" src="{{asset("assets/frontend/images/Become-Driver-1.png")}}" width="" height="" alt=""></a></div>
                <div class="col-md-4 col-sm-12 p-0 animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s"><a href="{{route("become-vendor")}}"><img class="img-fluid" src="{{asset("assets/frontend/images/Become-Vender.png")}}" width="" height="" alt=""></a></div>
                
            </div>
        </div>
    </section>
@endsection
