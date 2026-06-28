<header class="main-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-auto">
                <button class="btn btn-link sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @section('breadcrumb')
                            <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
                        @show
                    </ol>
                </nav>
            </div>

            <div class="col-auto d-flex align-items-center gap-3">
                <button class="btn btn-link text-decoration-none position-relative" id="notificationBell" type="button">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                        0
                    </span>
                </button>

                <a class="btn btn-link text-decoration-none" href="{{ route('calendar.index') }}" title="Calendar">
                    <i class="bi bi-calendar3 fs-5"></i>
                </a>

                <a class="btn btn-link text-decoration-none" href="{{ route('help') }}" title="Help">
                    <i class="bi bi-question-circle fs-5"></i>
                </a>

                <button class="btn btn-link text-decoration-none" id="darkModeToggle" type="button">
                    <i class="bi bi-moon-stars fs-5"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle bg-primary text-white">
                            <span>{{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}</span>
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->first_name ?? 'User' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
