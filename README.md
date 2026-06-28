# HRMS - Human Resource Management System

## About This Project

Hello everyone! This is a **Human Resource Management System (HRMS)** web application that I built using Laravel 10. Basically it's a complete software to manage employees, attendance, leaves, payroll, and all HR related work in one place. Companies can use this to handle their entire workforce digitally.

## Website Features

My HRMS website has two parts:

### 1. Public Marketing Website (Anyone can see)
- Home page with hero section, features, testimonials, company stats
- Features page showing all HRMS features
- Pricing page with monthly/yearly plans
- About Us page with company values
- Contact Us page with form
- Privacy Policy and Terms pages

### 2. Main Application (After login)
- **Dashboard** - See overview of employees, attendance, leaves, payroll stats
- **Employee Management** - Add, edit, view, delete employees with profiles
- **Department Management** - Organize employees by departments
- **Designation Management** - Job titles and roles
- **Role & Permission Management** - Control what each user can do
- **Attendance Tracking** - Clock in/out, break management, attendance reports
- **Leave Management** - Apply leaves, approve/reject, check balances
- **Holiday Calendar** - Company holidays list
- **Payroll Management** - Generate payslips, manage salaries
- **Documents** - Upload and manage employee documents
- **Announcements** - Company-wide notices
- **Settings** - Configure company preferences

## Tech Stack (Technologies Used)

- **Laravel 10** - PHP framework
- **PHP 8.2+**
- **MySQL** - Database
- **Blade Templates** - Frontend rendering
- **Bootstrap 5** - UI design and responsive layout
- **jQuery + Axios** - AJAX calls and interactivity
- **DataTables** - Tables with search, sort, pagination
- **SweetAlert2** - Beautiful popup alerts
- **Toastr** - Notification messages
- **Select2** - Better dropdown selects
- **Flatpickr** - Date pickers

## Important Architecture Decisions

Why I built it this way:

1. **Repository + Service Pattern** - Controllers are thin. Business logic goes in Services. Database queries go in Repositories. Makes code clean and reusable.

2. **Custom Roles & Permissions** - I did NOT use Spatie package. Built my own roles/permissions system from scratch. Each company has Owner, Admin, Employee roles. Permissions are stored in database.

3. **Multi-Company Ready** - One database, but each employee belongs to one company. First user who registers becomes the Owner. Everything is scoped by `company_id`.

4. **All CRUD in Modals** - No separate pages for create/edit. Everything happens in Bootstrap modals using AJAX. Delete uses SweetAlert2 confirmation.

5. **Dark Mode Support** - Users can toggle dark mode. Preference saved in localStorage and company settings.

## 📸 Demo Credentials

You can login with these demo accounts (all password is `password`):

| Email | Role | Description |
|-------|------|-------------|
| owner@example.com | **Owner** | Full access - can see everything and manage all modules |
| admin@example.com | **Admin** | Admin access - can manage employees, attendance, leaves, payroll, etc |
| employee@example.com | **Employee** | Limited access - can only view own data, apply leaves, mark attendance |

Other employee logins: `firstname@example.com` (e.g. priya@example.com, raj@example.com, emily@example.com) — password is also `password`.

All demo credentials are displayed on the login page itself so it's easy to check.

## How to Setup (Installation)

### Requirements
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js (optional, for frontend assets)

### Step-by-Step Setup

```bash
# 1. Clone the project
git clone <your-repo-url> hrms
cd hrms

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Create a MySQL database named 'hrms'
#    Then update .env file with your database details:
#    DB_DATABASE=hrms
#    DB_USERNAME=root
#    DB_PASSWORD=yourpassword

# 6. Run migrations and seeders (creates tables + demo data)
php artisan migrate --seed

# 7. Start the development server
php artisan serve

# 8. Open browser and go to http://localhost:8000
```

### Seed Data (Dummy Data)

When you run `php artisan migrate --seed`, the seeder will automatically create:

- **1 Company** - Milind Corporation
- **8 Departments** - Engineering, Marketing, Sales, HR, Finance, Operations, Design, Legal
- **30+ Designations** - Various job titles under each department
- **5 Employee Statuses** - Active, Probation, Notice Period, Terminated, Resigned
- **3 Roles** - Owner, Admin, Employee (with proper permissions)
- **20 Employees** - With profiles, departments, designations, salaries
- **6 Leave Types** - Annual, Sick, Personal, Maternity, Paternity, Bereavement
- **20+ Holidays** - Current year and next year US holidays
- **8 Salary Components** - Earning and deduction components
- **60 Days Attendance** - For all active employees
- **Leave Records** - Approved and pending leaves
- **3 Months Payroll** - Salary slips for all employees
- **Documents** - Sample documents for employees
- **7 Announcements** - Company announcements
- **Company Settings** - Default configuration

### To reset everything:

```bash
php artisan migrate:fresh --seed
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Controllers (Auth, Dashboard, Modules, Frontend)
│   ├── Requests/        # Form validation requests
│   ├── Middleware/       # Company context, permission middleware
│   └── Controllers/
│       └── Auth/         # Login, Register controllers
├── Models/               # Eloquent models
├── Repositories/         # Database query layer
├── Services/             # Business logic layer
├── Traits/               # Reusable traits (HasCompany, HasCreator, HasStatus)
├── Policies/             # Authorization policies
└── Helpers/              # Global helper functions
database/
├── migrations/           # 26 migration files
└── seeders/              # PermissionSeeder, DemoDataSeeder
resources/views/
├── layouts/              # Master, auth, public layouts
├── auth/                 # Login, register pages
├── public/               # Marketing website pages
└── (module views)        # departments, employees, etc.
routes/
└── web.php               # All routes
public/
├── css/app.css           # Main styles
├── css/dark-mode.css     # Dark mode overrides
├── css/public.css        # Marketing website styles
└── js/app.js             # Global App helper (modal, datatable, form, etc.)
```

## Some Screenshots

(Add screenshots here if needed)

## Contact

If you have any questions or suggestions, feel free to reach out!

---

*Built with Laravel 10, Bootstrap 5, jQuery, MySQL — because Indian developers know how to build solid stuff! 😎*
