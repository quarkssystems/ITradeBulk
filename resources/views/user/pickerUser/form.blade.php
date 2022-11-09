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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {!! Form::model($user, [
                                'route' => ["$route.store"],
                                'method' => 'POST',
                                'id' => 'form',
                                'autocomplete' => 'off',
                                'name' => 'offerForm',
                                'files' => true,
                            ]) !!}

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('title', $title, null, [
                                        'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                                        'placeholder' => '*Select title',
                                    ]) !!}
                                    @if ($errors->has('title'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('first_name', 'First name', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('first_name', null, [
                                        'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => '*First name',
                                    ]) !!}

                                    @if ($errors->has('first_name'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('last_name', 'Last name', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('last_name', null, [
                                        'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),
                                        'placeholder' => '*Last name',
                                    ]) !!}
                                    @if ($errors->has('last_name'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('gender', 'Gender: ', ['class' => 'form-label label-required']) !!}
                                    @foreach ($gender as $genderKey => $genderValue)
                                        <label class="radio-inline">
                                            {!! Form::radio('gender', $genderKey, null, ['id' => $genderKey]) !!} {{ __($genderValue) }}
                                        </label>
                                    @endforeach
                                    @if ($errors->has('gender'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('email', 'Email', ['class' => 'form-label label-required']) !!}
                                    {!! Form::email('email', isset($user->id) ? $user->email : '', [
                                        'class' => 'form-control ' . ($errors->has('email') ? ' is-invalid' : ''),
                                        'placeholder' => '*Email',
                                        'autocomplete' => 'off',
                                        'autofill' => 'off',
                                        'data-old' => isset($user->id) ? $user->email : '',
                                    ]) !!}

                                    @if ($errors->has('email'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </small>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('password', 'Password', ['class' => 'form-label label-required']) !!}
                                    {!! Form::password('password', [
                                        'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                        'placeholder' => '*Password',
                                        'autocomplete' => 'new-password',
                                    ]) !!}

                                    @if ($errors->has('password'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'form-label label-required']) !!}
                                    {!! Form::password('password_confirmation', [
                                        'class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''),
                                        'placeholder' => '*Confirm password',
                                        'autocomplete' => 'new-password',
                                    ]) !!}

                                    @if ($errors->has('password_confirmation'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </small>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save_exit']) !!}
                                </div>
                                <div class="col-xs-12 col-lg-12">
                                    <small><i><label class="label-required"></label>
                                            {{ __('required fields') }}</i></small>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>


                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {!! Form::model($offerDeal, [
                                'route' => ["$route.store"],
                                'method' => 'POST',
                                'id' => 'form',
                                'autocomplete' => 'off',
                                'name' => 'offerForm',
                                'files' => true,
                            ]) !!}

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                    {!! Form::label('offer_method', 'Promotion Method', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select(
                                        'offer_method',
                                        $offer_method,
                                        null,
                                        ['class' => 'form-control', 'autofocus'],
                                        ['id' => 'offer_method'],
                                    ) !!}

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('title', null, [
                                        'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Promotion Title',
                                    ]) !!}


                                </div>

                            </div>

                            <div class="row">



                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('startdate', 'Start Date', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('startdate', null, [
                                        'id' => 'startdate',
                                        'class' => 'form-control' . ($errors->has('startdate') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Start Date',
                                    ]) !!}
                                    @if ($errors->has('start_date'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('startdate') }}</strong>
                                        </small>
                                    @endif

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('enddate', 'End Date', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('enddate', null, [
                                        'id' => 'enddate',
                                        'class' => 'form-control' . ($errors->has('enddate') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'End Date',
                                    ]) !!}

                                    @if ($errors->has('endtime'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('enddate') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('starttime', 'Start Time', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('starttime', null, [
                                        'id' => 'starttime',
                                        'class' => 'form-control' . ($errors->has('starttime') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Start Time',
                                    ]) !!}
                                    @if ($errors->has('starttime'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('starttime') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('endtime', 'End Time', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('endtime', null, [
                                        'id' => 'endtime',
                                        'class' => 'form-control' . ($errors->has('endtime') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'End Time',
                                    ]) !!}
                                    @if ($errors->has('endtime'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('enddate') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                    {!! Form::label('offer_type', 'Promotion Type', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('offer_type', $offer_type, null, ['class' => 'form-control', 'autofocus']) !!}

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('offer_value', 'Promotion Value', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('offer_value', null, [
                                        'class' => 'form-control' . ($errors->has('offer_value') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                </div>


                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required" id="product">
                                    {!! Form::label('product', 'Product', ['class' => 'form-label label-required']) !!}

                                    <select class="form-control form-control-sm select-dropdown" name="product_id">
                                        @foreach ($products as $key => $pd)
                                            <option value="{{ $pd['uuid'] }}">{{ $pd['name'] }}</option>
                                        @endforeach
                                    </select>


                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required" id="offercode">
                                    {!! Form::label('offercode', 'Promotion Code', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('offercode', null, [
                                        'class' => 'form-control' . ($errors->has('offercode') ? ' is-invalid' : ''),
                                        'autofocus',
                                    ]) !!}

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required" id="product">
                                    {!! Form::label('arrival_type', 'Arrival Type', ['class' => 'form-label label-required']) !!}

                                    <select class="form-control form-control-sm select-dropdown" name="product_id">
                                        @foreach ($arrival as $key => $pd)
                                            <option value="{{ $pd['uuid'] }}">{{ $pd['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('arrival_type'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('arrival_type') }}</strong>
                                        </small>
                                    @endif

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">

                                    <div class="form-group ">
                                        {!! Form::label('image', 'Image', ['class' => 'form-label']) !!}
                                        {!! Form::file('image_file', ['class' => 'form-control dropify ' . ($errors->has('icon') ? ' is-invalid' : '')]) !!}
                                        <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                                    </div>
                                    <div class="form-group ">
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

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                    {!! Form::textarea('description', null, [
                                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Prmotion description',
                                    ]) !!}

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-xs-12 col-lg-12">
                                    {!! Form::submit('Save', ['type' => 'submit', 'class' => 'btn btn-success', 'name' => 'save_exit']) !!}
                                </div>
                                <div class="col-xs-12 col-lg-12">
                                    <small><i><label class="label-required"></label>
                                            {{ __('required fields') }}</i></small>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footerScript')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(() => {

                console.log('dtfghjkl;');
            }, 1000);
            $("#product_id").on('change', function() {
                let product_id = $('#product_id :selected').val();
                // console.log(product_id);

                $.ajax({
                    url: "/admin/productData/" + product_id,
                    type: 'GET',
                    success: function(data, status) {
                        if (data != null) {
                            let barcode = (data.barcode != null) ? 'b' + data.barcode : 'b0';
                            let store_id = (data.store_id != null) ? 's' + data.store_id : 's0';
                            // let id = store_id+'_'+barcode;
                            let promotion_id = $('#promotion_id').val();

                            if (data.stock_expiry_date !== null) {
                                $('.stock_expiry_date_show').show();
                            } else {
                                $('.stock_expiry_date_show').hide();
                            }
                            $('#current_price').val(data.base_price);
                            $('#stock_expiry_date').val(data.stock_expiry_date);
                            let check = promotion_id.includes('s');
                            console.log('check: ', check);

                            let id = '';
                            if (check) {
                                var mySubString = promotion_id.split('s').pop().split('_f')[0];
                                console.log('+mySubString', 's' + mySubString);
                                id = promotion_id.replace('s' + mySubString, store_id + '_' +
                                    barcode);
                            } else {
                                id = store_id + '_' + barcode;
                            }
                            $('#promotion_id').val(id);
                        } else {
                            $('#promotion_id').val('');
                            $('#current_price').val('');
                            $('#stock_expiry_date').val('');
                            $('.stock_expiry_date_show').hide();
                        }
                        console.log(data);
                        // location.reload();
                    },
                    error: function(data) {
                        $('#promotion_id').val('');
                        $('#current_price').val('');
                        $('#stock_expiry_date').val('');
                        $('.stock_expiry_date_show').hide();
                    }
                });


                // $.get("/admin/productData/" +
                //     product_id,
                //     function(data, status) {

                //     });

            });

            $("#period_from").on('change', function() {
                // console.log(this);
                var formattedDate = new Date($(this).val());
                var d = formattedDate.getDate();
                var m = formattedDate.getMonth();
                m += 1; // JavaScript months are 0-11
                var y = formattedDate.getFullYear();

                let date = d + '' + m + '' + y;

                let promotion_id = $('#promotion_id').val();

                let check = promotion_id.includes('_f');
                let id = '';
                if (check) {
                    var mySubString = promotion_id.split('_f').pop().split('_')[0];
                    id = promotion_id.replace('_f' + mySubString, '_f' + date);
                } else {
                    id = promotion_id + '_f' + date;
                }
                $('#promotion_id').val(id);
            });

            $("#period_to").on('change', function() {
                // console.log(this);
                var formattedDate = new Date($(this).val());
                var d = formattedDate.getDate();
                var m = formattedDate.getMonth();
                m += 1; // JavaScript months are 0-11
                var y = formattedDate.getFullYear();

                let date = d + '' + m + '' + y;

                let promotion_id = $('#promotion_id').val();

                let check = promotion_id.includes('_e');
                let id = '';
                if (check) {
                    var mySubString = promotion_id.split('_e').pop().split('_')[0];
                    id = promotion_id.replace('_e' + mySubString, '_e' + date);
                } else {
                    id = promotion_id + '_e' + date;
                }
                $('#promotion_id').val(id);
            });
        });
        // $(document).ready(function() {
        //     console.log('guhjk');
        //     $('#offercode').hide();


        //     var method = $('#offer_method').val();
        //     // alert(method);
        //     if (method == 'OFFER') {
        //         $('#product').show();
        //         $('#offercode').hide();
        //     }
        //     if (method == 'COUPON CODE') {
        //         $('#product').hide();
        //         $('#offercode').show();
        //     }


        //     $('#offer_method').change(function() {
        //         var method = $(this).val();
        //         if (method == 'OFFER') {
        //             $('#product').show();
        //             $('#offercode').hide();
        //         }
        //         if (method == 'COUPON CODE') {
        //             $('#product').hide();
        //             $('#offercode').show();
        //         }
        //     });

        // });
    </script>
@endsection
