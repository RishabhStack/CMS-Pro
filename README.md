# HRMS - Human Resource Management System

## About

A comprehensive **Human Resource Management System** built with Laravel 10. Manage employees, attendance, leaves, payroll, performance, expenses, assets, shifts, timesheets, travel, exit management, helpdesk tickets, and more — all in one platform.

## Features

### Public Website
- Home page with hero, features, testimonials, stats
- Features, Pricing, About, Contact pages
- Privacy Policy & Terms

### Application Modules
| Module | Description |
|--------|-------------|
| **Dashboard** | Overview stats, attendance trends, pending leaves, birthdays, anniversaries |
| **Calendar** | Full calendar with leaves, holidays, attendance summary |
| **Employee Management** | Employee profiles, departments, designations, documents |
| **Attendance** | Clock in/out, break tracking, overtime, daily logs |
| **Leave Management** | Apply/approve/reject, leave types, balances, holiday calendar |
| **Payroll** | Salary components, payroll processing, payslips |
| **Performance Reviews** | Review cycles, goals/KPIs, self/manager review, 360 feedback, rating scales |
| **Expense Management** | Claims submission, receipt upload, approval workflow (pending → approved → paid) |
| **Asset Management** | Asset types, lifecycle tracking, assign/return, status tracking |
| **Shift Scheduling** | Shift types, daily roster, shift swap requests with approval |
| **Timesheets** | Project-based time tracking, approval workflow |
| **Travel Management** | Travel requests, itineraries, approval workflow |
| **Exit Management** | Resignations, clearance checklist, exit interviews |
| **Helpdesk / Tickets** | Ticket creation, assignment, priority, comments, status workflow |
| **Reports & Analytics** | Attendance/leave/payroll trends, headcount, turnover, saved reports |
| **Org Chart** | Visual organizational chart with employee cards |
| **Documents** | Upload and manage employee documents by type |
| **Announcements** | Company-wide notices with priority and expiry |
| **Role Management** | Custom roles and granular permissions |
| **Audit Logs** | Track all user activities, changes, and access |
| **Settings** | Company configuration, theme, cache management |

## Tech Stack

- **Laravel 10** / **PHP 8.2+**
- **MySQL** database
- **Blade Templates** + **Bootstrap 5** UI
- **jQuery** + **Axios** for AJAX
- **DataTables** (server-side), **SweetAlert2**, **Toastr**, **Select2**, **Flatpickr**, **Chart.js**

## Architecture

- **Repository + Service Pattern** — thin controllers, business logic in Services
- **Custom Roles & Permissions** — built from scratch (no Spatie)
- **Multi-Company Ready** — everything scoped by `company_id`
- **All CRUD in Modals** — AJAX modals for create/edit, SweetAlert for delete
- **Dark Mode** — user-preference with localStorage persistence

## Demo Credentials

| Email | Role | Password |
|-------|------|----------|
| owner@example.com | Owner | `Admin@123456` |
| admin@example.com | Admin | `Admin@123456` |
| employee@example.com | Employee | `Admin@123456` |

Other employees: `firstname@example.com` (e.g. priya@example.com) — password `Admin@123456`.

## Installation

```bash
git clone <repo-url> hrms && cd hrms
composer install
cp .env.example .env
php artisan key:generate
# Create MySQL database 'hrms' and update .env
php artisan migrate --seed
php artisan serve
```

Open `http://localhost:8000`.

### Reset Database
```bash
php artisan migrate:fresh --seed
```

## Seeded Demo Data

- 1 Company, 8 Departments, 30+ Designations
- 3 Roles (Owner, Admin, Employee), 20 Employees
- 6 Leave Types, 20+ Holidays
- 8 Salary Components, 3 Months Payroll
- 60 Days Attendance, Leave Records
- Performance Reviews with Goals & Feedback
- Expense Categories + 30+ Expense Claims
- Asset Types + Assignments
- Shift Types + Daily Roster (3 months)
- Timesheets (100+ entries) + Projects
- Travel Requests with Itineraries
- Resignation with Clearance Checklist
- Tickets with Comments
- Documents, Announcements, Settings

## Project Structure

```
app/
├── Http/Controllers/     # All module controllers
├── Http/Requests/        # Form validation
├── Http/Middleware/       # Company context, permissions
├── Models/               # Eloquent models
├── Repositories/         # DB query layer
├── Services/             # Business logic
├── Policies/             # Authorization
├── Traits/               # HasCompany, HasCreator, HasStatus
└── Helpers/              # Global helpers (including logAudit)
database/
├── migrations/           # 40+ migration files
└── seeders/              # PermissionSeeder, DemoDataSeeder
resources/views/          # Blade views
routes/web.php            # All application routes
```

---

*Built with Laravel 10. Developed by [Milind Daraniya](https://milinddaraniya.com).*
