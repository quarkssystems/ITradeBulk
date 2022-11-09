<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('frontend.layouts.head')
<body class="{{ isset($bodyClass) ? implode(' ', $bodyClass) : '' }}">
@include('frontend.layouts.header')
<main role="main">
	
	@if(!request()->route()->named('become-supplier') && !request()->route()->named('become-vendor') &&  !request()->route()->named('become-driver') ) 
    
    	@include('frontend.helpers.globalMessage.message')
    
    @endif

    @yield('content')
</main>
@include('frontend.layouts.footer')
@include('frontend.layouts.footerScripts')
</body>
</html>
