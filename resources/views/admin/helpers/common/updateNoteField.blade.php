{!! Form::label('update_note', 'Update note', ['class' => 'form-label label-required']) !!}
{!! Form::textarea("update_note", null,["class"=>"form-control".($errors->has('update_note')?" is-invalid":""),'placeholder'=>'Update Note', 'rows' => 3]) !!}

@if ($errors->has('update_note'))
    <small class="text-danger">
        <strong>{{ $errors->first('update_note') }}</strong>
    </small>
@endif