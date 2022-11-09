<div class="frontend-ajax-location-country">
    {!! Form::label('country_id', 'Country', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "country_id",
    $countries,
    null,
    [
    "class"=>"form-control load-states-on-change ".($errors->has('country_id')?" is-invalid":""),
    'placeholder'=>'Select country',
    'data-state-holder' => 'frontend-ajax-location-state',
    'data-ajax-url' => route('frontend.ajax.postGetStates'),
    'data-view-file' => 'frontend.helpers.ajax.locationStateDropdown'
    ]
    ) !!}
</div>
