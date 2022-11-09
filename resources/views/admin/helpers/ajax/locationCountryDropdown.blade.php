<div class="admin-ajax-location-country">
    {!! Form::label('country_id', 'Country', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "country_id",
    $countries,
    null,
    [
    "class"=>"form-control load-states-on-change ".($errors->has('country_id')?" is-invalid":""),
    'placeholder'=>'Select country',
    'data-state-holder' => 'admin-ajax-location-state',
    'data-ajax-url' => route('admin.ajax.postGetStates'),
    'data-view-file' => 'admin.helpers.ajax.locationStateDropdown'
    ]
    ) !!}
</div>
