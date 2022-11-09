{{-- /**

 * Created by MANAN-S-MOZAR.

 * User: Manan

 * Date: 16/07/20

 * Time: 06:42 PM

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
            <?php 
            $countStart = ($data->currentPage() - 1) * $data->perPage();
            ?>
            {{-- @php($countStart = ($data->currentPage() - 1) * $data->perPage()) @endphp --}}
            @foreach ($data as $datum)
                <tr>
                    <td>{{ $countStart + $loop->iteration }}</td>
                    <td>#{{ $datum->order_number }} </td>
                    <td>
                        {{-- {!! Form::select("user_id",$drivers, null,["class"=>"form-control form-control-sm select-dropdown drivers","autofocus"]) !!} --}}

                        @php $test = \App\Models\OrderLogisticQueue::getDrivers($datum->order_id) @endphp

                        @php $Is_occupied = false; @endphp

                        <select class="form-control form-control-sm select-dropdown drivers" name="user_id">
                            <option value=''>-- Select Driver --</option>
                            @foreach ($test as $key => $tst)
                                <option value="{{ $tst->driver_id }}"
                                    {{ $tst->status == 'OCCUPIED' ? ($Is_occupied = 'selected') : '' }}>
                                    {{ $tst->full_name }}</option>
                            @endforeach
                        </select>

                    </td>
                    <td>{{ $datum->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td>

                        <select class="form-control status" data-id="{{ $datum->order_id }}">
                            <option value=''>-- Select Status --</option>
                            <option value='ACCEPT'>Accept</option>
                            <option value="OCCUPIED" {{ $Is_occupied == true ? 'selected' : '' }}>Occupied</option>
                            <option value="REJECT">Reject</option>
                        </select>

                    </td>
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


@section('footerData')
    <script>
        $(document).ready(function() {
            // $('.status').onchange(function(){
            $(document).on('change', '.status', function() {
                // alert('test');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                    }
                });
                var val = $(this).children("option:selected").val();
                var driver_id = $('.drivers').val();
                var order_id = $(this).data('id');
                // alert(uuid);
                swal({
                        title: "Order Logistic in Queue Status Change",
                        text: "Would you like to change Order Logisitc Status to " + val + "?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, change it!",
                        closeOnConfirm: true
                    },

                    function(inputValue) {
                        if (inputValue === false) {
                            location.reload();
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('/admin/orderLogisticQueueAccept') }}",
                                data: {
                                    _token: '<?php echo csrf_token(); ?>',
                                    val: val,
                                    order_id: order_id,
                                    driver_id: driver_id
                                },
                                success: function(data) {
                                    location.reload();
                                }
                            });
                        }
                    });



            });

        });
    </script>
@stop
