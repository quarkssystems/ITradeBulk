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
            {{-- <a href="{{ route("$route.create") }}" class="btn btn-info">{{__('ADD TRANSPORTER')}}</a> --}}
            <div class="card">

                <div class="card-body">
                    <div class="data-grid">
                        @include('supplier.drivers.grid_driver')
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

    </div>
@endsection
@section('footerScript')
    <script>
        $(document).on('click', '.rejectDelivery', function() {
            // console.log('hi');
            var order_id = $(this).data('id');
            let url = `/supplier/notification_reject/` + order_id;
            //    console.log('hi',url);

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
    </script>
@endsection
