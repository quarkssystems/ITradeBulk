@include('admin.layouts.head')
<body class="bg-default {{ isset($bodyClass) ? implode(' ', $bodyClass) : '' }}">
<!-- Main content -->
<div class="main-content">
    <!-- Page content -->
    @include('admin.layouts.authNav')
        @yield('content')
    @include('admin.layouts.footer')
</div>
<!-- Argon Scripts -->
<!-- Core -->
@include('admin.layouts.footerScripts')
</body>
</html>