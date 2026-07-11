# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Laravel 12 API backend for a multi-tenant Task Tracker. Teams have members, tasks, labels, and reports. Authorization is team-scoped via Spatie Laravel Permission's "teams" feature — the same user can hold different roles (`owner`, `admin`, `member`, `viewer`) in different teams.

## Commands

Run via Docker (recommended, matches the dev stack in `docker-compose.yml`):

```bash
docker compose up -d                                  # app, nginx, db (mysql:3310), vite, queue, scheduler, mailhog
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=RoleSeeder   # required once: creates owner/admin/member/viewer roles
docker compose exec app php artisan test                          # run all tests
docker compose exec app php artisan test --filter=TestName         # run a single test
docker compose exec app ./vendor/bin/pint                          # code style (Laravel Pint)
```

Without Docker (`composer run dev` starts server + queue listener + pail logs + vite together):

```bash
composer install && npm install
php artisan migrate
php artisan db:seed --class=RoleSeeder
composer test          # clears config cache, then `php artisan test`
composer dev
```

App runs behind nginx at `http://localhost:8003` in Docker. Mailhog UI at `http://localhost:8025` for viewing dev emails (invitations, reports, reminders).

## Architecture

The `app/` directory follows a layered, DDD-ish structure rather than the vanilla Laravel MVC layout — controllers are thin and never talk to Eloquent directly:

```
Http/Controllers   → validate via Http/Requests, delegate to a Service, return JSON
Application/Services → business logic/orchestration (one per domain: Tasks, Teams, TeamMembers, Invitation, Reports, Misc/Label, Dropdown, Auth)
Domain/Interfaces  → repository contracts (e.g. TaskRepositoryInterface, TeamRepositoryInterface)
Infrastructure/Repositories → Eloquent implementations of the Domain interfaces (EloquentTaskRepository, etc.)
Infrastructure/Providers/RepositoryServiceProvider → binds each interface to its Eloquent implementation
Infrastructure/Mail   → Mailables (TeamInvitationMail, SendReportMail, SendReminderMail)
Models             → Eloquent models
Policies           → authorization (see AuthorizesTeamMembership concern for shared team-membership checks)
```

When adding a feature: add/extend the interface in `Domain/Interfaces`, implement it in `Infrastructure/Repositories`, bind it in `RepositoryServiceProvider`, add the orchestration in an `Application/Services` class, then wire up a thin controller + FormRequest + route.

### Team-scoped RBAC (Spatie Laravel Permission)

Full details in `docs/TEAM_RBAC.md`. Key points to remember when touching auth/permissions:

- `config/permission.php` has `teams => true`; the team foreign key is `team_id` on `model_has_roles`/`model_has_permissions`.
- Any route with a `{team}` parameter must run the `team.context` middleware (`TeamContextMiddleware`) first — it resolves `{team}` and calls `setPermissionsTeamId()` so `$user->hasRole()` checks apply to that team.
- Route-level role gating uses `team.role:owner,admin` (`EnsureTeamRole` middleware), which must come **after** `team.context` in the middleware chain.
- Roles (`owner`, `admin`, `member`, `viewer`) are global role *definitions* (guard `web`, no `team_id`); the per-team assignment lives in `model_has_roles.team_id`. Seed them with `php artisan db:seed --class=RoleSeeder`.
- Never pass a `Team` model as a second arg to `assignRole()` — Spatie treats it as another role name. Instead wrap with `setPermissionsTeamId($team->id)` / restore the previous team id afterward (see pattern in `TeamService::create` and `InvitationService::acceptInvitation`).
- `team_members.role` is a denormalized display copy of the role string; `model_has_roles` is the actual source of truth for authorization. Keep both in sync when assigning/changing roles.
- Task routes additionally use `Route::scopeBindings()` so `{task}` / `{memberDailyTask}` route-model-binding is implicitly scoped to the parent `{team}`.

### Domain model relationships worth knowing

- `Team` → many `TeamMember` (team_id), many `Task`, many `Label`, many `ReportReceiver`.
- `TeamMember` (UUID PK) belongs to `Team` and `User`; `TeamMember->tasks()` is keyed on `Task.assigned_to`, not a `user_id` — tasks are assigned to a *team membership*, not a user directly, so the same user can be assigned tasks separately per team.
- `Task` (UUID PK) belongs to `Team` and to an assignee `TeamMember` (`assigned_to`); also tracks an `entry_maker` (`TeamMember` who created it); many-to-many with `Label`; has many `MemberDailyTask` (a per-day planning entry linking a member to a task).
- Because assignment is by `team_member_id`/`assigned_to` rather than `user_id`, any "list tasks by user" feature must join through `team_members.user_id`, not add a `user_id` column to tasks.

### Known rough edges (see `FEATURE_ANALYSIS_AND_ROADMAP.md`)

That doc tracks a bug/feature audit of this codebase — check it before assuming something is unimplemented or broken; it may already describe the fix needed (e.g. label update/attach/detach routes exist but some had no controller logic at time of writing).
