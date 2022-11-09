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
        <th>{{__('Slug')}}</th>
        <th>{{__('Description')}}</th>
        <th>{{__('Meta title')}}</th>
        <th>{{__('Meta description')}}</th>
        <th>{{__('Meta keywords')}}</th>
        <th>{{__('Icon')}}</th>
        <th>{{__('Status')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $datum)
        <tr>
            <td>{{$datum->name}}</td>
            <td>{{$datum->slug}}</td>
            <td>{{$datum->description}}</td>
            <td>{{$datum->meta_title}}</td>
            <td>{{$datum->meta_description}}</td>
            <td>{{$datum->meta_keywords}}</td>
            <td>{{!is_null($datum->icon_file) ? url($datum->icon_file) : 'NA'}}</td>
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