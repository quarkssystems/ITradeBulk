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
        <td>{{__('Name')}}</td>
        <td>{{__('Postal code')}}</td>
        <td>{{__('Status')}}</td>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $datum)
        <tr>
            <td>{{$datum->zipcode_name}}</td>
            <td>{{$datum->zipcode}}</td>
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