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

            </tr>

            @php($countStart = ($data->currentPage() - 1) * $data->perPage())

            @foreach ($data as $datum)
                <tr>

                    <td>{{ $countStart + $loop->iteration }}</td>

                    <td>{!! $datum->grid_image !!}</td>

                    <td>{{ $datum->name }}</td>

                    <td>{{ $datum->slug }}</td>

                    <td>{{ $datum->status }}</td>

                    <td>

                        <div class="dropdown">

                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <i class="fas fa-ellipsis-v"></i>

                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}"
                                    title="{{ __('Edit') }}">{{ __('Edit') }}</a>

                                <a class="dropdown-item delete-item" href="javascript:;"
                                    data-form-id=".delete-form-{{ $datum->uuid }}"
                                    title="{{ __('Delete') }}">{{ __('Delete') }}</a>

                                <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}?copy"
                                    title="{{ __('Copy') }}">{{ __('Copy') }}</a>

                            </div>

                        </div>

                        {!! Form::open([
                            'route' => ["$route.destroy", $datum->uuid],
                            'method' => 'DELETE',
                            'class' => 'delete-form-' . $datum->uuid,
                        ]) !!}

                        {!! Form::close() !!}

                    </td>
                    <td>{!! $datum->switch !!}</td>

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
