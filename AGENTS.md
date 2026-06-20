# AI Agent Instructions for Task Tracker

## Purpose
This file helps AI coding agents quickly understand the repository and make useful changes without misinterpreting architecture or conventions.

## Workspace overview
- `backend-t-t/`: Laravel backend application
  - PHP 8.2+, Laravel 12
  - Uses Sanctum authentication and Spatie team-scoped permissions
  - Layered architecture: `app/Http/Controllers`, `app/Application/Services`, `app/Infrastructure/Repositories`, `app/Domain/Interfaces`
  - Team-scoped RBAC uses `TeamContextMiddleware` and `EnsureTeamRole` middleware
  - Livewire components are used for web routes in `app/Http/Livewire`
- `frontend-t-t/`: Vue 3 + Vite frontend
  - Vue 3 Single File Components, Vue Router, Pinia, Tailwind CSS
  - Node engines: `^20.19.0 || >=22.12.0`

## Build and test commands
- Backend:
  - `composer install`
  - `php artisan test`
  - `vendor/bin/phpunit`
  - `php artisan pint`
  - `php artisan migrate`
  - `php artisan db:seed`
  - `docker-compose up -d` for local services
- Frontend:
  - `npm install`
  - `npm run dev`
  - `npm run build`
  - `npm run lint`
  - `npm run format`

## Key conventions
- Backend `Team` routes are team-scoped. Always use `team.context` middleware on routes with `{team}` and keep role checks scoped to that team.
- Backend task authorization and filtering should use `team_member_id`/`team_members.user_id`, not `user_id` on `tasks`.
- The codebase uses repository/service separation for business logic; prefer updating `Services` and `Repositories` rather than placing logic directly in controllers.
- Route definitions may include API routes with controller actions and Livewire-based web routes. Confirm the route type before modifying request handling.
- Label-related routes exist for update/delete/attach/detach, but controllers/services may be incomplete. Check implementation before assuming label functionality is present.

## Important files and docs
- `backend-t-t/FEATURE_ANALYSIS_AND_ROADMAP.md` — current bug list, feature status, and roadmap guidance
- `backend-t-t/docs/TEAM_RBAC.md` — team-based RBAC design and middleware usage
- `backend-t-t/TaskTracker.postman_collection.json` — API endpoint reference
- `backend-t-t/routes/api.php` — primary API route definitions
- `frontend-t-t/src/` — main frontend application files

## When modifying backend behavior
- Preserve Laravel and Spatie permission semantics.
- Prefer using request validation classes from `app/Http/Requests`.
- Keep API responses consistent with existing JSON response patterns.
- When adding new team-scoped routes, ensure the correct middleware ordering: `auth:sanctum`, `team.context`, `team.role:...`.

## When modifying frontend behavior
- Keep Pinia store usage consistent with existing store structure.
- Use Vue Router route names and module-based route definitions if the project already follows that pattern.
- Apply Tailwind utility classes consistent with the existing styling approach.

## Notes for AI agents
- Do not assume the backend task/user relationship is `tasks.user_id`; inspect models and migrations first.
- If a route exists in `routes/api.php`, verify the controller/service implementation before editing.
- When making changes that cross backend/frontend boundaries, update API expectations in both the backend route/controller and the frontend caller.

## Suggested next customizations
- Add a focused backend instruction file under `backend-t-t/.github/copilot-instructions.md` for Laravel-specific patterns and RBAC details.
- Add a frontend instruction file under `frontend-t-t/.github/copilot-instructions.md` for Vue/Pinia/Tailwind conventions.
