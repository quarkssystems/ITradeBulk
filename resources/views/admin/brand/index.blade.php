{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}

@extends('admin.layouts.main')

@section('header')
    {{-- @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __('MANAGE BRAND') }}</h1>
            {{-- <h1 class="display-2 text-white">{{ __('MANAGE Manufacturer') }}</h1> --}}
            {{-- <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p> --}}
            <a href="{{ route("$route.create") }}" class="btn btn-info">{{ __('ADD BRAND') }}</a>
            {{-- <a href="{{ route("$route.create") }}" class="btn btn-info">{{ __('ADD Manufacturer') }}</a> --}}

            <a href="{{ route('admin.manufacturerimport') }}" class="btn btn-success float-right"
                style="margin-left: 10px;">{{ __('Import') }}</a>
            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#myModal"
                style="margin-left: 20px;">{{ __('Upload Images') }}</button>
            @if ($data->count() > 0)
                <a href="{{ route("$route.index") }}?export_data"
                    class="btn btn-success float-right">{{ __('Export') }}</a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.brand.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Upload Image </h3>
                    <hr>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" method="post" action="{{ url('/admin/uploadZipImages') }}">
                        <label>Choose a zip file to upload: <input type="file" name="zip_file" id="zip_file" /></label>
                        <input type="hidden" name="foldername" id="foldername" value="manufacturer" /></label>
                        {{ csrf_field() }}
                        <input type="submit" name="submit" value="Upload" style="display:none" class="submitBtn" />
                        <br />
                    </form>
                    </hr>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" value="Upload" class="btn btn-success uploadBtn" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="photoPopup" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Upload Image </h3>
                    <hr>
                </div>
                <form action="/admin/storeBrandPhoto" method="POST" enctype="multipart/form-data">
                    {{-- <form action="{{ route('brand.storeBrandPhoto') }}" method="post" enctype="multipart/form-data"> --}}
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                            <input type="hidden" name="brand_id" value="" class="brand_id">
                            <input type="hidden" name="offSwitch" value="1" class="offSwitch">

                            {{-- {!! Form::model($brand, [
                            'route' => ["$route.update", $brand->uuid],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}

                        @if (isset($brand->id))
                            {!! Form::hidden('uuid', $brand->uuid) !!}
                        @endif --}}


                            {!! Form::label('icon', 'Brand icon', ['class' => 'form-label label-required']) !!}
                            {{-- {!! Form::label('icon', 'Manufacturer icon', ['class' => 'form-label label-required']) !!} --}}

                            {!! Form::file('icon', [
                                'class' => 'form-control dropify ' . ($errors->has('icon') ? ' is-invalid' : ''),
                            
                                'required' => 'true',
                            ]) !!}

                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>

                            @if ($errors->has('icon'))
                                <br><span class="help-block text-danger">

                                    <strong>{{ $errors->first('icon') }}</strong>

                                </span>
                            @endif

                            {{-- {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!} --}}
                        </div>
                        {{-- <form enctype="multipart/form-data" method="post" action="{{ url('/admin/uploadZipImages') }}">
                        <label>Choose a zip file to upload: <input type="file" name="zip_file" id="zip_file" /></label>
                        <input type="hidden" name="foldername" id="foldername" value="manufacturer" /></label>
                        {{ csrf_field() }}
                        <input type="submit" name="submit" value="Upload" style="display:none" class="submitBtn" />
                        <br />
                    </form>
                    </hr> --}}
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" value="Upload" class="btn btn-success " />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

                <form action="/admin/storeBrandPhoto" method="POST" enctype="multipart/form-data" id="offSwitchId">
                    <input type="hidden" name="brand_id" value="" class="brand_id">
                    <input type="hidden" name="offSwitch" value="0" class="offSwitch">
                    <input type="submit" name="submit" value="Upload" class="btn btn-success offSwitchIdNew"
                        style="display: none" />

                </form>

            </div>

        </div>
    </div>
@endsection


@section('footerData')
    <script>
        $(".uploadBtn").click(function() {
            $(".submitBtn").trigger("click");
        });

        $(document).on('click', '.onoff', function() {
            // console.log($(this).data('id'));
            console.log($(this).data('conoff'), $(this).data('fileexist'));

            if ($(this).data('fileexist') == 0) {
                if ($(this).data('conoff') == 1) {
                    // if ($(this).data('onoff') == 1) {
                    $('#photoPopup').modal('show');

                } else {

                    $.get("/admin/storeBrandPhoto/" +
                        $(this).data('id'),
                        function(data, status) {
                            location.reload();
                        });


                }
            } else {
                $.get("/admin/storeBrandPhoto/" +
                    $(this).data('id'),
                    function(data, status) {
                        location.reload();
                    });
            }

            $('.brand_id').val($(this).data('id'));
        });


        $("#photoPopup").on('hide.bs.modal', function() {
            $('.onoff').each(function() {
                console.log($(this).data('onoff'));
                if ($(this).data('onoff') == 0) {
                    $(this).prop('checked', false);
                }
            })
        });
    </script>
@stop
