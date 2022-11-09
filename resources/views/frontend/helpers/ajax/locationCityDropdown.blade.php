<div class="frontend-ajax-location-city">
    {!! Form::label('city_id', 'City', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "city_id",
    $cities,
    null,
    [
    "class"=>"form-control load-areas-on-change ".($errors->has('city_id')?" is-invalid":""),
    'placeholder'=>'Select town/city',
    'data-area-holder' => 'frontend-ajax-location-area',
    'data-ajax-url' => route('frontend.ajax.postGetAreas'),
    'data-view-file' => 'frontend.helpers.ajax.locationZipcodeDropdown'
    ]
    ) !!}
</div>
