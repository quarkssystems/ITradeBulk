<header class="animated wow fadeIn" data-wow-delay="0.5s" data-wow-duration="0.5s">
    <div class="container-fluid top-links">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="head-top-links text-right">
                        <li class="@if(request()->route()->named('about')) active @endif">
                            <a href="{{ route('about') }}">About Us</a>
                        </li>
                        <li class="@if(request()->route()->named('about')) active @endif">
                            <a href="{{ route('contact') }}">Contact Us</a>
                        </li>
                        <li class="@if(request()->route()->named('about')) active @endif">
                            <a href="{{ route('request-quote') }}">Request a Quote</a>
                        </li>
                        <li class="@if(request()->route()->named('supplier')) active @endif">
                            <a href="{{ route('supplier') }}">Supplier</a>
                        </li>

                        @if(auth()->guest())
                        <li class="@if(request()->route()->named('become-supplier')) active @endif">
                            <a href="{{ route('become-supplier') }}">Become a Supplier</a>
                        </li>
                        @endif

                        @if(auth()->guest())
                        <li class="@if(request()->route()->named('become-driver')) active @endif">
                            <a href="{{ route('become-driver') }}">Become a Driver</a>
                        </li>
                        @endif

                        @if(auth()->guest())
                        <li class="@if(request()->route()->named('become-vendor')) active @endif">
                            <a href="{{ route('become-vendor') }}">Become a Trader</a>
                        </li>
                        @endif

                        <li>
                            <a href="#">Help &amp; Community</a>
                        </li>
                        <li>
                            <a href="#">Get the App</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container header-logo animated wow fadeInDown" data-wow-delay="0.5s" data-wow-duration="1.5s">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('frontend/images/logo.png') }}" class="img-responsive" width="" height="" alt="Real Muscles Logo"></a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-12 top-search">
                {{-- <div class="dropdown">
                    <button type="button" class="btn btn-link pl-0" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                        <span>Select Categories</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Link 1</a>
                        <a class="dropdown-item" href="#">Link 2</a>
                        <a class="dropdown-item" href="#">Link 3</a>
                    </div>
                </div> --}}

                <div id="imaginary_container">
                    <form action="{{ route('products') }}" method="get">
                        <div class="input-group stylish-input-group">
                            <!-- <input type="text" class="form-control" placeholder="Search for products"> -->
                            <input class="form-control" type="search" autocomplete="off" id="searchproduct" data-ajax-url="{{ route('frontend.ajax.postGetProduct') }}"  data-product-holder="suggestions"  placeholder="Search for products" aria-label="Search" name="name" value="{{ request()->get('name') }}">

                            <span class="input-group-addon">
                                <button type="submit">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 header-btn-right">
                <a href="{{ route('products') }}" class="login-btn">Products</a>

                @if(auth()->guest())
                <a href="{{ route('user.loginForm') }}" class="login-btn">Login</a>
                <a href="/register" class="signup-btn">Signup</a>
                @endif

                @if(auth()->check())
                <a href="{{ route('supplier.dashboard') }}"><i class="fa fa-user"></i></a>
                @endif

                <a href="{{ route('checkout.cart') }}"><i class="fa fa-shopping-cart"></i><span class="topCartItemCount">{{ getBasketProductCount() }}</span></a>
            </div>
        </div>
    </div>

    @yield('carousel')
</header>
