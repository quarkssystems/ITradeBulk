{{--

/**

 * User: Mohit

 */

 --}}

@extends('admin.layouts.main')



@section('header')

    <div class="row">

        <div class="col-lg-7 col-md-10">

            <h1 class="display-2 text-white">{{$pageTitle}}</h1>

            <a href="{{ route("$route.index") }}" class="btn btn-info">{{__('Back')}}</a>

        </div>

    </div>

@endsection



@section('content')

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">

                    @if(isset($cmsmodule->uuid) && (isset($copy) && !$copy))

                        {!! Form::model($cmsmodule, ['route' => ["$route.update", $cmsmodule->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'files' => true]) !!}

                    @else

                        {!! Form::model($cmsmodule, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}

                    @endif

                    <input autocomplete="off" name="hidden" type="text" style="display: none">



                        @if(isset($cmsmodule->uuid))

                            {!! Form::hidden('uuid', $cmsmodule->uuid) !!}

                        @endif

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("name",null,["class"=>"form-control".($errors->has('name')?" is-invalid":""),"autofocus",'placeholder'=>'Name','oninput'=>'updateSlug(this)', 'id'=>'name']) !!}



                            @if ($errors->has('name'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('name') }}</strong>

                                </small>

                            @endif

                        </div>

                        

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("slug",null,["class"=>"form-control".($errors->has('slug')?" is-invalid":""),"autofocus",'placeholder'=>'Slug','id'=>'slug','readOnly']) !!}



                            @if ($errors->has('slug'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('slug') }}</strong>

                                </small>

                            @endif

                        </div>



                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}

                            {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}



                            @if ($errors->has('status'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('status') }}</strong>

                                </small>

                            @endif

                        </div>



                    </div>



                    <!-- <div class="row">

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('type', 'Type', ['class' => 'form-label']) !!}

                            {!! Form::select("type",array('page' => 'Page', 'block' => 'Block'), null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                            

                            @if ($errors->has('type'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('type') }}</strong>

                                </small>

                            @endif

                        </div>

                    </div> -->

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('content', 'Content', ['class' => 'form-label label-required']) !!}

                            {!! Form::textarea('content',null,['class'=>'form-control']) !!}

                            @if ($errors->has('content'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('content') }}</strong>

                                </small>

                            @endif

                        </div>

                    </div>



                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}

                            @if(!isset($cmsmodule->uuid))

                                {!! Form::button("Reset",["type" => "reset","class"=>"btn btn-warning"])!!}

                            @endif

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

@section('scripts')

<script type="text/javascript">

    CKEDITOR.plugins.addExternal('divarea', 'ckeditor/plugins', 'plugin.js');

    CKEDITOR.replace('content');

    editor.on("instanceReady", function(ev){                    

        ev.editor.on("paste", function (ev) {

            var _html=ev.data.html;



            //On paste, replace DIV => P

            var re = new RegExp("(<DIV)([^>]*>.*?)(<\/DIV>)","gi") ;

            _html = _html.replace( re, "<p$2</p>" ) ;

            ev.data.html = _html;

        });

    });

</script>

@stop