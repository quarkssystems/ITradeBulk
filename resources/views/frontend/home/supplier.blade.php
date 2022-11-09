@extends('frontend.layouts.main')
@section('content')
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet"> 
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/frontend/css/supplier.css')}}" />

<div id="carouselExampleIndicators supplier-carousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <!-- Slide One - Set the background image for this slide in the line below -->
            @foreach($banners as $banner)
                <?php 
                    $a = '';
                    $b = $banner->image ?? '';
                    $a = asset($b);
                ?>
                <div class="carousel-item active" style="background-image: url('<?php echo $a;?>')">
                    <div class="overlay">
                        <div class="carousel-caption d-none d-md-block">EXPLORE PRODUCTS FROM PREMIUM BRANDS
                            <h2 class="display-4 banner-main-title">{{$banner->name}}</h2>
                            <p class="lead">{{$banner->description}}</p>
                        </div>
                    </div>
                </div>
            @endforeach

          </div> 
      </header>
      
      <section id="exper-supplier">
      <div class="container-fluid">
      <div class="row">
      <h2 class="col-12 mt-5 title mb-0">Experienced Suppliers</h2>
      @foreach($suppliers as $supplier)
      @if (!empty($supplier->company->product_service_offered) && !empty($supplier->company->business_type) && !empty($supplier->name))
      <div class="col-sm-6 col-md-4"> 
          <?php //echo rtrim($supplier->image,'/'); //echo $supplier->image;exit;?>
        <div class="card">
            <div class="box"> 
                <div class="img"> 
                    
                    @if(file_exists(ltrim($supplier->image,'/')))                    
                        <img src="{{asset($supplier->image)}}">
                    @else 
                        <img src="/assets/frontend/images/default-user.png">
                    @endif
                </div>
                <h2> 
                    <a href="{{route('supplier-detail', $supplier->uuid)}}" class="text-black">{{$supplier->company->trading_name}}</a><br>
                    @if (!empty($supplier->company->business_type))
                    <span>{{$supplier->company->business_type}}</span>
                    @endif
                 </h2>
                 @if (!empty($supplier->company->product_service_offered))
                <p> {{$supplier->company->product_service_offered}}</p>
                @endif
                <span>
                    <ul>
                        <li><a href="{{$supplier->facebook_url ?? 'javascript:void(0)'}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="{{$supplier->twitter_url ?? 'javascript:void(0)'}}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <!-- <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li> -->
                        <li><a href="{{$supplier->insta_url ?? 'javascript:void(0)'}}"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                    </ul>
                </span>
            </div>
        </div>
      </div>
      @endif
      @endforeach
      </div>
      </div>
      </section> 
</div>
</section>

@endsection