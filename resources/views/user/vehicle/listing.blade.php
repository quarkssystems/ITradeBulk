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
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a href="{{ route('user.vehicle.create') }}" class="btn btn-primary">Add Vehicle</a>

    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('frontend.helpers.globalMessage.message')
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

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
                                <td><a href="{{ route('user.vehicle.edit', [$datum->uuid]) }}"
                                        class="btn btn-primary btn-small">Edit</a>
                                    <a class="btn btn-primary btn-small delete-item" href="javascript:;"
                                        data-form-id=".delete-form-{{ $datum->uuid }}"
                                        title="{{ __('Delete') }}">{{ __('Delete') }}</a>

                                    {!! Form::open([
                                        'route' => ["$route.destroy", $datum->uuid],
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


            {{-- <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Phone</th>
                        <th>Driving Licence</th>
                        <th>Transport Type</th>
                        <th>Vehicle Type</th>
                        <th>ON/OFF</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logisticDetails as $Key => $details)
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
        </div>
    </div>
@endsection
@section('footerScript')
    @include('utils.map')
    <script>
        $(document).on('click', '.onoff', function() {
            console.log($(this).data('conoff'));

            $.get("/admin/vehicleStatus/" +
                $(this).data('id'),
                function(data, status) {
                    // location.reload();
                });

        });
    </script>
@endsection
