<!doctype html>
<html>

@include('frontend.partials.prologue')

<body>

    @include('frontend.partials.header')

    @yield('content')

    @include('frontend.partials.quotation')

    @include('frontend.partials.footer-banner')

    @include('frontend.partials.epilogue')
</body>

</html>
