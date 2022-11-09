{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:26 AM
 */
--}}

<tr>
    @foreach($filters as $filter)
        <th {{isset($filter['width']) ? 'width=' . $filter['width'] : ''}}>
            @if(isset($filter['sorting']) && $filter['sorting'] === true)
                @php($filterColumns = '')
                @if(isset($filter['column']) && !empty($filter['column']))
                    @if(is_array($filter['column']))
                        @php($filterColumns = $filter['column'][0])
                    @else
                        @php($filterColumns = $filter['column'])
                    @endif
                @endif


                <a href="javascript:ajaxLoad('{{url($url. '?' . $sorting['sorting_field'] . '=' . $filterColumns . '&' . $sorting['sort'] . '='.(request()->session()->get($sorting['sort'])=='asc'?'desc':'asc'))}}', '{{$containerClass}}')">
                    {{__($filter['title'])}}
                </a>
                {!! request()->session()->get($sorting['sorting_field']) == $filterColumns ?(request()->session()->get($sorting['sort']) == 'asc' ? '&#9652;' : '&#9662;' ) : '' !!}
            @else
                {{__($filter['title'])}}
            @endif
        </th>
    @endforeach
</tr>