<div class="frontend-ajax-location-state">
    {!! Form::label('state_id', 'Province', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "state_id",
    $states,
    null,
    [
    "class"=>"form-control load-cities-on-change ".($errors->has('state_id')?" is-invalid":""),
    'placeholder'=>'Select province',
    'data-city-holder' => 'frontend-ajax-location-city',
    'data-ajax-url' => route('frontend.ajax.postGetCities'),
    'data-view-file' => 'frontend.helpers.ajax.locationCityDropdown'
    ]
    ) !!}
</div>
