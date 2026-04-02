# Project Manager - Development Plan & Progress

**Project Type:** Laravel 11 + Inertia.js + React SPA  
**Current Date:** March 6, 2026  
**Status:** In Development — Models Complete, Controllers Next

---

## Project Overview

A comprehensive role-based Project Management System. Admin manages everything, employees work on assigned tasks, clients get read-only access to their projects via secure time-limited links.

### Stack

- **Backend:** Laravel 11, MySQL, Laravel Breeze (session auth)
- **Frontend:** React 19, Inertia.js, Tailwind CSS, Vite
- **Dev Tools:** Laravel Herd, DBngin, Mailtrap

---

## Database Schema

### Tables & Relationships

#### 1. users

- `id`, `name`, `email`, `password`, `global_role` ENUM(admin, employee, client), `remember_token`, `timestamps`
- **Relationships:** ownedProjects (hasMany), memberProjects (belongsToMany via project_members), assignedTasks (hasMany), taskComments (hasMany), projectComments (hasMany), accessTokens (hasMany)

#### 2. projects

- `id`, `name`, `description`, `status` ENUM(pending, in_progress, completed, on_hold), `start_date`, `end_date`, `owner_id` FK→users, `created_by` FK→users, `type_id` FK→project_types, `deleted_at`, `timestamps`
- **Relationships:** owner (belongsTo), creator (belongsTo), type (belongsTo), members (belongsToMany via project_members), tasks (hasMany), comments (hasMany), accessTokens (hasMany)

#### 3. project_types

- `id`, `name`, `description`, `timestamps`
- **Relationships:** projects (hasMany), taskTemplates (hasMany)

#### 4. task_templates

- `id`, `project_type_id` FK→project_types, `title`, `description`, `default_due_days`, `order`, `timestamps`
- **Relationships:** projectType (belongsTo)
- **Purpose:** Auto-generate tasks when a project of this type is created

#### 5. tasks

- `id`, `project_id` FK→projects, `title`, `description`, `status` ENUM(todo, in_progress, review, done), `priority` ENUM(low, medium, high), `assigned_to` FK→users, `due_date`, `deleted_at`, `timestamps`
- **Relationships:** project (belongsTo), assignedTo (belongsTo), comments (hasMany)

#### 6. task_comments

- `id`, `task_id` FK→tasks, `user_id` FK→users, `type` ENUM(note, question, update), `body`, `timestamps`

#### 7. project_comments

- `id`, `project_id` FK→projects, `user_id` FK→users, `type` ENUM(evaluation, change_request, general), `body`, `timestamps`

#### 8. project_members

- `id`, `project_id` FK→projects, `user_id` FK→users, `project_role` ENUM(manager, developer, viewer), `timestamps`
- **Unique:** (project_id, user_id)

#### 9. client_access_tokens

- `id`, `client_id` FK→users, `project_id` FK→projects, `token` (unique), `expires_at`, `last_used_at`, `timestamps`
- **Purpose:** One-time expirable links for client read-only project access (like Discord invite)

#### 10. notifications

- Laravel built-in polymorphic structure: `id` (UUID), `type`, `notifiable_type`, `notifiable_id`, `data` (JSON), `read_at`, `timestamps`

---

## Completed Features ✅

### Database

- [x] All 10 migrations created, ordered correctly, and verified
- [x] global_role enum built into users table (no separate migration)
- [x] project_types runs before projects (FK dependency resolved)
- [x] SoftDeletes on projects and tasks
- [x] migrate:fresh --seed working cleanly

### Models (all complete)

- [x] User — fillable, casts, role helpers (isAdmin/isEmployee/isClient), all relationships
- [x] Project — SoftDeletes, fillable, casts, owner/creator/type/members/tasks/comments/tokens
- [x] Task — SoftDeletes, fillable, project/assignedTo/comments
- [x] TaskComment — fillable, user/task
- [x] ProjectComment — fillable, user/project
- [x] ProjectMember — fillable, user/project
- [x] ProjectType — fillable, projects/taskTemplates
- [x] TaskTemplate — fillable, projectType
- [x] ClientAccessToken — fillable, casts, isValid() helper, client/project

### Authentication

- [x] Laravel Breeze session-based auth
- [x] Login / Logout
- [x] Profile edit/update/delete
- [x] Post-login redirect by global_role

### Middleware & Authorization

- [x] EnsureRole middleware created and registered in bootstrap/app.php
- [x] role:admin protecting all /admin routes
- [x] role:employee protecting /employee routes
- [x] role:client protecting /client routes

### Admin — Users

- [x] List users (paginated)
- [x] Create user form
- [x] Store user (auto-generates password, sends welcome email via Mailtrap)
- [x] global_role correctly saved (admin creates employee or client accounts)

### Routes

- [x] Admin group: /admin prefix, role:admin middleware
- [x] Employee group: /employee prefix, role:employee middleware
- [x] Client group: /client prefix, role:client middleware
- [x] Dashboard redirects by role after login
- [x] Profile routes for all authenticated users

### Frontend

- [x] Inertia.js + React SPA architecture
- [x] AuthenticatedLayout
- [x] UserIndex page (paginated user list, + New User button)
- [x] UserCreate page (name, email, role selector, auto-password info)
- [x] Role-based UI (isAdmin check via auth.user.global_role)

### Seeder

- [x] AdminSeeder — creates default admin account

---

## Controllers Structure

```
app/Http/Controllers/
├── Admin/
│   ├── UserController.php          ✅ index, create, store
│   ├── ProjectController.php       ← index, create, store, show, edit, update, destroy
│   ├── TaskController.php          ← index, create, store, show, edit, update, destroy
│   ├── ProjectTypeController.php   ← index, create, store, edit, update, destroy
│   └── TaskTemplateController.php  ← index, create, store, edit, update, destroy
├── Employee/
│   ├── DashboardController.php     ← index
│   └── TaskController.php          ← index, show, updateStatus, addComment
└── Client/
    ├── ProjectController.php       ← show
    └── TokenAccessController.php   ← access, addComment
```

---

## Not Yet Implemented ❌

### Controllers

- [ ] Admin/ProjectController — full CRUD
- [ ] Admin/TaskController — full CRUD
- [ ] Admin/ProjectTypeController — full CRUD
- [ ] Admin/TaskTemplateController — full CRUD
- [ ] Admin/UserController — update, destroy
- [ ] Employee/DashboardController
- [ ] Employee/TaskController
- [ ] Client/ProjectController
- [ ] Client/TokenAccessController

### Features

- [ ] Task auto-generation on project creation (via task_templates)
- [ ] Project member assignment
- [ ] Client access token generation & email sending
- [ ] Notifications on task/project assignment
- [ ] Employee dashboard (assigned tasks/projects)
- [ ] Client read-only project view via token link

### Frontend Pages

- [ ] Admin: Projects (index, create, show, edit)
- [ ] Admin: Tasks (index, create, show, edit)
- [ ] Admin: Project Types + Task Templates
- [ ] Employee: Dashboard, Task list, Task detail
- [ ] Client: Project view (read-only)

### Testing

- [ ] Unit tests for models
- [ ] Feature tests for auth
- [ ] Controller tests
- [ ] Authorization tests

---

## Next Steps (in order)

1. **Admin/ProjectController** — index, create, store, show, edit, update, destroy
2. **Add projects routes** to web.php
3. **React pages** for projects (Index, Create, Show)
4. **Admin/TaskController** — CRUD within a project
5. **ProjectType + TaskTemplate controllers** — for automation
6. **Task auto-generation** logic in ProjectController@store
7. **Employee controllers** — dashboard + task updates
8. **Client controllers** — read-only view + token access
9. **Notifications** — on assignment events

---

## Architecture Reminder

```
Browser Request
     ↓
Laravel Router → Middleware (auth, role check)
     ↓
Controller → Inertia::render('PageName', ['data' => $data])
     ↓
React Component renders with props
     ↓
Browser (SPA, no full reload)
```

- No manual fetch() for page navigation — use Inertia Link
- No JWT — session-based auth via Breeze
- Forms use Inertia useForm hook (post/patch/delete)
- route() helper available in React via Ziggy

---

## Development Progress

```
Database Migrations:    ████████████████████ 100%
Eloquent Models:        ████████████████████ 100%
Authentication:         ████████████████████ 100%
Middleware/Auth:        ████████████████████ 100%
Admin Users CRUD:       ████████████░░░░░░░░  60% (missing update/destroy)
Admin Projects CRUD:    ░░░░░░░░░░░░░░░░░░░░   0%
Admin Tasks CRUD:       ░░░░░░░░░░░░░░░░░░░░   0%
Project Types/Templates:░░░░░░░░░░░░░░░░░░░░   0%
Employee Features:      ░░░░░░░░░░░░░░░░░░░░   0%
Client Features:        ░░░░░░░░░░░░░░░░░░░░   0%
Frontend Pages:         ████░░░░░░░░░░░░░░░░  20%
Testing:                ░░░░░░░░░░░░░░░░░░░░   0%
```

---

**Last Updated:** March 6, 2026  
**Last Commit:** feat: complete all models and controller structure

Pages/
├── Admin/
│ ├── Projects/
│ │ ├── Index.jsx ← list all projects
│ │ ├── Create.jsx ← create project form
│ │ ├── Show.jsx ← project detail
│ │ └── Edit.jsx ← edit project form
│ ├── Tasks/
│ │ ├── Index.jsx
│ │ ├── Create.jsx
│ │ ├── Show.jsx
│ │ └── Edit.jsx
│ ├── ProjectTypes/
│ │ ├── Index.jsx
│ │ └── Create.jsx
│ └── TaskTemplates/
│ ├── Index.jsx
│ └── Create.jsx
├── Employee/
│ ├── Dashboard.jsx
│ └── Tasks/
│ ├── Index.jsx
│ └── Show.jsx
└── Client/
└── Projects/
└── Show.jsx
