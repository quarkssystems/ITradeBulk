{{--

/**

 * Created by PhpStorm.

 * User: Mohit

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

                <td><a href="{{ route("$route.edit", $datum->uuid) }}">#{{$datum->order_number}}</a> </td>

                <td>{{$datum->user_name}}</td>

                <td>{{$datum->supplier_name}}</td>

                <td>{{$datum->logistic_name}}</td>

                <td>{{$datum->final_total}}</td>



                <td><div id="status-{{$datum->uuid}}"  >{{$datum->order_status}}</div> </td>

                <td>{{$datum->created_at->format("Y-m-d H:i:s")}}</td>

                <td>

                     {!! Form::select("status",$order_status,$datum->order_status,["class"=>"orderpayment_status","autofocus" ,"data-id"  => $datum->uuid,"data-area-holder" => "status-".$datum->uuid  , "data-ajax-url" => route('admin.ajax.updateOrderStatus')]) !!}

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