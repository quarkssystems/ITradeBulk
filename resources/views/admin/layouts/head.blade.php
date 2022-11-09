<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>{{ isset($pageTitle) ? $pageTitle : '' }} - {{ __('Admin') }} | {{ env('APP_NAME') }}</title>
    <!-- Favicon -->
    {{-- <link href="./assets/img/brand/favicon.png" rel="icon" type="image/png"> --}}
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <link href="{{ asset('assets/plugins/data-grid/data-grid.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('assets/admin/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- DropiFY css --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" />

    {{-- Sweet alert --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}" />

    <!-- Fancy Box master -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fancybox-master/jquery.fancybox.min.css') }}" />

    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('assets/admin/css/argon.css?v=1.0.0') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/admin/css/styles.css?v=1.0.0') }}" rel="stylesheet">
</head>
