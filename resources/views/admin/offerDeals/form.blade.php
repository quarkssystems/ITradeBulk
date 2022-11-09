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
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {!! Form::model($offerdeal, [
                                'route' => ["$route.store"],
                                'method' => 'POST',
                                'id' => 'form',
                                'autocomplete' => 'off',
                                'name' => 'offerForm',
                                'files' => true,
                            ]) !!}

                            <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('promotion_id', 'Promotion ID', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('promotion_id', null, [
                                        'class' => 'form-control' . ($errors->has('promotion_id') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Promotion ID',
                                        'readonly',
                                    ]) !!}

                                    @if ($errors->has('promotion_id'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('promotion_id') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('product_id', 'Product', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('product_id', $products, null, ['class' => 'form-control', 'autofocus']) !!}

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">

                                    {!! Form::label('promotion_type', 'Promotion Type', ['class' => 'form-label label-required']) !!}
                                    <select name="promotion_type" id="promotion_type" class="form-control">
                                        <option value="">Select Promo Type</option>
                                        @foreach ($promoType as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $offerdeal->promotion_type == $key ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    {{-- {!! Form::select('promotion_type', $promoType, null, ['class' => 'form-control', 'autofocus']) !!} --}}

                                    {{-- {!! Form::text("promotion_type",null,["class"=>"form-control".($errors->has('promotion_type')?" is-invalid":""),"autofocus",'placeholder'=>'Promotion Type']) !!} --}}

                                    @if ($errors->has('promotion_type'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('promotion_type') }}</strong>
                                        </small>
                                    @endif
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('user_id', 'Supplier', ['class' => 'form-label label-required']) !!}
                                    {!! Form::select('user_id', $suppliers, null, ['class' => 'form-control', 'autofocus']) !!}


                                    @if ($errors->has('user_id'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('user_id') }}</strong>
                                        </small>
                                    @endif


                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('period_from', 'Period From', ['class' => 'form-label label-required']) !!}
                                    {!! Form::date('period_from', null, [
                                        'class' => 'form-control' . ($errors->has('period_from') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Period From',
                                    ]) !!}

                                    @if ($errors->has('period_from'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('period_from') }}</strong>
                                        </small>
                                    @endif

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('period_to', 'Period To', ['class' => 'form-label label-required']) !!}
                                    {!! Form::date('period_to', null, [
                                        'class' => 'form-control' . ($errors->has('period_to') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Period To',
                                    ]) !!}

                                    @if ($errors->has('period_to'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('period_to') }}</strong>
                                        </small>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required ">
                                    {!! Form::label('current_price', 'Current Price', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('current_price', null, [
                                        'class' => 'form-control' . ($errors->has('current_price') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Current Price',
                                        'readonly',
                                    ]) !!}

                                    @if ($errors->has('current_price'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('current_price') }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('promotion_price', 'Promotion Price', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('promotion_price', null, [
                                        'class' => 'form-control' . ($errors->has('promotion_price') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Promotion Price',
                                    ]) !!}

                                    @if ($errors->has('promotion_price'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('promotion_price') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required stock_expiry_date_show"
                                    style="display: none">
                                    {!! Form::label('stock_expiry_date', 'Stock Expiry Date', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text('stock_expiry_date', null, [
                                        'class' => 'form-control' . ($errors->has('stock_expiry_date') ? ' is-invalid' : ''),
                                        'autofocus',
                                        'placeholder' => 'Stock Expiry Date',
                                        'disabled',
                                    ]) !!}

                                    @if ($errors->has('stock_expiry_date'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('stock_expiry_date') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('title', 'Title', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text("title",null,["class"=>"form-control".($errors->has('title')?" is-invalid":""),"autofocus",'placeholder'=>'Offer Title']) !!}

                                    @if ($errors->has('title'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                     {!! Form::label('user_id', 'Supplier', ['class' => 'form-label label-required']) !!}
                                        {!! Form::select("user_id",$suppliers, null,["class"=>"form-control","autofocus"]) !!}

                                </div>

                                 @if ($errors->has('user_id'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </small>
                            @endif

                               <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('startdate', 'Start Date', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text("startdate",null,["id" => "startdate" ,"class"=>"form-control startdate","autofocus",'placeholder'=>'Start Date']) !!}

                                    @if ($errors->has('startdate'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('startdate') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                    {!! Form::label('enddate', 'Enddate', ['class' => 'form-label label-required']) !!}
                                    {!! Form::text("enddate",null,["id" => "enddate" ,"class"=>"form-control enddate","autofocus",'placeholder'=>'End Date']) !!}

                                    @if ($errors->has('enddate'))
                                        <small class="text-danger">
                                            <strong>{{ $errors->first('enddate') }}</strong>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                     {!! Form::label('brands_id', 'Brand', ['class' => 'form-label']) !!}
                                        {!! Form::select("brands_id",$brands, null,["class"=>"form-control","autofocus"]) !!}

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                     {!! Form::label('categories_id', 'Category', ['class' => 'form-label']) !!}
                                        {!! Form::select("categories_id",$categories, null,["class"=>"form-control","autofocus"]) !!}

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group ">
                                     {!! Form::label('offer_type', 'Promotion Type', ['class' => 'form-label label-required']) !!}
                                        {!! Form::select("offer_type",$offer_type, null,["class"=>"form-control","autofocus"]) !!}

                                      @if ($errors->has('offer_type'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('offer_type') }}</strong>
                                </small>
                            @endif  

                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                     {!! Form::label('offer_value', 'Promotion Value', ['class' => 'form-label label-required']) !!}
                                        {!! Form::text("offer_value", null,["class"=>"form-control","autofocus"]) !!}
                                @if ($errors->has('offer_value'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('offer_value') }}</strong>
                                </small>
                            @endif

                                </div>
                                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group required">
                                     {!! Form::label('offercode', 'Promotion Code', ['class' =>  'form-label label-required']) !!}
                                        {!! Form::text("offercode", null,["class"=>"form-control".($errors->has('offercode')?" is-invalid":""),"autofocus"]) !!}

                                         @if ($errors->has('offercode'))
                                <small class="text-danger">
                                    <strong>{{ $errors->first('offercode') }}</strong>
                                </small>
                            @endif

                                </div>


                               </div> 
                                
                            </div>  

                        <div class="row">
                          
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group ">
                                        {!! Form::label('image', 'Image', ['class' => 'form-label']) !!}
                                        {!! Form::file("image_file", ["class"=>"form-control dropify ".($errors->has('icon')?" is-invalid":"") ]) !!}
                                        <small><i>{{__('Only JPG and PNG supported')}}</i></small>
                                    </div>
                                   
                                   
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group">
                                        {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                       {!! Form::textarea("description",null,["class"=>"form-control".($errors->has('description')?" is-invalid":""),"autofocus",'placeholder'=>'Offer description']) !!}
     
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group required">
                                      {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                        {!! Form::select("status",$statuses, null,["class"=>"form-control".($errors->has('status')?" is-invalid":""),"autofocus"]) !!}

                                        @if ($errors->has('status'))
                                            <small class="text-danger">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </small>
                                        @endif
                                    </div>
                            </div> --}}

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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerData')
    <script>
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

                        let check = promotion_id.includes('s');
                        console.log('check: ', data);

                        if (data.stock_expiry_date !== null) {
                            $('.stock_expiry_date_show').show();
                        } else {
                            $('.stock_expiry_date_show').hide();
                        }
                        $('#current_price').val(data.base_price);
                        $('#stock_expiry_date').val(data.stock_expiry_date);

                        let id = '';
                        if (check) {
                            var mySubString = promotion_id.split('s').pop().split('_f')[0];
                            console.log('+mySubString', 's' + mySubString);
                            id = promotion_id.replace('s' + mySubString, store_id + '_' + barcode);
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
    </script>
@stop
