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

                    @if(isset($shortcode->uuid) && (isset($copy) && !$copy))

                        {!! Form::model($shortcode, ['route' => ["$route.update", $shortcode->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'files' => true]) !!}

                    @else

                        {!! Form::model($shortcode, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}

                    @endif

                    <input autocomplete="off" name="hidden" type="text" style="display: none">



                        @if(isset($shortcode->uuid))

                            {!! Form::hidden('uuid', $shortcode->uuid) !!}

                        @endif

                    <div class="row">

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('shortcode_name', 'Shortcode Name', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("shortcode_name",null,["class"=>"form-control".($errors->has('shortcode_name')?" is-invalid":""),"autofocus",'placeholder'=>'Shortcode Name','oninput'=>'updateSlug(this)','id'=>'name']) !!}

                            <i>Use [ ] these bracket to create shortcodes e.g: [FIRSTNAME].</i>

                            @if ($errors->has('shortcode_name'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('shortcode_name') }}</strong>

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

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('shortcode_label', 'Shortcode Label', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("shortcode_label",null,["class"=>"form-control".($errors->has('shortcode_label')?" is-invalid":""),"autofocus",'placeholder'=>'Shortcode Label']) !!}



                            @if ($errors->has('shortcode_label'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('shortcode_label') }}</strong>

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

                      

                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}

                            {!! Form::textarea('description',null,['class'=>'form-control']) !!}

                            @if ($errors->has('description'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('description') }}</strong>

                                </small>

                            @endif

                        </div> -->

                    </div>



                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}

                            @if(!isset($shortcode->uuid))

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

    CKEDITOR.replace( 'description' );

</script>

@stop