{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}


<div class="table-responsive">
    <table class="table table-bordered table-bank">
        <thead >
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
                <td>
                    <div class="form-check">
                        <input type="radio"  data-corresponding-input="bank_id_{{$datum->uuid}}" @if($selectId == $datum->uuid) checked = 'true' @endif  name="bank_branch_id" class="form-check-input select-corresponding-input" id="bank_branch_id_{{$datum->uuid}}" value="{{$datum->uuid}}">

                        <label class="form-check-label" for="bank_branch_id_{{$datum->uuid}}">{{$datum->bank_name}}</label>

                        <input type="radio" name="bank_id" checked style="display: none"  class="bank_id_{{$datum->uuid}}" value="{{$datum->bank->uuid}}">
                    </div>
                </td>
                <td>{{$datum->branch_name}}</td>
                <td>{{$datum->branch_code}}</td>
                <td>{{$datum->swift_code}}</td>
                <td>{{$datum->state_name}}</td>
                <td>{{$datum->city_name}}</td>
                <td>{{$datum->zipcode_name}}</td>
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