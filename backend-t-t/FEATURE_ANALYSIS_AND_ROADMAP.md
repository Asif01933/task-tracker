# Task Tracker – Feature Analysis & Roadmap

Analysis of the backend codebase and a prioritized list of features to make it a full-fledged task tracker.

_Last updated: 2026-07-11. Supersedes the previous version of this doc, which predated the daily-task planner and the completed label CRUD/attach/detach work._

---

## Part 1: What You Already Have

### Authentication
| Feature | Status | Notes |
|--------|--------|--------|
| Register (email/password) | Done | Returns Sanctum token |
| Login (email/password) | Done | Returns Sanctum token |
| Google OAuth login | Done | Verifies Google ID token directly via `google/apiclient`; `laravel/socialite` is installed but unused |
| Logout | Done | Revokes current token |

### Profile
| Feature | Status | Notes |
|--------|--------|--------|
| Get current user profile | Done | `GET /me` |
| Update profile | Done | `PATCH /me` |

### Teams
| Feature | Status | Notes |
|--------|--------|--------|
| Create team | Done | Owner becomes first member, Spatie role assigned in team context |
| List my teams | Done | |
| View single team | Done | |
| Update team | Done | owner/admin only |
| Delete team | Done | owner/admin only |
| List team members | Done | `GET /teams/{team}/members` |

### Team invitations
| Feature | Status | Notes |
|--------|--------|--------|
| Invite by email | Done | Sends email with token |
| Accept invitation | Done | Token + email → creates TeamMember + assigns Spatie role. **Gap:** doesn't verify invitation `status === 'pending'` before accepting, and there's no `role` column on the invitation so invited role always falls back to `member`. |

### Team members
| Feature | Status | Notes |
|--------|--------|--------|
| Update member role | Done | owner/admin only, via `MemberController::update` |
| Remove member | Done | owner/admin only, via `MemberController::update`/`remove` |

### Tasks
| Feature | Status | Notes |
|--------|--------|--------|
| Create task | Done | Scoped to team + current member (assignee) |
| Update task | Done | |
| Delete task | Done | |
| Get single task | Done | `GET /teams/{team}/tasks/{task}` |
| List tasks by team | Done | `GET /teams/{team}/tasks` with filters (`assigned_to`, `priority`, `status`, `category`, `start_date`, `end_date`) + pagination (`paginate(10)`, hardcoded page size) |
| List my tasks | Done | `GET /tasks/self` |
| Daily task planner ("plan my day") | Done | `MemberDailyTask`: attach a task to a specific date, `task_note`, mark complete, unique per member/task/date — full CRUD under `/teams/{team}/member-daily-tasks` |

### Labels (team-scoped)
| Feature | Status | Notes |
|--------|--------|--------|
| Create label | Done | |
| List team labels | Done | |
| Update label | Done | Fully implemented (previously listed as route-only; now has controller/service/repo) |
| Delete label | Done | Fully implemented |
| Attach label to task | Done | Fully implemented |
| Detach label from task | Done | Fully implemented |

### Reports
| Feature | Status | Notes |
|--------|--------|--------|
| Download team report (PDF) | Done | No filters, dumps all team tasks |
| Send report by email | Done | Uses report receivers; runs synchronously (not queued) |
| Add report receiver | Done | **Gap:** `email` is globally unique across the whole app, not per-team, so one email can only ever be a receiver for a single team |
| List report receivers (by team) | Done | |
| Update report receiver | Done | |

**Rough total already implemented: ~9 feature areas, ~35 individual endpoints.**

---

## Part 2: Bugs to Fix First

1. **Scheduled report reminder never runs**
   - `app/Console/Kernel.php` schedules the command `reports:send-team-member`.
   - The actual Artisan command signature (in `SendReportReminder.php`) is `reports:send-teams`.
   - Because the names don't match, the daily 11:00 cron silently never fires — no reminder emails are ever sent via the scheduler.

2. **`ReportService::generateReport` references a stale field**
   - Reads `$task->description`.
   - The `tasks` table column was renamed to `problem_description` in a later migration.
   - Result: generated reports (PDF and email) always show a blank description.

3. **`DropdownController::getRoles()` is dead and broken**
   - No route is registered for it in `routes/api.php` — currently unreachable.
   - It also declares/depends on the wrong namespace (`App\Http\Services\Dropdown` vs. the actual `App\Application\Services\Dropdown\DropdownService`) — would fatal-error if a route were ever added.
   - Fix the namespace and either wire up a route or remove the dead code.

4. **Invitations can't carry a target role**
   - `TeamInvitation` has no `role` column in its migration/`$fillable`.
   - `InvitationService::acceptInvitation` reads `$invitation->role ?? 'member'`, which always falls back to `'member'` since the attribute never exists.
   - Add a `role` column (default `member`) and pass it through on invite.

5. **Invitation accept doesn't check invitation status**
   - `acceptInvitation` doesn't verify `status === 'pending'` before processing.
   - An already-accepted or revoked token could be replayed to re-join or re-trigger role assignment.

6. **No safeguard against removing/demoting the last team owner**
   - `MemberController::update`/`remove` are protected only by route-level `team.role:owner,admin` middleware — no check prevents an admin from demoting or removing the sole remaining owner, which would leave a team ownerless.

7. **`ReportReceiver.email` is globally unique instead of per-team**
   - The unique constraint is on `email` alone, not `(team_id, email)`.
   - Blocks the same address from being a report receiver for more than one team.

---

## Part 3: Features to Add for a Full-Fledged Task Tracker

### Tier 1 – Core completeness (do first)

| # | Feature | Why |
|---|--------|-----|
| 1 | **Due dates on tasks** | Add `due_date` (and optionally `start_date`); filter/sort by it. Currently absent entirely — only `raised_at` exists. |
| 2 | **Task comments** | New `task_comments` table (task_id, team_member_id, body, timestamps) + CRUD API. No collaboration primitive exists today. |
| 3 | **Consistent pagination** | Only the task list paginates today. Add it to teams list, members list, labels list, report receivers, self-tasks. |
| 4 | **Rate limiting** | No `throttle` middleware anywhere, including on auth endpoints. |
| 5 | **Fix bugs #1 and #2** | Both are shipping broken behavior silently right now. |

### Tier 2 – Team & invitation completeness

| # | Feature | Why |
|---|--------|-----|
| 6 | **List pending invitations** | Per team: `GET /teams/{team}/invitations`. |
| 7 | **Revoke invitation** | Cancel a pending invite. |
| 8 | **Resend invitation** | Send email again with same/new token. |
| 9 | **Leave team** | Current user leaves (with ownership-transfer handling if they're the owner). |
| 10 | **Guard the last owner** | Fixes bug #6 — block demote/remove when it would leave a team without an owner. |
| 11 | **Invitation role support** | Fixes bug #4 — add `role` column, let invites specify a non-default role. |

### Tier 3 – Tasks & workflow

| # | Feature | Why |
|---|--------|-----|
| 12 | **Bulk update tasks** | e.g. update status or assignee for multiple task IDs. |
| 13 | **Enforced status/priority enums** | Currently near-free-text; `TaskCreateRequest` only requires `title`/`category`. |
| 14 | **Task attachments** | No file upload endpoint or storage-backed field exists on tasks today. |

### Tier 4 – Collaboration & visibility

| # | Feature | Why |
|---|--------|-----|
| 15 | **Activity / audit log** | Log "created", "status changed", "reassigned", "comment added" for tasks. No such table/package exists yet. |
| 16 | **Task-event notifications** | "Assigned to you", "due soon", "comment added". Mail infra exists (`Infrastructure/Mail/*`) but nothing dispatches on task events, and none of the mailables implement `ShouldQueue` — everything currently sends synchronously in-request. |
| 17 | **Optional: real-time updates** | Broadcasting/websockets — genuinely optional, skip unless there's a concrete need. |

### Tier 5 – Reports & notifications

| # | Feature | Why |
|---|--------|-----|
| 18 | **Queue report/PDF generation** | Queue config exists (`config/queue.php`, `database` driver) but nothing dispatches a `ShouldQueue` job — everything runs synchronously today. |
| 19 | **Filterable reports** | Currently dumps all team tasks with no date/status filter. |
| 20 | **List reports endpoint** | `GET /teams/{team}/reports` — scheduled and sent reports. |

### Tier 6 – Polish & production readiness

| # | Feature | Why |
|---|--------|-----|
| 21 | **Consistent error format** | Same JSON shape for validation errors and 403/404/500. |
| 22 | **API versioning** | e.g. `/api/v1/...` for future compatibility. |
| 23 | **OpenAPI/Swagger** | Document endpoints for frontend or external use. |
| 24 | **Real automated test suite** | Currently only Laravel's default stub tests exist (`tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`) — zero coverage of controllers, services, repositories, policies, or middleware, despite `phpunit`/`mockery` being installed. |
| 25 | **CORS config review** | `config/cors.php` doesn't exist; currently relying on framework defaults. |
| 26 | **Basic search** | e.g. task title/description text search — absent. |

---

## Part 4: Summary Counts

| Category | Implemented | To implement (suggested) |
|----------|-------------|---------------------------|
| Auth | 4 | 0 (optional: password reset, email verify) |
| Profile | 2 | 0 |
| Teams | 6 | 0 |
| Invitations | 2 | 3 (list, revoke, resend) + role support |
| Team members | 2 | 2 (leave team, last-owner guard) |
| Tasks | 7 | 3 (due date, bulk update, enum validation) |
| Daily task planner | 5 | 0 |
| Labels | 6 | 0 |
| Comments | 0 | 1 |
| Attachments | 0 | 1 |
| Activity/notifications | 0 | 2 (audit log, task-event notifications) |
| Reports | 5 | 3 (queue, filters, list) |
| Polish | 0 | 6 (errors, versioning, docs, tests, CORS, search) |
| **Bugs** | — | 7 |

**Rough total:** ~39 endpoints/features already in place, **~21 features + 7 bug fixes** to reach a genuinely full-fledged task tracker.

---

## Part 5: Suggested order of work

1. **Fix the 2 silent-failure bugs** (reminder cron name mismatch, stale `description` field) — both are currently shipping broken behavior with no visible error.
2. **Tier 1** – Due dates, comments, consistent pagination, rate limiting.
3. **Tier 2** – Invitation completeness (list/revoke/resend/role) and the last-owner removal guard (closes a real security/data-integrity gap).
4. **Tier 3–4** – Bulk task ops, enum validation, attachments, activity log, notifications.
5. **Tier 5–6** – Report polish, then API/production hardening — but start the real test suite much earlier than "last," since coverage is currently zero.

If you tell me which tier or feature you want next, exact routes, request DTOs, and service/repository changes can be scoped out step by step.
