{{-- /**

 * Created by PhpStorm.

 * User: mayank

 * Date: 22/11/18

 * Time: 11:17 AM

 */ --}}

@extends('admin.layouts.main')



@section('header')
    <div class="row">

        <div class="col-lg-7 col-md-10">

            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>

            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>

        </div>

    </div>
@endsection



@section('content')
    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="card-body">

                    @if (isset($brand->id) && (isset($copy) && !$copy))
                        {!! Form::model($brand, [
                            'route' => ["$route.update", $brand->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::model($brand, [
                            'route' => ["$route.store"],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @endif

                    <input autocomplete="off" name="hidden" type="text" style="display: none">



                    @if (isset($brand->id))
                        {!! Form::hidden('uuid', $brand->uuid) !!}
                    @endif

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}

                            {!! Form::text('name', null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Name',
                                'oninput' => 'updateSlug(this)',
                                'id' => 'name',
                            ]) !!}



                            @if ($errors->has('name'))
                                <small class="text-danger">

                                    <strong>{{ $errors->first('name') }}</strong>

                                </small>
                            @endif

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}

                            {!! Form::text('slug', null, [
                                'class' => 'form-control' . ($errors->has('slug') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Slug',
                                'id' => 'slug',
                                'readOnly',
                            ]) !!}



                            @if ($errors->has('slug'))
                                <small class="text-danger">

                                    <strong>{{ $errors->first('slug') }}</strong>

                                </small>
                            @endif

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}

                            {!! Form::select('status', $statuses, null, [
                                'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                                'autofocus',
                            ]) !!}



                            @if ($errors->has('status'))
                                <small class="text-danger">

                                    <strong>{{ $errors->first('status') }}</strong>

                                </small>
                            @endif

                        </div>

                    </div>



                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                            <div class="form-group required">

                                {!! Form::label('description', 'Description', ['class' => 'form-label label-required']) !!}

                                {!! Form::textarea('description', null, [
                                    'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'Description',
                                    'rows' => 4,
                                ]) !!}



                                @if ($errors->has('description'))
                                    <small class="text-danger">

                                        <strong>{{ $errors->first('description') }}</strong>

                                    </small>
                                @endif

                            </div>

                            <div class="form-group required">

                                {!! Form::label('meta_title', 'Meta title', ['class' => 'form-label label-required']) !!}

                                {!! Form::text('meta_title', null, [
                                    'class' => 'form-control' . ($errors->has('meta_title') ? ' is-invalid' : ''),
                                    'autofocus',
                                    'placeholder' => 'Meta title',
                                ]) !!}



                                @if ($errors->has('meta_title'))
                                    <small class="text-danger">

                                        <strong>{{ $errors->first('meta_title') }}</strong>

                                    </small>
                                @endif

                            </div>







                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('icon', 'Brand icon', ['class' => 'form-label label-required']) !!}
                            {{-- {!! Form::label('icon', 'Manufacturer icon', ['class' => 'form-label label-required']) !!} --}}

                            {!! Form::file('icon', [
                                'class' => 'form-control dropify ' . ($errors->has('icon') ? ' is-invalid' : ''),
                            
                                'data-default-file' => isset($brand->id) && (isset($copy) && !$copy) ? $brand->icon_file : '',
                            ]) !!}

                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>

                            @if ($errors->has('icon'))
                                <br><span class="help-block text-danger">

                                    <strong>{{ $errors->first('icon') }}</strong>

                                </span>
                            @endif

                        </div>

                    </div>





                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('meta_description', 'Meta description', ['class' => 'form-label label-required']) !!}

                            {!! Form::textarea('meta_description', null, [
                                'class' => 'form-control' . ($errors->has('meta_description') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Meta description',
                                'rows' => 4,
                            ]) !!}



                            @if ($errors->has('meta_description'))
                                <small class="text-danger">

                                    <strong>{{ $errors->first('meta_description') }}</strong>

                                </small>
                            @endif

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                            {!! Form::label('meta_keywords', 'Meta keywords', ['class' => 'form-label label-required']) !!}

                            {!! Form::textarea('meta_keywords', null, [
                                'class' => 'form-control' . ($errors->has('meta_keywords') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Meta keywords',
                                'rows' => 4,
                            ]) !!}



                            @if ($errors->has('meta_keywords'))
                                <small class="text-danger">

                                    <strong>{{ $errors->first('meta_keywords') }}</strong>

                                </small>
                            @endif

                        </div>

                    </div>





                    <div class="form-group row">

                        <div class="col-xs-12 col-lg-12">

                            {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}

                            {!! Form::submit('Save & Continue', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'name' => 'save_continue',
                            ]) !!}

                            @if (!isset($brand->id))
                                {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                            @endif

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
@endsection
