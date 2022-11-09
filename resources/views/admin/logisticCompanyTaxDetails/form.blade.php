{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */
 --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{$pageTitle}}</h1>
        </div>
    </div>
@endsection

@section('content')

    @include($navTab)


    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">

                    @if(isset($logistic_company_tax_detail->id))
                        {!! Form::model($logistic_company_tax_detail, ['route' => ["$route.update", $logisticCompany->uuid, $logistic_company_tax_detail->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($logistic_company_tax_detail, ['route' => ["$route.store", $logisticCompany->uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif

                        {!! Form::hidden('logistic_company_id', $logisticCompany->uuid) !!}
                        
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('tax_number', 'Tax number', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("tax_number",null,["class"=>"form-control".($errors->has('tax_number')?" is-invalid":""),'placeholder'=>'Tax number']) !!}

                            @if ($errors->has('tax_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('tax_number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('vat_number', 'VAT number', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("vat_number",null,["class"=>"form-control".($errors->has('vat_number')?" is-invalid":""),'placeholder'=>'VAT number']) !!}

                            @if ($errors->has('vat_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('vat_number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('passport_number', 'ID or passport number', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("passport_number",null,["class"=>"form-control".($errors->has('passport_number')?" is-invalid":""),'placeholder'=>'Passport number']) !!}

                            @if ($errors->has('passport_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('passport_number') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('verify_tax_details', 'Verify tax details?', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("verify_tax_details", $verifyTaxDetailsDropDown, null, ["class"=>"form-control".($errors->has('verify_tax_details')?" is-invalid":"")]) !!}

                            @if ($errors->has('verify_tax_details'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('verify_tax_details') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('passport_document', 'ID or passport', ['class' => 'form-label label-required']) !!}
                            {!! Form::file("passport_document", [
                                            "class"=>"form-control dropify ".($errors->has('passport_document')?" is-invalid":""),
                                            'data-default-file' => isset($logistic_company_tax_detail->id) ? $logistic_company_tax_detail->passport_document_file : ''
                                            ]) !!}
                            <small><i>{{__('Only JPG, PNG and PDF supported')}}</i></small>
                            @if ($errors->has('passport_document'))
                                <br><span class="help-block text-danger">
                        <strong>{{ $errors->first('passport_document') }}</strong>
                    </span>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($logistic_company_tax_detail->id))
                                {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
