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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">

                    {!! Form::model($user, ['route' => ["$route.update", $user->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm']) !!}

                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if(isset($user->id))
                        {!! Form::hidden('uuid', $user->uuid) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("title",null,["class"=>"form-control".($errors->has('title')?" is-invalid":""),"autofocus",'placeholder'=>'Title']) !!}
                            @if ($errors->has('title'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}
                                <textarea class="form-control" placeholder="You can add your success story here." rows="3" name="description" cols="50" id="description">{{$user->description}}</textarea>

                            @if ($errors->has('description'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_exit'])!!}
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

@endsection
@section("footerScript")
    @include("utils.map")
@endsection