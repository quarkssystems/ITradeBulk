@extends('frontend.layouts.main')
@section('content')
    <section class="spacer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{__('Thank you for your order!')}}</b></h3>
                    <p>Here's your confirmation for order number #{{$orderNumber}}.</p>
                    <p>Review your order <a href="{{ route('user.sales-orders.edit', $order->uuid) }}">#{{$orderNumber}}</a> for more details.</p>
                   
                        <p><a href="{{route('user.sales-orders.index')}}" class="btn btn-success">Review Order</a><a href="{{ route('user.sales-orders.edit', $order->uuid) }}" class="btn btn-success ml-2">Track my Order</a></p>
                   
                </div>

                </div>
            </div>
        </div>
    </section>
@endsection