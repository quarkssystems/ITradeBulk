<div class="frontend-ajax-location-area">
    {!! Form::label('zipcode_id', 'Postal Code', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "zipcode_id",
    $zipcodes,
    null,
    [
    "class"=>"form-control select-dropdown ".($errors->has('zipcode_id')?" is-invalid":""),
    'placeholder'=>'Select postal code'
    ]
    ) !!}
</div>
