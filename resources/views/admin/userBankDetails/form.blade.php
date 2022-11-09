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

                    @if(isset($user_bank_detail->id))
                        {!! Form::model($user_bank_detail, ['route' => ["$route.update", $user->uuid, $user_bank_detail->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @else
                        {!! Form::model($user_bank_detail, ['route' => ["$route.store", $user->uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif

                        {!! Form::hidden('user_id', $user->uuid) !!}
                        
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('bank_account_name', 'Account name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("bank_account_name",isset($user_bank_detail->id) ? $user_bank_detail->bank_account_name : $user->name,["class"=>"form-control".($errors->has('bank_account_name')?" is-invalid":""),'placeholder'=>'Account name']) !!}

                            @if ($errors->has('bank_account_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('bank_account_name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('bank_account_number', 'Account number', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("bank_account_number",null,["class"=>"form-control".($errors->has('bank_account_number')?" is-invalid":""),'placeholder'=>'Account number']) !!}

                            @if ($errors->has('bank_account_number'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('bank_account_number') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('bank_account_type', 'Account type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("bank_account_type", $accountTypes, null,["class"=>"form-control".($errors->has('bank_account_type')?" is-invalid":"")]) !!}

                            @if ($errors->has('bank_account_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('bank_account_type') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('account_confirmation_letter', 'Bank Confirmation Letter', ['class' => 'form-label label-required']) !!}
                            {!! Form::file("account_confirmation_letter", [
                                            "class"=>"form-control dropify ".($errors->has('account_confirmation_letter')?" is-invalid":""),
                                            'data-default-file' => isset($user_bank_detail->id) ? $user_bank_detail->account_confirmation_letter_file : ''
                                            ]) !!}
                            <small><i>{{__('Only JPG, PNG and PDF supported')}}</i></small>
                            @if ($errors->has('account_confirmation_letter'))
                                <br><span class="help-block text-danger">
                        <strong>{{ $errors->first('account_confirmation_letter') }}</strong>
                    </span>
                            @endif
                        </div>

                    </div>

                        @if(isset($user_bank_detail->uuid))
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                         @if($user_bank_detail->bank_branch_id && $user_bank_detail->bankBranch()->exists() )

                                                    <div class="form-check">
                                                        {!! Form::radio('bank_branch_id', $user_bank_detail->bank_branch_id, ['data-corresponding-input' => 'bank_id_'.$user_bank_detail->bank_branch_id, 'class' => 'form-check-input select-corresponding-input', 'id' => 'bank_branch_id_'.$user_bank_detail->bank_branch_id]) !!}
                                                        <label class="form-check-label" for="bank_branch_id_{{$user_bank_detail->bank_branch_id}}">{{$user_bank_detail->bank()->exists() ? $user_bank_detail->bank->name : ''}}</label>
                                                        <div style="display: none">
                                                            {!! Form::radio('bank_id', $user_bank_detail->bank_id, ['class' => 'bank_id_'.$user_bank_detail->bank_branch_id]) !!}</div>

                                                    </div>
                                                </td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->branch_name : ''}}</td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->branch_code : ''}}</td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->swift_code : ''}}</td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->state_name : ''}}</td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->city_name : ''}}</td>
                                                <td>{{$user_bank_detail->bankBranch()->exists() ? $user_bank_detail->bankBranch->zipcode_name : ''}}</td>
                                            </tr>
                                         @endif   
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($errors->has('bank_id'))
                                    <span class="help-block text-danger">
                <strong>{{ $errors->first('bank_id') }}</strong>
            </span>
                                @endif
                                @if ($errors->has('bank_branch_id'))
                                    <span class="help-block text-danger">
                <strong>{{ $errors->first('bank_branch_id') }}</strong>
            </span>
                                @endif
                                <div class="data-grid">
                                    @include('admin.userBankDetails.bankBranch.grid')
                                </div>
                            </div>
                        </div>
                        
                        
                    <div class="form-group row mt-3">
                        <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($user_bank_detail->id))
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
