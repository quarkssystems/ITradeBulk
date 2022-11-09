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

            <a href="{{ route("$route.index") }}" class="btn btn-info">{{__('Back')}}</a>

        </div>

    </div>

@endsection



@section('content')

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">

                    @if(isset($banner->uuid) && (isset($copy) && !$copy))

                        {!! Form::model($banner, ['route' => ["$route.update", $banner->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}

                    @else

                        {!! Form::model($banner, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}

                    @endif

                    <input autocomplete="off" name="hidden" type="text" style="display: none">



                        @if(isset($banner->uuid))

                            {!! Form::hidden('id', $banner->uuid) !!}

                        @endif

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('name', 'Name', ['class' => 'form-label']) !!}

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

                            {!! Form::label('status', 'Status', ['class' => 'form-label label-required']) !!}

                            {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}



                            @if ($errors->has('status'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('status') }}</strong>

                                </small>

                            @endif

                        </div>

                    </div>



                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('page_name', 'Page Name', ['class' => 'form-label label-required']) !!}

                            {!! Form::select("page_name",$page, null,["class"=>"form-control".($errors->has('page_name')?" is-invalid":""),"autofocus"]) !!}

                            

                            @if ($errors->has('page_name'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('page_name') }}</strong>

                                </small>

                            @endif

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('sequence_number', 'Sequence Number', ['class' => 'form-label label-required']) !!}

                            {!! Form::text("sequence_number",null,["class"=>"form-control".($errors->has('sequence_number')?" is-invalid":""),"autofocus",'placeholder'=>'Sequence Number']) !!}

                            

                            @if ($errors->has('sequence_number'))

                                <small class="text-danger">

                                    <strong>{{ $errors->first('sequence_number') }}</strong>

                                </small>

                            @endif

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                            <label class="form-label">
                                <input type="checkbox" name="in_slider" id="in_slider" @if(isset($banner->in_slider) && $banner->in_slider == "on") checked @endif>
                                Check to featured your banner in home page slider
                            </label>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-5 col-md-5 col-sm-10 col-xs-10 form-group required">

                                {!! Form::label('image', 'Banner', ['class' => 'form-label label-required']) !!}

                                {!! Form::file("image", [

                                                "class"=>"form-control dropify ".($errors->has('image')?" is-invalid":""),

                                                'data-default-file' => (isset($banner->uuid) && (isset($copy) && !$copy)) ? $banner->image : ''

                                                ]) !!}

                                <small><i>{{__('Only JPG and PNG supported')}}</i></small>

                                @if ($errors->has('image'))

                                    <br>

                                    <span class="help-block text-danger">

                                        <strong>{{ $errors->first('image') }}</strong>

                                    </span>

                                @endif

                            </div>



                            <!-- <div class="col-lg-5 col-md-5 col-sm-10 col-xs-10 form-group">

                                {!! Form::label('video', 'Banner', ['class' => 'form-label']) !!}

                                {!! Form::file("video", [

                                                "class"=>"form-control dropify ".($errors->has('video')?" is-invalid":""),

                                                'data-default-file' => (isset($banner->uuid) && (isset($copy) && !$copy)) ? $banner->video : ''

                                                ]) !!}

                                <small><i>{{__('Only Video supported')}}</i></small>

                                @if ($errors->has('video'))

                                    <br>

                                    <span class="help-block text-danger">

                                        <strong>{{ $errors->first('video') }}</strong>

                                    </span>

                                @endif

                            </div> -->

                            </div>

                    </div>





                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}

                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}

                            @if(!isset($banner->uuid))

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

