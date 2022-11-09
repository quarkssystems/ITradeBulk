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
        <th>{{__('Name')}}</th>
        <th>{{__('Status')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $datum)
        <tr>
            <td>{{$datum->country_name}}</td>
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