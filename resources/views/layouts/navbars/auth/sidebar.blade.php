@can('Sidebar')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    /* Sidebar enhancements */
    #sidenav-main {
        background: #fff;
        border-radius: 15px;
        overflow-y: auto;
        max-height: 100vh;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    #sidenav-main .nav-item {
        transition: all 0.2s ease-in-out;
    }

    #sidenav-main .nav-link {
        border-radius: 12px;
        margin: 4px 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #444;
        transition: background 0.3s ease, color 0.3s ease;
    }

    #sidenav-main .nav-link:hover {
        background: linear-gradient(90deg, #0ba7d0, #00beda);
        color: #fff;
        transform: translateX(3px);
    }

    #sidenav-main .nav-link.active {
        background: linear-gradient(90deg, #0ba7d0, #00beda);
        color: #fff !important;
        box-shadow: 0 3px 10px rgba(0, 190, 218, 0.3);
    }

    #sidenav-main .icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 10px;
        background-color: #f8f9fa;
    }

    #sidenav-main .nav-link.active .icon {
        background-color: rgba(255, 255, 255, 0.2);
    }

    #sidenav-main h6 {
        font-size: 11px;
        color: #888;
        margin-top: 10px;
        margin-bottom: 5px;
    }
</style>

<aside class="card sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none"
            id="iconSidenav"></i>
        <a class="d-flex justify-content-center m-0 navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct.png') }}" class="img-fluid p-2" alt="Logo">
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            <!-- Dashboard -->
            <li class="nav-item mt-2">
                <h6 class="ps-4 text-uppercase">Dashboard</h6>
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-gauge"></i>
                    </div>
                    Dashboard
                </a>
            </li>

            <!-- Operations -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase">Operations</h6>
                <a class="nav-link {{ Request::is('customers') ? 'active' : '' }}" href="{{ url('customers') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    Customers
                </a>
                <a class="nav-link {{ Request::is('inquiry') ? 'active' : '' }}" href="{{ url('inquiry') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-question-circle"></i>
                    </div>
                    Inquiry
                </a>
                <a class="nav-link {{ Request::is('followups') ? 'active' : '' }}" href="{{ route('followups.index') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    Follow-up List
                </a>
            </li>

            <!-- Preferences -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase">Preferences</h6>
                <a class="nav-link {{ Request::is('inquiry-type') ? 'active' : '' }}" href="{{ url('inquiry-type') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-list"></i>
                    </div>
                    Inquiry Types
                </a>
                {{-- <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ url('services') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-concierge-bell"></i>
                    </div>
                    Services
                </a> --}}
                <a class="nav-link {{ Request::is('sales-reference') ? 'active' : '' }}" href="{{ url('sales-reference') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-concierge-bell"></i>
                    </div>
                    Sales Reference
                </a>
            </li>

            <!-- Admin -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase">Administration</h6>
                @can('Customers list')
                    <a class="nav-link {{ Request::is('user-management') ? 'active' : '' }}" href="{{ url('user-management') }}">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        User Management
                    </a>
                @endcan
                <a class="nav-link {{ Request::is('roles') ? 'active' : '' }}" href="{{ url('roles') }}">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                    Roles Management
                </a>
                @can('Super-Admin')
                    <a class="nav-link {{ Request::is('permission') ? 'active' : '' }}" href="{{ url('permission') }}">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        Permissions
                    </a>
                @endcan
            </li>

        </ul>
    </div>
</aside>
@endcan
