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

                    @if(isset($emailTemplate->uuid) && (isset($copy) && !$copy))

                        {!! Form::model($emailTemplate, ['route' => ["$route.update", $emailTemplate->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'files' => true]) !!}

                    @else

                        {!! Form::model($emailTemplate, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}

                    @endif

                    <input autocomplete="off" name="hidden" type="text" style="display: none">



                        @if(isset($emailTemplate->uuid))

                            {!! Form::hidden('uuid', $emailTemplate->uuid) !!}

                        @endif

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("name",null,["class"=>"form-control".($errors->has('name')?" is-invalid":""),"autofocus",'placeholder'=>'Name','oninput'=>'updateSlug(this)','id'=>'name']) !!}



                            @if ($errors->has('name'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('name') }}</strong>

                                </small>

                            @endif

                        </div>

                        

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("slug",null,["class"=>"form-control".($errors->has('slug')?" is-invalid":""),"autofocus",'placeholder'=>'Slug','id'=>'slug', 'readOnly']) !!}



                            @if ($errors->has('slug'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('slug') }}</strong>

                                </small>

                            @endif

                        </div>



                        <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}

                            {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}



                            @if ($errors->has('status'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('status') }}</strong>

                                </small>

                            @endif

                        </div> -->



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

                            {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}

                            {!! Form::textarea('description',null,['class'=>'form-control']) !!}

                            @if ($errors->has('description'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('description') }}</strong>

                                </small>

                            @endif

                        </div>

                    </div>



                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                        @if(isset($shortCodes) && count($shortCodes) > 0)

                            <h4>Short Codes : </h4>

                            @foreach($shortCodes as $shortCode)

                            <a class="btn btn-light addShortCode" data-value="{{$shortCode->shortcode_name}}"> {{$shortCode->shortcode_label}}</a>

                            @endforeach

                        @endif

                        </div>

                    </div>



                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}

                            @if(!isset($emailTemplate->uuid))

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

CKEDITOR.replace('description');

CKEDITOR.on("instanceReady", function(ev){

    ev.editor.on("paste", function (ev) {

        var _html=ev.data.html;



        //On paste, replace DIV => P

        var re = new RegExp("(<DIV)([^>]*>.*?)(<\/DIV>)","gi") ;

        _html = _html.replace( re, "<p$2</p>" ) ;

        ev.data.html = _html;

    });

});

$(document).ready(function (){

    $(".addShortCode").click(function(){

        var data = CKEDITOR.instances['description'].getData()+$(this).data('value');

        CKEDITOR.instances['description'].setData(data);

    });

});

//     if("{{$emailTemplate->uuid}}"){

//         var element = CKEDITOR.dom.element.createFromHtml( '{{$emailTemplate->description}}' );

//         CKEDITOR.instances.description.insertHtml('{{$emailTemplate->description}}');

//         // CKEDITOR.on("instanceReady", function(ev){

//         //     console.log(ev);

//         //     // ev.editor.on("paste", function (ev) {

//         //         // var _html='';

//         //         // var re = new RegExp("(<DIV)([^>]*>.*?)(<\/DIV>)","gi") ;

//         //         _html = _html.replace( re, "{{$emailTemplate->description}}" ) ;

//         //         console.log(_html);

//         //         ev.data.html = _html;

//         //     // });

//         // });

//     //     CKEDITOR.insertText("{{$emailTemplate->description}}");

//     //     // CKEDITOR.instances.s1_CKEditor.insertText(grid);

//     //     // CKEDITOR.instances.editor1.setData('<div class="simplebox align-left"><h2 class="simplebox-title">Title</h2><div class="simplebox-content"><p>Content...</p></div></div>', {

//     //     //     callback: function() {

//     //     //         this.checkDirty(); // true

//     //     //     }

//     //     // });

//     //     // var imgHtml = CKEDITOR.dom.element.createFromHtml("{{$emailTemplate->description}}");

//     //     // CKEDITOR.instances.body.setData(imgHtml);

//     //     // // $('textarea').val("{{$emailTemplate->description}}");

//         alert("{{$emailTemplate->uuid}}");

//     }

// });

</script>

@stop