{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}

<table class="table ">
    <thead class="thead-light">
    <tr>
        <td>{{__('Bank')}}</td>
        <td>{{__('Branch Name')}}</td>
        <td>{{__('Branch Code')}}</td>
        <td>{{__('SWIFT Code')}}</td>
        <td>{{__('Province')}}</td>
        <td>{{__('City')}}</td>
        <td>{{__('Postal code')}}</td>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $datum)
        <tr>
            <td>{{$datum->bank_name}}</td>
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