<section class="spacer bg-primary">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="mb-4 text-white"><b>Client Testimonials</b></h3>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">  </div>
                        <div id="carouselTestimonialControls" data-ride="carousel" class="col-md-8 text-center text-white carousel slide">
                            <div class="carousel-inner">
                                @php $cSlide = 0; @endphp
                                @foreach ($testimonials as $testimonial)
                                <div class="carousel-item @if ($cSlide == 0) active @endif">
                                    <div class="testimonial">
                                        <div class="image before after">
                                            <img src="{{(isset($testimonial->user['image']) && !empty($testimonial->user['image'])) ? asset($testimonial->user['image']) : asset('assets/frontend/images/testimonial-default.png')}}">
                                        </div>
                                        <div class="text">
                                            <p><i>{{$testimonial['message']}}</i></p>
                                        </div>
                                        <div class="subtext">
                                            <p>
                                                <b>
                                                    @if(isset($testimonial->user->first_name))
                                                      {{$testimonial->user->first_name}}
                                                    @endif
                                                    @if(isset($testimonial->user->last_name))
                                                        {{$testimonial->user->last_name}}
                                                     @endif 
                                                </b>
                                                {{$testimonial['type']}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @php $cSlide++; @endphp
                                @endforeach
                            </div>
                            @if (isset($testimonials) && count($testimonials))
                            <a class="carousel-control-prev" href="#carouselTestimonialControls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselTestimonialControls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                            @endif
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@section('footerScript')
    <script>
        $(document).ready(function() {
            $('.carousel').carousel({
              interval: 2000
            });
        });
    </script>
@endsection