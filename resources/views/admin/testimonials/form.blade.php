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
                    @if(isset($testimonial->uuid) && (isset($copy) && !$copy))
                        {!! Form::model($testimonial, ['route' => ["$route.update", $testimonial->uuid], 'method'=>'PUT','id'=>'form', 'autocomplete' => 'off', 'files' => true]) !!}
                    @else
                        {!! Form::model($testimonial, ['route' => ["$route.store"], 'id'=>'form', 'autocomplete' => 'off', 'name' => 'usersForm', 'files' => true]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                        @if(isset($testimonial->uuid))
                            {!! Form::hidden('uuid', $testimonial->uuid) !!}
                        @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('type', 'Client Type', ['class' => 'form-label label-required']) !!}
                            {!! Form::select("type",array('' => 'Select Client Type','supplier' => 'SUPPLIER', 'vendor' => 'VENDOR'), null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                            @if ($errors->has('name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('client_id', 'Client Name', ['class' => 'form-label label-required']) !!}
                            @if($testimonial->client_id)
                                {!! $testimonial->client_id !!}
                            @else
                                {!! Form::select("client_id",array(), null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}
                            @endif
                            @if ($errors->has('client_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('client_id') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('slug', 'Slug', ['class' => 'form-label label-required']) !!}
                            {!! Form::text("slug",null,["class"=>"form-control".($errors->has('slug')?" is-invalid":""),"autofocus",'placeholder'=>'Slug']) !!}

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
                            {!! Form::label('message', 'Message', ['class' => 'form-label label-required']) !!}
                            {!! Form::textarea('message',null,['class'=>'form-control']) !!}
                            @if ($errors->has('message'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('message') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit("Save & Exit",["type" => "submit","class"=>"btn btn-primary", 'name' => 'save_exit'])!!}
                            {!! Form::submit("Save & Continue",["type" => "submit","class"=>"btn btn-success", 'name' => 'save_continue'])!!}
                            @if(!isset($testimonial->uuid))
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

$('#type').on('change', function() {
  $.ajax({

        type:'POST',

        url:"{{url('admin/testimonials/getClients')}}",

        data:{_token: "{{ csrf_token() }}",type:this.value},

        success:function(data){
            $("#client_id").empty().append(data);

        }

    });
});
</script>
@endsection
