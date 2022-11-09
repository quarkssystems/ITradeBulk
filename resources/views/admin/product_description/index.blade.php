{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __($pageTitle) }}</h1>
            {{-- <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p> --}}

        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.product_description.grid')
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
                        <input type="hidden" name="foldername" id="foldername" value="product" /></label>
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
@endsection


@section('footerData')
    <script>
        $(".uploadBtn").click(function() {
            $(".submitBtn").trigger("click");
        });
    </script>
@stop
