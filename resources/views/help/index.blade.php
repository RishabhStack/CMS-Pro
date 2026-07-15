@extends('layouts.master')

@section('title', 'Help & Documentation')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold">Help & Documentation</h4>
        <p class="text-muted">Complete guide to all CMS Pro modules.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Navigation</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @php
                        $sections = [
                            ['Dashboard', 'dashboard'],
                            ['Calendar', 'calendar'],
                            ['Employee Management', 'employees'],
                            ['Attendance', 'attendance'],
                            ['Leave Management', 'leaves'],
                            ['Payroll', 'payroll'],
                            ['Performance Reviews', 'performance'],
                            ['Expense Management', 'expenses'],
                            ['Asset Management', 'assets'],
                            ['Shift Scheduling', 'shifts'],
                            ['Timesheets', 'timesheets'],
                            ['Travel Management', 'travel'],
                            ['Exit Management', 'exit'],
                            ['Helpdesk / Tickets', 'tickets'],
                            ['Reports & Analytics', 'reports'],
                            ['Org Chart', 'orgchart'],
                            ['Documents', 'documents'],
                            ['Announcements', 'announcements'],
                            ['Audit Logs', 'audit'],
                        ];
                    @endphp
                    @foreach($sections as $s)
                    <div class="col-md-4 col-lg-3">
                        <a href="#{{ $s[1] }}" class="btn btn-outline-primary w-100 btn-sm mb-1 text-start">
                            <i class="bi bi-chevron-right me-1"></i> {{ $s[0] }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Dashboard --}}
    <div class="col-12" id="dashboard">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h5></div>
            <div class="card-body">
                <p>The Dashboard provides a quick overview of your organization's key metrics.</p>
                <h6>Admin View</h6>
                <ul>
                    <li><strong>Stats Cards</strong> — Total employees, present today, on leave, not marked attendance</li>
                    <li><strong>Weekly Attendance Trend</strong> — Bar chart showing daily present/absent counts</li>
                    <li><strong>Department Distribution</strong> — Employees per department with progress bars</li>
                    <li><strong>Pending Leaves</strong> — Recent leave requests awaiting approval</li>
                    <li><strong>New Hires & Anniversaries</strong> — This month's joiners and work anniversaries</li>
                    <li><strong>Employment Type Breakdown</strong> — Permanent, contract, probation counts</li>
                    <li><strong>Upcoming Birthdays & Anniversaries</strong> — Widgets on right sidebar</li>
                </ul>
                <h6>Employee View</h6>
                <ul>
                    <li>Personal attendance status for today</li>
                    <li>Recent leave history and upcoming approved leaves</li>
                    <li>Upcoming holidays and announcements</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Calendar --}}
    <div class="col-12" id="calendar">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-calendar3 me-2"></i>Calendar</h5></div>
            <div class="card-body">
                <p>Full calendar view showing all company events in one place.</p>
                <ul>
                    <li><strong>Leaves</strong> — Approved employee leaves displayed on calendar</li>
                    <li><strong>Holidays</strong> — Company holidays marked with badges</li>
                    <li><strong>Attendance Summary</strong> — Quick view of attendance for a selected date</li>
                    <li>Navigate between months, click on dates for details</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Employees --}}
    <div class="col-12" id="employees">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-people me-2"></i>Employee Management</h5></div>
            <div class="card-body">
                <p>Complete employee lifecycle management from onboarding to exit.</p>
                <h6>Sub-modules</h6>
                <ul>
                    <li><strong>All Employees</strong> — DataTable with search, filter, export. Add/edit/view employees via modals</li>
                    <li><strong>Departments</strong> — Manage organizational departments</li>
                    <li><strong>Designations</strong> — Job titles and role hierarchy</li>
                </ul>
                <h6>Employee Profile Includes</h6>
                <p>Employee code, personal info, department, designation, reporting manager, employment type, work location, emergency contacts, documents, salary details</p>
            </div>
        </div>
    </div>

    {{-- Attendance --}}
    <div class="col-12" id="attendance">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-clock me-2"></i>Attendance Tracking</h5></div>
            <div class="card-body">
                <p>Track daily employee attendance with clock in/out, break management.</p>
                <ul>
                    <li>Mark attendance manually or view existing records</li>
                    <li>Clock in/out times with break start/end tracking</li>
                    <li>Automatic total hours and overtime calculation</li>
                    <li>Status indicators: Present, Absent, Late, Half-day</li>
                    <li>IP address and location tracking</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Leaves --}}
    <div class="col-12" id="leaves">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-calendar-check me-2"></i>Leave Management</h5></div>
            <div class="card-body">
                <p>Complete leave management with application, approval workflow, and balance tracking.</p>
                <ul>
                    <li><strong>Leave Types</strong> — Configurable leave types (Annual, Sick, Personal, Maternity, etc.)</li>
                    <li><strong>Apply Leave</strong> — Select type, date range, reason, attach documents</li>
                    <li><strong>Approval Workflow</strong> — Admin approves/rejects with reason</li>
                    <li><strong>Balance Tracking</strong> — Carry forward, max days per year</li>
                    <li><strong>Leave Calendar</strong> — View all approved leaves</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Payroll --}}
    <div class="col-12" id="payroll">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-wallet2 me-2"></i>Payroll Management</h5></div>
            <div class="card-body">
                <p>Salary structure definition and payroll processing.</p>
                <ul>
                    <li><strong>Salary Components</strong> — Define earning (Basic, HRA, etc.) and deduction (PF, Tax, etc.) components</li>
                    <li><strong>Employee Salary</strong> — Assign salary structure to each employee</li>
                    <li><strong>Payroll Processing</strong> — Generate monthly payroll, calculate earnings/deductions</li>
                    <li><strong>Payslips</strong> — View and download individual payslips</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Performance --}}
    <div class="col-12" id="performance">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-star me-2"></i>Performance Reviews</h5></div>
            <div class="card-body">
                <p>Manage employee performance reviews, goals, and feedback.</p>
                <ul>
                    <li><strong>Rating Scales</strong> — Define custom rating scales (e.g., 1-5, 1-10)</li>
                    <li><strong>Review Cycles</strong> — Create review periods with start/end dates</li>
                    <li><strong>Self Review</strong> — Employees submit self-assessment</li>
                    <li><strong>Manager Review</strong> — Managers rate and comment on performance</li>
                    <li><strong>Goals & KPIs</strong> — Track goals with target vs achieved values</li>
                    <li><strong>Feedback</strong> — 360-degree feedback from peers and managers</li>
                    <li><strong>Workflow</strong> — Draft → Self Review → Manager Review → Completed</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Expenses --}}
    <div class="col-12" id="expenses">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-currency-dollar me-2"></i>Expense Management</h5></div>
            <div class="card-body">
                <p>Track and manage employee expense claims.</p>
                <ul>
                    <li><strong>Expense Categories</strong> — Travel, Meals, Office Supplies, Technology, etc.</li>
                    <li><strong>Submit Claims</strong> — Employees submit expenses with amount, date, category, receipt upload</li>
                    <li><strong>Approval Workflow</strong> — Pending → Approved → Paid (with rejection option)</li>
                    <li><strong>Receipt Upload</strong> — Attach receipt images/PDFs to claims</li>
                    <li>Admin can view all claims; employees see only their own</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Assets --}}
    <div class="col-12" id="assets">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-laptop me-2"></i>Asset Management</h5></div>
            <div class="card-body">
                <p>Track company assets and their assignments to employees.</p>
                <ul>
                    <li><strong>Asset Types</strong> — Laptops, Monitors, Phones, Peripherals, etc.</li>
                    <li><strong>Asset Lifecycle</strong> — Purchase date, cost, warranty, status tracking</li>
                    <li><strong>Assign & Return</strong> — Assign assets to employees with condition notes</li>
                    <li><strong>Assignment History</strong> — Track who had which asset and when</li>
                    <li>Status: Available, Assigned, Under Maintenance, Retired</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Shifts --}}
    <div class="col-12" id="shifts">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-arrow-left-right me-2"></i>Shift Scheduling</h5></div>
            <div class="card-body">
                <p>Define and manage work shifts, rosters, and swap requests.</p>
                <ul>
                    <li><strong>Shift Types</strong> — Morning, Day, Evening, Night, Flexible shifts with time slots</li>
                    <li><strong>Roster Assignment</strong> — Assign shifts to employees on daily basis</li>
                    <li><strong>Shift Swaps</strong> — Employees can request shift swaps with approval workflow</li>
                    <li>Color-coded shifts for easy visual identification</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Timesheets --}}
    <div class="col-12" id="timesheets">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Timesheets</h5></div>
            <div class="card-body">
                <p>Project-based time tracking for employees.</p>
                <ul>
                    <li><strong>Projects</strong> — Define projects with status tracking</li>
                    <li><strong>Time Entries</strong> — Log hours against projects with task descriptions</li>
                    <li><strong>Approval Workflow</strong> — Pending → Approved / Rejected</li>
                    <li>Employees log time against active projects</li>
                    <li>Admin can approve/reject timesheet entries</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Travel --}}
    <div class="col-12" id="travel">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-airplane me-2"></i>Travel Management</h5></div>
            <div class="card-body">
                <p>Manage travel requests and itineraries.</p>
                <ul>
                    <li><strong>Travel Requests</strong> — Submit trip details: destination, dates, purpose, budget</li>
                    <li><strong>Travel Modes</strong> — Flight, Train, Bus, Cab etc.</li>
                    <li><strong>Itinerary Planning</strong> — Day-by-day activity schedule</li>
                    <li><strong>Approval Workflow</strong> — Pending → Approved → Rejected</li>
                    <li>Estimated vs actual cost tracking</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Exit --}}
    <div class="col-12" id="exit">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-door-open me-2"></i>Exit Management</h5></div>
            <div class="card-body">
                <p>Manage employee resignations, clearance, and exit interviews.</p>
                <ul>
                    <li><strong>Resignation Submission</strong> — Notice date, last working day, reason</li>
                    <li><strong>Approval Workflow</strong> — Pending → Approved → Rejected</li>
                    <li><strong>Clearance Checklist</strong> — IT, HR, Finance clearance items tracking</li>
                    <li><strong>Exit Interviews</strong> — Capture feedback, reasons for leaving, suggestions</li>
                    <li>Accrued leave payout and notice period tracking</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Tickets --}}
    <div class="col-12" id="tickets">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-ticket-perforated me-2"></i>Helpdesk / Tickets</h5></div>
            <div class="card-body">
                <p>Internal support ticket system for employee requests.</p>
                <ul>
                    <li><strong>Categories</strong> — IT, HR, Administration, Other</li>
                    <li><strong>Priority Levels</strong> — Low, Medium, High, Critical</li>
                    <li><strong>Status Workflow</strong> — Open → In Progress → Resolved → Closed</li>
                    <li><strong>Assignment</strong> — Admin assigns tickets to specific users</li>
                    <li><strong>Comments</strong> — Internal and public comments on tickets</li>
                    <li>Auto-generated ticket numbers (TKT-XXXXXX)</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Reports --}}
    <div class="col-12" id="reports">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-bar-chart me-2"></i>Reports & Analytics</h5></div>
            <div class="card-body">
                <p>Interactive charts and reports for data-driven insights.</p>
                <ul>
                    <li><strong>Attendance Trend</strong> — Daily present/absent/late counts over time (Chart.js)</li>
                    <li><strong>Leave Trend</strong> — Monthly leave usage by type</li>
                    <li><strong>Payroll Summary</strong> — Monthly payroll totals (earnings, deductions, net)</li>
                    <li><strong>Headcount</strong> — Employee count by department</li>
                    <li><strong>Turnover Rate</strong> — Monthly hires vs departures</li>
                    <li><strong>Save Reports</strong> — Save and load report configurations</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Org Chart --}}
    <div class="col-12" id="orgchart">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-diagram-3 me-2"></i>Organization Chart</h5></div>
            <div class="card-body">
                <p>Visual org chart showing company structure.</p>
                <ul>
                    <li>Departments displayed as columns</li>
                    <li>Employee cards with avatar initials</li>
                    <li>Manager highlighting with dotted connections</li>
                    <li>Click employee card to view profile</li>
                    <li>Pure CSS/JS tree layout (no external library)</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="col-12" id="documents">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-folder me-2"></i>Documents</h5></div>
            <div class="card-body">
                <p>Upload and manage employee documents.</p>
                <ul>
                    <li>Document types: Offer Letter, Appraisal, ID Proof, Resume, Certification, NDA</li>
                    <li>Upload with expiry date tracking</li>
                    <li>Download and view documents</li>
                    <li>Categorize by type for easy filtering</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Announcements --}}
    <div class="col-12" id="announcements">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-megaphone me-2"></i>Announcements</h5></div>
            <div class="card-body">
                <p>Company-wide announcements and notifications.</p>
                <ul>
                    <li>Types: General, Event, Policy, Emergency</li>
                    <li>Priority levels: Low, Normal, High</li>
                    <li>Automatic expiry for time-sensitive announcements</li>
                    <li>Displayed on dashboard for all employees</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Audit Logs --}}
    <div class="col-12" id="audit">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-journal-text me-2"></i>Audit Logs</h5></div>
            <div class="card-body">
                <p>Track all user activities across the system for security and compliance.</p>
                <ul>
                    <li>Records who did what and when</li>
                    <li>Events tracked: Created, Updated, Deleted, Approved, Rejected, Assigned</li>
                    <li>Shows changed values (old → new) for updates</li>
                    <li>IP address and user agent stored for security</li>
                    <li>Sensitive data (passwords, tokens) automatically redacted</li>
                    <li>Filter by event type</li>
                    <li>Only accessible by Admin/Owner roles</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
