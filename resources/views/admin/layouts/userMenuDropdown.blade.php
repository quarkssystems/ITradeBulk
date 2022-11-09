{{--<div class=" dropdown-header noti-title">--}}
{{--<h6 class="text-overflow m-0">Welcome!</h6>--}}
{{--</div>--}}
{{--<a href="#" class="dropdown-item">--}}
{{--<i class="ni ni-single-02"></i>--}}
{{--<span>My profile</span>--}}
{{--</a>--}}
{{--<a href="#" class="dropdown-item">--}}
{{--<i class="ni ni-settings-gear-65"></i>--}}
{{--<span>Settings</span>--}}
{{--</a>--}}
{{--<a href="#" class="dropdown-item">--}}
{{--<i class="ni ni-calendar-grid-58"></i>--}}
{{--<span>Activity</span>--}}
{{--</a>--}}
{{--<a href="#" class="dropdown-item">--}}
{{--<i class="ni ni-support-16"></i>--}}
{{--<span>Support</span>--}}
{{--</a>--}}
{{--<div class="dropdown-divider"></div>--}}

<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i class="ni ni-user-run"></i> {{__('Logout')}}</a>
{!! Form::open(['route' => 'logout', 'class' => 'hidden' , 'id' => 'logout-form']) !!}
{!! Form::close() !!}