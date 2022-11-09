{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
@include('admin.helpers.dataGrid.pager')


<div class="table-responsive">
    <table class="table ">
        <thead class="thead-light">
            {!! $dataGridTitle !!}
        </thead>
        <tbody>
            <tr>
                {!! $dataGridSearch !!}
                {{-- <th>No.</th>
                <th>Image</th>
                <th>Barcode</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Brand</th>
                <th>User</th>
                <th>Audited</th>
                <th>Published</th> --}}
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
                                    href="{{ route('admin.supplier-product', [$datum->uuid, $datum->user_id]) }}"
                                    title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                            </div>
                        </div>
                        {{-- <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('admin.products.edit', $datum->uuid) }}"
                                    title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                                <a class="dropdown-item delete-item" href="javascript:;"
                                    data-form-id=".delete-form-{{ $datum->uuid }}"
                                    title="{{ __('Delete') }}">{{ __('Delete') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.products.edit', $datum->uuid) }}?copy"
                                    title="{{ __('Copy') }}">{{ __('Copy') }}</a>
                            </div>
                        </div>
                        {!! Form::open([
                            'route' => ['admin.products.destroy', $datum->uuid],
                            'method' => 'DELETE',
                            'class' => 'delete-form-' . $datum->uuid,
                        ]) !!}
                        {!! Form::close() !!} --}}
                    </td>
                    <td>{!! $datum->base_image_data !!}</td>
                    <td>{{ $datum->barcode }}</td>
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->slug }}</td>
                    <td>{{ $datum->brand_name }}</td>
                    {{-- <td>{{ $datum->UserName }}</td> --}}
                    {{-- <td>{{ $datum->status }}</td> --}}
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
