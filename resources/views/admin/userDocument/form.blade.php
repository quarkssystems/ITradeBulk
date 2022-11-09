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

                    {!! Form::open(['route' => ["$route.store", $user->uuid], 'method'=>'POST','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                        {!! Form::hidden('user_id', $user->uuid) !!}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                @foreach($documentTypes as $key => $documentType)
                                    <tr>
                                        <td width="40%">
                                            {!! Form::hidden("title[$key]", $documentType['title']) !!}

                                            @php($document = $user_document->ofTitle($documentType['title'])->exists() ? $user_document->ofTitle($documentType['title'])->first() : false)

                                            {!! Form::hidden("document_one_exists[$key]", $document ? 'YES' : 'NO') !!}
                                            {!! Form::label("document_one[$key]", $documentType['title'], ['class' => $documentType['required'] == 'YES' ? 'input-label label-required' : 'input-label']) !!}

                                            @if($document)
                                                <a data-toggle="tooltip" title="{{__("Download")}}" href="{{$document->document_file_one}}" download=""><i class="fa fa-download"></i></a>
                                            @endif

                                            {!! Form::file("document_one[$key]", [
                                            "class"=>"form-control dropify ".($errors->has('document_one')?" is-invalid":""),
                                            'data-default-file' => $document ? $document->document_file_one : ''
                                            ]) !!}
                                            <small><i>{{__('Only JPG, PNG and PDF supported')}}</i></small>
                                            @if ($errors->has('document_one.'.$loop->index))
                                                <br><span class="help-block text-danger">
                        <strong>{{ $errors->first('document_one.'.$loop->index) }}</strong>
                    </span>
                                            @endif
                                        </td>
                                        <td width="60%">
                                            <div class="form-group">
                                                {!! Form::label("comment[$key]", 'Comment', ['class' => 'form-label']) !!}
                                                {!! Form::textarea("comment[$key]",$document ? $document->comment : null,["class"=>"form-control".($errors->has('comment')?" is-invalid":""),'placeholder'=>'Comment', 'rows' => 3]) !!}

                                                @if ($errors->has('comment'))
                                                    <small class="text-danger">
                                                        <strong>{{ $errors->first('comment') }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                            {!! Form::label("approved[$key]", 'Approved?', ['class' => $documentType['required'] == 'YES' ? 'input-label label-required' : 'input-label']) !!}
                                            {!! Form::select("approved[$key]",['YES' => 'YES', 'NO' => 'NO'], $document ? $document->approved : 'NO',["class"=>"form-control".($errors->has('approved')?" is-invalid":"")]) !!}

                                            @if ($errors->has('approved'))
                                                <small class="text-danger">
                                                    <strong>{{ $errors->first('approved') }}</strong>
                                                </small>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if ($errors->has('document_one.*'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('document_one') }}</strong>
                                </small>
                            @endif
                            @if ($errors->has('document_one'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('document_one') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
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
