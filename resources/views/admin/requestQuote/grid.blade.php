{{--
/**
 * Created by PhpStorm.
 * User: Mohit
 */
 --}}
@include('admin.helpers.dataGrid.pager')
<div class="">
    <table class="table">
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
                <td>{{$countStart + $loop->iteration}}</td>
{{--                <td>{!! $datum->grid_image !!}</td>--}}
                <td>{{$datum->name}}</td>
                <td>{{$datum->email}}</td>
                <td style="width: 50px !important">{!! mb_strimwidth(nl2br($datum->message), 0, 50, "...") !!}</td>
                <td>
                    <select class="form-control status" data-id="{{$datum->uuid}}">
                        <option value='pending' {{$datum->status =='pending' ? 'selected' : ''}}>Pending</option>
                        <option value="hold" {{$datum->status =='hold' ? 'selected' : ''}}>Hold</option>
                        <option value="complete" {{$datum->status =='complete' ? 'selected' : ''}}>Complete</option>
                    </select>
                </td>
                <td>
                    @if(!is_null($datum->attachment) && !empty($datum->attachment))
                        <a href="{{asset($datum->attachment)}}" download>Download File</a>
                    @endif
                </td>
{{--                <td>--}}
{{--                    <div class="dropdown">--}}
{{--                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                            <i class="fas fa-ellipsis-v"></i>--}}
{{--                        </a>--}}
{{--                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">--}}
{{--                            <!-- <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}" title="{{__("Edit")}}">{{__("Edit")}}</a>--}}
{{--                            <a class="dropdown-item delete-item" href="javascript:;" data-form-id=".delete-form-{{$datum->uuid}}" title="{{__("Delete")}}">{{__("Delete")}}</a> -->--}}
{{--                            <!-- <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}?copy" title="{{__("Copy")}}">{{__("Copy")}}</a> -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    {!! Form::open(['route' => ["$route.destroy", $datum->uuid], 'method' => 'DELETE', 'class' => 'delete-form-'.$datum->uuid]) !!}--}}
{{--                    {!! Form::close() !!}--}}
{{--                </td>--}}
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
@section('footerData')
<script>
$(document).ready(function() {
    // $('.status').onchange(function(){
    $(document).on('change', '.status', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
            }
        });
        var val = $(this).children("option:selected").val();
        var uuid = $(this).data('id');
        $.ajax({
            type:'POST',
            url:"{{ url('/admin/requestQuoteStatus') }}",
            data:{_token: '<?php echo csrf_token() ?>', val: val,uuid:uuid},
            success:function(data) {
                location.reload();
            }
        });
    });

});
</script>
@stop