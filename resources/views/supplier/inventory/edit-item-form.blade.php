@extends('supplier.layouts.main')
@section('page-header')

    <div class="container-fluid">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item"><a href="/">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
            </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{$pageTitle}}</h4>
            </div>
        </div>

    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        @include('frontend.helpers.globalMessage.message')
    </div>
</div>
{!! Form::model($inventory, ['route' => ["supplier.updateFact"],'id'=>'form', 'autocomplete' => 'off', 'name' => 'additemform', 'files' => true]) !!}
{{--<div class="modal-header">--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
        {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
{{--</div>--}}

<div class="modal-body">
    {!! Form::hidden("product_id",$product->uuid) !!}
    {!! Form::hidden("user_id",Auth::user()->uuid) !!}
    {!! Form::hidden("store_id",$product->store_id) !!}
    <h1>Edit Fact</h1>
    {{-- <h5 class="modal-title" id="exampleModalLabel">{{$product->name}}</h5> --}}
<hr>
<div class="row">

<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
    {!! Form::label('stoc_vat', 'Vat', ['class' => 'form-label label-required']) !!}
    @if ($inventory == null)
    {!! Form::text("stoc_vat",null,["class"=>"form-control".($errors->has('stoc_vat')?" is-invalid":""),"autofocus",'placeholder'=>'Vat']) !!}
    @else
    {!! Form::text("stoc_vat",$inventory->stoc_vat,["class"=>"form-control".($errors->has('stoc_vat')?" is-invalid":""),"autofocus",'placeholder'=>'Vat']) !!}
    @endif

    @if ($errors->has('vat'))
        <small class="text-danger">
            <strong>{{ $errors->first('vat') }}</strong>
        </small>
    @endif
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
    {!! Form::label('cost', 'Cost', ['class' => 'form-label label-required']) !!}
       @if ($inventory == null) 
       {!! Form::text("cost",null,["class"=>"form-control".($errors->has('cost')?" is-invalid":""),"autofocus",'placeholder'=>'Cost']) !!}
        @else
        {!! Form::text("cost",$inventory->cost,["class"=>"form-control".($errors->has('cost')?" is-invalid":""),"autofocus",'placeholder'=>'Cost']) !!}
      @endif 
       @if ($errors->has('cost'))
        <small class="text-danger">
            <strong>{{ $errors->first('cost') }}</strong>
        </small>
    @endif
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
    {!! Form::label('markup', 'Markup', ['class' => 'form-label label-required']) !!}
    @if ($inventory == null) 
    {!! Form::text("markup",null,["class"=>"form-control".($errors->has('markup')?" is-invalid":""),"autofocus",'placeholder'=>'Markup']) !!}
    @else
    {!! Form::text("markup",$inventory->markup,["class"=>"form-control".($errors->has('markup')?" is-invalid":""),"autofocus",'placeholder'=>'Markup']) !!}
    @endif
    @if ($errors->has('markup'))
        <small class="text-danger">
            <strong>{{ $errors->first('markup') }}</strong>
        </small>
    @endif
</div>
</div>

<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('autoprice', 'Auto Price', ['class' => 'form-label label-required']) !!}
        @if ($inventory == null) 
        {!! Form::text("autoprice",null,["class"=>"form-control".($errors->has('autoprice')?" is-invalid":""),"autofocus",'placeholder'=>'Auto Price']) !!}
        @else
        {!! Form::text("autoprice",$inventory->autoprice,["class"=>"form-control".($errors->has('autoprice')?" is-invalid":""),"autofocus",'placeholder'=>'Auto Price']) !!}
        @endif
        @if ($errors->has('autoprice'))
            <small class="text-danger">
                <strong>{{ $errors->first('autoprice') }}</strong>
            </small>
        @endif
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('single_price', 'Price', ['class' => 'form-label label-required']) !!}
        @if ($inventory == null) 
        {!! Form::text("single_price",null,["class"=>"form-control".($errors->has('single_price')?" is-invalid":""),"autofocus",'placeholder'=>'Price']) !!}
        @else
        {!! Form::text("single_price",$inventory->single_price,["class"=>"form-control".($errors->has('single_price')?" is-invalid":""),"autofocus",'placeholder'=>'Price']) !!}
        @endif
        @if ($errors->has('single_price'))
            <small class="text-danger">
                <strong>{{ $errors->first('single_price') }}</strong>
            </small>
        @endif
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('single', 'Quantity', ['class' => 'form-label label-required']) !!}
        @if ($inventory == null) 
        {!! Form::text("single",null,["class"=>"form-control".($errors->has('single')?" is-invalid":""),"autofocus",'placeholder'=>'Quantity']) !!}
        @else
        {!! Form::text("single",$inventory->single,["class"=>"form-control".($errors->has('single')?" is-invalid":""),"autofocus",'placeholder'=>'Quantity']) !!}
        @endif
        @if ($errors->has('single'))
            <small class="text-danger">
                <strong>{{ $errors->first('single') }}</strong>
            </small>
        @endif
    </div>
    </div>

<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('min_order_quantity', 'Min Order Quantity', ['class' => 'form-label label-required']) !!}
        @if ($inventory == null) 
        {!! Form::text("min_order_quantity",null,["class"=>"form-control".($errors->has('min_order_quantity')?" is-invalid":""),"autofocus",'placeholder'=>'Min Order Quantity']) !!}
        @else
        {!! Form::text("min_order_quantity",$inventory->min_order_quantity,["class"=>"form-control".($errors->has('min_order_quantity')?" is-invalid":""),"autofocus",'placeholder'=>'Min Order Quantity']) !!}
        @endif
        @if ($errors->has('min_order_quantity'))
            <small class="text-danger">
                <strong>{{ $errors->first('min_order_quantity') }}</strong>
            </small>
        @endif
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
        {!! Form::label('stock_expiry_date', 'Stock Expiry Date', ['class' => 'form-label label-required']) !!}
        @if ($inventory == null) 
        {!! Form::date("stock_expiry_date",null,["class"=>"form-control".($errors->has('stock_expiry_date')?" is-invalid":""),"autofocus",'placeholder'=>'Stock Expiry Date']) !!}
        @else
        {!! Form::date("stock_expiry_date",$inventory->stock_expiry_date,["class"=>"form-control".($errors->has('stock_expiry_date')?" is-invalid":""),"autofocus",'placeholder'=>'Stock Expiry Date']) !!}
        @endif
        @if ($errors->has('stock_expiry_date'))
            <small class="text-danger">
                <strong>{{ $errors->first('stock_expiry_date') }}</strong>
            </small>
        @endif
    </div>
   
    </div>
    {{-- <table>
        <tr>
            <td class="label">{{__('Quantity')}}</td>
            <td>
                <div class="quantity">
                    <button type="button" id="sub" class="btn btn-default btn-sm btn sub"><i class="fa fa-minus"></i></button>
                    {!! Form::text("single",null,["class"=>"form-control form-control-sm".($errors->has('single')?" is-invalid":""),"autofocus"]) !!}
                    <button type="button" id="add" class="btn btn-default btn-sm btn  btn add"><i class="fa fa-plus"></i></button>
                </div>
            </td>
            <td class="label">{{__('Price')}}</td>
            <td>
                {!! Form::text("single_price",null,["class"=>"form-control form-control-sm".($errors->has('single_price')?" is-invalid":""), 'placeholder' => 'Price']) !!}
            </td>
        </tr>
        <tr>
            <td>
                {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {!! Form::text("remarks",null,["class"=>"form-control".($errors->has('remarks')?" is-invalid":""),"autofocus"]) !!}
            </td>

        </tr>

    </table> --}}
    <hr>

    <small><i><em>*</em> Prices will be per item</i></small><br>
    {!! Form::button("Submit",["type" => "submit","class"=>"btn btn-primary btn-small"])!!}
   <a  href="/supplier/inventory" class="btn btn-primary btn-small">Close</a>
    {{-- <button type="button" class="btn btn-default btn-small"  data-dismiss="modal" aria-label="Close">
        {{__('Close')}}
    </button> --}}



</div>

{!! Form::close() !!}

@endsection
