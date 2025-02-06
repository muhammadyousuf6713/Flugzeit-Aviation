<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<aside class="card sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="d-flex justify-content-center m-0 navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct.png') }}" class="img-fluid" style="max-width: 100%; height: auto;"
                alt="Logo">
        </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Dashboard</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-house"
                            style="color: {{ Request::is('admission/list') ? '#fff' : '#0ba7d0' }};"></i>

                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Operations</h6>
            </li>


            <li class="nav-item pb-2">
                <a class="nav-link {{ Request::is('customers') ? 'active' : '' }}" href="{{ url('customers') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                            style="color: {{ Request::is('customers') ? '#fff' : '#00beda' }};" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Customers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('inquiry') ? 'active' : '' }}" href="{{ url('inquiry') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-file-alt" style="color: {{ Request::is('inquiry') ? '#fff' : '#00beda' }};"></i>
                        {{-- <i class="fa fa-question-circle" title="Inquiry"></i> --}}
                    </div>
                    <span class="nav-link-text ms-1">Inquiry</span>
                </a>
            </li>



            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Preferrences</h6>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Request::is('inquiry-type') ? 'active' : '' }}"
                    href="{{ url('inquiry-type') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                            style="color: {{ Request::is('inquiry-type') ? '#fff' : '#00beda' }};"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inquiry Types</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ url('services') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                            style="color: {{ Request::is('services') ? '#fff' : '#00beda' }};" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Services</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Request::is('sales-reference') ? 'active' : '' }}"
                    href="{{ url('sales-reference') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                            style="color: {{ Request::is('sales-reference') ? '#fff' : '#00beda' }};"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sales References</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administration</h6>
            </li>

            @can('Customers list')
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Request::is('user-management') ? 'active' : '' }}"
                        href="{{ url('user-management') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                                style="color: {{ Request::is('user-management') ? '#fff' : '#00beda' }};"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">User Management</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item pb-2">
                <a class="nav-link {{ Request::is('roles') ? 'active' : '' }}" href="{{ url('roles') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                            style="color: {{ Request::is('roles') ? '#fff' : '#00beda' }};" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Roles Management</span>
                </a>
            </li>

            @can('Super-Admin')
                <li class="nav-item pb-2">
                    <a class="nav-link {{ Request::is('permission') ? 'active' : '' }}" href="{{ url('permission') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"
                                style="color: {{ Request::is('permission') ? '#fff' : '#00beda' }};"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Permissions</span>
                    </a>
                </li>
            @endcan


        </ul>
    </div>
</aside>
