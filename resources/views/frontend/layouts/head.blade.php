<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta name="description" content="Start your development with a Dashboard for Bootstrap 4."> --}}
    {{-- <meta name="author" content="Creative Tim"> --}}
    <title>{{ isset($pageTitle) ? $pageTitle : '' }} | {{ env('APP_NAME') }}</title>
    <!-- Favicon -->
    {{-- <link href="./assets/img/brand/favicon.png" rel="icon" type="image/png"> --}}
    <!-- Fonts -->

    {{-- added --}}
    {{--  --}}
    <link type="text/css" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/frontend/css/font-awesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/data-grid/data-grid.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap|IBM+Plex+Sans" rel="stylesheet">
    {{-- DropiFY css --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" />

    {{-- Sweet alert --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}" />

    <link type="text/css" href="{{ asset('assets/frontend/css/style.css?v=1.1.2') }}.{{ uniqid() }}"
        rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/frontend/css/slider/owl.theme.default.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/frontend/css/animate.min.css') }}" rel="stylesheet">
    <link type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.css"
        rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"
        integrity="sha512-KXkS7cFeWpYwcoXxyfOumLyRGXMp7BTMTjwrgjMg0+hls4thG2JGzRgQtRfnAuKTn2KWTDZX4UdPg+xTs8k80Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    @stack('styles')
</head>
