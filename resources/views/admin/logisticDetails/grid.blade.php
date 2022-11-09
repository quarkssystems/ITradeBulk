{{-- /**

 * Created by MANAN-S-MOZAR.

 * User: Manan

 * Date: 16/07/20

 * Time: 06:42 PM

 */ --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            {{--            <a href="{{ route($redirectBackRoute) }}" class="btn btn-info">{{__('Back')}}</a> --}}
        </div>
    </div>
@endsection

@section('content')
    @include($navTab)


    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.helpers.dataGrid.pager')
                    <div class="table-responsive">
                        <table class="table ">
                            <thead class="thead-light">
                                {!! $dataGridTitle !!}
                            </thead>
                            <tbody>
                                <tr>
                                    {!! $dataGridSearch !!}
                                </tr>
                                @php($countStart = ($data->currentPage() - 1) * $data->perPage())
                                @foreach ($data as $datum)
                                    <tr>
                                        <td>{{ $countStart + $loop->iteration }}</td>
                                        <td>{{ $datum->name }}</td>
                                        <td>{{ $datum->phone }}</td>
                                        <td>{{ $datum->driving_licence }}</td>
                                        <td>{{ $datum->transport_type }}</td>
                                        <td>{{ $datum->vin_number }}</td>
                                        <td>{{ $datum->vehicle_type }}</td>
                                        <td>{!! $datum->switch !!}</td>
                                        <td>
                                            <a href="{{ // route('user.vehicle.edit', [$datum->uuid])
                                                route('admin.logistic-detail.edit', [
                                                    'user_uuid' => $user->uuid,
                                                    'logistic_detail' => $datum->uuid,
                                                ]) }}"
                                                class="btn btn-primary btn-small">Edit</a>
                                            <a class="btn btn-primary btn-small delete-item" href="javascript:;"
                                                data-form-id=".delete-form-{{ $datum->uuid }}"
                                                title="{{ __('Delete') }}">{{ __('Delete') }}</a>

                                            {!! Form::open([
                                                'route' => ["$route.destroy", 'user_uuid' => $user->uuid, 'logistic_detail' => $datum->uuid],
                                                'method' => 'DELETE',
                                                'class' => 'delete-form-' . $datum->uuid,
                                            ]) !!}

                                            {!! Form::close() !!}

                                            {{-- <a href=""
                            class="btn btn-primary btn-small">Delete</a> --}}
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

                        {!! $dataGridPagination !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerData')
    <script>
        $(document).on('click', '.onoff', function() {
            console.log($(this).data('conoff'));

            $.get("/admin/vehicleStatus/" +
                $(this).data('id'),
                function(data, status) {
                    // location.reload();
                });

        });
        $(document).ready(function() {
            // $('.status').onchange(function(){
            $(document).on('change', '.status', function() {
                // alert('test');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    }
                });
                var val = $(this).children("option:selected").val();
                var driver_id = $('.drivers').val();
                var order_id = $(this).data('id');
                // alert(uuid);
                swal({
                        title: "Order Logistic in Queue Status Change",
                        text: "Would you like to change Order Logisitc Status to " + val + "?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, change it!",
                        closeOnConfirm: true
                    },

                    function(inputValue) {
                        if (inputValue === false) {
                            location.reload();
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('/admin/orderLogisticQueueAccept') }}",
                                data: {
                                    _token: '<?php echo csrf_token(); ?>',
                                    val: val,
                                    order_id: order_id,
                                    driver_id: driver_id
                                },
                                success: function(data) {
                                    location.reload();
                                }
                            });
                        }
                    });



            });

        });
    </script>
@stop
