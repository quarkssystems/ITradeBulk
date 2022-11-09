<div class="table-responsive">
    <table class="table ">
        <thead class="thead-light"> {!! $dataGridTitle !!}
        </thead>
        <tbody>
            <tr>
                {!! $dataGridSearch !!}
                {{--                                                 
                <th>No.</th>
                <th>Product Name</th>
                <th>Barcode</th>
                <th>Vat</th>
                <th>Cost</th>
                <th>Markup</th>
                <th>Auto Price</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Min Order Quantity</th>
                <th>Stock Expiry Date</th> --}}

            </tr>
            @php($countStart = ($data->currentPage() - 1) * $data->perPage())
            @foreach ($data as $datum)
                <tr>
                    <td>{{ $countStart + $loop->iteration }}
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item"
                                    href="{{ route('admin.supplier-fact', [$datum->uuid, $datum->user_id]) }}"
                                    title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </td>
                    {{-- <td>{!! $datum->base_image_data !!}</td> --}}
                    {{-- <td>{{ $datum->barcode }}</td> --}}
                    {{-- <td>{{ $datum->name }}</td>
                    <td>{{ $datum->slug }}</td>
                    <td>{{ $datum->brand_name }}</td>
                    <td>{{ $datum->UserName }}</td>
                    <td>{{ $datum->status }}</td> --}}
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->barcode }}</td>
                    <td>{{ $datum->vat != null ? $datum->vat : '-' }}</td>
                    <td>R {{ $datum->cost != null ? $datum->cost : '0' }}</td>
                    <td>{{ $datum->markup != null ? $datum->markup : '-' }}</td>
                    <td>R {{ $datum->autoprice != null ? $datum->autoprice : '0' }}</td>
                    <td>R {{ $datum->price != null ? $datum->price : '0' }}</td>
                    <td>{{ $datum->quantity != null ? $datum->quantity : '-' }}</td>
                    <td>{{ $datum->min_order_quantity != null ? $datum->min_order_quantity : '-' }}
                    </td>
                    <td>{{ $datum->stock_expiry_date != null ? $datum->stock_expiry_date : '-' }}
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

    {{-- {!! $data->links() !!} --}}
</div>
