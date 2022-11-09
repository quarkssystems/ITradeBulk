{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
<table class="table ">
    <thead class="thead-light">
        <tr>


            <th>{{ __('Barcode') }} </th>
            <th>{{ __('Description') }} </th>
            <th>{{ __('Category Group') }} </th>
            <th>{{ __('Department') }} </th>
            <th>{{ __('Category') }} </th>
            <th>{{ __('Sub Category') }} </th>
            <th>{{ __('Segment') }} </th>
            <th>{{ __('Sub Segment') }} </th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data[0] as $datum)
            @php
                $defaultValue = '';
            @endphp
            <tr>

                <td>{{ $datum->barcode ? $datum->barcode : $defaultValue }}</td>
                <td>{{ $datum->description ? $datum->description : $defaultValue }}</td>
                <td>{{ $datum->category_group ? $datum->category_group : $defaultValue }}</td>
                <td>{{ $datum->department ? $datum->department : $defaultValue }}</td>
                <td>{{ $datum->category ? $datum->category : $defaultValue }}</td>
                <td>{{ $datum->sub_category ? $datum->sub_category : $defaultValue }}</td>
                <td>{{ $datum->segment ? $datum->segment : $defaultValue }}</td>
                <td>{{ $datum->sub_segment ? $datum->sub_segment : $defaultValue }}</td>

            </tr>
        @endforeach
        @if ($data[0]->count() == 0)
            <tr>
                <td colspan="35">
                    <div class="alert alert-primary">{{ __('No data found') }}</div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
