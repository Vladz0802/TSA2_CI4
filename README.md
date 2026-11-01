# Tasks for Today Management System

IT0049 – Technical Summative Assessment 2 (extends TSA1). Built with CodeIgniter 4, PHP and MySQL.

TSA2 adds a login system, validated create and edit forms for tasks, and soft deletion.

## Pages

| Route | Who can open it | Description |
|---|---|---|
| `/` | Anyone | Only today's tasks (archived tasks are hidden) |
| `/tasks` | Anyone | Every task, ordered by date (archived tasks are hidden) |
| `/profile` | Anyone | The single demo user |
| `/about` | Anyone | Static page about the developer |
| `/login` | Anyone | Login form |
| `/tasks/new` | Logged in only | New Task form (title and date required) |
| `/tasks/{id}/edit` | Logged in only | Edit form, pre-filled with the task |

Creating, editing and deleting a task also require login. If you are logged out, these routes redirect to `/login`.

## Demo login

Username: `jdelacruz`  Password: `password123`

The password is stored as a bcrypt hash (`password_hash`) and checked with `password_verify`.

## Requirements

PHP 8.2+ (extensions: intl, mbstring, mysqli), Composer, MySQL/MariaDB.

## Setup

1. `composer install`
2. Copy `env` to `.env` (`copy env .env` on Windows) and adjust the database settings if needed.
3. Create an empty database named `tasks_for_today`.
4. `php spark migrate`
5. `php spark db:seed DatabaseSeeder`
6. `php spark serve` and open http://localhost:8080

`database.sql` is included as an alternative to steps 4–5 (import it into the empty database with phpMyAdmin).

If you already ran the TSA1 migrations, steps 4–5 only add the new columns and replace the demo user with one that has a password.

## TSA2 changes

- Migration `AddPasswordToUsers` adds `users.password`. The seeder stores a hashed password for the demo user.
- Migration `AddIsArchivedToTasks` adds `tasks.is_archived` (default 0).
- "Delete" sets `is_archived = 1` instead of removing the row. `TaskModel` leaves archived tasks out of the Welcome and Task List pages.
- `app/Filters/AuthFilter.php` protects the management routes (see `app/Config/Routes.php`).
- Forms are validated in `Tasks.php`: `title` is required (max 150 characters) and `task_date` is required and must be a valid date. Errors show under the fields and typed values are kept.
- CSRF protection is switched on in `app/Config/Filters.php`, and every form includes a CSRF token.

## Testing access control

1. Logged out: open `/tasks/new` and `/tasks/1/edit`. Both redirect to `/login`.
2. Log in with the demo account. The same pages now open.
3. Create a task, edit it, then delete it. It disappears from `/` and `/tasks`, but the row is still in the `tasks` table with `is_archived = 1`.
4. Log out. The management links disappear and `/tasks/new` redirects to `/login` again.

## Structure

- `app/Controllers` – Home, Tasks, Profile, About, Auth
- `app/Filters` – AuthFilter
- `app/Models` – TaskModel, UserModel
- `app/Views` – layout, welcome, tasks, task_form, login, profile, about
- `app/Database` – migrations and seeders
- `public/assets/css` – style.css, forms.css

## Live demo
<paste hosted link here>
