<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo/logo-sm.png') }}" alt="" height="30" />
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo/logo-dark.png') }}" alt="" height="40" />
                        </span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="" height="30" />
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="" height="40" />
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                @impersonating
                    <div class="bg-danger-subtle p-2 rounded">
                        You are currently logged in as
                        <strong>{{ auth()->user()->name }}</strong>
                    </div>
                @endImpersonating
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown topbar-head-dropdown ms-1">
                    {{-- <span role="button" class="text-primary fw-semibold text-uppercase" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        {{ session()->get('cabang_nama') }}
                        <i class="bx bx-buildings fs-22 ms-1 align-bottom"></i>
                    </span> --}}
                    {{-- <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"
                        aria-labelledby="branch-dropdown">
                        <livewire:admin.system.setting.cabang />
                    </div> --}}
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class="bx bx-fullscreen fs-22"></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class="bx bx-moon fs-22"></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('assets/admin/images/users/user-dummy-img.jpg') }}" alt="Avatar" />
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    {{ auth()->user()->name }}
                                    <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        @impersonating
                            <a class="dropdown-item" href="{{ route('admin.impersonate.leave') }}">
                                <i class="mdi mdi-account-switch-outline text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Leave impersonation</span>
                            </a>
                        @endImpersonating

                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">
                                Logout
                                <form method="post" action="{{ route('admin.logout') }}" class="d-none"
                                    id="logout-form">
                                    @csrf
                                </form>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
