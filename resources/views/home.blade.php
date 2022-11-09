@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                        @if(Auth::user()->role == "SUPPLIER")
                            <a href="{{route('supplier.dashboard')}}">{{__('Go to dashboard')}}</a>
                        @else
                            <a href="{{route('admin.dashboard')}}">{{__('Go to dashboard')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
