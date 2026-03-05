# Project Manager - Development Plan & Progress

**Project Type:** Laravel 11 Application with React/Inertia.js Frontend  
**Current Date:** February 20, 2026  
**Status:** In Development

---

## Project Overview

This is a comprehensive Project Management System built with Laravel 11 backend and React/Inertia.js frontend. The application allows users to manage projects, tasks, and team members with role-based access control.

### Core Features

- User Authentication & Authorization
- Project Management
- Task Management
- Team Member Management
- Admin User Management
- Role-Based Access Control

---

## Database Schema

### Tables & Relationships

#### 1. **Users Table**

- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `role` (string, default: 'employee') - **Options:** admin, employee, customer
- `remember_token`
- `timestamps`

**Relationships:**

- Has Many Projects
- Has Many ProjectMembers

---

#### 2. **Projects Table**

- `id` (Primary Key)
- `name` (string)
- `description` (text, nullable)
- `status` (enum) - **Options:** pending, active, completed
- `start_date` (date)
- `end_date` (date)
- `timestamps`

**Relationships:**

- Belongs To User (created_by)
- Has Many Tasks
- Has Many ProjectMembers

---

#### 3. **Tasks Table**

- `id` (Primary Key)
- `name` (string)
- `description` (text)
- `project_id` (foreign key → projects)
- `status` (enum) - **Options:** pending, active, completed
- `start_date` (date)
- `end_date` (date)
- `timestamps`

**Relationships:**

- Belongs To Project

---

#### 4. **Project Members Table**

- `id` (Primary Key)
- `project_id` (foreign key → projects, cascade delete)
- `user_id` (foreign key → users, cascade delete)
- `role` (string) - Project-specific role
- `timestamps`

**Relationships:**

- Belongs To Project
- Belongs To User

---

#### 5. **Additional Built-in Tables**

- `password_reset_tokens` - For password recovery
- `sessions` - For session management
- `cache` - For caching
- `jobs` - For queued jobs
- `personal_access_tokens` - For Sanctum API authentication

---

## Completed Features ✅

### 1. Database Setup

- [x] Users table with role column
- [x] Projects table with status and dates
- [x] Tasks table with project relationship
- [x] ProjectMembers table (pivot/team table)
- [x] All migrations created and organized by date

### 2. Eloquent Models

- [x] User model with relationships
    - `projects()` - hasMany
    - `projectMembers()` (implied for future use)
- [x] Project model with relationships
    - `user()` - belongsTo
    - `tasks()` - hasMany
- [x] Task model with relationships
    - `project()` - belongsTo
- [x] ProjectMember model created

### 3. Authentication System

- [x] Login (with middleware check for guests)
- [x] Registration (admin-only)
- [x] Password Reset
- [x] Email Verification
- [x] Logout
- [x] Password Change
- [x] Session Management
- [x] Sanctum API Authentication

### 4. Authorization & Gates

- [x] Role-based access control (admin-only gate)

### 5. Admin Features

- [x] Admin user creation endpoint: `POST /admin/users`
    - Validates: name, email, role (employee/customer)
    - Auto-generates 12-character password
    - Sends welcome email to 'kablouti.fady01@gmail.com'
    - Returns JSON response

### 6. Email Features

- [x] WelcomeUserMail class created
- [x] Mail queue setup for async sending

### 7. Frontend Setup

- [x] Inertia.js integration
- [x] React 19 with Vite bundler
- [x] Pages directory structure created
    - `/Auth` - Authentication pages
    - `/Profile` - User profile pages
    - `/Dashboard` - Dashboard (WIP)
    - `/Welcome` - Landing page (commented out)

### 8. Routes

- [x] Auth routes (login, register, forgot-password, reset-password, verify-email, logout)
- [x] Web routes with admin prefix for user management
- [x] API routes with Sanctum middleware for `/user` endpoint
- [x] CORS configuration available

### 9. Development Tools

- [x] Vite for asset bundling
- [x] Pest for testing framework setup
- [x] PHPUnit for unit tests

---

## Current Architecture

### Backend Stack

- **Framework:** Laravel 11
- **Database:** MySQL/SQLite
- **Authentication:** Laravel Sanctum + Sessions
- **Mail:** Queue-based with notification mailing
- **Testing:** Pest PHP

### Frontend Stack

- **Framework:** React 19
- **Server-Side Rendering:** Inertia.js
- **Build Tool:** Vite
- **Package Manager:** NPM

### API Design

- Currently hybrid: Web routes + API routes
- Uses Inertia.js for server-side rendering with React

---

## Role Definitions

### User Roles

1. **admin** - Full system access, can create users, manage projects
2. **employee** - Can participate in projects, create tasks
3. **customer** - Limited access, view-only permissions (to be implemented)

---

## API Endpoints (Current)

### Authentication Routes

```
GET    /auth/login              - Login page
POST   /auth/login              - Submit login
GET    /auth/register           - Register page (admin-only)
POST   /auth/register           - Submit registration
GET    /auth/forgot-password    - Forgot password page
POST   /auth/forgot-password    - Send reset link
GET    /auth/reset-password/{token} - Reset password page
POST   /auth/reset-password     - Submit new password
GET    /auth/verify-email       - Email verification prompt
GET    /auth/verify-email/{id}/{hash} - Verify email
POST   /auth/email/verification-notification - Resend verification
GET    /auth/confirm-password   - Confirm password page
POST   /auth/confirm-password   - Submit password confirmation
PUT    /auth/password           - Update password
POST   /auth/logout             - Logout
```

### Admin Routes

```
POST   /admin/users             - Create new user (admin-only)
```

### API Routes

```
GET    /api/user                - Get authenticated user (Sanctum)
```

### Web Routes

```
GET    /profile                 - User profile page
PATCH  /profile                 - Update profile
DELETE /profile                 - Delete account
```

---

## Seeders & Initial Data

### Seeded Data

- **AdminSeeder** creates default admin user:
    - Email: `admin231@gmail.com`
    - Password: `khalil123`
    - Role: `admin`
    - Name: `khalil admin`

---

## Not Yet Implemented ❌

### Core Features Still TODO

- [ ] Projects API endpoints (CRUD)
- [ ] Tasks API endpoints (CRUD)
- [ ] Project Members management
- [ ] Task assignment to team members
- [ ] User editing/deletion endpoints (beyond profile)
- [ ] Project status workflow validation
- [ ] Task dependency management
- [ ] Real-time notifications

### Frontend Pages TODO

- [ ] Dashboard with statistics/overview
- [ ] Projects listing and detail pages
- [ ] Project creation/editing forms
- [ ] Task management interface
- [ ] Team member management UI
- [ ] User profile page implementation
- [ ] Admin user management panel

### Authorization & Validation TODO

- [ ] Project ownership verification
- [ ] Task assignment authorization
- [ ] Team member role-based actions
- [ ] Customer role permissions
- [ ] Middleware for role checks

### Testing TODO

- [ ] Unit tests for models
- [ ] Feature tests for auth
- [ ] API endpoint tests
- [ ] Authorization tests

### Additional Features TODO

- [ ] Project templates
- [ ] Task priority levels
- [ ] File attachments
- [ ] Activity logging
- [ ] Project reports/analytics
- [ ] Email notifications for tasks
- [ ] User invitations
- [ ] Two-factor authentication

---

## File Structure Summary

```
project_manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       (Admin-only functionality)
│   │   │   ├── Auth/        (Authentication controllers)
│   │   │   └── *.php        (Other controllers)
│   │   └── Middleware/      (Custom middlewares)
│   ├── Mail/
│   │   └── WelcomeUserMail.php
│   └── Models/              (Eloquent models: User, Project, Task, ProjectMember)
├── database/
│   ├── migrations/          (Schema definitions)
│   └── seeders/             (Database seeders)
├── resources/
│   ├── js/
│   │   ├── Components/      (React components)
│   │   ├── Layouts/         (Layout components)
│   │   └── Pages/           (Page components)
│   └── css/                 (Stylesheets)
├── routes/                  (API, web, auth routes)
├── tests/                   (Test files)
└── config/                  (Configuration files)
```

---

## Next Steps Priority

### High Priority

1. Implement Projects CRUD API endpoints
2. Implement Tasks CRUD API endpoints
3. Create Project management frontend pages
4. Add authorization checks for project access
5. Implement task assignment

### Medium Priority

6. Create admin panel for user management UI
7. Implement ProjectMembers management
8. Add project status workflow validation
9. Create unit tests for models
10. Add feature tests for CRUD operations

### Low Priority

11. Email notifications
12. Activity logging
13. Project templates
14. Advanced analytics

---

## Recent Changes Log

### February 21, 2026 - Auth Cleanup & Planning

- **Removed RegisteredUserController** from auth routes (redundant with admin-only UserController)
- **Cleaned up auth.php** - Now only contains public authentication flows (login, password reset, email verify, logout)
- **Identified auth gaps** - MustVerifyEmail interface and role field handling for future implementation
- **Route organization clarified** - auth.php for auth, web.php for app routes, api.php for optional JSON endpoints

### February 20, 2026 - Bug Fixes & Code Review

- **Fixed User Model:** Changed `casts()` method to `protected $casts` property
    - Issue: Eloquent was ignoring the method; mass assignment and casting not working
    - This ensures password hashing and email_verified_at datetime casting apply correctly
- **Fixed task_employees Migration:** Corrected pivot table structure
    - Removed incorrect `project_id` column
    - Added proper `user_id` foreign key to establish Task ↔ User many-to-many relationship
    - Added foreign key constraints with cascade delete for referential integrity
    - Added unique composite key `(task_id, user_id)` to prevent duplicate assignments
- **Code Review:** All models and migrations validated for consistency and correctness

### February 19-20, 2026

- Created ProjectMembers table for team management
- Finalized database schema with all four core tables
- Set up role-based authorization with admin-only gates
- Implemented admin user creation with auto-generated passwords
- Configured mail queue for welcome emails
- Established Inertia.js + React frontend setup
- Created basic route structure for authentication and admin panel

---

## TODO - Tomorrow (February 22, 2026)

### High Priority - Start Here

- [ ] Implement Projects CRUD API endpoints
    - POST `/projects` - Create new project
    - GET `/projects` - List user's projects
    - GET `/projects/{id}` - Get project details
    - PUT `/projects/{id}` - Update project
    - DELETE `/projects/{id}` - Delete project
- [ ] Implement Tasks CRUD API endpoints
    - POST `/projects/{projectId}/tasks` - Create task
    - GET `/projects/{projectId}/tasks` - List tasks
    - GET `/tasks/{id}` - Get task details
    - PUT `/tasks/{id}` - Update task
    - DELETE `/tasks/{id}` - Delete task
- [ ] Add authorization checks
    - Users can only access/modify own projects
    - Users can only manage tasks in projects they belong to

### Medium Priority - After CRUD Works

- [ ] Create Project management frontend pages
- [ ] Implement ProjectMembers management endpoints
- [ ] Add project status workflow validation

---

## Development Status

```
Database Schema:      ████████████████████ 100%
Models:              ████████████████████ 100%
Authentication:      ███████████████████░ 95%
Admin Features:      ███████░░░░░░░░░░░░ 30%
API Endpoints:       ███░░░░░░░░░░░░░░░░ 10%
Frontend Setup:      ████████░░░░░░░░░░░ 40%
Frontend Pages:      ██░░░░░░░░░░░░░░░░░ 10%
Authorization:       ████░░░░░░░░░░░░░░░ 15%
Testing:             ░░░░░░░░░░░░░░░░░░░ 0%
```

---

**Last Updated:** February 20, 2026  
**Status:** Ready for API endpoint implementation phase

1. Routes (URL design) → What URLs exist?
2. Controllers → What handles each URL?
3. Requests (validation) → What input is allowed?
4. Resources (responses) → What output format?
5. Tests → Does it all work?

## Methods needed per controller:

```
Admin/ProjectController     → index, create, store, show, edit, update, destroy
Admin/TaskController        → index, create, store, show, edit, update, destroy
Admin/ProjectTypeController → index, create, store, edit, update, destroy
Admin/TaskTemplateController → index, create, store, edit, update, destroy
Admin/UserController        → index, create, store (already built)

Employee/DashboardController → index
Employee/TaskController      → index, show, updateStatus, addComment

Client/ProjectController     → show
Client/TokenAccessController → access, addComment
```
