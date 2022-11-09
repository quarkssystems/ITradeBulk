{!! Form::model($inventory, ['route' => ["$route.store"],'id'=>'form', 'autocomplete' => 'off', 'name' => 'additemform', 'files' => true]) !!}
{{--<div class="modal-header">--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
        {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
{{--</div>--}}


<div class="modal-body">
    {!! Form::hidden("product_id",$product->uuid) !!}
    {!! Form::hidden("user_id",Auth::user()->uuid) !!}
    <h5 class="modal-title" id="exampleModalLabel">{{$product->name}}</h5>
<hr>
    <table>
        <tr>
            <td class="label">{{__('Quantity')}}</td>
            <td>
                <div class="quantity">
                    <button type="button" id="sub" class="btn btn-default btn-sm btn sub"><i class="fa fa-minus"></i></button>
                    {!! Form::text("single",null,["class"=>"form-control form-control-sm".($errors->has('single')?" is-invalid":""),"autofocus"]) !!}
                    <button type="button" id="add" class="btn btn-default btn-sm btn  btn add"><i class="fa fa-plus"></i></button>
                </div>
            </td>
            <td class="label">{{__('Price')}}</td>
            <td>
                {!! Form::text("single_price",null,["class"=>"form-control form-control-sm".($errors->has('single_price')?" is-invalid":""), 'placeholder' => 'Price']) !!}
            </td>
        </tr>
{{--    <tr>
            <td class="label">
                {{__('Shrink')}}</td>
            <td>
                <div class="quantity">
                    <button type="button" id="sub" class="btn btn-default btn-sm btn  btn-number btn-sm sub"><i
                                class="fa fa-minus"></i></button>
                    {!! Form::text("shrink",null,["class"=>"form-control form-control-sm".($errors->has('shrink')?" is-invalid":""),"autofocus",'onkeydown'=>'numbericOnly(event)','pattern'=>'[0-9.]+']) !!}
                    <button type="button" id="add" class="btn btn-default btn-sm btn  btn add"><i class="fa fa-plus"></i></button>
                </div>
            </td>
            <td>
                {!! Form::text("shrink_price",null,["class"=>"form-control form-control-sm".($errors->has('shrink_price')?" is-invalid":""), 'placeholder' => 'Shrink price']) !!}
            </td>
        </tr>
        <tr>
            <td class="label">
                {{__('Case')}} </td>
            <td>
                <div class="quantity">
                    <button type="button" id="sub" class="btn btn-default btn-sm btn  sub"><i class="fa fa-minus"></i></button>
                    {!! Form::text("case",null,["class"=>"form-control form-control-sm".($errors->has('case')?" is-invalid":""),"autofocus"]) !!}
                    <button type="button" id="add" class="btn btn-default btn-sm btn  add"><i class="fa fa-plus"></i></button>
                </div>
            </td>
            <td>
                {!! Form::text("case_price",null,["class"=>"form-control form-control-sm".($errors->has('case_price')?" is-invalid":""), 'placeholder' => 'Case price']) !!}
            </td>
        </tr>
        <tr>
            <td class="label">
                {{__('Pallet')}}</td>
            <td>
                <div class="quantity">
                    <button type="button" id="sub" class="btn btn-default btn-sm btn  sub"><i class="fa fa-minus"></i></button>
                    {!! Form::text("pallet",null,["class"=>"form-control form-control-sm".($errors->has('pallet')?" is-invalid":""),"autofocus"]) !!}
                    <button type="button" id="add" class="btn btn-default btn-sm btn  add"><i class="fa fa-plus"></i></button>
                </div>
            </td>
            <td>
                {!! Form::text("pallet_price",null,["class"=>"form-control form-control-sm".($errors->has('pallet_price')?" is-invalid":""), 'placeholder' => 'Pallet price']) !!}
            </td>
        </tr>

--}}
        <tr>
            <td>
                {!! Form::label('remarks', 'Remarks', ['class' => 'form-label']) !!}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {!! Form::text("remarks",null,["class"=>"form-control".($errors->has('remarks')?" is-invalid":""),"autofocus"]) !!}
            </td>

        </tr>

    </table>
    <hr>

    <small><i><em>*</em> Prices will be per item</i></small><br>
    {!! Form::button("Submit",["type" => "submit","class"=>"btn btn-primary btn-small"])!!}
    <button type="button" class="btn btn-default btn-small"  data-dismiss="modal" aria-label="Close">
        {{__('Close')}}
    </button>



</div>

{!! Form::close() !!}

