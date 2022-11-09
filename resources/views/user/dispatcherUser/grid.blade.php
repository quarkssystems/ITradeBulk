{{--

/**

 * Created by PhpStorm.

 * User: 

 * Date: 22/11/18

 * Time: 10:24 AM

 */

 --}}

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
                    <td>{{ $datum->first_name . ' ' . $datum->last_name }}</td>
                    <td>{{ $datum->gender }}</td>
                    <td>{{ $datum->email }}</td>
                    <td>{!! $datum->switch !!}</td>
                    {{-- <td>{{ $datum->mobile }}</td> --}}
                    {{-- <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                            <a class="dropdown-item delete-item" href="javascript:;"
                                data-form-id=".delete-form-{{ $datum->uuid }}"
                                title="{{ __('Delete') }}">{{ __('Delete') }}</a>

                        </div>
                    </div>
                    {!! Form::open([
                        'route' => ["$route.destroy", $datum->uuid],
                        'method' => 'DELETE',
                        'class' => 'delete-form-' . $datum->uuid,
                    ]) !!}
                    {!! Form::close() !!}
                    </td> --}}
                    <td>

                        <a class="btn btn-info m-1"
                            href="{{ route('supplier.dispatcher-users.edit', $datum->uuid) }}">{{ __('Edit') }}</a>



                        {{-- <a class="dropdown-item delete-item btn btn-primary btn-small" href="javascript:;"
                            data-form-id=".delete-form-{{ $datum->id }}"
                            title="{{ __('Delete') }}">{{ __('Delete') }}</a>

                        {!! Form::open([
                            'route' => ["$route.destroy", $datum->id],
                            'method' => 'DELETE',
                            'class' => 'delete-form-' . $datum->id,
                        ]) !!}
                        {!! Form::close() !!} --}}
                        <form action="{{ url('supplier/dispatcher-users', [$datum->uuid]) }}" method="POST">

                            <input type="hidden" name="_method" value="DELETE">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input type="submit" class="btn btn-danger" value="Delete" />

                        </form>



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
