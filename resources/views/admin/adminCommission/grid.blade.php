{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}
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
                <td>{{$countStart + $loop->iteration}}</td>
                <td><a href="{{ route("admin.sales-orders.edit", $datum->order_id) }}">#{{$datum->order_no}}</td></a>
                <td>{{number_format($datum->credit_amount,2)}}</td>
                @if($datum->remarks == 'ADMIN CHARGE FOR ORDER')
                    <td>SUPPLIER COMMISSION</td>
                @elseif($datum->remarks == 'ADMIN CHARGE FOR SHIPMENT' )
                    <td>TRANSPORTER COMMISSION</td>
                @endif
                <td>{{$datum->admin_charge}}</td>
                <td>{{$datum->status}}</td>
            
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