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
                    <td>{{ $countStart + $loop->iteration }}
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}"
                                    title="{{ __('Edit') }}">{{ __('Edit') }}</a>

                            </div>
                        </div>
                        {!! Form::open([
                            'route' => ["$route.destroy", $datum->uuid],
                            'method' => 'DELETE',
                            'class' => 'delete-form-' . $datum->uuid,
                        ]) !!}
                        {!! Form::close() !!}
                    </td>
                    <td>{!! $datum->base_image_data !!}</td>
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->barcode }}</td>
                    <td  
                    title="{{$datum->description}}"
                    class="resizeDescription"
                    > 
                    {{ $datum->description }}</td>
                    <td> {{ ($datum->vat != "") ? 'R '.$datum->vat : '' }}</td>
                    <td> {{ ($datum->cost != "") ? 'R '.$datum->cost : '' }}</td>
                    <td>{{ $datum->markup }}</td>
                    <td> {{ ($datum->autoprice != "") ? 'R '.$datum->autoprice : '' }}</td>
                    <td> {{ ($datum->price != "") ? 'R '.$datum->price : '' }}</td>
                    <td>{{ $datum->quantity }}</td>
                    <td>{{ $datum->min_order_quantity }}</td>
                    <td>{{ $datum->stock_expiry_date }}</td>

                    
                    
                    
                    
                    
                    
                    
                    
                    

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
