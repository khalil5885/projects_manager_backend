# Project Management Application

A full-stack web application for managing projects, tasks, and team collaboration within a company. Built as an internship project.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | React.js + Inertia.js |
| Backend | Laravel 12 |
| Auth | Laravel Breeze (session) + Sanctum (API/mobile ready) |
| Database | MySQL |
| Local Dev | Laravel Herd |
| Email Testing | Mailtrap |

---

## Roles

The system has three global roles stored as an enum on the `users` table:

| Role | Description |
|------|-------------|
| `admin` | Full access — manages users, projects, tasks |
| `employee` | Internal user — works on assigned tasks |
| `client` | External — read-only access via secure expirable link |

Clients are not stored in a separate table — they live in `users` with `global_role = 'client'`.

---

## Database Schema

Key tables and their purpose:

- **users** — all actors (admin, employee, client) unified in one table
- **projects** — core project info, linked to a client via `owner_id` and a creator via `created_by`
- **project_members** — pivot table for employee-to-project assignments with a `project_role` (manager, developer, viewer)
- **tasks** — tasks linked to projects, assigned to employees
- **task_comments** — comments on tasks (admin ↔ employee communication)
- **project_comments** — comments on projects (admin ↔ client communication)
- **project_types** — project type templates
- **task_templates** — task templates linked to project types for auto-generation
- **client_access_tokens** — expirable secure tokens for client project access

> Note: Comment tables are intentionally **not polymorphic** — kept separate for clarity and query simplicity.

---

## Architecture Decisions

### Unified SPA with future API compatibility
The app is a unified SPA via Inertia.js — no separate REST API for the web layer. However, all controllers are written with `wantsJson()` branching so they can serve mobile clients via Sanctum tokens without rewriting logic.

```php
if ($request->wantsJson()) {
    return response()->json([...], 201);
}
return redirect()->route('admin.projects.index')->with('success', '...');
```

### DB Transactions on write operations
Any controller action that touches multiple tables is wrapped in `DB::transaction()` to prevent partial writes.

### Role middleware
A custom `EnsureRole` middleware is registered under the alias `role` in `bootstrap/app.php`. Routes are split by role access:

```php
Route::middleware('role:admin')->group(function () {
    Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
});
Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
```

### Task auto-generation (planned)
When a project is created with a `type_id`, tasks will be automatically generated from `task_templates` linked to that project type. This reduces repetitive admin work. Currently marked as TODO in `ProjectController@store`.

---

## Setup

```bash
git clone <repo-url>
cd <project-folder>

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure your `.env`:
```
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

MAIL_MAILER=smtp
# Mailtrap credentials here
```

Then:
```bash
php artisan migrate
php artisan db:seed  # if seeders are available
npm run dev
```

> Uses **Laravel Herd** for local development. Do not run `php artisan serve` alongside Herd.

---

## Features Status

### Admin
- [x] User management (create, list)
- [x] Role-based UI gating
- [x] Queued welcome email on user creation
- [x] Project creation (store)
- [ ] Project list / show / edit / delete
- [ ] Task management
- [ ] Project type & task template management

### Employee
- [ ] Dashboard
- [ ] View assigned projects and tasks
- [ ] Update task status
- [ ] Comment on tasks

### Client
- [ ] Secure link access via `client_access_tokens`
- [ ] Read-only project view
- [ ] Project comments (modification requests)

---

## Folder Structure (notable)

```
app/
  Http/
    Controllers/
      Admin/         # Admin-scoped controllers
    Middleware/
      EnsureRole.php # Role-based access middleware
  Models/            # All Eloquent models
resources/
  js/
    Pages/
      Admin/         # Inertia React pages for admin
```

---

## Notes

- Flash messages use Inertia's shared `flash` prop
- Forms use Inertia's `useForm` hook
- All admin actions are protected by `role:admin` middleware
- `created_by` is always set server-side from `auth()->id()` — never from the request