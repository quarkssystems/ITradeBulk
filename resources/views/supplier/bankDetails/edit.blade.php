@if(isset($user_bank_detail->id))
    {!! Form::model($user_bank_detail, ['route' => ["$route.update",$user_bank_detail->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'class'=>'theme-form','name' => 'usersForm', 'files' => true]) !!}
@else
    {!! Form::model($user_bank_detail, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true,'class'=>'theme-form']) !!}
@endif
{!! Form::hidden('uuid',null) !!}
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                {!! Form::label('bank_account_name', 'Account name', ['class' => 'form-label label-required']) !!}
                {!! Form::text("bank_account_name",null,["class"=>"form-control".($errors->has('bank_account_name')?" is-invalid":""),'placeholder'=>'Account name']) !!}

                @if ($errors->has('bank_account_name'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('bank_account_name') }}</strong>
                    </small>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                {!! Form::label('bank_account_number', 'Account number', ['class' => 'form-label label-required']) !!}
                {!! Form::text("bank_account_number",null,["class"=>"form-control".($errors->has('bank_account_number')?" is-invalid":""),'placeholder'=>'Account number']) !!}
                @if ($errors->has('bank_account_number'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('bank_account_number') }}</strong>
                    </small>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                {!! Form::label('bank_account_type', 'Account type', ['class' => 'form-label label-required']) !!}
                {!! Form::select("bank_account_type", $accountTypes, null,["class"=>"form-control".($errors->has('bank_account_type')?" is-invalid":"")]) !!}

                @if ($errors->has('bank_account_type'))
                    <small class="text-danger">
                        <strong>{{ $errors->first('bank_account_type') }}</strong>
                    </small>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                {!! Form::label('account_confirmation_letter', 'Bank Confirmation Letter', ['class' => 'form-label label-required' ,'style' =>'display:block' ]) !!}
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
    </div>
</div>

@if(isset($user_bank_detail->id))
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>
                            <div class="form-check">
                                {!! Form::radio('bank_branch_id', $user_bank_detail->bank_branch_id, ['data-corresponding-input' => 'bank_id_'.$user_bank_detail->bank_branch_id, 'class' => 'form-check-input select-corresponding-input', 'id' => 'bank_branch_id_'.$user_bank_detail->bank_branch_id]) !!}
                                <label class="form-check-label"
                                       for="bank_branch_id_{{$user_bank_detail->bank_branch_id}}">{{$user_bank_detail->bank()->exists() ? $user_bank_detail->bank->name : ''}}</label>
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
        
        <div class="data-grid" id='bankgrid'>
            @include('frontend.bankBranch.grid')
        </div>
        
      
        <div class="form-group">
                        <div class="col-xs-12 col-lg-12">
                        <a type="button" class="btn btn-primary bank_btn" data-toggle="modal" data-target="#bankModal">
                 Add Bank </a>

                        <a type="button" class="btn btn-primary bank_details_btn" data-toggle="modal" data-target="#bankDetailModal">
                 Add Bank Branch</a>
                        
                    </div>
        </div>
    </div>
</div>



<div class="form-group row ">
    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
        {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-primary"]) !!}
    </div>
</div>
{!! Form::close() !!}

<div class="modal" id="bankModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add New Bank ')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                    <div class='errorclass alert alert-danger alert-dismissible' style='display:none;'>

                    </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row form-group">
                        {!! Form::open(['route' => "frontend.ajax.new-bank" ,'id'=>'bankfrmodel'] ) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                            
                            {!! Form::label('name', 'Bank name', ['class' => 'form-label label-required' ]) !!}
                            {!! Form::text("name",null,[ "class"=>"form-control".($errors->has('name')?" is-invalid":""),"autofocus",'placeholder'=>'Bank name']) !!}

                            @if ($errors->has('name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </small>
                            @endif
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required" >
                            {!! Form::label('short_name', 'Short Name', ['class' => 'form-label label-required' ]) !!}
                            {!! Form::text("short_name",null,[ "class"=>"form-control".($errors->has('short_name')?" is-invalid":""),"autofocus",'placeholder'=>'Bank Short name']) !!}

                            @if ($errors->has('short_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('short_name') }}</strong>
                                </small>
                            @endif
                            </div>



                    <div class="form-group">
                        <div class="col-xs-12 col-lg-12">
                             <button type="button" class="btn btn-theme" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn_bank btn btn-success"><i class="far fa-money-bill-alt"></i> Save</button>
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                     


<!-- The Modal -->
    <div class="modal" id="bankDetailModal">
        <div class="modal-dialog  modal-ml">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add New Bank Details')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                    <div class='errorclass alert alert-danger alert-dismissible' style='display:none;'>

                    </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        {!! Form::open(['route' => "frontend.ajax.new-bankbranch" ,'id'=>'bankbranchform'] ) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                      
                      
                      
                    <div class="row">
                        <div class="col-lg-12 col-md-12  col-sm-12 col-xs-12 form-group required"> 
                            {!! Form::label('bank_master_id', 'Select bank', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("bank_master_id", $banks, null,["class"=>"form-control".($errors->has('bank_master_id')?" is-invalid":""),"autofocus",'placeholder'=>'Select Bank']) !!}

                            @if ($errors->has('bank_master_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('bank_master_id') }}</strong>
                                </small>
                            @endif
                        </div>
                        
                    </div>

                     <div class="row">
                         
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('branch_name', 'Branch name', ['class' => 'form-label label-required' ]) !!}
                            {!! Form::text("branch_name",null,[ "class"=>"form-control".($errors->has('branch_name')?" is-invalid":""),"autofocus",'placeholder'=>'Branch name']) !!}

                            @if ($errors->has('branch_name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('branch_name') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('branch_code', 'Branch code', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("branch_code",null,["class"=>"form-control".($errors->has('branch_code')?" is-invalid":""),"autofocus",'placeholder'=>'Branch code']) !!}

                            @if ($errors->has('branch_code'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('branch_code') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('swift_code', 'Swift code', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("swift_code",null,["class"=>"form-control".($errors->has('swift_code')?" is-invalid":""),"autofocus",'placeholder'=>'Swift code']) !!}

                            @if ($errors->has('swift_code'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('swift_code') }}</strong>
                                </small>
                            @endif
                        </div>

                     </div>   

                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('address1', 'Address line 1', ['class' => 'form-label label-required']) !!}
                                {!! Form::text("address1",null,["class"=>"form-control".($errors->has('address1')?" is-invalid":""),'placeholder'=>'Address line 1']) !!}
                                @if ($errors->has('address1'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('address1') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                {!! Form::label('address2', 'Address line 2', ['class' => 'form-label']) !!}
                                {!! Form::text("address2",null,["class"=>"form-control".($errors->has('address2')?" is-invalid":""),'placeholder'=>'Address line 2']) !!}
                                @if ($errors->has('address2'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('address2') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                               @include('frontend.helpers.ajax.locationCountryDropdown')
                                @if ($errors->has('country_id'))
                                    <small class="help-block text-danger">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                @include('frontend.helpers.ajax.locationStateDropdown')
                                @if ($errors->has('state_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                 @include('frontend.helpers.ajax.locationCityDropdown')

                                @if ($errors->has('city_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('city_id') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                 @include('frontend.helpers.ajax.locationZipcodeDropdown')
                                @if ($errors->has('zipcode_id'))
                                    <small class="help-block text-danger">
                                    <strong>{{ $errors->first('zipcode_id') }}</strong>
                                </small>
                                @endif
                            </div>

                        </div>


                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                             <button type="button" class="btn btn-theme" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn_save_branchbank btn btn-success"><i class="far fa-money-bill-alt"></i> Save</button>
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{__('required fields')}}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                
                        </div>
                       
                      
                    </div>
                </div>

            </div>
        </div>
    </div>

