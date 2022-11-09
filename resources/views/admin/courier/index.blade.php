{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */
 --}}

@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="display-2 text-white">{{ __('Courier Management') }}</h1>
            {{-- <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p> --}}
            <a href="{{ route("$route.create") }}" class="btn btn-info">{{ __('ADD COURIER') }}</a>

        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.courier.grid')
                    </div>
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
        $(document).on('click', '.onoff', function() {
            console.log($(this).data('conoff'));

            $.get("/admin/changeCourierStatus/" +
                $(this).data('id'),
                function(data, status) {
                    // location.reload();
                });

        });
    </script>
@stop
