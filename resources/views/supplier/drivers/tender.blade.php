{{-- /**
 * Created by PhpStorm.
 * User: Mohit
 */ --}}

@extends('supplier.layouts.main')

@section('header')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __('Sales Order') }}</h1>
            {{-- <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p> --}}
            {{-- <a href="{{ route("$route.create") }}" class="btn btn-info">{{__('ADD Team')}}</a> --}}
            @if ($data->count() > 0)
            @endif



        </div>
    </div>
@endsection

@section('content')
    @if (Session::has('error_message'))
        <div class="alert alert-danger">
            {!! Session::get('error_message') !!}
        </div>
    @endif
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">

                <div class="card-body">
                    <div class="data-grid">

                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="thead-light">
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Order Id</td>
                                        <td>Delivery Tender Price</td>
                                        <td>Delivery Km (One Way)</td>
                                        <td>Volumn M3</td>
                                        <td>Pallets</td>
                                        <td>Payload (Kg)</td>
                                        <td>Scheduled Pickup Time</td>
                                        {{-- <td>Platinium</td>
                                        <td>Standard</td> --}}
                                        <td>Trading Area</td>
                                        <td>Vehicle</td>
                                        {{-- <td>Status</td> --}}
                                        <td>More Info</td>
                                        <td>Action</td>
                                    </tr>
                                    @php($countStart = ($data->currentPage() - 1) * $data->perPage())
                                    @foreach ($data as $datum)
                                        <tr>
                                            <td>{{ $datum['orderId'] }}</td>
                                            <td>R {{ $datum['tender_price'] }}</td>
                                            <td>{{ $datum['distance'] }}</td>
                                            <td>{{ $datum['body_volumn'] }}</td>
                                            </td>
                                            <td>{{ $datum['pallets'] }}</td>
                                            <td>{{ $datum['payload'] }}</td>
                                            <td>{{ $datum['scheduled_pickup'] }}</td>
                                            {{-- <td>10 mins</td>
                                            <td>30 mins</td> --}}
                                            <td>{{ $datum['town'] }}</td>
                                            <td>{{ $datum['vehicle'] }}</td>
                                            {{-- <td>{{ $datum['status'] }}</td> --}}
                                            <td> <button type="button" class="btn btn-primary btn-sm deliveryDetails mr-1"
                                                    data-id="{{ $datum['uuid'] }}" title="More Info">More Info</button>
                                            </td>
                                            <td style="display: flex;">

                                                <button type="button"
                                                    class="btn btn-primary btn-sm deliveryDetails mr-1 fa fa-check"
                                                    data-id="{{ $datum['uuid'] }}"
                                                    data-vehicle_id="{{ $datum['vehicle_id'] }}"></button>
                                                {{-- <form
                                                    action="{{ route('supplier.notification.accept', [$datum['uuid']]) }}"
                                                    method="POST" class="mr-1">
                                                    @csrf
                                                    <input type="hidden" name="logistic_details_id"
                                                        id="logistic_details_id" value="{{ $datum['vehicle_id'] }}">
                                                    <button type="submit" class="btn btn-primary btn-sm fa fa-check"
                                                        title="Accept"></button>
                                                </form> --}}
                                                {{-- <button type="button" class="btn btn-primary btn-sm acceptDelivery"
                                                    data-id="{{ $datum['uuid'] }}">Accept</button> --}}
                                                <button type="button"
                                                    class="btn btn-primary btn-sm rejectDelivery fa fa-ban"
                                                    data-id="{{ $datum['uuid'] }}" title="Reject"></button>
                                            </td>

                                        </tr>
                                    @endforeach
                                    @if ($data->count() == 0)
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
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <h4 class="modal-title">Reason</h4>

                        <form action="" method="post" class="rejectUrl">
                            @csrf
                            <textarea name="reason" id="" cols="30" rows="10" class="form-control" required></textarea>
                            <button type="submit" class="btn btn-primary form-control">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="acceptmyModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <h4 class="modal-title">Delivery Date and Time</h4>

                        <form action="" method="post" class="acceptUrl">
                            @csrf
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">From Time</label>
                                <input type="time" name="from_time" class="form-control" min="00:00" max="24:00"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="">To Time</label>
                                <input type="time" name="to_time" class="form-control" min="00:00" max="24:00"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary form-control">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deliveryDetailsModal" role="dialog">
        <div class="modal-dialog fullsizepopdialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="thead-light">
                                    <tr>
                                        <td>Order Id</td>
                                        <td>Total Amount</td>
                                        <td>Delivery Amount</td>
                                        <td>Pickup Address</td>
                                        <td>Drop Address</td>
                                        <td>Distance (km)</td>
                                        <td>Scheduled Pickup Time</td>
                                        <td>Trader</td>
                                        <td>Supplier</td>
                                        {{-- <td>Driver</td> --}}
                                        {{-- <td>Total Weight</td> --}}
                                        {{-- <td>Status</td> --}}
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody class="addDeliveryDetails">

                                    {{-- <tr>
                                         <td>{{ $data->orderId }}</td>
                                        <td>{{ $data->trader_name }}</td>
                                        <td>{{ $data->suppliers_name }}</td>
                                        <td>{{ Auth::user()->first_name }}</td>
                                        <td>R {{ $data->final_total }}</td>
                                        <td>R {{ $data->shipment_amount }}</td>
                                        <td>{{ $data->total_weight }}</td>
                                        <td>{{ $data->distance }}</td>
                                        <td>{{ $data->order_status }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary rejectDelivery"
                                                data-id="{{ $data->uuid }}">Reject</button>
                                        </td> 

                                    </tr> --}}
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    </div>
@endsection
@section('footerScript')
    <script>
        $(document).on('click', '.rejectDelivery', function() {
            // console.log('hi');
            var order_id = $(this).data('id');
            let url = `/supplier/notification_reject/` + order_id;
            //    console.log('hi',url);

            $('#deliveryDetailsModal').modal("hide");
            $('#myModal').modal("show");
            $('.rejectUrl').attr('action', url);
        })

        $(document).on('click', '.acceptDelivery', function() {
            // console.log('hi');
            var order_id = $(this).data('id');
            let url = `/supplier/notification_accept/` + order_id;
            //    console.log('hi',url);

            $('#acceptmyModal').modal("show");
            $('.acceptUrl').attr('action', url);
        })

        $(document).on('click', '.deliveryDetails', function() {

            console.log('ftuyguh: ', $(this).data('id'), $(this).data('vehicle_id'));
            $('.addDeliveryDetails').empty();
            let vehicle_id = $(this).data('vehicle_id');

            $.ajax({
                type: "GET",
                url: "/getDeliveryData/" + $(this).data('id'),
                // data: myusername,
                // cache: false,
                success: function(data) {
                    console.log('data: ', data, vehicle_id);

                    // $("#resultarea").text(data);

                    // <td>Order Id</td>
                    // <td>Total Amount</td>
                    // <td>Delivery Amount</td>
                    // <td>Pickup Address</td>
                    // <td>Drop Address</td>
                    // <td>Distance(km)</td>
                    // <td>Scheduled Pickup Time</td>
                    // <td>Trader</td>
                    // <td>Supplier</td>
                    // <td>Action</td>

                    if (data != null) {
                        let uuid = data.uuid;
                        $('.addDeliveryDetails').append(`
                    <tr>
                        <td>${(data.order_number != null) ? data.order_number : '-'} </td>
                        <td>${(data.final_total != null) ? 'R '+data.final_total : '-'} </td>
                        <td>${(data.shipment_amount != null) ? 'R '+data.shipment_amount : '-'} </td>
                        <td>${(data.supplierAddress != null) ? data.supplierAddress : '-'} </td>
                        <td>${(data.traderAddress != null) ? data.traderAddress : '-'} </td>
                        <td>${(data.distance != null) ? data.distance : '-'} </td>
                        <td>${(data.scheduled_pickup != null) ? data.scheduled_pickup : '-'} </td>
                        <td>${(data.trader_name != null) ? data.trader_name : '-'} </td>
                        <td>${(data.suppliers_name != null) ? data.suppliers_name : '-'} </td>
                      <td style="display: flex;">
                            <form
                                action="/supplier/notification_accept/${uuid}"
                                method="POST" class="mr-1">
                                @csrf
                                <input type="hidden" name="logistic_details_id"
                                                        id="logistic_details_id" value="${vehicle_id}">
                                <button type="submit" class="btn btn-primary btn-sm fa fa-check"
                                    title="Accept"></button>
                            </form>
                           
                            <button type="button"
                                class="btn btn-primary btn-sm rejectDelivery fa fa-ban"
                                data-id="${uuid}" title="Reject"></button>
                            </td>

                    </tr> 
                    `);

                    }

                },
                error: function(jqXHR, exception) {

                },
            });
            $('#deliveryDetailsModal').modal("show");
        })
    </script>
@endsection
