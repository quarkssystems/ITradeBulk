{{--
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 11:17 AM
 */
 --}}
@extends('admin.layouts.main')

@section('header')
    <div class="row">
        <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">{{ $pageTitle }}</h1>
            <a href="{{ route("$route.index") }}" class="btn btn-info">{{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($courier->id))
                        {!! Form::model($courier, [
                            'route' => ["$route.update", $courier->id],
                            'method' => 'PUT',
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::model($courier, [
                            'route' => ["$route.store"],
                            'id' => 'form',
                            'autocomplete' => 'off',
                            'name' => 'usersForm',
                            'files' => true,
                        ]) !!}
                    @endif
                    <input autocomplete="off" name="hidden" type="text" style="display: none">

                    @if (isset($courier->id))
                        {!! Form::hidden('id', $courier->id) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('name', 'Name', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('name', null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Name',
                                'rows' => 4,
                            ]) !!}

                            @if ($errors->has('name'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('account', 'Account', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('account', null, [
                                'class' => 'form-control' . ($errors->has('account') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Account',
                                'rows' => 4,
                            ]) !!}

                            @if ($errors->has('account'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('account') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('link_to_portal', 'Link To Portal', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('link_to_portal', null, [
                                'class' => 'form-control' . ($errors->has('link_to_portal') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Link To Portal',
                                'rows' => 4,
                            ]) !!}

                            @if ($errors->has('link_to_portal'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('link_to_portal') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('address', 'Address', ['class' => 'form-label label-required']) !!}
                            {!! Form::textarea('address', null, [
                                'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Address',
                                'rows' => 4,
                            ]) !!}

                            @if ($errors->has('address'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('default_courier', 'Default Courier', ['class' => 'form-label label-required']) !!}
                            <select name="default_courier" id="default_courier" class="form-control">
                                <option value="">Select Option</option>
                                <option {{ $courier->default_courier == '1' ? 'selected' : '' }} value="1">Yes</option>
                                <option {{ $courier->default_courier == '0' ? 'selected' : '' }} value="0">No</option>
                            </select>
                            {{-- {!! Form::text('default_courier', null, [
                                'class' => 'form-control' . ($errors->has('default_courier') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Default Courier',
                            ]) !!} --}}

                            @if ($errors->has('default_courier'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('default_courier') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('delivery_option', 'Delivery Option', ['class' => 'form-label label-required']) !!}
                            <select name="delivery_option" id="delivery_option" class="form-control delivery_option">
                                <option value="">Select Option</option>
                                <option {{ $courier->delivery_option == 'Same Day' ? 'selected' : '' }} value="Same Day">
                                    Same Day</option>
                                <option {{ $courier->delivery_option == 'Express' ? 'selected' : '' }} value="Express">
                                    Express</option>
                                <option {{ $courier->delivery_option == 'Economy' ? 'selected' : '' }} value="Economy">
                                    Economy</option>
                            </select>
                            {{-- {!! Form::text('delivery_option', null, [
                                'class' => 'form-control' . ($errors->has('delivery_option') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Delivery Option',
                            ]) !!} --}}

                            @if ($errors->has('delivery_option'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('delivery_option') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('upload_option_pic', 'Upload Option Pic', ['class' => 'form-label label-required']) !!}
                            {!! Form::file('upload_option_pic', [
                                'class' => 'form-control dropify ' . ($errors->has('upload_option_pic') ? ' is-invalid' : ''),
                                'data-default-file' => isset($courier->id) ? $courier->upload_option_pic : '',
                            ]) !!}
                            <small><i>{{ __('Only JPG and PNG supported') }}</i></small>
                            @if ($errors->has('upload_option_pic'))
                                <br><span class="help-block text-danger">
                                    <strong>{{ $errors->first('upload_option_pic') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('std_lead_time', 'Std Lead Time', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('std_lead_time', null, [
                                'class' => 'form-control' . ($errors->has('std_lead_time') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Std Lead Time',
                                'rows' => 4,
                                'readonly',
                            ]) !!}
                            {{-- {!! Form::date('std_lead_time', null, [
                                'class' => 'form-control' . ($errors->has('std_lead_time') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Std Lead Time',
                                'rows' => 4,
                            ]) !!} --}}

                            @if ($errors->has('std_lead_time'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('std_lead_time') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('courier_lead_time', 'Courier Lead Time', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('courier_lead_time', null, [
                                'class' => 'form-control' . ($errors->has('courier_lead_time') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Courier Lead Time',
                                'rows' => 4,
                                'readonly',
                            ]) !!}
                            {{-- {!! Form::date('courier_lead_time', null, [
                                'class' => 'form-control' . ($errors->has('courier_lead_time') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Courier Lead Time',
                                'rows' => 4,
                            ]) !!} --}}

                            @if ($errors->has('courier_lead_time'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('courier_lead_time') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('delivery_markup', 'Delivery Markup', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('delivery_markup', null, [
                                'class' => 'form-control' . ($errors->has('delivery_markup') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Delivery Markup',
                                'rows' => 4,
                                'readonly',
                            ]) !!}

                            @if ($errors->has('delivery_markup'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('delivery_markup') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('min_delivery_fee', 'Min Delivery Fee', ['class' => 'form-label label-required']) !!}
                            {!! Form::text('min_delivery_fee', null, [
                                'class' => 'form-control' . ($errors->has('min_delivery_fee') ? ' is-invalid' : ''),
                                'autofocus',
                                'placeholder' => 'Min Delivery Fee',
                                'rows' => 4,
                                'readonly',
                            ]) !!}

                            @if ($errors->has('min_delivery_fee'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('min_delivery_fee') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                            {!! Form::label('is_own', 'Own Courier', ['class' => 'form-label label-required']) !!}
                            <select name="is_own" id="is_own" class="form-control">
                                <option value="">Select Option</option>
                                <option {{ $courier->is_own == '1' ? 'selected' : '' }} value="1">Yes</option>
                                <option {{ $courier->is_own == '0' ? 'selected' : '' }} value="0">No</option>
                            </select>

                            @if ($errors->has('is_own'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('is_own') }}</strong>
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required userShow"
                            style="display: none">
                            {!! Form::label('own_user_id', 'User', ['class' => 'form-label label-required']) !!}
                            <select name="own_user_id" id="own_user_id" class="form-control">
                                <option value="">Select Option</option>
                                @foreach ($users as $key => $user)
                                    <option {{ $courier->own_user_id == $user->uuid ? 'selected' : '' }}
                                        value="{{ $user->uuid }}">
                                        {{ $user->first_name . ' ' . $user->last_name }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('own_user_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('own_user_id') }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12 col-lg-12">
                            {!! Form::submit('Save & Exit', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'save_exit']) !!}
                            {!! Form::submit('Save & Continue', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'name' => 'save_continue',
                            ]) !!}
                            @if (!isset($courier->id))
                                {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-warning']) !!}
                            @endif
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <small><i><label class="label-required"></label> {{ __('required fields') }}</i></small>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerData')
    <script>
        $(document).ready(function() {

            let is_own = $('#is_own :selected').val();
            if (is_own == '1') {
                $('.userShow').show();
            } else {
                $('.userShow').hide();

            }

            $('#is_own').on('change', function() {
                if ($(this).val() == '1') {
                    $('.userShow').show();
                } else {
                    $('.userShow').hide();

                }
            })

            $(document).on('change', '.delivery_option', function() {
                if ($(this).val() == '') {
                    $('#std_lead_time').val('');
                    $('#courier_lead_time').val('');
                    $('#delivery_markup').val('');
                    $('#min_delivery_fee').val('');
                }
                if ($(this).val() == 'Same Day') {
                    $('#std_lead_time').val('24');
                    $('#courier_lead_time').val('24');
                    $('#delivery_markup').val('25%');
                    $('#min_delivery_fee').val('5%');
                }
                if ($(this).val() == 'Express') {
                    $('#std_lead_time').val('24');
                    $('#courier_lead_time').val('24');
                    $('#delivery_markup').val('20%');
                    $('#min_delivery_fee').val('5%');
                }
                if ($(this).val() == 'Economy') {
                    $('#std_lead_time').val('48');
                    $('#courier_lead_time').val('48');
                    $('#delivery_markup').val('15%');
                    $('#min_delivery_fee').val('5%');
                }
            });
        })
    </script>
@stop
