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

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('performance-reviews.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#performanceMenu">
                    <i class="bi bi-star"></i>
                    <span>Performance</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('performance-reviews.*') ? 'show' : '' }}" id="performanceMenu">
                    <li>
                        <a href="{{ route('performance-reviews.index') }}" class="nav-link {{ request()->routeIs('performance-reviews.*') ? 'active' : '' }}">
                            <span>Reviews</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('timesheets.*') || request()->routeIs('projects.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#timesheetMenu">
                    <i class="bi bi-clock-history"></i>
                    <span>Timesheets</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('timesheets.*') || request()->routeIs('projects.*') ? 'show' : '' }}" id="timesheetMenu">
                    <li>
                        <a href="{{ route('timesheets.index') }}" class="nav-link {{ request()->routeIs('timesheets.*') ? 'active' : '' }}">
                            <span>Time Entries</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <span>Projects</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#expenseMenu">
                    <i class="bi bi-currency-dollar"></i>
                    <span>Expenses</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'show' : '' }}" id="expenseMenu">
                    <li>
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') && !request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                            <span>Claims</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                            <span>Categories</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#assetMenu">
                    <i class="bi bi-laptop"></i>
                    <span>Assets</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('assets.*') ? 'show' : '' }}" id="assetMenu">
                    <li>
                        <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                            <span>All Assets</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-submenu">
                <a href="#" class="nav-link {{ request()->routeIs('shifts.*') || request()->routeIs('shift-assignments.*') || request()->routeIs('shift-swaps.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#shiftMenu">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Shifts</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="nav sub-menu collapse {{ request()->routeIs('shifts.*') || request()->routeIs('shift-assignments.*') || request()->routeIs('shift-swaps.*') ? 'show' : '' }}" id="shiftMenu">
                    <li>
                        <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                            <span>Shift Types</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shift-assignments.index') }}" class="nav-link {{ request()->routeIs('shift-assignments.*') ? 'active' : '' }}">
                            <span>Roster</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shift-swaps.index') }}" class="nav-link {{ request()->routeIs('shift-swaps.*') ? 'active' : '' }}">
                            <span>Swap Requests</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('travel-requests.index') }}" class="nav-link {{ request()->routeIs('travel-requests.*') ? 'active' : '' }}">
                    <i class="bi bi-airplane"></i>
                    <span>Travel</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('orgchart.index') }}" class="nav-link {{ request()->routeIs('orgchart.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Org Chart</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Helpdesk</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('exit-management.index') }}" class="nav-link {{ request()->routeIs('exit-management.*') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i>
                    <span>Exit Management</span>
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

                <li class="nav-item">
                    <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Audit Logs</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('help') }}" class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}">
                    <i class="bi bi-question-circle"></i>
                    <span>Help</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
