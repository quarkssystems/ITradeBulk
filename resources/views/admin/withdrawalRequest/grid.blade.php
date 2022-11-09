{{--
/**
 * Created by PhpStorm.
 * User: 
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
                <td>W-{{$datum->id}}</td>
                <td>{{$datum->name}}</td>
                <td>{{$datum->amount}}</td>
                <td>
                    @if($datum->status == "PENDING" || is_null($datum->status))
                                                    <a href="{{route('admin.approve-withdrawalrequest-transaction', $datum->uuid)}}">{{__("Approve")}}</a> | <a href="{{route('admin.cancel-withdrawalrequest-transaction', $datum->uuid)}}">{{__("Cancel")}} </a>| 
                                                     
                                                    @if($datum->role=='SUPPLIER')
                                                    <a target="_blank"
                                                    href="{{
                                                    $datum->bankDetails()->exists() ? route('admin.supplier-bank-details.edit', ['user_uuid' => $datum->user_id, 'supplier_bank_detail' => $datum->bankDetails()->first()->uuid]) : route('admin.supplier-bank-details.create', $datum->user_id)
                                                    }}">{{__("Bank Detail")}} 
                                                    </a>
                                                    @elseif( $datum->role =='DRIVER' || $datum->role =='COMPANY' )
                                                    <!-- <a target="_blank"
                                                    href="{{
                                                    $datum->bankDetails()->exists() ? route('admin.logistic-company-bank-details.edit', ['logistic_company_uuid' => $datum->uuid, 'logistic_bank_detail' => $datum->bankDetails()->first()->uuid]) : route('admin.logistic-company-bank-details.create', $datum->uuid)
                                                    }}">{{__("Bank Detail")}} 
                                                    </a> -->
                                                     <a href="{{ $datum->bankDetails()->exists() ? route('admin.logistic-bank-details.edit',['user_uuid' => $datum->user_id, 'logistic_bank_detail' => $datum->bankDetails()->first()->uuid]) : route('admin.logistic-bank-details.create', $datum->user_id) }}">{{__("Bank Detail")}} </a>
                                                   @elseif( $datum->role =='VENDOR')
                                                    <a target="_blank"
                                                    href="{{
                                                    $datum->bankDetails()->exists() ? route('admin.vendor-bank-details.edit', ['user_uuid' => $datum->user_id, 'vendor_bank_detail' => $datum->bankDetails()->first()->uuid]) : route('admin.vendor-bank-details.create', $datum->user_id)
                                                    }}">{{__("Bank Detail")}} 
                                                    </a> 
                                                 @endif   
                 @else
                    - {{__($datum->status)}}
                @endif
                </td>
                <!-- <td>
                        <a href="{{ $datum->bankDetails()->exists() ? route('admin.logistic-bank-details.edit',['user_uuid' => $datum->user_id, 'logistic_bank_detail' => $datum->bankDetails()->first()->uuid]) : route('admin.logistic-bank-details.create', $datum->user_id) }}">{{__("Bank Detail")}} </a>
                </td> -->



            
                <td>{{$datum->created_at->format("Y-m-d H:i:s")}}</td>
                
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