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


        </div>

        <div class="col-md-12">

            <div class="content-header ">

                <form action="{{ route('supplier.supplier-delivery-post') }}" method="post">
                    @csrf
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                        <input type="radio" name="supplier_delivery" id="supplier_delivery" value="itb_delivery"
                            {{ auth()->user()->supplier_delivery == 'itb_delivery' ? 'checked' : '' }}> ITB
                        Delivery

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                        <input type="radio" name="supplier_delivery" id="supplier_delivery" value="own_distributor"
                            {{ auth()->user()->supplier_delivery == 'own_distributor' ? 'checked' : '' }}>
                        Own
                        Distributor

                        @if ($errors->has('supplier_delivery'))
                            <small class="text-danger">
                                <strong>{{ $errors->first('supplier_delivery') }}</strong>
                            </small>
                        @endif
                    </div>
                    <input type="hidden" name="rateData" id="rateData" value="{{ auth()->user()->delivery_rate }}"
                        disabled>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group showRate" style="display: none">
                        {!! Form::label('rate', 'Rate', ['class' => 'form-label label-required']) !!}
                        {!! Form::text('rate', null, [
                            'class' => 'form-control' . ($errors->has('rate') ? ' is-invalid' : ''),
                            'placeholder' => 'Rate',
                        ]) !!}
                        @if ($errors->has('rate'))
                            <small class="text-danger">
                                <strong>{{ $errors->first('rate') }}</strong>
                            </small>
                        @endif
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save_exit']) !!}
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <!-- The Modal -->

    <div class="modal" id="prevOrderModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- Modal Header -->

                <div class="modal-header">

                    <h4 class="modal-title">Would you like to go with previous order ?</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>



                <!-- Modal body -->

                <div class="modal-body">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <a href="{{ route('user.fav-orders.index') }}" class="btn btn-success">Ok</a>



                    </div>

                </div>



            </div>

        </div>

    </div>

    </div>
@endsection

@section('footerScript')
    <script>
        $(document).ready(function() {
            let valCheck = $('input[name=supplier_delivery]:checked').val()
            if (valCheck == 'own_distributor') {
                $('.showRate').show();
                $('#rate').val($('#rateData').val());
            }
            $('input[name=supplier_delivery]').on('change', function() {

                let val = $(this).val();
                if (val == 'own_distributor') {
                    $('.showRate').show();
                } else {
                    $('.showRate').hide();
                }
            });
        });
    </script>
@endsection
