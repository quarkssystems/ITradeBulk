<div class="table-responsive">
    <table class="table ">
        <thead class="thead-light">
            {!! $dataGridTitle !!}
        </thead>
        <tbody>
            <tr>
                {!! $dataGridSearch !!}
                {{-- <th>Action</th>
                <th>Supplier Name</th>
                <th>Fact</th>
                <th>Product</th> --}}
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
                                <a class="dropdown-item" href="{{ route('admin.fact-list', $datum->uuid) }}"
                                    title="{{ __('View Facts') }}">{{ __('View Facts') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.product-list', $datum->uuid) }}"
                                    title="{{ __('View Products') }}">{{ __('View Products') }}</a>
                            </div>
                        </div>
                        {!! Form::open([
                            'route' => ["$route.destroy", $datum->uuid],
                            'method' => 'DELETE',
                            'class' => 'delete-form-' . $datum->uuid,
                        ]) !!}
                        {!! Form::close() !!}
                    </td>
                    <td>{{ $datum->first_name . ' ' . $datum->last_name }}</td>
                    <td>{!! $datum->switch !!}</td>
                    <td>{!! $datum->switchpublished !!}</td>
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
