{{-- /**
 * Created by PhpStorm.
 * User: Mohit
 */ --}}

@extends('supplier.layouts.main')
@section('styles')
@endsection

@section('header')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __('Sales Order') }}</h1>




        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{-- <div id='calendar'></div> --}}

            {{-- <a href="{{ route("$route.create") }}" class="btn btn-info">{{__('ADD TRANSPORTER')}}</a> --}}
            <div class="card">

                <div class="card-body">
                    <div class="data-grid">

                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="thead-light">
                                    {{-- {!! $dataGridTitle !!} --}}
                                </thead>
                                <tbody>
                                    <tr>
                                        {{-- {!! $dataGridSearch !!} --}}
                                        <td style="min-width: 160px !important;">#</td>
                                        <td style="min-width: 160px !important;">Name</td>
                                        <td style="min-width: 160px !important;">Vehicle</td>
                                        <td style="min-width: 160px !important;">Vin Number</td>
                                        <td style="min-width: 160px !important;">Total Amount</td>
                                        <td style="min-width: 160px !important;">Delivery Amount</td>
                                        <td style="min-width: 160px !important;">Pickup Address</td>
                                        <td style="min-width: 160px !important;">Drop Address</td>
                                        <td style="min-width: 160px !important;">Distance (km)</td>
                                        <td style="min-width: 160px !important;">Scheduled Pickup Time</td>
                                        <td style="min-width: 160px !important;">More Info</td>
                                        <td style="min-width: 160px !important;">Action</td>


                                        {{-- <td style="min-width: 160px !important;">#</td>
                                        <td style="min-width: 160px !important;">Name</td>
                                        <td style="min-width: 160px !important;">Phone</td>
                                        <td style="min-width: 160px !important;">Driving Licence No</td>
                                        <td style="min-width: 160px !important;">Vehicle</td>
                                        <td style="min-width: 160px !important;">Vehicle Model</td>
                                        <td style="min-width: 160px !important;">Vin Number</td>
                                        <td style="min-width: 160px !important;">Vehicle Color</td>
                                        <td style="min-width: 160px !important;">More Info</td>
                                        <td style="min-width: 160px !important;">Action</td> --}}


                                    </tr>
                                    @php($countStart = ($data->currentPage() - 1) * $data->perPage())
                                    @foreach ($data as $datum)
                                        <tr>

                                            <td>{{ $countStart + $loop->iteration }}</td>
                                            <td>{{ $datum->name }} </td>
                                            <td>{{ $datum->vehicle_type }} </td>
                                            <td>{{ $datum->vin_number }} </td>
                                            <td>{{ $datum->final_total }} </td>
                                            <td>{{ $datum->shipment_amount }} </td>
                                            @if (gettype($datum->supplierAddress) == 'array')
                                                <td>{{ implode(' ', $datum->supplierAddress) }}</td>
                                            @else
                                                <td> {{ $datum->supplierAddress }}</td>
                                            @endif
                                            @if (gettype($datum->traderAddress) == 'array')
                                                <td> {{ implode(' ', $datum->traderAddress) }}</td>
                                            @else
                                                <td> {{ $datum->traderAddress }}</td>
                                            @endif
                                            {{-- <td>{{ typeof($datum->traderAddress) == 'array' ? implode(' ', $datum->traderAddress) : '' }}
                                            </td> --}}
                                            <td>{{ $datum->distance }} </td>
                                            <td>{{ $datum->scheduled_pickup }} </td>

                                            <td> <button type="button" class="btn btn-primary btn-sm deliveryDetails mr-1"
                                                    data-id="{{ $datum->order_id }}" data-uuid="{{ $datum->uuid }}"
                                                    data-vehicle={{ $datum->vehicle_id }} title="More Info">More
                                                    Info</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary rejectDelivery"
                                                    data-id="{{ $datum->order_id }}"
                                                    data-uuid="{{ $datum->uuid }}">Reject</button>
                                            </td>

                                            {{-- <td>{{ $countStart + $loop->iteration }}</td>
                                            <td>{{ $datum->name }} </td>
                                            <td>{{ $datum->phone }} </td>
                                            <td>{{ $datum->driving_licence }} </td>
                                            <td>{{ $datum->vehicle_type }} </td>
                                            <td>{{ $datum->vehicle_model }} </td>
                                            <td>{{ $datum->vin_number }} </td>
                                            <td>{{ $datum->vehicle_color }} </td>
                                            <td> <button type="button" class="btn btn-primary btn-sm deliveryDetails mr-1"
                                                    data-id="{{ $datum->order_id }}" data-vehicle={{ $datum->vehicle_id }}
                                                    title="More Info">More Info</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary rejectDelivery"
                                                    data-id="{{ $datum->order_id }}">Reject</button>
                                            </td> --}}
                                        </tr>
                                    @endforeach


                                </tbody>

                            </table>
                            {!! $data->links() !!}

                            {{-- {!! $dataGridPagination !!} --}}
                        </div>
                    </div>
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
                                        <td>Phone</td>
                                        <td>Driving Licence No</td>
                                        <td>Vehicle Model</td>
                                        <td>Vehicle Color</td>
                                        <td>Trader</td>
                                        <td>Supplier</td>

                                        {{-- <td>Total Amount</td>
                                        <td>Delivery Amount</td>
                                        <td>Pickup Address</td>
                                        <td>Drop Address</td>
                                        <td>Distance (km)</td>
                                        <td>Scheduled Pickup Time</td> --}}
                                    </tr>
                                </thead>
                                <tbody class="addDeliveryDetails">


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
                            <textarea name="reason" id="" cols="30" rows="10" class="form-control"></textarea>
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
    </div>
@endsection
@section('footerScript')
    <script>
        $(document).ready(function() {

            var SITEURL = "{{ url('/') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                defaultView: 'agendaWeek',
                events: SITEURL + "/supplier/delivery_schedule",
                displayEventTime: false,
                editable: false,
                events: function(start, end, timezone, callback) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                    $.ajax({
                        type: 'GET',
                        url: SITEURL + "/supplier/delivery_schedule",
                        data: {},
                        dataType: 'json',
                        success: function(data) {
                            console.log('data: ', data);
                            var events = [];
                            $(data).each(function(key, val) {
                                console.log('val: ', val);

                                // start: $(this).attr('start_date'),
                                //       end: $(this).attr('end_date'),
                                events.push({
                                    id: val.id,
                                    title: val.start_date + ' to ' + val
                                        .end_date,
                                    start: val.start_date,
                                    end: val.end_date,
                                });
                            });
                            callback(events);
                        },
                        error: function(data) {
                            console.log(data);
                            //   alert("Ajax call error");
                            return false;
                        },
                    });
                },
                eventRender: function(event, element, view) {

                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,


            });

        });

        function displayMessage(message) {
            toastr.success(message, 'Event');
        }

        $(document).on('click', '.deliveryDetails', function() {

            console.log('ftuyguh: ', $(this).data('id'), $(this).data('vehicle'));
            $('.addDeliveryDetails').empty();

            $.ajax({
                type: "GET",
                // url: "/getAllDeliverySchduleData/" + $(this).data('vehicle'),
                url: "/getDeliverySchduleData/" + $(this).data('uuid'),
                // url: "/getDeliverySchduleData/" + $(this).data('vehicle'),
                success: function(data) {
                    console.log('data: ', data);

                    if (data != null) {
                        $('.addDeliveryDetails').append(`
        <tr>
            <td>${(data.order_number != null) ? data.order_number : '-'} </td>
            <td> ${(data.phone != null) ? data.phone : '-'}  </td>
            <td> ${(data.driving_licence != null) ? data.driving_licence : '-'}  </td>
            <td> ${(data.vehicle_model != null) ? data.vehicle_model : '-'}  </td>
            <td> ${(data.vehicle_color != null) ? data.vehicle_color : '-'}  </td>
            <td>${(data.trader_name != null) ? data.trader_name : '-'} </td>
            <td>${(data.suppliers_name != null) ? data.suppliers_name : '-'} </td>
          
        </tr> 
        `);
                    }

                    //             $('.addDeliveryDetails').empty();
                    //             $.each(datas, function(key, data) {
                    //                 if (data != null) {
                    //                     $('.addDeliveryDetails').append(`
                // <tr>
                //     <td>${(data.order_number != null) ? data.order_number : '-'} </td>
                //     <td> ${(data.phone != null) ? data.phone : '-'}  </td>
                //     <td> ${(data.driving_licence != null) ? data.driving_licence : '-'}  </td>
                //     <td> ${(data.vehicle_model != null) ? data.vehicle_model : '-'}  </td>
                //     <td> ${(data.vehicle_color != null) ? data.vehicle_color : '-'}  </td>
                //     <td>${(data.trader_name != null) ? data.trader_name : '-'} </td>
                //     <td>${(data.suppliers_name != null) ? data.suppliers_name : '-'} </td>

                // </tr> 
                // `);
                    //                 }

                    //             })
                    // <td>Order Id</td>
                    //                     <td>Phone</td>
                    //                     <td>Driving Licence No</td>
                    //                     <td>Vehicle Model</td>
                    //                     <td>Vehicle Color</td>
                    //                     <td>Trader</td>
                    //                     <td>Supplier</td>





                    // <td>${(data.order_number != null) ? data.order_number : '-'} </td>
                    //     <td>${(data.final_total != null) ? 'R '+data.final_total : '-'} </td>
                    //     <td>${(data.shipment_amount != null) ? 'R '+data.shipment_amount : '-'} </td>
                    //     <td>${(data.supplierAddress != null) ? data.supplierAddress : '-'} </td>
                    //     <td>${(data.traderAddress != null) ? data.traderAddress : '-'} </td>
                    //     <td>${(data.distance != null) ? data.distance : '-'} </td>
                    //     <td>${(data.scheduled_pickup != null) ? data.scheduled_pickup : '-'} </td>
                    //     <td>${(data.trader_name != null) ? data.trader_name : '-'} </td>
                    //     <td>${(data.suppliers_name != null) ? data.suppliers_name : '-'} </td>


                },
                error: function(jqXHR, exception) {

                },
            });
            $('#deliveryDetailsModal').modal("show");
        })
        $(document).on('click', '.rejectDelivery', function() {
            // console.log('hi');
            var order_id = $(this).data('uuid');
            // var order_id = $(this).data('id');
            let url = `/supplier/notification_reject_after_accept/` + order_id;
            //    console.log('hi',url);

            $('#myModal').modal("show");
            $('.rejectUrl').attr('action', url);
        })
    </script>
@endsection
