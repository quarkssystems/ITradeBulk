<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('name', $field['label'], ['class' => 'form-label']) !!}
        {!! Form::text($field['name'], \setting($field['name']), [
            'class' => 'form-control' . ($errors->has($field['name']) ? ' is-invalid' : ''),
            'autofocus',
            'placeholder' => $field['label'],
        ]) !!}

        @if ($errors->has($field['name']))
            <small class="text-danger">
                <strong>{{ $errors->first($field['name']) }}</strong>
            </small>
        @endif

    </div>
</div>
