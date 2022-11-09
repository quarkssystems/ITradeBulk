{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}
<div class="row">
    @include('admin.helpers.dataGrid.pager')
    @include('admin.helpers.quickAction.action')
</div>

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
                <td>@include('admin.helpers.quickAction.checkBox'){{$countStart + $loop->iteration}}</td>
                <td>{{$datum->vehicle_type}}</td>
                <td>{{$datum->capacity}}</td>
                <td>{{$datum->pallet_capacity_standard}}</td>
                <td>{{$datum->price_per_km}}</td>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}" title="{{__("Edit")}}">{{__("Edit")}}</a>
                            <a class="dropdown-item delete-item" href="javascript:;" data-form-id=".delete-form-{{$datum->uuid}}" title="{{__("Delete")}}">{{__("Delete")}}</a>
                            <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}?copy" title="{{__("Copy")}}">{{__("Copy")}}</a>
                        </div>
                    </div>
                    {!! Form::open(['route' => ["$route.destroy", $datum->uuid], 'method' => 'DELETE', 'class' => 'delete-form-'.$datum->uuid]) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        @if($data->count() == 0)
            <tr>
                <td colspan="11">
                    <div class="alert alert-primary">{{__('No data found')}}</div>
                </td>
            </tr>
        @endif
        </tbody>

    </table>

    {!! $dataGridPagination !!}
</div>