@extends('frontend.layouts.main')
@section('content') 

    <!-- Product Detail HTML -->
    <div id="product_detail">
        <div class="container-fluid" style="margin-top:50px;">
        <a href="javascript:history.back()" type="button" class="btn btn-sm btn-theme product_details_btn" 
            style="background: #fa6500;">Back</a>
            <div class="row">
            <div class="col-md-12 col-sm-12 p-4">
                <div class="details product_detail">

                <div class="br_more_cate">
                    <!-- <a href="{{ url('/products/detail/category') }}" style="font-size: 16px;">See more</a>
                        <span id="seemore" style="font-size: 16px;">See more</span> -->
                        <div class="allcategory" style="display: block;">
                            <!-- <button id="close-btn">X</button> -->
                        @php
                        $type = $data['type'];
                        unset($data['type']);
                        @endphp
                            <ul class="FilterDirectory-list">
                                @foreach($data as $key => $da)
                                    <li class="FilterDirectory-listTitle">{{ $key }}</li>

                                    @foreach($da as $d)
                                    @if($type == 'supplier')
                                    @if (isset($d->company) && !empty($d->company)) 
                                        <li class='nav-link'>  
                                            <a href="#" class="buildURL" bid="supplier" aval="{{ $d->uuid }}">
                                                <span>{{ $d->name }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    @elseif($type == 'manufacturer')
                                        <li class='nav-link'>
                                            <a href="#" class="buildURL" bid="brand" aval="{{ $d->slug }}">
                                                <span>{{ $d->name }}</span>
                                            </a>
                                        </li>
                                    @elseif($type == 'category')
                                        <li class="nav-link">
                                            <a href="{{ route('products') }}?category={{ $d->slug }}"> <span style="font-size: 14px">{{ $d->name }}</span></a>
                                        </li>
                                    @endif

                                        <!-- <li>
                                            <label><a href="{{ route('products') }}?category={{ $d->slug }}" >{{ $d->name }}</a></label>
                                        </li> -->
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
      <!-- Product Detail HTML Close -->
@endsection
