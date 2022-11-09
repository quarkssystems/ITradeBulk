<script>
    APP_URL = '{{ env('APP_URL') }}';
    TOKEN = '{{ csrf_token() }}';
</script>
<script src="{{ asset('assets/frontend/js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/jquery/dist/jquery.min.js') }}"></script>

{{-- Plugin Js --}}
<script src="{{ asset('assets/plugins/data-grid/data-grid.js') }}"></script>

<script src="{{ asset('assets/admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset('assets/admin/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/chart.js/dist/Chart.extension.js') }}"></script>

<!-- DropiFY JS -->
<script src="{{ asset('assets/plugins/dropify/js/dropify.js') }}"></script>
<script src="{{ asset('assets/plugins/form/dropify.js') }}"></script>

<!-- Input Mask -->
<script src="{{ asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>

<!-- Sweet alert -->
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- Fancy Box master -->
<script src="{{ asset('assets/plugins/fancybox-master/jquery.fancybox.min.js') }}"></script>

<!-- Argon JS -->
<script src="{{ asset('assets/admin/js/argon.js?v=1.0.0') }}"></script>
<script src="{{ asset('assets/admin/js/app.js?v=1.0.7') }}"></script>

<!-- Comman js admin and frontend -->
<script src="{{ asset('assets/comman/js/comman.js?v=1.0.7') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap-datepicker.js') }}"></script>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@yield('scripts')
