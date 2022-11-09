{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:25 AM
 */
--}}
<ul class="pagination pagination-wrapper" data-container="{{$containerClass}}">
    @if($records->perPage() <= $records->total())
    {{ $records->links() }}

    <li class="per-page-data" style="padding-left: 15px">

        <div class="search-input-wrapper">
            <input class="form-control search-input-field search-pagination-input {{request()->session()->get("per_page_count") != '' ? 'search-input-has-value' : ''}}"
                   value="{{ request()->session()->get("per_page_count") }}"
                   data-url="{{url($url)}}?{{__("per_page_count")}}="
                   placeholder="{{__("Per page")}}" name="{{__("per_page_count")}}"
                   data-container="{{$containerClass}}"
                   type="text"
                   size="10",
                   style="display: none"
            />

            {!! Form::select("pagination_per_page", [5 => 5,10 => 10,15 => 15,20 => 20,25 => 25,50 => 50,100=>100,200=>200], request()->session()->get("per_page_count"), ['class' => 'form-control ', 'onchange' => "$('.search-pagination-input').val($(this).val()); $('.search-pagination-input').trigger($.Event( 'keypress', { keyCode: 13 } ));"]) !!}

            <span class="clear-search-input-button"><i class="fa fa-times-circle"></i></span>
        </div>
    </li>
    @endif
</ul>
