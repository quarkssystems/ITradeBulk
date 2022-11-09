<script>
    APP_URL = '{{ env('APP_URL') }}';
    TOKEN = '{{ csrf_token() }}';
</script>
<script src="{{ asset('assets/frontend/js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/imagezoom.js') }}"></script>
<script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/data-grid/data-grid.js') }}"></script>
<!-- DropiFY JS -->
<script src="{{ asset('assets/plugins/dropify/js/dropify.js') }}"></script>
<script src="{{ asset('assets/plugins/form/dropify.js') }}"></script>

<!-- Sweet alert -->
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>

{{-- <!-- Fancy Box master --> --}}
{{-- <script src="{{asset('assets/plugins/fancybox-master/jquery.fancybox.min.js')}}"></script> --}}

<script src="{{ asset('assets/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/slider/owl.carousel.min.js') }}"></script>

<script src="{{ asset('assets/frontend/js/script.js?v=1.0.8') }}"></script>

<script src="{{ asset('assets/frontend/js/popper.min.js') }}"></script>

<script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>


<script src="{{ asset('assets/frontend/js/bootstrap-datepicker.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<!-- Comman js admin and frontend -->
<script src="{{ asset('assets/comman/js/comman.js?v=1.0.7') }}"></script>





<script src="{{ asset('assets/frontend/js/jquery.zoom.min.js') }}"></script>


<!-- ✅ load moment.js ✅ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
    integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- ✅ load FullCalendar ✅ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"
    integrity="sha512-o0rWIsZigOfRAgBxl4puyd0t6YKzeAw9em/29Ag7lhCQfaaua/mDwnpE2PVzwqJ08N7/wqrgdjc2E0mwdSY2Tg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

@yield('footerScript')
@yield('footerNew')

{{-- <script type="text/javascript">
    var maxHeight = Math.max.apply(null, $(".reason-block").map(function ()
        {
            return $(this).height();
        }).get());

    $(".reason-block").css("min-height",maxHeight);

</script> --}}
