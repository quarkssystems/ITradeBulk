@if (isset($product->is_promotion) && $product->is_promotion != 0)
    <img src="{{ asset('assets/frontend/images/on-promotion.png') }}" alt="" class="promotionset">
@endif
<div class="card-hover before ">
    <div class="product-list-image product-list-image-new">
        <a href="{{ route('productdetail', $product->slug) }} "> <img src="{{ checkImageExists($product->base_image) }}"
                class="img-fluid"></a>
    </div>
    <div class="content">
        <a href="{{ route('productdetail', $product->slug) }} ">
            <p class="title">{{ $product->name }}</p>
        </a>
        <div class="links">
            <p style="margin-right: 0px !important;" class="popupshow">
                <span>{{ $product->unit_value }}{{ $product->unit_name }}
                    @if ($product->stock_type)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{-- ({{$product->stock_type}}) --}}
                    @endif
                </span>


                {{-- @if (Auth::guest())
               <a href="javascript:;" data-product="#add-product-{{$product->uuid}}"  class="add-to-cart popover-external-html"><img src="{{asset('assets/frontend/images/addtocart.png')}}" class="img-fluid float-right " ></a>
                   {!! Form::open(['route' => "checkout.add-to-cart",'id' => "add-product-".$product->uuid ]) !!}
                 {!! Form::hidden('product_id', $product->uuid) !!}
                 {!! Form::hidden('single_qty', 1) !!}
                  {!! Form::close() !!}

            @elseif( Auth::user()->role == 'VENDOR') 
                  <a href="javascript:;" data-product="#add-product-{{$product->uuid}}"  class="add-to-cart popover-external-html"><img src="{{asset('assets/frontend/images/addtocart.png')}}" class="img-fluid float-right " ></a>
                   {!! Form::open(['route' => "checkout.add-to-cart",'id' => "add-product-".$product->uuid ]) !!}
                 {!! Form::hidden('product_id', $product->uuid) !!}
                 {!! Form::hidden('single_qty', 1) !!}
                  {!! Form::close() !!}
            @endif --}}

                <a tabindex="0" class="callPopover" role="button" data-toggle="popover" title="Add to Cart"
                    style="float: right;
            font-size: 1.50em;"
                    data-template="<div class='popover' role='tooltip'><div class='arrow'></div>
                                    <h3 class='popover-header'></h3>
                                    <div class='popover-body'>
                                    </div>
                                </div>"
                    data-content="
                    <label>Stock Type:</label>
                    <div class='row'><div class='col-sm-12'>                    
                        @foreach ($product->child as $cp)
@if (Auth::guest())
<a href='javascript:;' data-product='#add-product-{{ $cp->uuid }}' title='Add to Cart' class='add-to-cartb btn btn-sm btn-outline-success popover-external-html' style='margin-bottom: 1px;'><i class='fas fa-shopping-cart'></i> {{ $cp->stock_type }} </a><span style='padding:5px;font-weight: bold;font-size: medium;position: relative;top: 8px;'>{{ $cp->stock_of . 'x' . $cp->unit_value . '' . $cp->unit_name }}</span>
                                <form action='{{ route('checkout.add-to-cart') }}' method='POST' id='add-product-{{ $cp->uuid }}'>
                                    <input type='hidden' name='_token' value='{{ csrf_token() }}' />
                                    <input type='hidden' name='product_id' value='{{ $cp->uuid }}'>
                                    <input type='hidden' name='single_qty' value='1'>
                                </form>
@elseif(Auth::user()->role == 'VENDOR')
<a href='javascript:;' data-product='#add-product-{{ $cp->uuid }}' title='Add to Cart' class='add-to-cartb btn btn-sm btn-outline-success popover-external-html' style='margin-bottom: 1px;'><i class='fas fa-shopping-cart'></i> {{ $cp->stock_type }} </a><span style='padding:5px;font-weight: bold;font-size: medium;position: relative;top: 8px;'>{{ $cp->stock_of . 'x' . $cp->unit_value . '' . $cp->unit_name }}</span>
                                <form action='{{ route('checkout.add-to-cart') }}' method='POST' id='add-product-{{ $cp->uuid }}'>
                                    <input type='hidden' name='_token' value='{{ csrf_token() }}' />
                                    <input type='hidden' name='product_id' value='{{ $cp->uuid }}'>
                                    <input type='hidden' name='single_qty' value='1'>
                                </form>
@endif
@endforeach
                    </div></div>
                ">
                    <i class='fas fa-shopping-cart' title="Choose Stock Type"></i>

                </a>
            </p>
            @if ($product->stock_expiry_date != 'NA')
                <p>{{ $product->stock_expiry_date != null ? 'Expiry Date: ' . $product->stock_expiry_date : '' }}</p>
            @endif
            @if (isset($product->pdetails) &&
                !empty($product->pdetails->period_from) &&
                !empty($product->pdetails->period_to))
                <p> Promotion Date: {{ $product->pdetails->period_from }} To {{ $product->pdetails->period_to }}</p>
            @endif
            @if (isset($product->user) && isset($product->pdetails))
                @if (isset($product->user->supplierCompany) && $product->user->supplierCompany->legal_name)
                    <p> Supplier :{{ strtoupper($product->user->supplierCompany->legal_name) }}</p>
                @else
                    <p> Supplier :{{ strtoupper($product->user->first_name . ' ' . $product->user->last_name) }}</p>
                @endif
            @endif
            @if ($product->colour_variants != '')
                <p> {{ $product->colour_variants != null ? 'Color: ' . $product->colour_variants : '' }}</p>
            @endif
            @if ($product->size_variants != '')
                <p> {{ $product->size_variants != null ? 'Size: ' . $product->size_variants : '' }}</p>
            @endif



            {{-- <div class="hide header-{{$product->uuid}}">{{$product->name}}</div>
                                    <div class="hide content-{{$product->uuid}}">
                                        {!! Form::open(['route' => "checkout.add-to-cart"]) !!}
                                        {!! Form::hidden('product_id', $product->uuid) !!}
                                        <table>
                                            <thead>
                                            <tr>
                                                <th width="50">{{__("Qty")}}</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__('Single')}} </td>
                                                <td>{!! Form::number("single_qty",0, ["class"=>"form-control allownumericwithoutdecimal","autofocus",'placeholder'=>'Single Qty']) !!}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Shrink')}}</td>
                                                <td>{!! Form::number("shrink_qty",0,["class"=>"form-control allownumericwithoutdecimal","autofocus",'placeholder'=>'Shrink Qty']) !!}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Case')}}</td>
                                                <td>{!! Form::number("case_qty",0,["class"=>"form-control allownumericwithoutdecimal","autofocus",'placeholder'=>'Case Qty']) !!}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Pallet')}}</td>
                                                <td>{!! Form::number("pallet_qty",0,["class"=>"form-control allownumericwithoutdecimal","autofocus",'placeholder'=>'Pallet Qty']) !!}</td>
                                            </tr>
                                            <tr>

                                                <td colspan="2">
                                                    <button type="submit" class="btn btn-sm btn-danger float-right">{{__("Add to cart")}}</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {!! Form::close() !!}
                                    </div> --}}
            {{-- </p> --}}
        </div>

    </div>
</div>
