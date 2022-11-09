@if(isset($offer))
@php $bgimg = (isset($offer->image) && !empty($offer->image)) ? asset($offer->image) : asset('assets/frontend/images/offerbg.png') @endphp
<div class="card text-center card-offer"> 
    <div class="offer_image" style="background-image:url(@if(file_exists(str_replace(URL::to('/').'/', '',  $bgimg))) '{{$bgimg}}' @else '/assets/frontend/images/default-offer-img.jpg' @endif);"></div>
  <div class="card-body">
    <h5 class="card-title">{{$offer->title}}</h5>
    <p class="card-text offer-large-text">
        @if($offer->offer_type == 'PERCENTAGE')
        {{$offer->offer_value}}% OFF
        @else
        R {{$offer->offer_value}} OFF  
        @endif
        <p class="p-2 mt-1 offer_code">Offer Code : {{$offer->offercode}}</p>
    </p>
    <a href="{{ route('orderdetail', $offer->uuid) }}" class="btn btn-custom-theme-1">View Detail</a>
  </div>
</div>
@endif

@if(isset($pdOffer))
@php $bgimg = (isset($pdOffer->product->base_image) && !empty($pdOffer->product->base_image)) ? asset($pdOffer->product->base_image) : asset('assets/frontend/images/offerbg.png') @endphp
<div class="card text-center card-offer"> 
    <div class="offer_image" style="background-image:url(@if(file_exists(str_replace(URL::to('/').'/', '',  $bgimg))) '{{$bgimg}}' @else '/assets/frontend/images/default-offer-img.jpg' @endif);"></div>
  <div class="card-body">
    <h5 class="card-title">{{$pdOffer->product->name}}</h5>
    <p class="card-text offer-large-text">
        @if($pdOffer->offer_type == 'PERCENTAGE')
        {{$pdOffer->offer_value}}% OFF
        @else
        R {{$pdOffer->offer_value}} OFF  
        @endif
    </p>
  </div>
</div>
@endif