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
                        @php($document = $user_document->ofTitle($documentType['title'])->where('document_file_one','!=',null)->exists() ? $user_document->ofTitle($documentType['title'])->first() : false)

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
                            <div class="alert alert-success">
                                {{$document ? (is_null($document->comment) ? 'Pending' :$document->comment)  : 'Pending'}}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label("approved[$key]", 'Approved?', ['class' => $documentType['required'] == 'YES' ? 'input-label label-required' : 'input-label']) !!}
                            <div class="alert alert-success">

                                {{$document ? (is_null($document->approved) ? 'Pending' :$document->approved): 'No'}}
                            </div>
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
        {!! Form::submit("Save",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
    </div>

</div>
{!! Form::close() !!}
