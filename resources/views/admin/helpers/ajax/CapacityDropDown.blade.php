<div class="admin-ajax-capacity-area">
    {!! Form::label('vehicle_type', 'Vehicle Type', ['class' => 'form-label label-required']) !!}
    {!! Form::select(
    "vehicle_type",
    $vehicle_type,
    null,
    [
    "class"=>"form-control select-dropdown ".($errors->has('vehicle_type')?" is-invalid":""),
    'placeholder'=>'Select vehicle Type',
     'data-ajax-url' => route('admin.ajax.postGetPallet'),
    ]
    ) !!}
</div>
