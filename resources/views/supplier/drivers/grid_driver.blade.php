{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
{{-- @include('admin.helpers.dataGrid.pager') --}}


<div class="table-responsive">
    <a href="{{route('supplier.notification.readAll')}}" class="btn btn-primary btn-small" style="float: right;">Mark All As Read</a>
    <table class="table ">
        <thead class="thead-light">
            {{-- {!! $dataGridTitle !!} --}}
        </thead>
        <tbody>
            <tr>
                {{-- {!! $dataGridSearch !!} --}}
                <td>No.</td>
                {{-- <td>Order Id</td> --}}
                <td>Notification</td>
                {{-- <td>Delivery Amount</td>
                <td>Total Weight</td>
                <td>Distance</td>
                <td>Lead Date and Time</td> --}}
                <td>Status</td>
                <td>Action</td>
            </tr>
            @php($countStart = ($data->currentPage() - 1) * $data->perPage())
            {{-- {{dd($data)}} --}}
            @foreach ($data as $datum)
          
            {{-- {{dd($datum)}} --}}
                <tr>
                    <td>{{ ++$countStart }}</td>
                    {{-- <td>{{ $datum->order_number }}</td> --}}
                    {{-- <td>{{ $datum->id }}</td> --}}

                    <td>{!! $datum->notification !!}</td>
                    {{-- <td>
                                    <form action="" method="post" class="acceptUrl">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="date" name="date" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">From Time</label>
                                            <input type="time" name="from_time" class="form-control" min="00:00"
                                                max="24:00" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">To Time</label>
                                            <input type="time" name="to_time" class="form-control" min="00:00"
                                                max="24:00" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary form-control">Accept</button>
                                    </form>

                                </td>
                                <td><button type="button" class="btn btn-primary rejectDelivery"
                                        data-id="'.$value->order_id.'">Reject</button></td> --}}

                    {{-- <td>R {{ $datum->delivery_amount }}</td>
                    <td>{{ $datum->total_weight }}</td>
                    <td>{{ $datum->distance }}</td>
                    <td>{{ $datum->dateAndTime }}</td> --}}
                    <td>{{ $datum->status }}</td>
                    @if ($datum->status == 'UNREAD')
                    <td><a href="{{route('supplier.notification.read',[$datum->uuid])}}" class="btn btn-small" style="background:#ac8a4a;color:white;">Mark As Read</a></td>
                    @else
                    <td></td>
                    @endif
                   
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
    {{ $data->links() }}
    {{-- {!! $dataGridPagination !!} --}}
</div>
