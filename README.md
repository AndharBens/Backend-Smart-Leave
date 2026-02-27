# Smart Leave Management System

A comprehensive leave management system built with Laravel 12 and PHP 8.4, featuring multi-role authentication (Employee, Manager, Admin) and a classic industries design theme.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Routes](#routes)
- [Demo Accounts](#demo-accounts)
- [API Endpoints](#api-endpoints)

## Features

### For Employees
- ✅ Dashboard with leave statistics
- ✅ Submit new leave requests
- ✅ View all leave requests with status tracking
- ✅ View detailed request information
- ✅ Cancel pending requests
- ✅ Profile management

### For Managers
- ✅ Dashboard with team overview
- ✅ View all pending leave requests
- ✅ Approve/Reject requests with notes
- ✅ Bulk action processing
- ✅ Approval history with filtering
- ✅ Export history functionality

### Design
- Classic industries theme with navy blue (#1e3a5f) color scheme
- Responsive layout with Tailwind CSS
- Professional and clean interface
- Font Awesome icons
- Inter font family

## Tech Stack

- **Backend:** Laravel 12
- **PHP Version:** 8.4
- **Database:** MySQL
- **Frontend:** Blade Templates + Tailwind CSS
- **Server:** Laragon (local development)
- **Icons:** Font Awesome 6.4.0

## Project Structure

```
Backend-Smart-Leave/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php      # Authentication logic
│   │       ├── EmployeeController.php  # Employee features
│   │       └── ManagerController.php   # Manager features
│   └── Models/
│       ├── User.php                    # User model with relationships
│       └── LeaveRequest.php            # Leave request model
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_leave_requests_table.php
│   │   └── 2025_02_27_000001_add_missing_columns_to_leave_requests_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Main layout with sidebar
│       ├── auth/
│       │   ├── login.blade.php         # Login page
│       │   └── register.blade.php      # Registration page
│       ├── employee/
│       │   ├── dashboard.blade.php     # Employee dashboard
│       │   ├── create.blade.php        # New leave request form
│       │   ├── my-requests.blade.php   # List of employee requests
│       │   └── profile.blade.php       # Profile management
│       └── manager/
│           ├── dashboard.blade.php     # Manager dashboard
│           ├── pending.blade.php       # Pending requests
│           └── history.blade.php       # Approval history
└── routes/
    └── web.php                          # Web routes definition
```

## Installation

### Prerequisites
- PHP 8.4 or higher
- Composer
- MySQL
- Laragon (recommended) or any local server

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd Backend-Smart-Leave
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Database
Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_leave
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Create Database and Run Migrations
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS smart_leave"
php artisan migrate
```

### Step 6: Seed Demo Data (Optional)
```bash
php artisan db:seed
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Access the application at `http://localhost:8000`

## Database Schema

### users table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | User full name |
| email | string | User email (unique) |
| password | string | Hashed password |
| role | enum | Role: 'employee', 'manager', 'admin' |
| department | string | Department name |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Last update |

### leave_requests table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| type | string | Leave type: 'annual', 'sick', 'personal', 'maternity', 'paternity', 'unpaid' |
| start_date | date | Leave start date |
| end_date | date | Leave end date |
| total_days | integer | Total leave days |
| reason | text | Reason for leave |
| status | string | Status: 'pending', 'approved', 'rejected' |
| manager_note | text | Optional manager note |
| approved_by | bigint | Foreign key to users (manager who approved) |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Last update |

## Routes

### Public Routes
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | / | - | Redirect to login |
| GET | /login | login | AuthController@showLogin |
| POST | /login | login.submit | AuthController@webLogin |
| GET | /register | register | AuthController@showRegister |
| POST | /register | register.submit | AuthController@webRegister |
| POST | /logout | logout | AuthController@logout |

### Employee Routes (Protected)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | /dashboard | dashboard | EmployeeController@dashboard |
| GET | /leave/create | leave.create | EmployeeController@createLeave |
| POST | /leave | leave.store | EmployeeController@storeLeave |
| GET | /leave/my-requests | leave.my-requests | EmployeeController@myRequests |
| GET | /leave/{id} | leave.show | EmployeeController@showLeave |
| DELETE | /leave/{id} | leave.cancel | EmployeeController@cancelLeave |
| GET | /profile | profile | EmployeeController@profile |
| PUT | /profile | profile.update | EmployeeController@updateProfile |

### Manager Routes (Protected)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | /manager/dashboard | manager.dashboard | ManagerController@dashboard |
| GET | /manager/pending | manager.pending | ManagerController@pendingRequests |
| GET | /manager/leave/{id} | manager.show.request | ManagerController@showRequest |
| PATCH | /manager/leave/{id} | manager.process.request | ManagerController@processRequest |
| POST | /manager/leave/bulk | manager.bulk.action | ManagerController@bulkAction |
| GET | /manager/history | manager.history | ManagerController@history |
| GET | /manager/history/{id} | manager.history.detail | ManagerController@historyDetail |
| GET | /manager/history/export | manager.history.export | ManagerController@exportHistory |

## Demo Accounts

| Role | Email | Password | Department |
|------|-------|----------|------------|
| Employee | employee@test.com | password | Engineering |
| Manager | manager@test.com | password | Engineering |
| Admin | admin@test.com | password | Management |

## API Endpoints

### Leave Request Details (JSON Response)

**Endpoint:** `GET /leave/{id}`

**Response:**
```json
{
    "id": 1,
    "type": "annual",
    "start_date": "Feb 28, 2026",
    "end_date": "Mar 02, 2026",
    "total_days": 3,
    "reason": "Family vacation",
    "status": "pending",
    "manager_note": null,
    "created_at": "Feb 27, 2026 10:30"
}
```

**Error Response:**
```json
{
    "error": "Leave request not found",
    "message": "No query results for model..."
}
```

### Create Leave Request

**Endpoint:** `POST /leave`

**Request Body:**
```json
{
    "leave_type": "annual",
    "start_date": "2026-03-01",
    "end_date": "2026-03-03",
    "reason": "Family vacation (at least 10 characters)",
    "attachment": "file.pdf" (optional)
}
```

**Validation Rules:**
- leave_type: required, must be one of: annual, sick, personal, maternity, paternity, unpaid
- start_date: required, date, must be today or in the future
- end_date: required, date, must be equal to or after start_date
- reason: required, string, minimum 10 characters
- attachment: optional, file, accepted types: pdf, jpg, jpeg, png, max 5MB

### Process Leave Request (Manager)

**Endpoint:** `PATCH /manager/leave/{id}`

**Request Body:**
```json
{
    "action": "approve",
    "note": "Approved - Enjoy your vacation!"
}
```

**or**

```json
{
    "action": "reject",
    "note": "Rejected - Critical project period"
}
```

**Validation Rules:**
- action: required, must be: approve or reject
- note: optional, string

## Design System

### Colors
- Primary: `#1e3a5f` (Navy Blue)
- Primary Dark: `#0f2744`
- Secondary: `#64748b` (Slate Gray)
- Accent: `#3b82f6` (Blue)
- Success: `#059669` (Green)
- Warning: `#d97706` (Amber)
- Danger: `#dc2626` (Red)
- Light: `#f8fafc`
- Border: `#e2e8f0`

### Status Badge Colors
- Pending: Yellow background (`#fef3c7`) with brown text (`#92400e`)
- Approved: Green background (`#d1fae5`) with dark green text (`#065f46`)
- Rejected: Red background (`#fee2e2`) with dark red text (`#991b1b`)

### Leave Type Icons
- Annual: `fa-umbrella-beach` (Blue)
- Sick: `fa-medkit` (Green)
- Personal: `fa-user` (Yellow)
- Maternity: `fa-baby` (Pink)
- Paternity: `fa-baby-carriage` (Purple)
- Unpaid: `fa-calendar` (Gray)

## Models & Relationships

### User Model
```php
// Has many leave requests
public function leaveRequests()
{
    return $this->hasMany(LeaveRequest::class);
}
```

### LeaveRequest Model
```php
// Belongs to user (employee)
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

// Belongs to approver (manager)
public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}
```

## Key Features Implementation

### Leave Days Calculation
The system automatically calculates total days between start and end dates:
```php
$startDate = Carbon::parse($validated['start_date']);
$endDate = Carbon::parse($validated['end_date']);
$totalDays = $startDate->diffInDays($endDate) + 1;
```

### Role-Based Redirect
After login, users are redirected based on their role:
```php
if ($user->role === 'manager' || $user->role === 'admin') {
    return redirect()->route('manager.dashboard');
}
return redirect()->route('dashboard');
```

### Status Workflow
1. Employee submits leave request → Status: `pending`
2. Manager reviews request
3. Manager approves → Status: `approved`
4. Manager rejects → Status: `rejected` with optional note

## Security Features

- CSRF protection on all forms
- Password hashing (bcrypt)
- Authentication middleware on protected routes
- Role-based access control
- Input validation and sanitization

## Development Notes

### Clearing Caches
```bash
# Clear route cache
php artisan route:clear

# Clear application cache
php artisan cache:clear

# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear
```

### Running Migrations
```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## License

This project is proprietary software.

## Support

For issues and questions, please contact the development team.

---

**Version:** 1.0.0
**Last Updated:** February 2026
bmaskdpaskdpoaksdop