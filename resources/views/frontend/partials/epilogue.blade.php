<script>
    APP_URL = '{{ env('APP_URL') }}';
    TOKEN = '{{ csrf_token() }}';
</script>

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/slider/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/js/wow.min.js') }}"></script>
<script src="{{asset('assets/frontend/js/bootstrap-datepicker.js')}}"></script>
<script src="{{ asset('assets/frontend/js/script.js?v=1.0.8') }}"></script>

@yield('script')

@yield('js')
