@extends('frontend.layouts.main')
@section('content')
    <section class="spacer">
        <div class="container">
            <div class="row">

                <div class="col-md-12 alert alert-danger cartMessage" role="alert" style="display:none">

                </div>
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{ __('Cart') }}</b></h3>
                </div>
            </div>
            <div class="row mb-15">
                @if (!is_null($basket) && !is_null($products) && $products->count() > 0)
                    <div class="col-md-8 col-lg-8 offset-2">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>{{ __('No') }}</th>
                                <th>{{ __('Item') }}</th>
                                <th width="300">{{ __('Qty') }}</th>
                                {{-- @if (count($getColorVariants) != 0) --}}
                                {{-- <th width="300">{{ __('Color') }}</th> --}}
                                {{-- @endif --}}
                                {{-- @if (count($getColorVariants) != 0) --}}
                                {{-- <th width="300">{{ __('Size') }}</th> --}}
                                {{-- @endif --}}
                                <th></th>
                            </tr>

                            {{-- getSizeVariants --}}
                            @foreach ($products as $cartProduct)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset($cartProduct->product->base_image ?? '') }}" class="img-fluid"
                                            style="max-height: 150px"><br>
                                        <strong
                                            style="font-size: 14px;">{{ $cartProduct->product->name ?? '' }}</strong><br>
                                        <strong style="font-size: 14px;">{{ $cartProduct->product->unit }}:
                                            {{ $cartProduct->product->unit_value ?? '' }}
                                            {{ $cartProduct->product->unit_name ?? '' }}</strong><br>
                                        <strong style="font-size: 14px;">Brand:
                                            {{ $cartProduct->product['brand']['name'] ?? '' }}</strong><br>
                                        {{-- <strong style="font-size: 14px;">Manufacturer: {{$cartProduct->product['brand']['name'] ?? ''}}</strong><br> --}}
                                        <strong style="font-size: 14px;">Stock Type:
                                            {{ ucfirst($cartProduct->product->stock_type) ?? '' }}</strong>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => 'checkout.add-to-cart']) !!}
                                        {!! Form::hidden('product_id', $cartProduct->product_id) !!}

                                        <table>
                                            <tr>
                                                <!-- <td><label>{{ __('Single') }}</label></td> -->
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-number btn-sm"
                                                                data-type="minus"
                                                                data-field="single_qty-{{ $cartProduct->product_id }}"><span
                                                                    class="fa fa-minus"></span></button>
                                                        </span>
                                                        <input type="text" name="single_qty"
                                                            class="form-control input-number form-control-sm single_qty-{{ $cartProduct->product_id }}"
                                                            value="{{ $cartProduct->single_qty }}" min="1"
                                                            max="100">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-number btn-sm"
                                                                data-type="plus"
                                                                data-field="single_qty-{{ $cartProduct->product_id }}"><span
                                                                    class="fa fa-plus"></span></button>
                                                        </span>
                                                        @if (count($cartProduct->getColorVariants) != 0)
                                                            <select name="color" id=""
                                                                class="btn btn-default btn-number btn-sm"
                                                                style="width: 100%;" required>
                                                                <option value="">Select Color</option>
                                                                @foreach ($cartProduct->getColorVariants as $color)
                                                                    <option value="{{ $color }}"
                                                                        {{ $color == $cartProduct->color ? 'selected' : '' }}>
                                                                        {{ $color }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                        @if (count($cartProduct->getColorVariants) != 0)
                                                            <select name="size" id=""
                                                                class="btn btn-default btn-number btn-sm"
                                                                style="width: 100%;" required>
                                                                <option value="">Select Size</option>
                                                                @foreach ($cartProduct->getSizeVariants as $size)
                                                                    <option value="{{ $size }}"
                                                                        {{ $size == $cartProduct->size ? 'selected' : '' }}>
                                                                        {{ $size }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                                                                                                                                                                                                                                        <td><label>{{ __('Shrink') }}</label></td>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <div class="input-group">
                                                                                                                                                                                                                                                                    <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="minus" data-field="shrink_qty-{{ $cartProduct->product_id }}"><span class="fa fa-minus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                <input type="text" name="shrink_qty" class="form-control input-number form-control-sm shrink_qty-{{ $cartProduct->product_id }}" value="{{ $cartProduct->shrink_qty }}"
                                                                                                                                                                                                                                                                                       min="1" max="100">
                                                                                                                                                                                                                                                                                <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="plus" data-field="shrink_qty-{{ $cartProduct->product_id }}"><span class="fa fa-plus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                        <td><label>{{ __('Case') }}</label></td>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <div class="input-group">
                                                                                                                                                                                                                                                                    <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="minus" data-field="case_qty-{{ $cartProduct->product_id }}"><span class="fa fa-minus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                <input type="text" name="case_qty" class="form-control input-number form-control-sm case_qty-{{ $cartProduct->product_id }}" value="{{ $cartProduct->case_qty }}"
                                                                                                                                                                                                                                                                                       min="1" max="100">
                                                                                                                                                                                                                                                                                <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="plus" data-field="case_qty-{{ $cartProduct->product_id }}"><span class="fa fa-plus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                        <td><label>{{ __('Pallet') }}</label></td>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <div class="input-group">
                                                                                                                                                                                                                                                                    <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="minus" data-field="pallet_qty-{{ $cartProduct->product_id }}"><span class="fa fa-minus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                <input type="text" name="pallet_qty" class="form-control input-number form-control-sm pallet_qty-{{ $cartProduct->product_id }}" value="{{ $cartProduct->pallet_qty }}"
                                                                                                                                                                                                                                                                                       min="1" max="100">
                                                                                                                                                                                                                                                                                <span class="input-group-btn">
                                                                                                                                                                                                                                                                        <button type="button" class="btn btn-default btn-number btn-sm" data-type="plus" data-field="pallet_qty-{{ $cartProduct->product_id }}"><span class="fa fa-plus"></span></button>
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                        </td> -->


                                            <tr>
                                                <td colspan="2"><button type="submit"
                                                        class="btn btn-sm btn-theme float-right">{{ __('Update') }}</button>
                                                </td>
                                            </tr>
                                        </table>
                                        {!! Form::close() !!}


                                    </td>
                                    <td class="text-center">
                                        <a title="Remove product from cart"
                                            href="{{ route('checkout.remove-product-from-cart', $cartProduct->product_id) }}"
                                            class="text-theme delete-cart-item"><i class="fa fa-trash fa-3"
                                                style="font-size: 20px;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <a href="{{ route('checkout.select-supplier') }}"
                            class="btn btn-theme">{{ __('Select Supplier') }}</a>
                    </div>
                @else
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            {{ __('Oops!! Your cart is empty.') }}
                            Click <a href="{{ route('products') }}">here</a> for continue your shopping.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="spacer bg-dark supplier-page-section-2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    &nbsp;
                </div>
            </div>
        </div>
    </section>
@endsection
