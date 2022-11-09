@extends('supplier.layouts.main')

@section('page-header')
    <div class="container-fluid">

        <ol class="breadcrumb breadcrumb-style1">

            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>

            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>

        </ol>

        <div class="page-header">

            <div class="page-title">

                <h4>{{ $pageTitle }}</h4>

            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-12">

            @include('frontend.helpers.globalMessage.message')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">iTradeBulk™ Payment Instruction</h4>
                {{-- <h4 class="alert-heading">ITZ Payment Instruction</h4> --}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- <p>Please pay the total amount to out bank account and enter your Beneficiary reference number "41076",You can confirnation by using your bank's email payment confiramtion service or please allow one or two working day's to receive and verify your payment.</p> -->
                <p class="mb-0"><label>Bank Name : </label>FNB</p>
                <p class="mb-0"><label>Account Name : </label>{{ env('APP_NAME') }} Pty Ltd.</p>
                {{-- <p class="mb-0"><label>Account Name : </label>iTradeZon Pty Ltd.</p> --}}
                <p class="mb-0"><label>Account No : </label>123456789</p>
                <p class="mb-0"><label>Branch Code : </label>012-342</p>
                <p class="mb-0"><label>Branch Name : </label>Spring</p>
                <p class="mb-0"><label>Account Type : </label>Business Cheque Account</p>
                <!-- <p class="mb-0"><label>Beneficiary Ref : </label>41076</p> -->
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            {!! Form::model($wallet, [
                                'route' => ["$route.store"],
                                'method' => 'POST',
                                'id' => 'form',
                                'autocomplete' => 'off',
                                'name' => 'walletForm',
                            ]) !!}



                            <div class="row">



                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                                    {!! Form::label('credit_amount', 'Credit amount', ['class' => 'form-label label-required']) !!} (R)

                                    {!! Form::text('credit_amount', null, [
                                        'class' => 'form-control' . ($errors->has('credit_amount') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Credit amount',
                                    ]) !!}



                                    @if ($errors->has('credit_amount'))
                                        <small class="text-danger">

                                            <strong>{{ $errors->first('credit_amount') }}</strong>

                                        </small>
                                    @endif

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                                    {!! Form::label('transaction_type', 'Transaction type', ['class' => 'form-label label-required']) !!}

                                    {!! Form::select('transaction_type', $transactionTypeDropDown, null, [
                                        'class' => 'form-control' . ($errors->has('transaction_type') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Transaction type',
                                    ]) !!}



                                    @if ($errors->has('transaction_type'))
                                        <small class="text-danger">

                                            <strong>{{ $errors->first('transaction_type') }}</strong>

                                        </small>
                                    @endif

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                                    {!! Form::label('remarks', 'Details', ['class' => 'form-label label-required']) !!}

                                    {!! Form::textarea('remarks', null, [
                                        'class' => 'form-control' . ($errors->has('remarks') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Details',
                                        'rows' => 2,
                                    ]) !!}



                                    @if ($errors->has('remarks'))
                                        <small class="text-danger">

                                            <strong>{{ $errors->first('remarks') }}</strong>

                                        </small>
                                    @endif

                                </div>

                            </div>



                            <div class="form-group row required">

                                {!! Form::label('receipt', 'Receipt', ['class' => 'form-label']) !!}

                                {!! Form::file('receipt', ['class' => 'form-control dropify ' . ($errors->has('receipt') ? ' is-invalid' : '')]) !!}



                                @if ($errors->has('receipt'))
                                    <small class="text-danger">

                                        <strong>{{ $errors->first('receipt') }}</strong>

                                    </small>
                                @endif

                            </div>

                            <div class="form-group row">

                                <div class="col-xs-12 col-lg-12">

                                    {!! Form::submit('Credit', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save_exit']) !!}

                                    {{-- {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!} --}}

                                </div>

                                <div class="col-xs-12 col-lg-12">

                                    <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>

                                </div>

                            </div>

                            {!! Form::close() !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
