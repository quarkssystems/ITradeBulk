@if(isset($user_tax_detail->id))
    {!! Form::model($user_tax_detail, ['route' => ["$route.update", $user_tax_detail->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true,'class'=>'theme-form']) !!}
@else
    {!! Form::model($user_tax_detail, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true,'class'=>'theme-form']) !!}
@endif

   <div class="form-group required">
            @if(isset($user_tax_detail->verify_tax_details))
                    @if($user_tax_detail->verify_tax_details == "YES")
                        <label class="label-verified color-background-success"><i class="fa  fa-check"></i> {{__('Verified')}}</label>
                        @else
                        <label class="label-verified color-background-danger"><i class="fa fa-times"></i> {{__('Not Verified')}}</label>

                    @endif
            @endif
            </div>

<div class="row">
    <div class="col-md-6">
          
            <div class="form-group required">
                {!! Form::label('tax_number', 'Tax number', ['class' => 'form-label label-required']) !!}
                {!! Form::text("tax_number",null,["class"=>"form-control".($errors->has('tax_number')?" is-invalid":""),'placeholder'=>'Tax number']) !!}

                @if ($errors->has('tax_number'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('tax_number') }}</strong>
                    </small>
                @endif
            </div>
            <div class=" form-group required">
                {!! Form::label('vat_number', 'VAT number', ['class' => 'form-label label-required']) !!}
                {!! Form::text("vat_number",null,["class"=>"form-control".($errors->has('vat_number')?" is-invalid":""),'placeholder'=>'VAT number']) !!}

                @if ($errors->has('vat_number'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('vat_number') }}</strong>
                    </small>
                @endif
            </div>
            <div class=" form-group required">
                {!! Form::label('passport_number', 'ID or passport number', ['class' => 'form-label label-required']) !!}
                {!! Form::text("passport_number",null,["class"=>"form-control".($errors->has('passport_number')?" is-invalid":""),'placeholder'=>'Passport number']) !!}

                @if ($errors->has('passport_number'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('passport_number') }}</strong>
                    </small>
                @endif
            </div>
           
        </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                {!! Form::label('passport_document', 'ID or passport', ['class' => 'form-label label-required']) !!}
                {!! Form::file("passport_document", [
                                "class"=>"form-control dropify ".($errors->has('passport_document')?" is-invalid":""),
                                'data-default-file' => isset($user_tax_detail->id) ? $user_tax_detail->passport_document_file : ''
                                ]) !!}
                <small><i>{{__('Only JPG, PNG and PDF supported')}}</i></small>
                @if ($errors->has('passport_document'))
                    <br><span class="help-block text-danger">
                        <strong>{{ $errors->first('passport_document') }}</strong>
                    </span>
                @endif
            </div>

        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
        {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

    </div>

</div>
{!! Form::close() !!}
