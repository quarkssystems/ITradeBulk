@include('admin.layouts.head')

<body class="{{ isset($bodyClass) ? implode(' ', $bodyClass) : '' }}">
    <!-- Sidenav -->
    @include('admin.layouts.navigation')
    <!-- Main content -->
    <div class="main-content">
        <div class="site-loader"><i class="fas fa-circle-notch fa-spin"></i></div>
        <!-- Top navbar -->
        @include('admin.layouts.topNav')
        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-2 pt-md-5">
            <div class="container-fluid">
                <div class="header-body">
                    <!-- Card stats -->
                    @yield('header')
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--7">
            @include('admin.helpers.globalMessage.message')
            @yield('content')
            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>
    <!-- Argon Scripts -->
    <!-- Core -->
    @include('admin.layouts.footerScripts')
    @yield('footerData')
</body>

</html>
