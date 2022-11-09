{{-- /**

 * Created by PhpStorm.

 * User: Mohit

 * Date: 22/11/18

 * Time: 10:24 AM

 */ --}}

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
                {{-- {{ dd($datum->basket_items) }} --}}
                @if ($datum->basket_items != null)
                    <tr>

                        <td>{{ $countStart + $loop->iteration }}</td>

                        @if ($role != 'COMPANY')
                            <td><a href="{{ route("$route.edit", $datum->uuid) }}">#{{ $datum->order_number }}</a> </td>
                        @else
                            <td>#{{ $datum->order_number }}</td>
                        @endif

                        <td>{{ $datum->user_name }}</td>

                        <td>{{ $datum->supplier_name }}</td>

                        @if ($role == 'COMPANY')
                            <td>{{ $datum->logistic_name }}</td>
                        @endif

                        @if (($role == 'DRIVER' && $logistic_type == 'INDIVIDUAL') || ($role == 'COMPANY' && $logistic_type == 'COMPANY'))
                            <td>{{ $datum->shipment_amount }}</td>
                        @elseif($role == 'VENDOR')
                            <td>{{ $datum->final_total }}</td>
                        @elseif($role == 'SUPPLIER')
                            <td>{{ $datum->cart_amount }}</td>
                        @endif

                        <td>{{ $datum->order_status }}</td>



                        @if ($role == 'DRIVER' && $logistic_type == 'INDIVIDUAL')
                            <td><a href="{{ route('user.view_driver_invoice', $datum->uuid) }}">Invoice</a> </td>
                        @elseif($role == 'COMPANY' && $logistic_type == 'COMPANY')
                            <td><a href="#">Invoice</a> </td>
                        @endif

                        <td>{{ $datum->created_at->format('Y-m-d H:i:s') }}</td>

                        {{-- <td> --}}

                        {{-- <div class="dropdown"> --}}

                        {{-- <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> --}}

                        {{-- <i class="fas fa-ellipsis-v"></i> --}}

                        {{-- </a> --}}

                        {{-- <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"> --}}

                        {{-- <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}" title="{{__("Edit")}}">{{__("Edit")}}</a> --}}

                        {{-- <a class="dropdown-item delete-item" href="javascript:;" data-form-id=".delete-form-{{$datum->uuid}}" title="{{__("Delete")}}">{{__("Delete")}}</a> --}}

                        {{-- <!-- <a class="dropdown-item" href="{{ route("$route.edit", $datum->uuid) }}?copy" title="{{__("Copy")}}">{{__("Copy")}}</a> --> --}}

                        {{-- </div> --}}

                        {{-- </div> --}}

                        {{-- {!! Form::open(['route' => ["$route.destroy", $datum->uuid], 'method' => 'DELETE', 'class' => 'delete-form-'.$datum->uuid]) !!} --}}

                        {{-- {!! Form::close() !!} --}}

                        {{-- </td> --}}

                    </tr>
                @endif
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
