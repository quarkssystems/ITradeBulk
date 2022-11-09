<ul class="user-sidebar nav flex-column">
    <li class="nav-item {{ request()->route()->named('supplier.dashboard.*')? 'nav-active': '' }}">
        <a class="nav-link" href="{{ route('supplier.dashboard') }}"><i class="fa fa-tachometer-alt"></i>
            {{ __('Dashboard') }}</a>
    </li>

    @if (auth()->check() && auth()->user()->role == 'SUPPLIER')
        <li class="nav-item {{ request()->route()->named('supplier.picker-users.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.picker-users.index') }}"><i class="fa fa-university"></i>
                {{ __('Picker Users') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('supplier.dispatcher-users.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.dispatcher-users.index') }}"><i class="fa fa-university"></i>
                {{ __('Dispatcher Users') }}</a>
        </li>
    @endif

    @if (auth()->check() && (auth()->user()->role != 'PICKER' && auth()->user()->role != 'DISPATCHER'))
        <li class="nav-item {{ request()->route()->named('supplier.document.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.document.create', auth()->user()->uuid) }}"><i
                    class="fa fa-file"></i>
                {{ __('Documents') }}</a>
        </li>
    @endif


    @if (auth()->check() && (auth()->user()->role != 'PICKER' && auth()->user()->role != 'DISPATCHER'))
        @if (auth()->check() && auth()->user()->logistic_company_id == '')
            <li class="nav-item {{ request()->route()->named('supplier.bank-details.*')? 'nav-active': '' }}">
                <a class="nav-link" href="{{ route('supplier.bank-details.edit', auth()->user()->uuid) }}"><i
                        class="fa fa-university"></i> {{ __('Bank details') }}</a>
        @endif
    @endif

    @if (auth()->check() && auth()->user()->role == 'VENDOR')
        <li class="nav-item {{ request()->route()->named('supplier.company.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.company.edit', auth()->user()->uuid) }}"><i
                    class="fa fa-industry"></i> {{ __('Company') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('user.fav-orders.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.fav-orders.index') }}"><i class="fas fa fa-history"></i>
                {{ __('Recent Orders') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('supplier.company*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.notification.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Notification') }}
            </a>
        </li>
    @endif

    @if (auth()->check() && auth()->user()->role == 'SUPPLIER')
        <li class="nav-item {{ request()->route()->named('supplier.company.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.company.edit', auth()->user()->uuid) }}"><i
                    class="fa fa-industry"></i> {{ __('Company') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('supplier.inventory.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.inventory.index') }}"><i class="fa fa-table"></i>
                {{ __('Stock Management') }}</a>
        </li>

        <li class="nav-item {{ request()->route()->named('supplier.stock.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.stock.index') }}"><i class="fa fa-table"></i>
                {{ __('Price Error') }}
                {{-- {{ __('Updated Stock') }} --}}
            </a>
        </li>


        </li>
        <li class="nav-item {{ request()->route()->named('supplier.tax-details.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.tax-details.edit', auth()->user()->uuid) }}"><i
                    class="fa fa-percent"></i> {{ __('Tax details') }}</a>
        </li>
        {{-- <li class="nav-item {{ request()->route()->named('supplier.products.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.products.index') }}"><i class="fa fa-boxes"></i>
                {{ __('Request Product') }}</a>
        </li> --}}

        <li class="nav-item {{ request()->route()->named('user.offers.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.offers.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Promotions') }}</a>
        </li>
        {{-- <li class="nav-item {{ request()->route()->named('user.vehicle.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.vehicle.index', auth()->user()->uuid) }}"><i
                    class="fa fa-wallet"></i> {{ __('Vehicle') }}</a>
        </li> --}}
        <li class="nav-item {{ request()->route()->named('user.supplier-delivery.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.supplier-delivery') }}"><i class="fa fa-wallet"></i>
                {{ __('Supplier Delivery Option') }}</a>
        </li>
    @endif
    @if (auth()->check() && auth()->user()->role == 'COMPANY')
        <li class="nav-item {{ request()->route()->named('supplier.drivers.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.drivers.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Add Transporter') }}</a>
        </li>
    @endif

    @if (auth()->check() && auth()->user()->role == 'DRIVER')
        <li class="nav-item {{ request()->route()->named('user.vehicle.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.vehicle.index', auth()->user()->uuid) }}"><i
                    class="fa fa-wallet"></i> {{ __('Vehicle') }}</a>
        </li>
        {{-- <li class="nav-item {{ request()->route()->named('user.vehicle.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.vehicle.edit', auth()->user()->uuid) }}"><i
                    class="fa fa-wallet"></i> {{ __('Vehicle') }}</a>
        </li> --}}
    @endif

    @if (auth()->check() && (auth()->user()->role != 'PICKER' && auth()->user()->role != 'DISPATCHER'))
        @if (auth()->check() && auth()->user()->logistic_company_id == '')
            <li class="nav-item {{ request()->route()->named('user.wallet.*')? 'nav-active': '' }}">
                <a class="nav-link" href="{{ route('user.wallet.index') }}"><i class="fa fa-wallet"></i>
                    @if (auth()->user()->role == 'VENDOR')
                        {{ __('Wallet') }}
                    @else
                        {{ __('Payment Summery') }}
                    @endif
                </a>
            </li>
        @endif
    @endif

    @if (auth()->check() &&
        (auth()->user()->role == 'SUPPLIER' ||
            (auth()->user()->logistic_type == 'COMPANY' && auth()->user()->role == 'COMPANY') ||
            (auth()->user()->role == 'DRIVER' && auth()->user()->logistic_type == 'INDIVIDUAL') ||
            auth()->user()->role == 'VENDOR'))
        <li class="nav-item {{ request()->route()->named('user.withdrawal.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.withdrawal.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Settle Request') }}</a>
        </li>
    @endif

    <li class="nav-item {{ request()->route()->named('user.sales-orders.*')? 'nav-active': '' }}">
        <a class="nav-link" href="{{ route('user.sales-orders.index') }}"><i class="fa fa-boxes"></i>
            {{ __('Orders') }}</a>
    </li>

    <li class="nav-item {{ request()->route()->named('user.profile.*')? 'nav-active': '' }}">
        <a class="nav-link" href="{{ route('user.profile.edit', auth()->user()->uuid) }}"><i class="fa fa-user"></i>
            {{ __('Profile') }}</a>
    </li>
    @if (auth()->user()->role == 'SUPPLIER')
        <li class="nav-item {{ request()->route()->named('user.success-story.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.success-story.index', auth()->user()->uuid) }}"><i
                    class="fa fa-thumbs-up"></i> {{ __('Success Story') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('supplier.notification.index')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.notification.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Notification') }}
            </a>
        </li>
        {{-- <li class="nav-item {{ request()->route()->named('user.quick-view.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('user.quick-view.index') }}"><i class="fa fa-thumbs-up"></i>
                {{ __('Quick View Method') }}</a>
        </li> --}}
    @endif
    @if (auth()->check() && (auth()->user()->role == 'PICKER' || auth()->user()->role == 'DISPATCHER'))
        <li class="nav-item {{ request()->route()->named('supplier.notification.index')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.notification.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Notification') }}
            </a>
        </li>
    @endif


    @if ((auth()->check() && auth()->user()->role == 'DRIVER') ||
        (auth()->check() && auth()->user()->role == 'COMPANY'))
        <li class="nav-item {{ request()->route()->named('supplier.tender')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.tender') }}"><i class="fa fa-wallet"></i>
                {{ __('Tender Details') }}
            </a>
        </li>

        {{-- <li class="nav-item {{ request()->route()->named('supplier.drivers.*')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.notification.accepted_delivery') }}"><i
                    class="fa fa-wallet"></i> {{ __('My Delivery') }}</a>
        </li> --}}
        <li class="nav-item {{ request()->route()->named('supplier.delivery_schedule')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.delivery_schedule') }}"><i class="fa fa-wallet"></i>
                {{ __('Delivery Schedule') }}</a>
        </li>
        <li class="nav-item {{ request()->route()->named('supplier.notification.index')? 'nav-active': '' }}">
            <a class="nav-link" href="{{ route('supplier.notification.index') }}"><i class="fa fa-wallet"></i>
                {{ __('Notification') }}
            </a>
        </li>
    @endif
</ul>
