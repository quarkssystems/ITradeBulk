<header class="header-v1">

    <div class="top-header-links">

        <div class="container-fluid">

            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <nav class="navbar navbar-expand-lg navbar-light">

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topHeaderNav"
                            aria-controls="topHeaderNav" aria-expanded="false" aria-label="Top Links">

                            <span class="navbar-toggler-icon"></span>

                        </button>

                        <div class="collapse navbar-collapse" id="topHeaderNav">

                            <ul class="navbar-nav mr-auto">

                                <li class="nav-item {{ request()->route()->named('about')? 'active': '' }}">

                                    <a class="nav-link" href="{{ route('about') }}">About Us</a>

                                </li>

                                <li class="nav-item {{ request()->route()->named('contact')? 'active': '' }}">

                                    <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>

                                </li>

                                @if (auth()->check())
                                    <li
                                        class="nav-item {{ request()->route()->named('request-quote')? 'active': '' }}">

                                        <a class="nav-link" href="{{ route('request-quote') }}">Request a Quote</a>

                                    </li>
                                @endif

                                <li class="nav-item">

                                    <a class="nav-link" href="{{ route('supplier') }}">Our Suppliers</a>

                                </li>

                                @if (auth()->guest())
                                    <li class="nav-item">

                                        <a class="nav-link" href="{{ route('become-supplier') }}">Become a Supplier</a>

                                    </li>
                                @endif

                                @if (auth()->guest())
                                    <li class="nav-item">

                                        <a class="nav-link" href="{{ route('become-driver') }}">Become a Transporter</a>

                                    </li>
                                @endif

                                @if (auth()->guest())
                                    <li class="nav-item">

                                        <a class="nav-link" href="{{ route('become-vendor') }}">Become a Trader</a>

                                    </li>
                                @endif

                                <li class="nav-item">

                                    <a class="nav-link" href="#">Help & Community</a>

                                </li>

                                <li class="nav-item">

                                    <a class="nav-link" href="#">Get the App</a>

                                </li>

                            </ul>

                        </div>

                    </nav>

                </div>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="header-logo float-left">

                    <a href="#" class="mmenu_icon"><i class="fa fa-bars"></i></a>

                    <a class="navbar-brand" href="{{ route('home') }}">

                        <img src="{{ asset('assets/frontend/images/logo.png') }}">

                    </a>

                </div>

                <div class="header-search-form float-left searchBox" style="width: 30%;">

                    <form action="{{ route('products') }}" method="get" class="form-inline my-2 my-lg-0"
                        style="position:relative">

                        <input class="form-control mr-sm-2" type="search" autocomplete="off" id="searchproduct"
                            data-ajax-url="{{ route('frontend.ajax.postGetProduct') }}"
                            data-product-holder="suggestions" placeholder="Type product name here..."
                            aria-label="Search" name="name" value="{{ request()->get('name') }}">



                        <button class="btn btn-primary my-2 my-sm-0 top-search-btn" type="submit"><i
                                class="fa fa-search"></i></button>

                        <div class="suggestions"></div>

                    </form>

                </div>





                <div class="header-right-menu-items">

                    <ul>

                        @if (auth()->guest())

                            <li class="top-menu-products">

                                <a href="{{ route('products') }}" data-toggle="tooltip" title="Products"><i
                                        class="fas fa-dolly-flatbed"></i></a>

                            </li>



                            <li class="top-menu-products">

                                <a href="{{ route('offers') }}" data-toggle="tooltip" title="Promotions"><i
                                        class="fas fa fa-gift"></i></a>

                            </li>
                        @elseif(auth()->check())
                            @if (Auth::user()->role == 'VENDOR')
                                <li class="top-menu-products">

                                    <a href="{{ route('products') }}" data-toggle="tooltip" title="Products"><i
                                            class="fas fa-dolly-flatbed"></i></a>

                                </li>

                                <li class="top-menu-products">

                                    <a href="{{ route('offers') }}" data-toggle="tooltip" title="Promotions"><i
                                            class="fas fa fa-gift"></i></a>

                                </li>

                                <li class="top-menu-products">

                                    <a href="{{ route('user.fav-orders.index') }}" data-toggle="tooltip"
                                        title="Recent orders"><i class="fas fa fa-history"></i></a>

                                </li>
                            @endif

                        @endif

                        @if (auth()->guest())
                            <li class="top-menu-user">

                                <a href="{{ route('user.loginForm') }}" data-toggle="tooltip"
                                    title="{{ auth()->check() ? 'Profile' : 'Login' }}"><i
                                        class="fa fa-user"></i></a>

                            </li>

                            <li class="">

                                <a class="" href="/register" data-toggle="tooltip" title="Register"><i
                                        class="fas fa-user-plus"></i></a>

                            </li>
                        @endif

                        @if (auth()->check())
                            <li class="top-menu-user">

                                <?php
                                
                                $notification = \App\Models\Notification::where('user_id', auth()->user()->uuid)
                                    ->where('status', 'UNREAD')
                                    ->count();
                                if (isset($notification) && $notification != null) {
                                    $notification = $notification;
                                } else {
                                    $notification = 0;
                                }
                                // dd($notification);
                                ?>

                                <a href="{{ route('supplier.notification.index') }}" data-toggle="tooltip"
                                    title="{{ auth()->check() ? 'Notification' : 'Login/Register' }}"><i
                                        class="fa fa-bell"> {{ $notification }}</i></a>

                            </li>
                        @endif

                        @if (auth()->check())

                            <li class="top-menu-user">

                                <a href="{{ route('supplier.dashboard') }}" data-toggle="tooltip"
                                    title="{{ auth()->check() ? 'Profile' : 'Login/Register' }}"><i
                                        class="fa fa-user"></i></a>

                            </li>

                            @if ((Auth::user()->role == 'COMPANY' && Auth::user()->logistic_type == 'COMPANY') ||
                                (Auth::user()->role == 'DRIVER' && Auth::user()->logistic_type == 'INDIVIDUAL') ||
                                Auth::user()->role == 'VENDOR' ||
                                Auth::user()->role == 'SUPPLIER')
                                <li class="top-menu-wallet">

                                    <a class="" href="#" data-toggle="tooltip" title="Wallet">

                                        <i class="fas fa-wallet"></i>

                                        <span
                                            class="topCartWalletAmount">R{{ bcdiv(auth()->user()->wallet_balance, 1, 2) }}</span>

                                    </a>

                                </li>
                            @endif

                        @endif



                        @if (auth()->check())

                            @if (Auth::user()->role == 'VENDOR')
                                <li class="top-menu-cart">

                                    <a class="" href="{{ route('checkout.cart') }}" data-toggle="tooltip"
                                        title="Cart">

                                        <i class="fas fa-shopping-cart"></i>

                                        <span class="topCartItemCount">{{ getBasketProductCount() }}</span>

                                    </a>

                                </li>
                            @endif
                        @else
                            <li class="top-menu-cart">

                                <a class="" href="{{ route('checkout.cart') }}" data-toggle="tooltip"
                                    title="Cart">

                                    <i class="fas fa-shopping-cart"></i>

                                    <span class="topCartItemCount">{{ getBasketProductCount() }}</span>

                                </a>

                            </li>

                        @endif



                        @if (auth()->check())
                            <li class="">

                                <a class="" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();

                                                     document.getElementById('logout-form').submit();"
                                    data-toggle="tooltip" title="Logout">

                                    <i class="fa fa-sign-out-alt"></i></a>

                                {!! Form::open(['route' => ['logout'], 'method' => 'post', 'id' => 'logout-form']) !!}

                                {!! Form::close() !!}

                            </li>
                        @endif

                    </ul>

                </div>



            </div>

        </div>

    </div>

</header>



<div class="close-menu"></div>

<div class="setHtmlOverlay"></div>

<div class="m_cat_menu">

    <ul>

        <li class="heading">

            <a href="#">SHOP BY CATEGORY</a>

        </li>

    </ul>

    <ul class="ml-3 mr-3 sidebarMenu">

        <li class="loading"></li>

    </ul>

    <ul class="extra_menu_items ml-3 mr-3"></ul>

</div>





{{-- <header> --}}

{{--    <nav class="navbar navbar-white navbar-expand-md navbar-light p-0"> --}}

{{--        <div class="bg-light py-2"> --}}

{{--            <div class="container-fluid firstNav"> --}}

{{--                <div class="row"> --}}

{{--                    <div class="col-md-12 justify-content-between"> --}}



{{--                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> --}}

{{--                            <span class="navbar-toggler-icon"></span> --}}

{{--                        </button> --}}

{{--                        <div class="collapse navbar-collapse" id="navbarCollapse"> --}}

{{--                            <ul class="navbar-nav ml-auto top-links"> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('about')}}">About Us</a> --}}

{{--                                </li> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('contact')}}">Contact Us</a> --}}

{{--                                </li> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('request-quote')}}">Request a Quote</a> --}}

{{--                                </li> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('supplier')}}">Supplier</a> --}}

{{--                                </li> --}}

{{--                                @if (auth()->guest()) --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('become-supplier')}}">Become a Supplier</a> --}}

{{--                                </li> --}}

{{--                                @endif --}}

{{--                                @if (auth()->guest()) --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('become-driver')}}">Become a Driver</a> --}}

{{--                                </li> --}}

{{--                                @endif --}}

{{--                                @if (auth()->guest()) --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="{{route('become-vendor')}}">Become a Vendor</a> --}}

{{--                                </li> --}}

{{--                                @endif --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="#">Help & Community</a> --}}

{{--                                </li> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="#">Get the App</a> --}}

{{--                                </li> --}}

{{--                            </ul> --}}



{{--                        </div> --}}



{{--                        <a class="navbar-brand" href="{{route('home')}}"> --}}

{{--                            <img src="{{asset('assets/frontend/images/logo.png')}}"> --}}

{{--                        </a> --}}



{{--                    </div> --}}

{{--                </div> --}}

{{--            </div> --}}

{{--        </div> --}}

{{--        <div class="secNav"> --}}

{{--            <div class="container-fluid"> --}}

{{--                <div class="row"> --}}

{{--                    <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between"> --}}



{{--                        <div class="dropdown"> --}}

{{--                            <button type="button" class="btn btn-link pl-0" data-toggle="dropdown"> --}}

{{--                                <i class="fas fa-bars"></i> --}}

{{--                                <span>Select Categories</span> --}}

{{--                                <i class="fas fa-chevron-down"></i> --}}

{{--                            </button> --}}

{{--                            <div class="dropdown-menu"> --}}

{{--                                <a class="dropdown-item" href="#">Link 1</a> --}}

{{--                                <a class="dropdown-item" href="#">Link 2</a> --}}

{{--                                <a class="dropdown-item" href="#">Link 3</a> --}}

{{--                            </div> --}}

{{--                        </div> --}}

{{--                        <form action="{{route("products")}}" method="get" class="form-inline my-2 my-lg-0"> --}}

{{--                            {!! Form::select("category",\App\Models\Category::getAllDropDown(), request()->get("category"), ["class" => "form-control mr-sm-2", "placeholder" => "Select category"]) !!} --}}

{{--                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="name" value="{{request()->get("name")}}"> --}}

{{--                            <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button> --}}

{{--                        </form> --}}

{{--                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> --}}

{{--                            <span class="navbar-toggler-icon"></span> --}}

{{--                        </button> --}}

{{--                        <div class="collapse navbar-collapse" id="navbarCollapse"> --}}

{{--                            <ul class="navbar-nav ml-auto"> --}}

{{--                                <li class="nav-item"> --}}

{{--                                    <a class="nav-link" href="#">Sell on Itradezone</a> --}}

{{--                                    <a class="nav-link" href="{{route('products')}}">Products</a> --}}

{{--                                </li> --}}



{{--                                @if (auth()->check()) --}}

{{--                                    <li class="nav-item"> --}}

{{--                                        <a  class="nav-link" href="{{ route('logout') }}" --}}

{{--                                            onclick="event.preventDefault(); --}}

{{--                                                     document.getElementById('logout-form').submit();"> --}}

{{--                                            {{ __('Logout') }}</a> --}}

{{--                                    </li> --}}

{{--                                    {!! Form::open(array('route'=>["logout"], 'method'=>'post','id'=>'logout-form')) !!} --}}

{{--                                    {!! Form::close() !!} --}}

{{--                                @else --}}



{{--                                    <li class="nav-item"> --}}

{{--                                        <a class="nav-link" href="{{route('user.loginForm')}}">Login</a> --}}

{{--                                    </li> --}}



{{--                                    <li class="nav-item"> --}}

{{--                                        <a class="nav-link" href="/register">Register</a> --}}

{{--                                    </li> --}}

{{--                                @endif --}}

{{--                                <li class="nav-item user"> --}}

{{--                                    <a class="nav-link" href="{{route('supplier.dashboard')}}"> --}}

{{--                                        <i class="fas fa-user"></i> --}}

{{--                                    </a> --}}

{{--                                </li> --}}

{{--                                @if (auth()->check()) --}}

{{--                                <li class="nav-item wallet"> --}}

{{--                                    <a class="nav-link" href="#"> --}}

{{--                                        <i class="fas fa-wallet"></i> --}}

{{--                                        <span class="topCartWalletAmount">R{{auth()->user()->wallet_balance}}</span> --}}

{{--                                    </a> --}}

{{--                                </li> --}}

{{--                                @endif --}}

{{--                                <li class="nav-item cart"> --}}

{{--                                    <a class="nav-link" href="{{route('checkout.cart')}}"> --}}

{{--                                        <i class="fas fa-shopping-cart"></i> --}}

{{--                                        <span class="topCartItemCount">{{getBasketProductCount()}}</span> --}}

{{--                                    </a> --}}

{{--                                </li> --}}

{{--                            </ul> --}}

{{--                        </div> --}}

{{--                    </div> --}}

{{--                </div> --}}

{{--            </div> --}}

{{--        </div> --}}

{{--    </nav> --}}

{{-- </header> --}}
