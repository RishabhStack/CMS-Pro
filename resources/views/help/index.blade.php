@extends('layouts.master')

@section('title', 'Help & User Guide')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">HRMS User Guide</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-4">
                <nav id="helpNav" class="nav flex-column nav-pills sticky-top" style="top: 20px;">
                    <a class="nav-link active" href="#getting-started">Getting Started</a>
                    <a class="nav-link" href="#employees">Employees</a>
                    <a class="nav-link" href="#attendance">Attendance</a>
                    <a class="nav-link" href="#leaves">Leaves</a>
                    <a class="nav-link" href="#payroll">Payroll</a>
                    <a class="nav-link" href="#salary-components">Salary Components</a>
                    <a class="nav-link" href="#departments">Departments & Designations</a>
                    <a class="nav-link" href="#holidays">Holidays</a>
                    <a class="nav-link" href="#announcements">Announcements</a>
                    <a class="nav-link" href="#roles">Roles & Permissions</a>
                    <a class="nav-link" href="#settings">Settings</a>
                </nav>
            </div>
            <div class="col-md-9">
                <div data-bs-spy="scroll" data-bs-target="#helpNav" data-bs-smooth-scroll="true">
                    <section id="getting-started" class="mb-5">
                        <h3>Getting Started</h3>
                        <p class="text-muted">Welcome to the HRMS! This guide will help you navigate and use all features of the system.</p>
                        <h5>Login</h5>
                        <ol>
                            <li>Navigate to the login page.</li>
                            <li>Enter your email and password provided by your administrator.</li>
                            <li>Click <strong>"Login"</strong> to access the dashboard.</li>
                        </ol>
                        <h5>Dashboard</h5>
                        <p>The dashboard gives you a quick overview of:</p>
                        <ul>
                            <li><strong>Employee Statistics</strong> - Total employees, attendance today, employees on leave</li>
                            <li><strong>Weekly Attendance Trend</strong> - Graphical view of attendance for the last 7 days</li>
                            <li><strong>Pending Leaves</strong> - Recent leave requests awaiting approval</li>
                            <li><strong>Upcoming Holidays</strong> - Next scheduled holidays</li>
                            <li><strong>Announcements</strong> - Latest company announcements</li>
                        </ul>
                    </section>

                    <section id="employees" class="mb-5">
                        <h3>Employees</h3>
                        <p>Manage all employee records from the Employees section.</p>
                        <h5>Add Employee</h5>
                        <ol>
                            <li>Go to <strong>Employees</strong> from the sidebar.</li>
                            <li>Click <strong>"Add Employee"</strong>.</li>
                            <li>Fill in employee details (name, email, department, designation, etc.).</li>
                            <li>Click <strong>"Create"</strong> to save. A user account is automatically created.</li>
                        </ol>
                        <h5>Edit Employee</h5>
                        <ol>
                            <li>Click the <strong>Edit</strong> icon next to an employee record.</li>
                            <li>Update the required fields.</li>
                            <li>Click <strong>"Update"</strong>.</li>
                        </ol>
                        <h5>Delete Employee</h5>
                        <ol>
                            <li>Click the <strong>Delete</strong> icon next to an employee record.</li>
                            <li>Confirm the deletion in the popup.</li>
                        </ol>
                        <h5>Import/Export</h5>
                        <p>Use the Import and Export buttons to bulk manage employee data via CSV files.</p>
                    </section>

                    <section id="attendance" class="mb-5">
                        <h3>Attendance</h3>
                        <p>Track employee attendance, clock-in/out times, and breaks.</p>
                        <h5>Manual Entry</h5>
                        <ol>
                            <li>Go to <strong>Attendance</strong> from the sidebar.</li>
                            <li>Click <strong>"Add Attendance"</strong>.</li>
                            <li>Select the employee, date, and status (present, absent, late, half-day).</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                        <h5>Quick Clock In/Out</h5>
                        <p>Use the Clock In/Out buttons on the dashboard or attendance page to quickly mark your own attendance.</p>
                        <h5>Break Management</h5>
                        <p>Click <strong>"Start Break"</strong> when going on break and <strong>"End Break"</strong> when returning. Break durations are tracked automatically.</p>
                        <h5>Filtering</h5>
                        <p>Use the date range and status filters at the top of the attendance table to find specific records.</p>
                    </section>

                    <section id="leaves" class="mb-5">
                        <h3>Leaves</h3>
                        <p>Manage leave applications, approvals, and balances.</p>
                        <h5>Apply for Leave</h5>
                        <ol>
                            <li>Go to <strong>Leaves</strong> from the sidebar.</li>
                            <li>Click <strong>"Apply Leave"</strong>.</li>
                            <li>Select <strong>Leave Type</strong> (Sick, Casual, Annual, etc.).</li>
                            <li>Choose <strong>Start Date</strong> and <strong>End Date</strong>.</li>
                            <li>Enter a <strong>Reason</strong> for the leave.</li>
                            <li>Click <strong>"Submit"</strong>.</li>
                        </ol>
                        <h5>Approve/Reject Leave</h5>
                        <ol>
                            <li>Open the leave record by clicking the <strong>View</strong> icon.</li>
                            <li>Click <strong>"Approve"</strong> or <strong>"Reject"</strong>.</li>
                            <li>Optionally provide a reason for rejection.</li>
                        </ol>
                        <h5>Leave Balances</h5>
                        <p>The leave index page shows your current leave balances (used/total) for each leave type at the top.</p>
                    </section>

                    <section id="payroll" class="mb-5">
                        <h3>Payroll</h3>
                        <p>Generate and manage employee payroll records.</p>
                        <h5>Generate Payroll</h5>
                        <ol>
                            <li>Go to <strong>Payroll</strong> from the sidebar under the Payroll section.</li>
                            <li>Click <strong>"Generate Payroll"</strong>.</li>
                            <li>Select one or more <strong>Employees</strong>.</li>
                            <li>Choose the <strong>Month</strong> and <strong>Year</strong>.</li>
                            <li>Click <strong>"Generate"</strong> to create draft payroll records.</li>
                        </ol>
                        <h5>Payroll Statuses</h5>
                        <ul>
                            <li><span class="badge bg-secondary">Draft</span> - Created but not yet processed</li>
                            <li><span class="badge bg-success">Generated</span> - Ready for processing</li>
                            <li><span class="badge bg-warning">Processing</span> - Being processed</li>
                            <li><span class="badge bg-info">Paid</span> - Payment completed</li>
                            <li><span class="badge bg-danger">Cancelled</span> - Payroll cancelled</li>
                        </ul>
                        <h5>Mark as Paid</h5>
                        <ol>
                            <li>Select the payroll records using the checkboxes.</li>
                            <li>Click <strong>"Process Selected"</strong>.</li>
                            <li>Choose <strong>"Mark as Paid"</strong> or <strong>"Cancel"</strong>.</li>
                            <li>Click <strong>"Confirm"</strong>.</li>
                        </ol>
                        <p>Alternatively, click the <strong>Mark as Paid</strong> button in the Actions column for individual records.</p>
                    </section>

                    <section id="salary-components" class="mb-5">
                        <h3>Salary Components</h3>
                        <p>Define salary structure components like Basic, HRA, DA, PF, etc.</p>
                        <h5>Add Component</h5>
                        <ol>
                            <li>Go to <strong>Salary Components</strong> from the sidebar under Payroll.</li>
                            <li>Click <strong>"Add Component"</strong>.</li>
                            <li>Enter <strong>Name</strong> (e.g., Basic Salary, House Rent Allowance).</li>
                            <li>Select <strong>Type</strong> - Earning or Deduction.</li>
                            <li>Select <strong>Value Type</strong> - Fixed Amount or Percentage.</li>
                            <li>Enter <strong>Default Value</strong>.</li>
                            <li>Set <strong>Status</strong> to Active.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                        <h5>Edit/Delete</h5>
                        <p>Use the Edit and Delete buttons in the Actions column to modify or remove salary components.</p>
                    </section>

                    <section id="departments" class="mb-5">
                        <h3>Departments & Designations</h3>
                        <p>Organize your company structure with departments and designations.</p>
                        <h5>Departments</h5>
                        <ol>
                            <li>Go to <strong>Departments</strong> from the sidebar.</li>
                            <li>Click <strong>"Add Department"</strong>.</li>
                            <li>Enter the department name and assign a manager.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                        <h5>Designations</h5>
                        <ol>
                            <li>Go to <strong>Designations</strong> from the sidebar under Employees.</li>
                            <li>Click <strong>"Add Designation"</strong>.</li>
                            <li>Enter the designation name and select the parent department.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                    </section>

                    <section id="holidays" class="mb-5">
                        <h3>Holidays</h3>
                        <p>Manage company holidays and observances.</p>
                        <ol>
                            <li>Go to <strong>Holidays</strong> from the sidebar.</li>
                            <li>Click <strong>"Add Holiday"</strong>.</li>
                            <li>Enter the holiday <strong>Name</strong>, select the <strong>Date</strong>, and choose the <strong>Type</strong>.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                        <p>Holidays are automatically displayed on the dashboard and used for attendance calculations.</p>
                    </section>

                    <section id="announcements" class="mb-5">
                        <h3>Announcements</h3>
                        <p>Post company-wide announcements and notices.</p>
                        <ol>
                            <li>Go to <strong>Announcements</strong> from the sidebar.</li>
                            <li>Click <strong>"Add Announcement"</strong>.</li>
                            <li>Enter the <strong>Title</strong>, <strong>Content</strong>, select <strong>Type</strong> and <strong>Priority</strong>.</li>
                            <li>Set the <strong>Status</strong> to Published to make it visible to all employees.</li>
                            <li>Optionally set an expiry date.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                    </section>

                    <section id="roles" class="mb-5">
                        <h3>Roles & Permissions</h3>
                        <p>Manage user roles and their permissions.</p>
                        <h5>Create Role</h5>
                        <ol>
                            <li>Go to <strong>Roles</strong> from the sidebar under Settings.</li>
                            <li>Click <strong>"Add Role"</strong>.</li>
                            <li>Enter the role name and description.</li>
                            <li>Select the permissions for this role.</li>
                            <li>Click <strong>"Create"</strong>.</li>
                        </ol>
                        <h5>Manage Permissions</h5>
                        <p>Click the <strong>Manage Permissions</strong> button for a role to assign or remove specific permissions.</p>
                    </section>

                    <section id="settings" class="mb-5">
                        <h3>Settings</h3>
                        <p>Configure company settings and preferences.</p>
                        <ol>
                            <li>Go to <strong>Settings</strong> from the sidebar.</li>
                            <li>Update company information (name, email, phone, address).</li>
                            <li>Configure work hours, grace period, late threshold, etc.</li>
                            <li>Set leave approval workflow (single or multi-level).</li>
                            <li>Choose date format, time format, language, and theme color.</li>
                            <li>Click <strong>"Update Settings"</strong> to save.</li>
                        </ol>
                        <p>You can also toggle dark mode using the moon/sun icon in the header bar.</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('#helpNav .nav-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#helpNav .active')?.classList.remove('active');
            this.classList.add('active');
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    document.addEventListener('scroll', function () {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('#helpNav .nav-link');
        let current = '';
        sections.forEach(function (section) {
            const top = section.offsetTop - 100;
            if (window.scrollY >= top) {
                current = section.getAttribute('id');
            }
        });
        navLinks.forEach(function (link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
</script>
@endpush
