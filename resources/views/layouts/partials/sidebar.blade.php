<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">
            <div class="brand-icon">
                <i class="bi bi-building"></i>
            </div>
            <span class="brand-text">{{ $company->short_name ?? config('app.name') }}</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('employees.*') || request()->routeIs('departments.*') || request()->routeIs('designations.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#employeesMenu">
                    <i class="bi bi-people"></i>
                    <span>Employees</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('employees.*') || request()->routeIs('departments.*') || request()->routeIs('designations.*') ? 'show' : '' }}" id="employeesMenu">
                    <li>
                        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <span>All Employees</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <span>Departments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('designations.index') }}" class="nav-link {{ request()->routeIs('designations.*') ? 'active' : '' }}">
                            <span>Designations</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#attendanceMenu">
                    <i class="bi bi-clock"></i>
                    <span>Attendance</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" id="attendanceMenu">
                    <li>
                        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                            <span>Daily Attendance</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('leaves.*') || request()->routeIs('leave-types.*') || request()->routeIs('holidays.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#leavesMenu">
                    <i class="bi bi-calendar-check"></i>
                    <span>Leaves</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('leaves.*') || request()->routeIs('leave-types.*') || request()->routeIs('holidays.*') ? 'show' : '' }}" id="leavesMenu">
                    <li>
                        <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') && !request()->routeIs('leave-types.*') && !request()->routeIs('holidays.*') ? 'active' : '' }}">
                            <span>All Leaves</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leave-types.index') }}" class="nav-link {{ request()->routeIs('leave-types.*') ? 'active' : '' }}">
                            <span>Leave Types</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('holidays.index') }}" class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}">
                            <span>Holiday Calendar</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('payroll.*') || request()->routeIs('salary-components.*') || request()->routeIs('payslips.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#payrollMenu">
                    <i class="bi bi-wallet2"></i>
                    <span>Payroll</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('payroll.*') || request()->routeIs('salary-components.*') || request()->routeIs('payslips.*') ? 'show' : '' }}" id="payrollMenu">
                    <li>
                        <a href="{{ route('salary-components.index') }}" class="nav-link {{ request()->routeIs('salary-components.*') ? 'active' : '' }}">
                            <span>Salary Components</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.index') ? 'active' : '' }}">
                            <span>Payroll List</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="bi bi-folder"></i>
                    <span>Documents</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    <span>Announcements</span>
                </a>
            </li>

            @if(auth()->user() && auth()->user()->hasRole(['Owner', 'Admin']))
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Role Management</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
