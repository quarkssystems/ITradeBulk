@extends('frontend.layouts.main')

@section('content')

    <section class="spacer">
        <div class="container-fluid">

            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{__('Latest Prmotions')}}</b></h3>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{ __('Latest Product Promotions') }}</b></h3>
                </div>
            </div>

            <div class="row mb-15">

                @if (isset($offers))
                    @foreach ($offers as $offer)
                        {{-- {{ dd($offer->child) }} --}}
                        <div class="col-sm-6 col-md-4 mb-15">
                            @php $bgimg = (isset($offer->products->base_image) && !empty($offer->products->base_image)) ? asset($offer->products->base_image) : asset('assets/frontend/images/offerbg.png') @endphp
                            {{-- @php $bgimg = (isset($offer->image) && !empty($offer->image)) ? asset($offer->image) : asset('assets/frontend/images/offerbg.png') @endphp --}}
                            <div class="card text-center card-offer">
                                <img src="{{ asset('assets/frontend/images/on-promotion.png') }}" alt=""
                                    class="promotionsetNew">
                                <div class="offer_image"
                                    style="background-image:url(@if (file_exists(str_replace(URL::to('/') . '/', '', $bgimg))) '{{ $bgimg }}' @else '/assets/frontend/images/default-offer-img.jpg' @endif);">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ isset($offer->products->name) && !empty($offer->products->name) ? $offer->products->name : '' }}
                                    </h5>
                                    <p class="card-text offer-large-text">
                                    <div class="style-1">
                                        {{-- <del> --}}
                                        <span class="amount" style="text-decoration: line-through;">R
                                            {{ $offer->current_price }}</span>
                                        {{-- </del> --}}
                                        <ins>
                                            <span class="amount">R {{ $offer->promotion_price }}</span>
                                        </ins>



                                        {{--  --}}
                                        {{-- @if (isset($offers)) --}}
                                        <a tabindex="0" class="callPopover" role="button" data-toggle="popover"
                                            title="Add to Cart" style="float: right;
            font-size: 1.50em;"
                                            data-template="<div class='popover' role='tooltip'><div class='arrow'></div>
                                    <h3 class='popover-header'></h3>
                                    <div class='popover-body'>
                                    </div>
                                </div>"
                                            data-content="
                    <label>Stock Type:</label>
                    <div class='row'><div class='col-sm-12'>                    
                        @foreach ($offer->child as $cp)
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
                                        {{-- @endif --}}

                                        {{--  --}}

                                    </div>
                                    {{-- Current Price: R {{ $offer->current_price }}
                                        Promotion Price: c --}}
                                    {{-- @if ($offer->promotion_type == 'PERCENTAGE')
                                            {{ $offer->promotion_price }}% OFF
                                        @else
                                            R {{ $offer->promotion_price }} OFF
                                        @endif --}}
                                    {{-- @if ($offer->offer_type == 'PERCENTAGE')
                                            {{ $offer->offer_value }}% OFF
                                        @else
                                            R {{ $offer->offer_value }} OFF
                                        @endif --}}
                                    {{-- <p class="p-2 mt-1 offer_code">Promotion Code : {{ $offer->offercode }}</p> --}}
                                    </p>
                                    <div class="details">
                                        <h2 class="product-title">{{ $offer->promotion_type }}</h2>
                                        {{-- <div class=" pb-2 mb-2"><span class="offer_code p-2 ">Promotion Code :
                                                {{ $offer->promotion_id }} </span></div> --}}
                                        <div class="product-title  mb-2">
                                            <i class="fas fa-calendar-times"></i> Stock Expiry Date:
                                            @if (isset($offer->products->stock_expiry_date) && !empty($offer->products->stock_expiry_date))
                                                {{ date('d, M Y', strtotime($offer->products->stock_expiry_date)) }}<br>
                                            @else
                                            @endif
                                        </div>
                                        <div class="product-title  mb-2"><i class="fa fa-calendar"></i>

                                            {{ date('d, M Y', strtotime($offer->period_from)) }} To
                                            {{ date('d, M Y', strtotime($offer->period_to)) }}</div>

                                        @if (isset($offer->user))
                                            <div class="sizes"><i class="fa fa-user"></i> Supiler : @if (isset($offer->user->supplierCompany) && $offer->user->supplierCompany->legal_name)
                                                    {{ strtoupper($offer->user->supplierCompany->legal_name) }}
                                                @else
                                                    {{ strtoupper($offer->user->first_name . ' ' . $offer->user->last_name) }}
                                                @endif
                                            </div>
                                        @endif

                                        <div class="product-code mt-3">
                                            {{-- <p class="offer_description">{{ ucfirst($offer_detail->description) }}</p> --}}
                                        </div>

                                    </div>
                                    {{-- <a href="{{ route('orderdetail', $offer->uuid) }}" class="btn btn-custom-theme-1">View
                                        Detail</a> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (count($offers) == 0)
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-15">
                        <div class="alert alert-warning">
                            {{ __('No matching Offer found') }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4"><b>{{ __('Latest Product Promotions') }}</b></h3>
                </div>
            </div> --}}

            {{-- <div class="row mb-15">
                @if (isset($productOffers))
                    @foreach ($productOffers as $pdOffer)
                        <div class="col-sm-6 col-md-3 mb-15">
                            @php $bgimg = (isset($pdOffer->products->base_image) && !empty($pdOffer->products->base_image)) ? asset($pdOffer->products->base_image) : asset('assets/frontend/images/offerbg.png') @endphp
                            <div class="card text-center card-offer">
                                <div class="offer_image"
                                    style="background-image:url(@if (file_exists(str_replace(URL::to('/') . '/', '', $bgimg))) '{{ $bgimg }}' @else '/assets/frontend/images/default-offer-img.jpg' @endif);">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ isset($pdOffer->products->name) && !empty($pdOffer->products->name) ? $pdOffer->products->name : '' }}
                                    </h5>
                                    <p class="card-text offer-large-text">
                                        R {{ $pdOffer->promotion_price }}
                                    <p class="p-2 mt-1 offer_code">Promotion Code : {{ $pdOffer->promotion_id }}</p>


                                    </p>
                                    <a href="{{ route('orderdetail', $pdOffer->uuid) }}"
                                        class="btn btn-custom-theme-1">View
                                        Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if ($productOffers->count() == 0)
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-15">
                        <div class="alert alert-warning">
                            {{ __('No matching Offer found') }}
                        </div>
                    </div>
                @endif
            </div> --}}


        </div>

    </section>

@endsection
