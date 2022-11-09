@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
        </div>
    </div>
@endsection

@section('content')
    @include($navTab)
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">
                        <form action="{{ route('admin.supplier-delivery-post') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->uuid }}">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                <input type="radio" name="supplier_delivery" id="supplier_delivery" value="itb_delivery"
                                    {{ $user->supplier_delivery == 'itb_delivery' ? 'checked' : '' }}> ITB
                                Delivery

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                <input type="radio" name="supplier_delivery" id="supplier_delivery"
                                    value="own_distributor"
                                    {{ $user->supplier_delivery == 'own_distributor' ? 'checked' : '' }}>
                                Own
                                Distributor

                                @if ($errors->has('supplier_delivery'))
                                    <small class="text-danger">
                                        <strong>{{ $errors->first('supplier_delivery') }}</strong>
                                    </small>
                                @endif
                            </div>
                            <input type="hidden" name="rateData" id="rateData" value="{{ $user->delivery_rate }}"
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
        </div>
    </div>
@endsection

@section('footerData')
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
