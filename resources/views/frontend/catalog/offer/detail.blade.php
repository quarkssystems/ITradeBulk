@extends('frontend.layouts.main')
@section('content')
    <!-- Product Detail HTML -->
    <div class="offer_detail">
        <div class="container  p-5">
            <div class="row ">
                <div class="col-md-3 col-sm-12">

                    @php $bgimg = (isset($offer_detail->products->base_image) && !empty($offer_detail->products->base_image)) ? asset($offer_detail->products->base_image) : asset('assets/frontend/images/offerbg.png') @endphp
                    <div class="offer_image"
                        style="background-image:url(@if (file_exists(str_replace(URL::to('/') . '/', '', $bgimg))) '{{ $bgimg }}' @else '/assets/frontend/images/default-offer-img.jpg' @endif);">
                    </div>
                    {{-- <div><img src="{{ $bgimg}}"></div> --}}
                    {{-- <div><img src="{{$offer_detail->image}}"></div> --}}
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="details">
                        <h2 class="product-title">{{ $offer_detail->promotion_type }}</h2>
                        <div class=" pb-2 mb-2"><span class="offer_code p-2 ">Promotion Code :
                                {{ $offer_detail->promotion_id }} </span></div>

                        <div class="product-title  mb-2"><i class="fa fa-calendar"></i>
                            {{ date('d, M Y', strtotime($offer_detail->start_date)) }} To
                            {{ date('d, M Y', strtotime($offer_detail->end_date)) }}</div>

                        <div class="sizes"><i class="fa fa-user"></i> Supiler : @if ($user->supplierCompany != null && $user->supplierCompany->legal_name)
                                {{ strtoupper($user->supplierCompany->legal_name) }}
                            @else
                                {{ strtoupper($user->first_name . ' ' . $user->last_name) }}
                            @endif
                        </div>
                        <div class="product-code mt-3">
                            {{-- <p class="offer_description">{{ ucfirst($offer_detail->description) }}</p> --}}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- Product Detail HTML Close -->
@endsection
