@extends('supplier.layouts.main')

@section('page-header')
    <div class="container-fluid">

        <ol class="breadcrumb breadcrumb-style1">

            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>

            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>

        </ol>

        <div class="page-header">

            <div class="page-title">

                <h4>{{ $pageTitle }}</h4>

            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-12">

            @include('frontend.helpers.globalMessage.message')

        </div>

        <div class="col-md-12">

            <div class="content-header ">


                @if ($role == 'COMPANY' && $logistic_type == 'COMPANY')
                    <div class="row">

                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('supplier.drivers.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Transporter</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $companyTransporter }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-truck fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.companyPendingOrder') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Pending Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $pendingOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.companyCompletedOrder') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Completed Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $completedOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Total Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $totalOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($role = ('DRIVER' && $logistic_type == 'COMPANY') || $logistic_type == 'INDIVIDUAL')
                    <div class="row">

                        <div class="col-xl-4 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.pendingOrder') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Pending Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $pendingOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.completedOrder') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Completed Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $completedOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Total Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $totalOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->role == 'VENDOR')
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $vendorOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.fav-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Recent Order</b></h5>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-history fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.lastorder') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Latest  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p>
                 </div>
                 </div>
                 </div> -->
                    </div>
                @elseif(auth()->user()->role == 'SUPPLIER')
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Order</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $supplierOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <a href="{{ route('become-dispatcher') }}"><img class="img-fluid"
                                        src="{{ asset('assets/frontend/images/Become-Dispatcher.png') }}" width=""
                                        height="" alt=""></a>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <a href="{{ route('become-picker') }}"><img class="img-fluid"
                                        src="{{ asset('assets/frontend/images/Become-Picker.png') }}" width=""
                                        height="" alt=""></a>
                            </div>
                        </div> --}}


                        <!-- 	<div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.fav-orders.index') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Favorite  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 </div>
                 </div>
                        </div> -->
                        <!-- <div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.lastorder') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Latest  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p>
                 </div>
                 </div>
                 </div> -->
                    </div>
                @elseif(auth()->user()->role == 'PICKER')
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Order PICKER</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $pickerOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                        <!-- 	<div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.fav-orders.index') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Favorite  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 </div>
                 </div>
                        </div> -->
                        <!-- <div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.lastorder') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Latest  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p>
                 </div>
                 </div>
                 </div> -->
                    </div>
                @elseif(auth()->user()->role == 'DISPATCHER')
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <center>
                                                <a href="{{ route('user.sales-orders.index') }}">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"
                                                        style="color: white !important;"><b>Order Dispatcher</b></h5>
                                                    <span class="h2 font-weight-bold mb-0">{{ $dispatcherOrder }}</span>
                                                </a>
                                            </center>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape text-white">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p> -->
                                </div>
                            </div>
                        </div>
                        <!-- 	<div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.fav-orders.index') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Favorite  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 </div>
                 </div>
                        </div> -->
                        <!-- <div class="col-xl-3 col-lg-6">
                 <div class="card card-stats mb-4 mb-xl-0" style="background: black;color: white;">
                 <div class="card-body">
                 <div class="row">
                 <div class="col">
                 <center>
                 <a  href="{{ route('user.lastorder') }}"><h5 class="card-title text-uppercase text-muted mb-0" style="color: white !important;"><b>Latest  Order</b></h5>
                 </center>
                 </div>
                 <div class="col-auto">
                 <div class="icon icon-shape text-white">
                 <i class="fa fa-star fa-2x"></i>
                 </div>
                 </div>
                 </div>
                 <p class="mt-3 mb-0 text-muted text-sm">
                 <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                 <span class="text-nowrap">Since last month</span>
                 </p>
                 </div>
                 </div>
                 </div> -->
                    </div>
                @endif
                <!-- <div class="content-title ">
                                <div class="alert alert-warning">
                                    <h3 class="m-0">{{ __('Coming Soon') }}</h3>
                                </div>
                            </div> -->
            </div>
        </div>
    </div>



    <!-- The Modal -->

    <div class="modal" id="prevOrderModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- Modal Header -->

                <div class="modal-header">

                    <h4 class="modal-title">Would you like to go with previous order ?</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>



                <!-- Modal body -->

                <div class="modal-body">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <a href="{{ route('user.fav-orders.index') }}" class="btn btn-success">Ok</a>



                    </div>

                </div>



            </div>

        </div>

    </div>

    </div>
@endsection

@section('footerScript')
    @if ($role == 'VENDOR' && $vendorOrder > 0)
        <script>
            $(document).ready(function() {

                //alert('dd');

                $('#prevOrderModal').modal('show');

            });
        </script>
    @endif
@endsection
