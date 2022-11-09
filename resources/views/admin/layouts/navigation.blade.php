<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">

    <div class="container-fluid">

        <!-- Toggler -->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
            aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Brand -->

        <a class="navbar-brand pt-0" href="{{ route('admin.dashboard') }}">

            <img src="{{ asset('assets') }}/images/logo.png" class="navbar-brand-img" alt="{{ env('APP_NAME') }}">

        </a>

        <!-- User -->

        <ul class="nav align-items-center d-md-none">

            <li class="nav-item dropdown">

                <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">

                    <i class="ni ni-bell-55"></i>

                </a>

                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right"
                    aria-labelledby="navbar-default_dropdown_1">

                    <a class="dropdown-item" href="#">Action</a>

                    <a class="dropdown-item" href="#">Another action</a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="#">Something else here</a>

                </div>

            </li>

            <li class="nav-item dropdown">

                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">

                    <div class="media align-items-center">

                        <span class="avatar avatar-sm rounded-circle">

                            <img alt="Image placeholder" src="{{ asset('assets/admin') }}/img/theme/team-1-800x800.jpg">



                        </span>

                    </div>

                </a>

                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">

                    @include('admin.layouts.userMenuDropdown')

                </div>

            </li>

        </ul>

        <!-- Collapse -->

        <div class="collapse navbar-collapse" id="sidenav-collapse-main">

            <!-- Collapse header -->

            <div class="navbar-collapse-header d-md-none">

                <div class="row">

                    <div class="col-6 collapse-brand">

                        <a href="{{ route('admin.dashboard') }}">

                            <img src="{{ asset('assets') }}/images/logo.png">

                        </a>

                    </div>

                    <div class="col-6 collapse-close">

                        <button type="button" class="navbar-toggler" data-toggle="collapse"
                            data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                            aria-label="Toggle sidenav">

                            <span></span>

                            <span></span>

                        </button>

                    </div>

                </div>

            </div>

            <!-- Form -->

            <form class="mt-4 mb-3 d-md-none">

                <div class="input-group input-group-rounded input-group-merge">

                    <input type="search" class="form-control form-control-rounded form-control-prepended"
                        placeholder="Search" aria-label="Search">

                    <div class="input-group-prepend">

                        <div class="input-group-text">

                            <span class="fa fa-search"></span>

                        </div>

                    </div>

                </div>

            </form>

            <!-- Navigation -->


            <ul class="navbar-nav">

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.dashboard')? 'active': '' }}"
                        href="{{ route('admin.dashboard') }}">

                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.users.*') ||
                    request()->route()->named('admin.manage-logistic.*') ||
                    request()->route()->named('admin.manage-supplier.*') ||
                    request()->route()->named('admin.manage-vendor.*')
                        ? 'active '
                        : 'collapsed' }}"
                        href="#transporterMenu" data-toggle="collapse" data-target="#userMenu"
                        aria-expanded="{{ request()->route()->named('admin.users.*') ||request()->route()->named('admin.manage-vendor.*')? 'true': 'false' }}">

                        <i class="fas fa-users text-blue"></i> {{ __('User Management') }}

                    </a>



                    <div class="collapse {{ request()->route()->named('admin.users.*') ||
                    request()->route()->named('admin.manage-vendor.*') ||
                    request()->route()->named('admin.manage-logistic.*') ||
                    request()->route()->named('admin.manage-supplier.*') ||
                    request()->route()->named('admin.logistic-document.*') ||
                    request()->route()->named('admin.logistic-bank-details.*') ||
                    request()->route()->named('admin.logistic-tax-details.*') ||
                    request()->route()->named('admin.logistic-wallet.*') ||
                    request()->route()->named('admin.supplier-company.*') ||
                    request()->route()->named('admin.supplier-document.*') ||
                    request()->route()->named('admin.supplier-bank-details.*') ||
                    request()->route()->named('admin.supplier-tax-details.*') ||
                    request()->route()->named('admin.supplier-wallet.*') ||
                    request()->route()->named('admin.supplier-stock.*') ||
                    request()->route()->named('admin.vendor-company.*') ||
                    request()->route()->named('admin.vendor-document.*') ||
                    request()->route()->named('admin.vendor-bank-details.*') ||
                    request()->route()->named('admin.vendor-tax-details.*') ||
                    request()->route()->named('admin.vendor-wallet.*') ||
                    request()->route()->named('admin.logistic-detail.*')
                        ? 'show '
                        : '' }}"
                        id="userMenu" aria-expanded="false">

                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.manage-vendor.*')? 'active': '' }}"
                                href="{{ route('admin.manage-vendor.index') }}">

                                <i class="ni ni-single-02 text-blue"></i> {{ __('Traders') }}

                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.manage-supplier.*')? 'active': '' }}"
                                href="{{ route('admin.manage-supplier.index') }}">

                                <i class="ni ni-single-02 text-blue"></i> {{ __('Suppliers') }}

                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.manage-logistic.*')? 'active': '' }}"
                                href="{{ route('admin.manage-logistic.index') }}">

                                <i class="ni ni-single-02 text-blue"></i> {{ __('Transporters') }}

                            </a>

                            {{-- <a class="nav-link {{ request()->route()->named('admin.manage-transporter-company.*')? 'active': '' }}"
                                href="{{ route('admin.manage-transporter-company.index') }}">

                                <i class="ni ni-single-02 text-blue"></i> {{ __('Transport Company') }}

                            </a> --}}

                        </div>

                    </div>



                </li>



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.permissions.*') ||
                    request()->route()->named('admin.roles.*')
                        ? 'active '
                        : 'collapsed' }}"

                            href="#rolePermissionMenu"

                            data-toggle="collapse"

                            data-target="#rolePermissionMenu"

                            aria-expanded="{{ request()->route()->named('admin.permissions.*') ||
                            request()->route()->named('admin.roles.*')
                                ? 'true'
                                : 'false' }}">

                        <i class="fas fa-key text-blue"></i> {{ __('Role & Permissions') }}

                    </a>



                    <div

                            class="collapse {{ request()->route()->named('admin.permissions.*') ||
                            request()->route()->named('admin.roles.*')
                                ? 'show '
                                : '' }}"

                            id="rolePermissionMenu"

                            aria-expanded="false">

                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.permissions.*')? 'active': '' }}"  href="{{ route('admin.permissions.index') }}">

                                <i class="fa fa-lock text-blue"></i> {{ __('Permissions') }}

                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.roles.*')? 'active': '' }}"  href="{{ route('admin.roles.index') }}">

                                <i class="fa fa-user-lock text-blue"></i> {{ __('Roles') }}

                            </a>

                        </div>

                    </div>
 -->
                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.sales-orders.*')? 'active': '' }}"
                        href="{{ route('admin.sales-orders.index') }}">

                        <i class="fas fa-chart-line text-blue"></i> {{ __('Sales order') }}

                    </a>

                </li>



                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.offerdeals.*')? 'active': '' }}"
                        href="{{ route('admin.offerdeals.index') }}">

                        <i class="fas fa-gift text-blue"></i> {{ __('Promotions') }}

                    </a>

                </li>

            </ul>

            <!-- Divider -->

            <hr class="my-3">

            <!-- Heading -->

            <h6 class="navbar-heading text-muted">{{ __('Masters') }}</h6>


            <?php
            // echo '<pre>';
            // $admin = \App\User::where('role', 'ADMIN')->first();
            $id = auth()->user()->uuid;
            $admin = \App\AdminQuickView::where('user_id', $id)->first();
            // print_r('123:' . $admin . ':123');
            ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->route()->named('admin.products.*') ||
                    request()->route()->named('admin.categories.*') ||
                    request()->route()->named('admin.brands.*') ||
                    request()->route()->named('admin.product-unit.*') ||
                    request()->route()->named('admin.products.variantsindex')
                        ? 'active '
                        : 'collapsed' }}"
                        href="#catalogMenu" data-toggle="collapse" data-target="#catalogMenu"
                        aria-expanded="{{ request()->route()->named('admin.products.*') ||request()->route()->named('admin.categories.*') ||request()->route()->named('admin.brands.*') ||request()->route()->named('admin.product-unit.*') ||request()->route()->named('admin.products.variantsindex')? 'true': 'false' }}">
                        <i class="fas fa-users text-blue"></i> {{ __('Catalog Management') }}
                    </a>

                    <div class="collapse {{ request()->route()->named('admin.products.*') ||
                    request()->route()->named('admin.products.variantsindex') ||
                    request()->route()->named('admin.categories.*') ||
                    request()->route()->named('admin.product-unit.*') ||
                    request()->route()->named('admin.brands.*') ||
                    request()->route()->named('admin.admin-fields.*') ||
                    request()->route()->named('admin.product-code.*') ||
                    request()->route()->named('admin.product-link.*') ||
                    request()->route()->named('admin.product_description.*') ||
                    request()->route()->named('admin.data-hierarchy.*') ||
                    request()->route()->named('admin.product-fact.*') ||
                    request()->route()->named('admin.packing-dimension.*') ||
                    request()->route()->named('admin.varients.*') ||
                    request()->route()->named('admin.attributes.*') ||
                    request()->route()->named('admin.image-management.*') ||
                    request()->route()->named('admin.promotions.*') ||
                    request()->route()->named('admin.promo-type.*') ||
                    request()->route()->named('admin.invoice-splitting.*') ||
                    request()->route()->named('admin.supplier-view.*') ||
                    request()->route()->named('admin.quick-view.*') ||
                    request()->route()->named('admin.product-list') ||
                    request()->route()->named('admin.fact-list') ||
                    request()->route()->named('admin.hierarchyimport') ||
                    request()->route()->named('admin.productimport')
                        ? 'show '
                        : '' }}"
                        id="catalogMenu" aria-expanded="false">














                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.products.*')? 'active': '' }}"
                                href="{{ route('admin.products.index') }}">
                                <i class="fas fa-truck-loading text-blue"></i> {{ __('Products') }}
                            </a>


                            {{-- {{ dd(auth()->user()->uuid) }} --}}
                            {{-- new --}}
                            @if (isset($admin) && $admin->admin_fields == '1')
                                <a class="nav-link {{ request()->route()->named('admin.admin-fields.*')? 'active': '' }}"
                                    href="{{ route('admin.admin-fields.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Admin Fields') }}
                                </a>
                            @endif

                            {{-- {{ dd(auth()->user()->uuid) }} --}}
                            @if (isset($admin) && $admin->product_codes == '1')
                                <a class="nav-link {{ request()->route()->named('admin.product-code.*')? 'active': '' }}"
                                    href="{{ route('admin.product-code.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Codes') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->product_links == '1')
                                <a class="nav-link {{ request()->route()->named('admin.product-link.*')? 'active': '' }}"
                                    href="{{ route('admin.product-link.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Links') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->product_description == '1')
                                <a class="nav-link {{ request()->route()->named('admin.product_description.*')? 'active': '' }}"
                                    href="{{ route('admin.product_description.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Description') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->data_hierarchy == '1')
                                <a class="nav-link {{ request()->route()->named('admin.data-hierarchy.*')? 'active': '' }}"
                                    href="{{ route('admin.data-hierarchy.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Data Hierarchy') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->fact == '1')
                                <a class="nav-link {{ request()->route()->named('admin.product-fact.*')? 'active': '' }}"
                                    href="{{ route('admin.product-fact.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Facts') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->pallet_configuration == '1')
                                <a class="nav-link {{ request()->route()->named('admin.packing-dimension.*')? 'active': '' }}"
                                    href="{{ route('admin.packing-dimension.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Packing and Dimensions') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->variants == '1')
                                <a class="nav-link {{ request()->route()->named('admin.varients.*')? 'active': '' }}"
                                    href="{{ route('admin.varients.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Variants') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->attributes == '1')
                                <a class="nav-link {{ request()->route()->named('admin.attributes.*')? 'active': '' }}"
                                    href="{{ route('admin.attributes.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Attributes') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->image_management == '1')
                                <a class="nav-link {{ request()->route()->named('admin.image-management.*')? 'active': '' }}"
                                    href="{{ route('admin.image-management.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Image Management') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->promotions == '1')
                                <a class="nav-link {{ request()->route()->named('admin.promotions.*')? 'active': '' }}"
                                    href="{{ route('admin.promotions.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Promotions') }}
                                </a>
                            @endif
                            @if (isset($admin) && $admin->invoice_splitting == '1')
                                <a class="nav-link {{ request()->route()->named('admin.invoice-splitting.*')? 'active': '' }}"
                                    href="{{ route('admin.invoice-splitting.index') }}">
                                    <i class="fas fa-truck-loading text-blue"></i> {{ __('Invoice Splitting') }}
                                </a>
                            @endif

                            <a class="nav-link {{ request()->route()->named('admin.promo-type.*')? 'active': '' }}"
                                href="{{ route('admin.promo-type.index') }}">
                                <i class="fa fa-list text-blue"></i> {{ __('Promo Type') }}
                            </a>

                            {{--  --}}

                            {{-- <a class="nav-link {{ request()->route()->named('admin.products.variantsindex')? 'active': '' }}"
                                href="{{ route('admin.products.variantsindex') }}">
                                <i class="fas fa-truck-loading text-blue"></i> {{ __('Products Variants') }}
                            </a> --}}

                            <a class="nav-link {{ request()->route()->named('admin.categories.*')? 'active': '' }}"
                                href="{{ route('admin.categories.index') }}">
                                <i class="fa fa-list text-blue"></i> {{ __('Categories') }}
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.brands.*')? 'active': '' }}"
                                href="{{ route('admin.brands.index') }}">
                                <i class="fa fa-list text-blue"></i> {{ __('Brand') }}
                                {{-- <i class="fa fa-list text-blue"></i> {{__('Manufacturer')}} --}}
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.product-unit.*')? 'active': '' }}"
                                href="{{ route('admin.product-unit.index') }}">
                                <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Unit') }}
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.supplier-view.*')? 'active': '' }}"
                                href="{{ route('admin.supplier-view.index') }}">
                                <i class="fas fa-truck-loading text-blue"></i> {{ __('Supplier View') }}
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.quick-view.*')? 'active': '' }}"
                                href="{{ route('admin.quick-view.index') }}">
                                <i class="fas fa-truck-loading text-blue"></i> {{ __('Quick View Method') }}
                            </a>
                        </div>

                    </div>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->route()->named('admin.delivery-vehicle-master.*') ||
                    request()->route()->named('admin.vehicle-capacity.*') ||
                    request()->route()->named('admin.order-logistic-queue.*')
                        ? 'active '
                        : 'collapsed' }}"
                        href="#transporterMenu" data-toggle="collapse" data-target="#transporterMenu"
                        aria-expanded="{{ request()->route()->named('admin.delivery-vehicle-master.*') ||request()->route()->named('admin.vehicle-capacity.*') ||request()->route()->named('admin.order-logistic-queue.*')? 'true': 'false' }}">
                        <i class="fas fa-users text-blue"></i> {{ __('Transporter Management') }}
                    </a>

                    <div class="collapse {{ request()->route()->named('admin.delivery-vehicle-master.*') ||
                    request()->route()->named('admin.vehicle-capacity.*') ||
                    request()->route()->named('admin.transport-type.*') ||
                    request()->route()->named('admin.order-logistic-queue.*')
                        ? 'show '
                        : '' }}"
                        id="transporterMenu" aria-expanded="false">

                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.delivery-vehicle-master.*')? 'active': '' }}"
                                href="{{ route('admin.delivery-vehicle-master.index') }}">
                                <i class="fas fa-truck text-blue"></i> {{ __('Delivery Vehicle Master') }}
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.vehicle-capacity.*')? 'active': '' }}"
                                href="{{ route('admin.vehicle-capacity.index') }}">
                                <i class="fas fa-truck text-blue"></i> {{ __('Vehicle Capacity') }}
                            </a>
                            <a class="nav-link {{ request()->route()->named('admin.transport-type.*')? 'active': '' }}"
                                href="{{ route('admin.transport-type.index') }}">
                                <i class="fas fa-truck text-blue"></i> {{ __('Vehicle Type') }}
                                {{-- <i class="fas fa-truck text-blue"></i> {{ __('Transport Type') }} --}}
                            </a>
                            <a class="nav-link {{ request()->route()->named('admin.order-logistic-queue.*')? 'active': '' }}"
                                href="{{ route('admin.order-logistic-queue.index') }}">

                                <i class="fas fa-chart-line text-blue"></i> {{ __('Order Logistic in Queue') }}

                            </a>

                        </div>

                    </div>
                </li>
            </ul>


            <ul class="navbar-nav mb-md-3">

                <li class="nav-item">

                    <a class="nav-link " href="{{ route('admin.courier.index') }}">

                        <i class="fas fa-truck text-blue text-primary"></i> {{ __('Courier Management') }}

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.country.*') ||
                    request()->route()->named('admin.state.*') ||
                    request()->route()->named('admin.city.*') ||
                    request()->route()->named('admin.zipcode.*')
                        ? 'active'
                        : '' }}"
                        href="{{ route('admin.country.index') }}">

                        <i class="fa fa-map-marker text-primary"></i> {{ __('Location') }}

                    </a>

                </li>



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.categories.*')? 'active': '' }}"  href="{{ route('admin.categories.index') }}">

                        <i class="fa fa-list text-blue"></i> {{ __('Categories') }}

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.brands.*')? 'active': '' }}"  href="{{ route('admin.brands.index') }}">

                        <i class="fa fa-list text-blue"></i> {{ __('Manufacturer') }}

                    </a>

                </li> -->

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.tax.*')? 'active': '' }}"
                        href="{{ route('admin.tax.index') }}">

                        <i class="fa fa-percentage text-blue"></i> {{ __('Tax') }}

                    </a>

                </li>

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.products.*')? 'active': '' }}"  href="{{ route('admin.products.index') }}">

                        <i class="fas fa-truck-loading text-blue"></i> {{ __('Products') }}

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.products.variantsindex')? 'active': '' }}"  href="{{ route('admin.products.variantsindex') }}">

                        <i class="fas fa-truck-loading text-blue"></i> {{ __('Products Variants') }}

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.product-unit.*')? 'active': '' }}"  href="{{ route('admin.product-unit.index') }}">

                        <i class="fas fa-truck-loading text-blue"></i> {{ __('Product Unit') }}

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.delivery-vehicle-master.*')? 'active': '' }}"  href="{{ route('admin.delivery-vehicle-master.index') }}">

                        <i class="fas fa-truck text-blue"></i> {{ __('Delivery Vehicle Master') }}

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.vehicle-capacity.*')? 'active': '' }}"  href="{{ route('admin.vehicle-capacity.index') }}">

                        <i class="fas fa-truck text-blue"></i> {{ __('Vehicle Capacity') }}

                    </a>

                </li> -->

                {{-- <li class="nav-item"> --}}

                {{-- <a class="nav-link {{request()->route()->named('admin.cmsmodule.*') ? 'active' : ''}}"  href="{{route('admin.cmsmodule.index')}}"> --}}

                {{-- <i class="fas fa-list text-blue"></i> {{__('CMS')}} --}}

                {{-- </a> --}}

                {{-- </li> --}}



                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.bank-master.*') ||
                    request()->route()->named('admin.bank-branch.*')
                        ? 'active '
                        : 'collapsed' }}"
                        href="#bankMenu" data-toggle="collapse" data-target="#bankMenu"
                        aria-expanded="{{ request()->route()->named('admin.bank-master.*') ||request()->route()->named('admin.bank-branch.*')? 'true': 'false' }}">

                        <i class="fa fa-university  text-blue"></i> {{ __('Bank Management') }}

                    </a>



                    <div class="collapse {{ request()->route()->named('admin.bank-master.*') ||request()->route()->named('admin.bank-branch.*')? 'show ': '' }}"
                        id="bankMenu" aria-expanded="false">

                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.bank-master.*')? 'active': '' }}"
                                href="{{ route('admin.bank-master.index') }}">

                                <i class="fa fa-university text-blue"></i> {{ __('Banks') }}

                            </a>



                            <a class="nav-link {{ request()->route()->named('admin.bank-branch.*')? 'active': '' }}"
                                href="{{ route('admin.bank-branch.index') }}">

                                <i class="fa fa-university text-blue"></i> {{ __('Branches') }}

                            </a>



                        </div>

                    </div>



                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.banner.*')? 'active': '' }}"
                        href="{{ route('admin.banner.index') }}">

                        <i class="fas fa-flag text-blue"></i> Banner

                    </a>

                </li>



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.cmsblock.*')? 'active': '' }}"  href="{{ route('admin.cmsblock.index') }}">

                        <i class="fas fa-archive text-blue"></i> CMS Block

                    </a>

                </li> -->



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.cmsmodule.*')? 'active': '' }}"  href="{{ route('admin.cmsmodule.index') }}">

                        <i class="fas fa-cog text-blue"></i> CMS Module

                    </a>

                </li> -->



                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.testimonials.*')? 'active': '' }}"
                        href="{{ route('admin.testimonials.index') }}">

                        <i class="fas fa-paper-plane text-blue"></i> Testimonials

                    </a>

                </li>



                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.team.*')? 'active': '' }}"
                        href="{{ route('admin.team.index') }}">

                        <i class="fas fa-users text-blue"></i> Team

                    </a>

                </li>



                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.requestQuote.*')? 'active': '' }}"
                        href="{{ route('admin.requestQuote.index') }}">

                        <i class="fas fa-quote-left text-blue"></i> Request a Quote

                    </a>

                </li>



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.shortcode.*')? 'active': '' }}"  href="{{ route('admin.shortcode.index') }}">

                        <i class="fas fa-code text-blue"></i> Short Code

                    </a>

                </li> -->



                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.emailTemplate.*')? 'active': '' }}"  href="{{ route('admin.emailTemplate.index') }}">

                        <i class="fas fa fa-envelope text-blue"></i> Email Template

                    </a>

                </li> -->

                <!-- <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.order-logistic-queue.*')? 'active': '' }}"  href="{{ route('admin.order-logistic-queue.index') }}">

                        <i class="fas fa-chart-line text-blue"></i> {{ __('Order Logistic Queue') }}

                    </a>

                </li> -->

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.withdrawalrequest.*')? 'active': '' }}"
                        href="{{ route('admin.withdrawalrequest') }}">
                        <i class="fa fa-university  text-blue"> </i>
                        Settle Request

                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->route()->named('admin.admin-commission.*')? 'active': '' }}"
                        href="{{ route('admin.admin-commission') }}">
                        <i class="fa fa-percent text-blue"> </i> iTradeBulk™ Commission
                        {{-- <i class="fa fa-percent text-blue"> </i> ITZ Commission --}}

                    </a>
                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.settings.*')? 'active': '' }}"
                        href="{{ route('admin.settings') }}">

                        <i class="fas fa-cog text-blue"></i> Commision Settings

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->route()->named('admin.admin-details')? 'active': '' }}"
                        href="{{ route('admin.admin-details') }}">

                        <i class="fas fa-cog text-blue"></i> Admin Details

                    </a>

                </li>

            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->route()->named('admin.cmsblock.*') ||
                    request()->route()->named('admin.cmsmodule.*') ||
                    request()->route()->named('admin.shortcode.*') ||
                    request()->route()->named('admin.emailTemplate.*')
                        ? 'active '
                        : 'collapsed' }}"
                        href="#developerMenu" data-toggle="collapse" data-target="#developerMenu"
                        aria-expanded="{{ request()->route()->named('admin.cmsblock.*') ||request()->route()->named('admin.cmsmodule.*') ||request()->route()->named('admin.shortcode.*') ||request()->route()->named('admin.emailTemplate.*')? 'true': 'false' }}">
                        <i class="fas fa-users text-blue"></i> {{ __('Developement Module') }}
                    </a>

                    <div class="collapse {{ request()->route()->named('admin.cmsblock.*') ||
                    request()->route()->named('admin.cmsmodule.*') ||
                    request()->route()->named('admin.shortcode.*') ||
                    request()->route()->named('admin.emailTemplate.*')
                        ? 'show '
                        : '' }}"
                        id="developerMenu" aria-expanded="false">

                        <div class="flex-column pl-2 nav">

                            <a class="nav-link {{ request()->route()->named('admin.cmsblock.*')? 'active': '' }}"
                                href="{{ route('admin.cmsblock.index') }}">
                                <i class="fas fa-archive text-blue"></i> CMS Block
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.cmsmodule.*')? 'active': '' }}"
                                href="{{ route('admin.cmsmodule.index') }}">
                                <i class="fas fa-cog text-blue"></i> CMS Module
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.shortcode.*')? 'active': '' }}"
                                href="{{ route('admin.shortcode.index') }}">
                                <i class="fas fa-code text-blue"></i> Short Code
                            </a>

                            <a class="nav-link {{ request()->route()->named('admin.emailTemplate.*')? 'active': '' }}"
                                href="{{ route('admin.emailTemplate.index') }}">
                                <i class="fas fa fa-envelope text-blue"></i> Email Template
                            </a>

                        </div>

                    </div>
                </li>
            </ul>



        </div>

    </div>

</nav>
