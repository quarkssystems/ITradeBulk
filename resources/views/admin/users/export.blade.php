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
                <th>{{__('Email')}}</th>
                <th>{{__('Status')}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $datum)
            <tr>
                <td>{{$datum->name}}</td>
                <td>{{$datum->email}}</td>
                <td>{{$datum->status}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>