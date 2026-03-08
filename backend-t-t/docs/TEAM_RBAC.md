# Team-Based RBAC (Spatie Laravel Permission)

This document describes the team-based role-based access control (RBAC) implementation using **Spatie Laravel Permission** with teams enabled.

---

## 1. Installation Steps

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

**Before running migrations:** enable teams in `config/permission.php` (see Configuration below).

```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

---

## 2. Configuration Changes

**File: `config/permission.php`**

- Set `'teams' => true` so that roles are scoped per team.
- `'team_foreign_key' => 'team_id'` (default) stores the team id in `model_has_roles` and `model_has_permissions`; this matches the application’s `teams.id`.

No other config changes are required for basic team-scoped roles.

---

## 3. Middleware Implementation

### TeamContextMiddleware

**File: `app/Http/Middleware/TeamContextMiddleware.php`**

- Reads the `team` route parameter (e.g. from `/teams/{team}/tasks`).
- Resolves it to a team id (supports route model binding or raw id).
- Calls `app(PermissionRegistrar::class)->setPermissionsTeamId($teamId)` so that all subsequent role/permission checks are scoped to that team.

**Registration:** `bootstrap/app.php` → `withMiddleware()` → `alias(['team.context' => TeamContextMiddleware::class])`.

Use `team.context` on any route that has `{team}` in the path so that `hasRole()` and `hasPermissionTo()` apply to the current team.

### EnsureTeamRole Middleware

**File: `app/Http/Middleware/EnsureTeamRole.php`**

- Must run **after** `team.context`.
- Accepts role names as arguments: `team.role:owner,admin`.
- Returns 403 if the authenticated user does not have any of the given roles in the current team.

**Registration:** alias `team.role` in `bootstrap/app.php`.

---

## 4. Role Seeder

**File: `database/seeders/RoleSeeder.php`**

Creates the default roles (with guard `web`):

- **owner**
- **admin**
- **member**
- **viewer**

Roles are created without a `team_id` in the `roles` table (global role definitions). Assignments are stored in `model_has_roles` with `team_id`, so the same role name is reused across teams.

Run once:

```bash
php artisan db:seed --class=RoleSeeder
```

Or include in `DatabaseSeeder` and run `php artisan db:seed`.

---

## 5. Example Controller Usage

**Team-scoped routes** use `team.context` so that the “current team” is set for the request.

**Inline role check (after team context is set):**

```php
// In a controller action for a route like GET /teams/{team}/members
public function members(Team $team): \Illuminate\Http\JsonResponse
{
    // Role check is scoped to $team because TeamContextMiddleware ran
    if (! $request->user()->hasRole('admin')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }
    // ...
}
```

**Using middleware (recommended):**

```php
// In routes/api.php
Route::patch('/teams/{team}', [TeamController::class, 'update'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');
```

Then the controller does not need to perform the role check; the middleware returns 403 when the user does not have one of the given roles in that team.

---

## 6. Example Routes

Relevant snippets from `routes/api.php`:

```php
// Team context set for these routes (role checks scoped to that team)
Route::get('/teams/{team}', [TeamController::class, 'view'])
    ->middleware('auth:sanctum', 'team.context');

Route::patch('/teams/{team}', [TeamController::class, 'update'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');

Route::get('/teams/{team}/members', [TeamController::class, 'members'])
    ->middleware('auth:sanctum', 'team.context');

Route::get('/teams/{team}/tasks/{task}', [TaskController::class, 'view'])
    ->middleware('auth:sanctum', 'team.context');

Route::prefix('teams/{team}/labels')->middleware('auth:sanctum', 'team.context')->...
```

Use `team.context` on every route that has a `{team}` parameter so that `$user->hasRole('admin')` and similar checks apply to the correct team.

---

## 7. How Team-Based RBAC Works Internally

1. **Roles table**  
   Holds role definitions (e.g. `name = 'admin'`, `guard_name = 'web'`). With teams enabled, `roles.team_id` can be used; in this app, roles are global (`team_id` null) and reused for all teams.

2. **Assignments**  
   When you want to assign a role within a specific team:
   - Spatie finds (or creates) the role by name and guard.
   - It inserts a row in `model_has_roles` with `role_id`, `model_type`, `model_id` (user id), and `team_id` = `$team->id`.

   **Important:** `assignRole()` does not take a `Team` model as a second parameter; if you pass `$team` as an extra argument it will be treated as another “role” and can trigger a type error.

   Use team context instead:

   ```php
   $previousTeamId = getPermissionsTeamId();
   setPermissionsTeamId($team->id);
   $user->assignRole('admin');
   setPermissionsTeamId($previousTeamId);
   ```

3. **Team context**  
   `PermissionRegistrar::setPermissionsTeamId($teamId)` sets the “current team” for the request. When you then call `$user->hasRole('admin')`:
   - Spatie looks up roles for that user **where `model_has_roles.team_id` = the current team id**.
   - So the same user can be “admin” in one team and “member” in another.

4. **When roles are assigned in this app**
   - **Team creation:** The creator is added as a `TeamMember` with role `owner` and `TeamService::create` assigns the Spatie role within the new team context.
   - **Invitation accept:** When a user accepts an invite, a `TeamMember` is created and `InvitationService::acceptInvitation` assigns the Spatie role within the invitation’s team context (default role `member` if not specified on the invitation).

5. **`team_members.role`**  
   The existing `team_members` table still stores a role string for app-specific use (e.g. display). Spatie’s `model_has_roles` is the source of truth for authorization; keep `team_members.role` in sync when assigning or changing roles (as done in team create and invite accept).

---

## 8. Summary

- **Install:** Spatie package, publish config/migrations, set `teams => true`, migrate, seed roles.
- **Config:** `config/permission.php` → `teams = true`, `team_foreign_key = team_id`.
- **Middleware:** `team.context` sets the active team from the route; `team.role:role1,role2` enforces roles in that team.
- **Roles:** Seeder creates `owner`, `admin`, `member`, `viewer` (guard `web`).
- **Usage:** Use `team.context` on all routes with `{team}`; use `$user->hasRole('role')` in controllers or `team.role` middleware for protection.
