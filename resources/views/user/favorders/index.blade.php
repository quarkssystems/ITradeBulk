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

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">



                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <table class="table ">

                                <thead class="thead-light">

                                    <tr>

                                        <th>{{ __('No') }}</th>

                                        <th>{{ __('Order ID') }}</th>

                                        <th>{{ __('Amount') }}</th>

                                        <th>{{ __('Time') }}</th>

                                        <th>{{ __('Action') }}</th>



                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($recentOrders as $datum)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td><a
                                                    href="{{ route('user.sales-orders.edit', $datum->salesID) }}">#{{ $datum->order_number }}</a>
                                            </td>

                                            <td>{{ $datum->cart_amount }}</td>

                                            <td>{{ $datum->created_at->format('d-m-Y h:i a') }}</td>

                                            @if ($datum->uuid != null)
                                                <td><a href="<?php echo e(route('user.addorder', $datum->uuid)); ?>">Add to Cart</a></td>
                                            @else
                                                <td></td>
                                            @endif

                                        </tr>
                                    @endforeach

                                    @if ($recentOrders->count() == 0)
                                        <tr>

                                            <td colspan="11">

                                                <div class="alert alert-primary">{{ __('No data found') }}</div>

                                            </td>

                                        </tr>
                                    @endif

                                </tbody>



                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
