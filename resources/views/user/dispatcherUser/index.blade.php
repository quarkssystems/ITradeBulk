{{--
/**
 * Created by PhpStorm.
 * User: Mohit
 */
 --}}

@extends('supplier.layouts.main')

@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{ $pageTitle }}</h4>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('frontend.helpers.globalMessage.message')
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h4> <a href="{{ route($route . '.create') }}" class="btn btn-info">Add Dispatcher User</a></h4>
                    <div class="data-grid">
                        @include('user.dispatcherUser.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScript')
    <script>
        $(document).on('click', '.onoff', function() {
            // console.log($(this).data('id'));
            console.log($(this).data('conoff'));

            $.get("/supplier/usersStatusChange/" +
                $(this).data('id'),
                function(data, status) {
                    location.reload();
                });

        });
    </script>
@endsection
