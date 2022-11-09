{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
{{-- @include('admin.helpers.dataGrid.pager') --}}


<div class="table-responsive">
    <table class="table ">
        <thead class="thead-light">
        </thead>
        <tbody>
            <tr>
                <td>No.</td>
                <td>Order Id</td>
                <td>Trader</td>
                <td>Supplier</td>
                <td>Driver</td>
                <td>Total Amount</td>
                <td>Delivery Amount</td>
                <td>Total Weight</td>
                <td>Distance</td>


                <td>Status</td>
                <td>Action</td>
            </tr>
            @php($countStart = ($data->currentPage() - 1) * $data->perPage())
            @foreach ($data as $datum)
                <tr>
                    <td>{{ ++$countStart }}</td>
                    <td>{{ $datum->order_number }}</td>
                    <td>{{ $datum->trader_name }}</td>
                    <td>{{ $datum->suppliers_name }}</td>
                    <td>{{ Auth::user()->first_name }}</td>
                    <td>R {{ $datum->final_total }}</td>
                    <td>R {{ $datum->shipment_amount }}</td>
                    <td>{{ $datum->total_weight }}</td>
                    <td>{{ $datum->distance }}</td>
                    <td>{{ $datum->order_status }}</td>
                    <td>
                        <button type="button" class="btn btn-primary rejectDelivery"
                            data-id="{{ $datum->uuid }}">Reject</button>
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
