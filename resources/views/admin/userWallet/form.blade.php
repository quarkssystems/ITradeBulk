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
                    <div class="row">
                        <div class="col-md-6">
                            @if(isset($walletTransactions->id))
                                {!! Form::model($walletTransactions, ['route' => ["$route.update", $user->uuid, $walletTransactions->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                            @else
                                {!! Form::model($walletTransactions, ['route' => ["$route.store", $user->uuid], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                            @endif

                            {!! Form::hidden('user_id', $user->uuid) !!}

                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">

                                    {!! Form::label('transaction_mode', 'Transaction Type', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select("transaction_mode",$transactionTypes, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),'placeholder'=>'Select Type']) !!}

                                    @if ($errors->has('transaction_type'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('transaction_type') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('amount', 'Amount', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text("amount",null,["class"=>"form-control".($errors->has('amount')?" is-invalid":""),'placeholder'=>'1500']) !!}

                                    @if ($errors->has('amount'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('transaction_type', 'Transaction by', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select("transaction_type", $transactionTypesDropdown, null, ["class"=>"form-control".($errors->has('transaction_type')?" is-invalid":""),"autofocus",'placeholder'=>'Transaction by']) !!}

                                    @if ($errors->has('transaction_type'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('transaction_type') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">


                                    {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}
                                    {!! Form::textarea("remarks",null,["rows"=>"3","class"=>"form-control".($errors->has('remarks')?" is-invalid":""),'placeholder'=>'Products purchase']) !!}

                                    @if ($errors->has('remarks'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('remarks') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="form-group  col-md-12 row mt-1">

                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                                        {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                                        {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                                        @if(!isset($walletTransactions->id))
                                            {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}
                                        @endif
                                    </div>

                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body py-2">
                                            <h3 class="text-primary text-uppercase">{{$walletTransactionsCounts['credited_amount']}} </h3>
                                            <h5>{{__('Credited Amount')}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body py-2">
                                            <h3 class="text-primary text-uppercase">{{$walletTransactionsCounts['debited_amount']}} </h3>
                                            <h5>{{__('Debited Amount')}} </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body py-2">
                                            <h3 class="text-primary text-uppercase">{{$walletTransactionsCounts['total_amount']}} </h3>
                                            <h5>{{__('Total Balance')}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <h3>Transactions</h3>
                            <hr>
                            @if(!$walletTransactions->isEmpty() && isset($walletTransactions))
                                @foreach($walletTransactions as $wt)
                                    @if(isset($wt->credit_amount) && $wt->credit_amount > 0)
                                        <div class="alert alert-success fade show p-1" role="alert">
                                            <span class="alert-inner--icon"><i class="ni ni-credit-card"></i></span>
                                            <span class="alert-inner--text"><strong>{{__('Credit !!')}}</strong> {{$wt->credit_amount}} {{_('is credited in your wallet with (beneficiary_ref_no : ')}} {{$wt->id}}{{__(')')}}
                                                @if($wt->status == "PENDING" || is_null($wt->status))
                                                    <a href="{{route('admin.approve-wallet-transaction', $wt->uuid)}}">{{__("Approve")}}</a> | <a href="{{route('admin.cancel-wallet-transaction', $wt->uuid)}}">{{__("Cancel")}} </a>
                                                    @else
                                                    - {{__($wt->status)}}
                                                @endif
                                                | <a id ='view-Tran' data-wallet-id="{{$wt->uuid}}" data-amt ="{{$wt->credit_amount}} {{_('is credited in your wallet with (beneficiary_ref_no : ')}} {{$wt->id}}{{__(')')}}" data-remarks="{{$wt->remarks}}" data-receipt="{{$wt->receipt}}" data-receipt="{{$wt->receipt}}" data-transaction_type="{{$wt->transaction_type}}"   data-status="{{$wt->status}}" >{{__("View Transactions")}}</a>
                                            </span>
                                        </div>
                                    @elseif(isset($wt->debit_amount))
                                        <div class="alert alert-danger fade show p-1" role="alert">
                                            <span class="alert-inner--icon"><i class="ni ni-credit-card"></i></span>
                                            <span class="alert-inner--text"><strong>{{__('Debited !!')}}</strong> <i
                                                        class="ni ni-rand"></i> {{$wt->debit_amount}}  {{__(' is debited from  wallet with (beneficiary_ref_no : ')}} {{$wt->id}}{{__(')')}}</span>
                                                        @if($wt->status == "PENDING" || is_null($wt->status))
                                                            <a href="{{route('admin.approve-wallet-transaction', $wt->uuid)}}">{{__("Approve")}}</a> | <a href="{{route('admin.cancel-wallet-transaction', $wt->uuid)}}">{{__("Cancel")}} </a>
                                                            @else
                                                            - {{__($wt->status)}}
                                                        @endif
                                                        | <a id ='view-Tran' data-wallet-id="{{$wt->uuid}}" data-amt ="{{$wt->debit_amount}}  {{__(' is debited from  wallet with (beneficiary_ref_no : ')}} {{$wt->id}}{{__(')')}}" data-remarks="{{$wt->remarks}}" data-receipt="{{$wt->receipt}}" data-receipt="{{$wt->receipt}}" data-transaction_type="{{$wt->transaction_type}}"   data-status="{{$wt->status}}" >{{__("View Transactions")}}</a>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="alert alert-danger alert-dismissible fade show p-1" role="alert">
                                <span class="alert-inner--text">{{_('No transactions found !!')}}</span>
                                </div>
                            @endif
                                {{--<div class="alert alert-success alert-dismissible fade show p-1" role="alert">--}}
                                {{--<span class="alert-inner--icon"><i class="ni ni-credit-card"></i></span>--}}
                                {{--<span class="alert-inner--text"><strong>{{__('Credit !!')}}</strong> 500 {{_('is credited in wallet with (beneficiary_ref_no : ')}} {{$wt->uuid}} {{_(')')_}}</span>--}}
                                {{--</div>--}}

                                {{--<div class="alert alert-danger fade show p-1" role="alert">--}}
                                {{--<span class="alert-inner--icon"><i class="ni ni-credit-card"></i></span>--}}
                                {{--<span class="alert-inner--text"><strong>Debited !!</strong> <i class="ni ni-rand"></i> 500 {{_('is debited from  wallet')}}</span>--}}
                                {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="viewTransModal">
        <div class="modal-dialog  modal-ml">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Transaction')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

               <div class="modal-body">
                     <div class="row form-group">
                   
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >Transaction : </div> 
                    <div  class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="m_amt" ></div>
                </div>
                   <div  class="row form-group">
                   
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >Remarks : </div> 
                    <div  class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="m_remarks" ></div>
                </div>
                  <div class="row form-group">
                   
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >Receipt : </div> 
                    <div  class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="m_receipt" ></div>
                </div>
                  <div class="row form-group">
                   
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >Transaction Type : </div> 
                    <div  class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="m_transaction_type" ></div>
                </div>

                 <div class="row form-group">
                   
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >Status : </div> 
                    <div  class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="m_status" ></div>
                </div>
   
                </div>
                <!-- Modal body -->
                <div class="modal-body"></div>
            </div>
        </div>


@endsection
