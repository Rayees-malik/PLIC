<header id="header" class="header">
    <div class="mobile-header-btns {{ config('app.env') !== 'production' ? 'dev' : '' }}">
        <div class="mobile-header-btns__left">
            <button id="open-side-menu" class="header-icon-btn" type="button">
                <i class="material-icons">menu</i>
            </button>
        </div>

        <div class="mobile-header-btns__right">
            <button id="open-mobile-notifications" class="header-icon-btn" type="button">
                <i class="material-icons">notifications</i>
            </button>

            <button id="open-mobile-search" class="header-icon-btn" type="button">
                <i class="material-icons">search</i>
            </button>
        </div>
    </div>

    <div class="logo-wrap {{ config('app.env') !== 'production' ? 'dev' : '' }}">
        <a href="{{ url('/') }}"><img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name', 'Purity Life') }}" /></a>
    </div>

    <div id="side-nav-wrap" class="side-nav-wrap {{ config('app.env') !== 'production' ? 'dev' : '' }}">
        <div class="nav-wrap {{ config('app.env') !== 'production' ? 'dev' : '' }}">
            @if(!app()->environment('production') && file_exists(base_path('BRANCH')))
            <div class="tw-inline-flex tw-justify-center tw-items-center tw-px-6 tw-text-sky-800 tw-font-normal tw-text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="tw-w-6 tw-h-6"><path d="M 11 4 C 9.355469 4 8 5.355469 8 7 C 8 8.292969 8.84375 9.394531 10 9.8125 L 10 22.1875 C 8.84375 22.605469 8 23.707031 8 25 C 8 26.644531 9.355469 28 11 28 C 12.644531 28 14 26.644531 14 25 C 14 23.730469 13.183594 22.65625 12.0625 22.21875 C 12.207031 20.988281 12.683594 20.382813 13.4375 19.875 C 14.335938 19.269531 15.714844 18.910156 17.21875 18.5625 C 18.722656 18.214844 20.335938 17.855469 21.6875 16.90625 C 22.875 16.074219 23.773438 14.710938 23.96875 12.8125 C 25.140625 12.402344 26 11.300781 26 10 C 26 8.355469 24.644531 7 23 7 C 21.355469 7 20 8.355469 20 10 C 20 11.277344 20.832031 12.351563 21.96875 12.78125 C 21.832031 14.09375 21.324219 14.746094 20.5625 15.28125 C 19.664063 15.910156 18.277344 16.28125 16.78125 16.625 C 15.285156 16.96875 13.664063 17.273438 12.3125 18.1875 C 12.203125 18.261719 12.101563 18.355469 12 18.4375 L 12 9.8125 C 13.15625 9.394531 14 8.292969 14 7 C 14 5.355469 12.644531 4 11 4 Z M 11 6 C 11.5625 6 12 6.4375 12 7 C 12 7.5625 11.5625 8 11 8 C 10.4375 8 10 7.5625 10 7 C 10 6.4375 10.4375 6 11 6 Z M 23 9 C 23.5625 9 24 9.4375 24 10 C 24 10.5625 23.5625 11 23 11 C 22.4375 11 22 10.5625 22 10 C 22 9.4375 22.4375 9 23 9 Z M 11 24 C 11.5625 24 12 24.4375 12 25 C 12 25.5625 11.5625 26 11 26 C 10.4375 26 10 25.5625 10 25 C 10 24.4375 10.4375 24 11 24 Z"/></svg>
              <span>@branch()</span>
            </div>
            @endif
            @if (auth()->check())
            <a class="nav-link" href="{{ route('user.submissions') }}">My Submissions</a>

            @can('signoff')
            <a class="nav-link" href="{{ route('signoffs.index') }}">Signoffs</a>
            @endcan

            @if (Bouncer::cannot('admin') && Bouncer::can('user.assign.vendor') && !auth()->user()->vendor_id)
            <a class="nav-link" href="{{ route('vendors.create') }}">Vendor Application</a>
            @else
            <div class="nav-linkgroup-wrap">
                <span class="js-nav-linkgroup-btn">
                    <a class="nav-linkgroup-btn" href="{{ route('products.index') }}">
                        <span>Products</span>
                        <i class="material-icons">keyboard_arrow_down</i>
                    </a>
                </span>
                <div class="nav-linkgroup-content">
                    @can('product.view.submissions')
                    <div class="linkgroup-submenu-wrap">
                        <a class="linkgroup-submenu-title" href="{{ route('products.index') }}">
                            Products
                            <i class="material-icons">chevron_right</i>
                        </a>
                        <div class="linkgroup-submenu-content">
                            <a href="{{ route('products.index.submissions') }}" class="linkgroup-link">Pending Submissions</a>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('products.index') }}" class="linkgroup-link">Products</a>
                    @endcan

                    <div class="linkgroup-submenu-wrap">
                        <a class="linkgroup-submenu-title" href="{{ route('promos.index') }}">
                            Promos
                            <i class="material-icons">chevron_right</i>
                        </a>
                        <div class="linkgroup-submenu-content">
                            <a href="{{ route('promos.index') }}" class="linkgroup-link">Promos</a>
                            @can('view', App\Models\PromoPeriod::class)
                            <a href="{{ route('promos.periods.index') }}" class="linkgroup-link">Promo Periods</a>
                            @endcan
                            @can('promo.update.discos')
                                <a href="{{ route('discopromos.edit') }}" class="linkgroup-link">Disco Promos</a>
                            @elsecan('promo.view.discos')
                                <a href="{{ route('discopromos.view') }}" class="linkgroup-link">Disco Promos</a>
                            @endcan
                            <a href="{{ route('retailers.promos.general.index') }}" class="linkgroup-link">Retailer Promos</a>
                            <a href="{{ route('casestackdeals.index') }}" class="linkgroup-link">Case Stack Deals</a>
                        </div>
                    </div>

                    @if (auth()->user()->isVendor)
                    <a href="{{ route('productdelists.index') }}" class="linkgroup-link">Product Delist Requests</a>
                    @endif

                    @can('view', App\Models\PricingAdjustment::class)
                    <a href="{{ route('pricingadjustments.index') }}" class="linkgroup-link">Pricing Adjustments</a>
                    @endcan

                    @can('view', App\Models\MarketingAgreement::class)
                    <a href="{{ route('marketingagreements.index') }}" class="linkgroup-link">Marketing Agreements</a>
                    @endcan

                    @can('view', App\Models\InventoryRemoval::class)
                    <a href="{{ route('inventoryremovals.index') }}" class="linkgroup-link">Inventory Removals</a>
                    @endcan

                    @canany(['qc.menu', 'qc.view-all-qc-records'])
                      <a href="{{ route('qc.index') }}" class="linkgroup-link">Quality Control</a>
                    @endcan

                </div>
            </div>
            @endif

            <div class="nav-linkgroup-wrap">
                <span class="js-nav-linkgroup-btn">
                    <a class="nav-linkgroup-btn" href="{{ auth()->user()->vendor_id ? route('vendors.show', auth()->user()->vendor_id) : route('vendors.index') }}">
                        <span>Vendor{{ auth()->user()->vendor_id ? '' : 's' }}</span>
                        <i class="material-icons">keyboard_arrow_down</i>
                    </a>
                </span>
                <div class="nav-linkgroup-content">
                    @if (auth()->user()->isVendor)
                    @can('create', \App\User::class)
                    <a href="{{ route('users.index') }}" class="linkgroup-link">Users</a>
                    @endcan
                    @if (auth()->user()->isBroker)
                    <a href="{{ route('vendors.index') }}" class="linkgroup-link">Vendors</a>
                    @else
                    @if (auth()->user()->vendor_id)
                    <a href="{{ route('vendors.show', auth()->user()->vendor_id) }}" class="linkgroup-link">Vendor</a>
                    @else
                    <a href="{{ route('vendors.create') }}" class="linkgroup-link">Vendor Application</a>
                    @endif
                    @endif
                    @else
                    <a href="{{ route('vendors.index') }}" class="linkgroup-link">Vendors</a>
                    @endif
                    <a href="{{ route('brands.index') }}" class="linkgroup-link">Brands</a>
                    @if (Bouncer::can('finance.vendor') || Bouncer::can('finance.vendor.all'))
                    <a href="{{ route('brand-finance.index') }}" class="linkgroup-link">Payments & Deductions</a>
                    @endif
                </div>
            </div>

            @if (Bouncer::can('exports.viewmenu') || auth()->user()->isVendor)
            <div class="nav-linkgroup-wrap">
                <span class="js-nav-linkgroup-btn">
                    <a class="nav-linkgroup-btn" href="#">
                        <span>Reports</span>
                        <i class="material-icons">keyboard_arrow_down</i>
                    </a>
                </span>
                <div class="nav-linkgroup-content">
                    <a href="{{ route('exports.index') }}" class="linkgroup-link">Exports</a>
                    @can('exports.listingforms')
                    <a href="{{ route('exports.listingforms.index') }}" class="linkgroup-link">Listing Forms</a>
                    @endcan
                </div>
            </div>
            @endif

            @if (Bouncer::can('admin.menu') || Bouncer::can('imports.viewmenu'))
            <div class="nav-linkgroup-wrap">
                <span class="js-nav-linkgroup-btn">
                    <a class="nav-linkgroup-btn" href="#">
                        <span>Admin</span>
                        <i class="material-icons">keyboard_arrow_down</i>
                    </a>
                </span>
                <div class="nav-linkgroup-content">
                    @can('create', \App\User::class)
                    <div class="linkgroup-submenu-wrap">
                        @can('user.roles.edit')
                        <div class="linkgroup-submenu-title">
                            <a href="{{ route('users.index') }}" class="linkgroup-link">Users</a>
                            <i class="material-icons">chevron_right</i>
                        </div>
                        <div class="linkgroup-submenu-content">
                            <div class="linkgroup-submenu-wrap">
                                <a href="{{ route('users.index') }}" class="linkgroup-link">Users</a>
                                <a href="{{ route('roles.index') }}" class="linkgroup-link">Roles</a>
                                <a href="{{ route('abilities.index') }}" class="linkgroup-link">Abilities</a>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('users.index') }}" class="linkgroup-link">Users</a>
                        @endif
                    </div>
                    @endcan

                    @can('view', App\Models\Broker::class)
                    <a href="{{ route('brokers.index') }}" class="linkgroup-link">Brokers</a>
                    @endcan

                    @can('view', App\Models\Retailer::class)
                    <a href="{{ route('retailers.index') }}" class="linkgroup-link">Retailers</a>
                    @endcan

                    @can('view', App\Models\BrandDiscoRequest::class)
                    <a href="{{ route('branddiscos.index') }}" class="linkgroup-link">Brand Disco Requests</a>
                    @endcan

                    @can('view', App\Models\ProductDelistRequest::class)
                    <a href="{{ route('productdelists.index') }}" class="linkgroup-link">Product Delist Requests</a>
                    @endcan

                    @can('lookups.edit')
                    <div class="linkgroup-submenu-wrap">
                        <div class="linkgroup-submenu-title">
                            Lookup Tables
                            <i class="material-icons">chevron_right</i>
                        </div>

                        <div class="linkgroup-submenu-content">
                            <a href="{{ route('countries.index') }}" class="linkgroup-link">Countries</a>
                            <a href="{{ route('currencies.index') }}" class="linkgroup-link">Currencies</a>
                            <a href="{{ route('uom.index') }}" class="linkgroup-link">Units of Measurement</a>
                        </div>
                    </div>
                    @endcan

                    @can('imports.viewmenu')
                    <a href="{{ route('imports.index') }}" class="linkgroup-link">Imports</a>
                    @endcan
                </div>
            </div>
            @endif

            @impersonating($guard = null)
                <a class="nav-link-btn impersonation-leave-btn {{ config('app.env') !== 'production' ? 'dev' : '' }}" href="{{ route('impersonate.leave') }}">
                        <i class="material-icons">
                            person_off
                        </i>
                        Stop Impersonating {{ Auth::user()->name }}
                </a>
            @endImpersonating

        </div>
    </div>
    @endif

    </div>

    <div id="search-wrap" class="search-wrap">
        <div class="search-input">
            <i class="material-icons search-icon">
                search
            </i>

            <input type="text" placeholder="Search" />

            <button id="close-mobile-search" class="header-icon-btn" type="button">
                <i class="material-icons">close</i>
            </button>
        </div>
    </div>


    @if (auth()->check())
    <div id="notifications-wrap" class="notifications-wrap">
        <a href="{{ route('notifications.index') }}">
            <div class="notification-btn">
                <i class="material-icons" style="color: white;">
                    notifications
                </i>

                @if (auth()->user()->unreadNotifications()->exists())
                    <div class="tw-absolute tw-inline-flex tw-border-amber-600 tw-items-center tw-justify-center tw-w-3 tw-h-3 tw-bg-amber-400 tw-border-2 tw-rounded-full tw-top-3.5 tw-z-0 tw-right-5"></div>
                @endif
            </div>
        </a>
        <div class="notification-dropdown">
            <div class="mobile-notifications-topbar {{ config('app.env') !== 'production' ? 'dev' : '' }}">
                <h3>Notifications</h3>

                <button id="close-mobile-notifications" class="header-icon-btn" type="button">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div id="notifications">
                @foreach (auth()->user()->unreadNotifications()->limit(5)->get() as $notification)
                @include("partials.notifications.simple")
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @guest
    <div class="login-btn-wrap hide-temp">
        <a href="{{ route('login') }}" class="accent-btn">
            <i class="material-icons">forward</i>
            Log in
        </a>
    </div>
    @else
    <div id="account-controls-wrap" class="account-controls-wrap {{ config('app.env') !== 'production' ? 'dev' : '' }}">
        <div id="account-btn" class="account-btn">

            <div class="user-photo">
                <span class="user-initials">
                    {{ auth()->user()->initials }}
                </span>
            </div>

            <span class="user-name">{{ auth()->user()->name }}</span>

            <i class="material-icons">keyboard_arrow_down</i><a href="#"></a>
        </div>
        <div class="account-dropdown">
            <a href="{{ route('profile.edit') }}">
                <i class="material-icons">perm_identity</i>
                Profile
            </a>
            <a href="{{ route('profile.change-password') }}">
              <i class="material-icons">lock</i>
              Change Password
          </a>
            @can('download.user-manual')
            <a href="{{ asset('media/PLICUserManual2021.pdf') }}" target="_blank">
                <i class="material-icons">menu_book</i>
                User Manual
            </a>
            @endcan
            <a class="account-logout" href="{{ route('logout') }}">
                <i class="material-icons">exit_to_app</i>
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        @endguest
    </div>
    </div>

    <div id="header-backdrop" class="header-backdrop"></div>
</header>
