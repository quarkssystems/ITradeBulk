@extends('frontend.layouts.main')

@section('content')
    <section class="inner-banner before primary-bg bg-cover bg-bottom text-center contact-page-banner">

        <div class="container-fluid text-white">

            <div class="row">

                <div class="col-md-12" style="color:black">

                    
                </div>

            </div>

        </div>

    </section>
    <section class="spacer">
        <div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 pages">
                    <h5 class="text-uppercase font-weight-bold" style="color: #000">{{ucfirst($cmsData['name'])}}</h5>
                    {!! $cmsData['content'] !!}
                    </div>
                </div>
            </div>
        </div>
    </section>    

@endsection
