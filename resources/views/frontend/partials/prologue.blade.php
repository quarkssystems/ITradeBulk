<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="../../favicon.ico"> -->

    <title>{{ isset($pageTitle) ? $pageTitle : "" }} | {{ env('APP_NAME') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/fontawesome-all.min.css') }}" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}" />
    <link href="{{ asset('frontend/css/animate.min.css') }}" rel="stylesheet">

    <!-- SLIDER CSS -->
    <link rel='stylesheet' href="{{ asset('frontend/css/slider/owl.theme.default.min.css') }}">
    <!-- SLIDER CSS -->

    @yield('style')
</head>
