{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:25 AM
 */
--}}

<tr>
    @foreach($filters as $filter)
        <th>
            @if(isset($filter['search']) && !empty($filter['search']))
                @php($columns = [])
                @if(isset($filter['column']) && !empty($filter['column']))
                    @if(is_array($filter['column']))
                        @foreach ($filter['column'] as $column)
                            @php($columns[] = $column)
                        @endforeach
                    @else
                        @php($columns[] = $filter['column'])
                    @endif
                @endif
                @php($sessionName = $tableName.'_'.implode('_',$columns))
                @switch($filter['search']['type'])
                    @case('select')
                    {!! Form::select($sessionName, $filter['search']['data'], request()->session()->get($sessionName), ['class' => 'form-control select-dropdown form-control-sm', 'onchange' => "ajaxLoad('" . url($url) . "?" . $queryString . $sessionName . "='+this.value, '$containerClass')", 'placeholder' => $filter['search']['placeholder']]) !!}
                    @break

                    @case('date')
                    <div class="search-input-wrapper">
                        <input class="form-control form-control-sm search-input-field search-date-input {{request()->session()->get($sessionName.'_start') != '' ? 'search-input-has-value' : ''}}"
                               value="{{ request()->session()->get($sessionName.'_start') }}"
                               data-url="{{url($url)}}?{{$queryString}}"
                               placeholder="{{__("Start ". $filter['search']['placeholder'])}}"
                               name="{{$sessionName}}_start"
                               data-name="{{$sessionName}}"
                               data-container="{{$containerClass}}"
                               type="text"/>
                        <span class="clear-search-input-button"><i class="fa fa-times-circle"></i></span>
                    </div>
                    <div class="search-input-wrapper">
                        <input class="form-control form-control-sm search-input-field search-date-input {{request()->session()->get($sessionName.'_end') != '' ? 'search-input-has-value' : ''}}"
                               value="{{ request()->session()->get($sessionName.'_end') }}"
                               data-url="{{url($url)}}?{{$queryString}}"
                               placeholder="{{__("End ". $filter['search']['placeholder'])}}"
                               name="{{$sessionName}}_end"
                               data-name="{{$sessionName}}"
                               data-container="{{$containerClass}}"
                               type="text"/>
                        <span class="clear-search-input-button"><i class="fa fa-times-circle"></i></span>
                    </div>
                    @break

                    @case('text')
                    @case('scope')
                    @default
                    <div class="search-input-wrapper">
                        <input class="form-control form-control-sm search-input-field {{request()->session()->get($sessionName) != '' ? 'search-input-has-value' : ''}}"
                               value="{{ request()->session()->get($sessionName) }}"
                               data-url="{{url($url)}}?{{$queryString}}{{$sessionName}}="
                               placeholder="{{__($filter['search']['placeholder'])}}"
                               name="{{$sessionName}}"
                               data-container="{{$containerClass}}"
                               type="text"/>
                        <span class="clear-search-input-button"><i class="fa fa-times-circle"></i></span>
                    </div>

                    @break


                @endswitch
            @endif
        </th>
    @endforeach
</tr>