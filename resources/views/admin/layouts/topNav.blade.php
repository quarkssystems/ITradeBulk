<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">

    <div class="container-fluid">

        <!-- Brand -->

        {{--<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index.html">Dashboard</a>--}}

        <!-- Form -->

        {{--<form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">--}}

            {{--<div class="form-group mb-0">--}}

                {{--<div class="input-group input-group-alternative">--}}

                    {{--<div class="input-group-prepend">--}}

                        {{--<span class="input-group-text"><i class="fas fa-search"></i></span>--}}

                    {{--</div>--}}

                    {{--<input class="form-control" placeholder="Search" type="text">--}}

                {{--</div>--}}

            {{--</div>--}}

        {{--</form>--}}

        <!-- User -->

        <ul class="navbar-nav align-items-center mr-3 d-none d-md-flex ml-lg-auto">

            <li class="nav-item dropdown">

                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <div class="media align-items-center">

                {{--<span class="avatar avatar-sm rounded-circle">--}}

                  {{--<img alt="Image placeholder" src="{{asset('assets/admin')}}/img/theme/team-4-800x800.jpg">--}}

                {{--</span>--}}

                         {{-- changes --}}
                        <div class="media-body ml-2  d-lg-block"> 
                            {{-- <div class="media-body ml-2 d-none d-lg-block"> --}}

                            <span class="mb-0 text-sm  font-weight-bold">{{auth()->user()->name}}</span>

                        </div>

                    </div>

                </a>

                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">

                    @include('admin.layouts.userMenuDropdown')

                </div>

            </li>

        </ul>

    </div>

</nav>