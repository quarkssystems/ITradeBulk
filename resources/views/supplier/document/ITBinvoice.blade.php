@extends('frontend.layouts.main')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        @include('admin.invoice.itbinvoice_base')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
