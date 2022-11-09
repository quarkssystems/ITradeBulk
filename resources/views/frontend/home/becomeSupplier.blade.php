@extends('frontend.layouts.main')
@section('content')
    <section class="banners">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-md-12 p-0">
                    <img src="{{asset('assets/frontend/images/become-supplier.png')}}" style="width:100%">
                </div>
            </div>
        </div>
    </section>

    <section class="banners">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-md-4 p-0">
                    <a href="#">
                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-4 p-0">
                    <a href="#">
                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-4 p-0">
                    <a href="#">
                        <img src="{{asset('assets/frontend/images/banner-1.jpg')}}" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection