<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('frontend.layouts.head')

<body class="{{ isset($bodyClass) ? implode(' ', $bodyClass) : '' }}">
    @include('frontend.layouts.header')
    <main role="main">
        <section class="banners">
            @yield('page-header')

            <div class="main-content">

                <div class="container-fluid">
                    <div class="row">
                        <div class=" col-md-2">
                            @include('supplier.layouts.navigation')
                        </div>
                        <div class="col-md-10">
                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('frontend.layouts.footer')
    @include('frontend.layouts.footerScripts')
</body>

</html>
