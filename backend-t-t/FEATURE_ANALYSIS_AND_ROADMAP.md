# Task Tracker – Feature Analysis & Roadmap

Analysis of the backend codebase and a prioritized list of features to make it a full-fledged task tracker.

---

## Part 1: What You Already Have

### Authentication
| Feature | Status | Notes |
|--------|--------|--------|
| Register (email/password) | Done | Returns Sanctum token |
| Login (email/password) | Done | Returns Sanctum token |
| Google OAuth login | Done | |
| Logout | Done | Revokes current token |

### Profile
| Feature | Status | Notes |
|--------|--------|--------|
| Get current user profile | Done | `GET /me` |
| Update profile | Done | `PATCH /me` |

### Teams
| Feature | Status | Notes |
|--------|--------|--------|
| Create team | Done | Owner becomes first member |
| List my teams | Done | |
| View single team | Done | |
| Update team | Done | |
| Delete team | Done | |

### Team invitations
| Feature | Status | Notes |
|--------|--------|--------|
| Invite by email | Done | Sends email with token |
| Accept invitation | Done | Token + email → creates TeamMember |

### Tasks
| Feature | Status | Notes |
|--------|--------|--------|
| Create task | Done | Scoped to team + current member (assignee) |
| Update task | Done | |
| Delete task | Done | |
| List tasks by team | Done | `GET /tasks/team/{team}` |
| List my tasks | Done | `GET /tasks/self` |
| List/filter tasks | Partial | `GET /tasks` with user_id, start_date, end_date (user_id filter may be wrong – Task has no user_id) |

### Labels (team-scoped)
| Feature | Status | Notes |
|--------|--------|--------|
| Create label | Done | |
| List team labels | Done | |
| Update label | **Route only** | No controller/service/repo |
| Delete label | **Route only** | No controller/service/repo |
| Attach label to task | **Route only** | No implementation |
| Detach label from task | **Route only** | No implementation |

### Reports
| Feature | Status | Notes |
|--------|--------|--------|
| Download team report (PDF) | Done | |
| Send report by email | Done | Uses report receivers |
| Add report receiver | Done | |
| List report receivers (by team) | Done | |
| Update report receiver | Done | |

---

## Part 2: Bugs to Fix First

1. **ReportController** – Wrong `Report` import  
   - Currently: `use SebastianBergmann\CodeCoverage\Report\Xml\Report;`  
   - Should be: `use App\Models\Report;`  
   - Otherwise `send(Report $report)` will fail or behave incorrectly.

2. **ReportService** – Typo  
   - Use `$report->team_member_id` (not `team_members_id`).

3. **TeamMember model** – Wrong relationship  
   - `tasks()` is `belongsTo(Task::class)` (one task per member).  
   - Should be `hasMany(Task::class)` (a member has many tasks).

4. **Label routes** – Either implement or remove  
   - Routes exist for: label update, label destroy, attach label to task, detach label from task.  
   - None of these are implemented in LabelController/LabelService. Implement them or remove the routes to avoid 404/500.

5. **Task list by user** – Logic  
   - Task has `team_member_id`, not `user_id`. Ensure task listing/filtering by “user” goes through `team_members.user_id` (e.g. in EloquentTaskRepository).

---

## Part 3: Features to Add for a Full-Fledged Task Tracker

### Tier 1 – Core completeness (do first)

| # | Feature | Why |
|---|--------|-----|
| 1 | **Label update** | Already have route; needed to rename/change color. |
| 2 | **Label delete** | Already have route; needed to remove labels. |
| 3 | **Attach label to task** | POST body: `label_id`; sync pivot. |
| 4 | **Detach label from task** | Delete pivot row. |
| 5 | **Get single task** | `GET /tasks/{task}` – view one task (with labels, assignee). |
| 6 | **Task filters** | Filter list by `status`, `category`, `priority`, `label_id`, `team_member_id` (assignee); optional date range. |
| 7 | **Pagination** | Paginate task list, team list, labels, report receivers (e.g. `?page=1&per_page=15`). |

### Tier 2 – Team & members

| # | Feature | Why |
|---|--------|-----|
| 8 | **List team members** | `GET /teams/{team}/members` – who is in the team, with role. |
| 9 | **Update member role** | e.g. admin / member (if you have roles). |
| 10 | **Remove member** | Admin removes a member from team. |
| 11 | **Leave team** | Current user leaves (and optionally transfer ownership). |
| 12 | **List pending invitations** | Per team: `GET /teams/{team}/invitations`; optionally “my pending invites”. |
| 13 | **Revoke invitation** | Cancel pending invite. |
| 14 | **Resend invitation** | Send email again with same or new token. |

### Tier 3 – Tasks & workflow

| # | Feature | Why |
|---|--------|-----|
| 15 | **Due date on tasks** | Add `due_date` (and optionally `start_date`); filter/sort by due date. |
| 16 | **Task ordering / sort** | e.g. drag-and-drop order or explicit `position`; or sort by due_date, priority, created_at. |
| 17 | **Reassign task** | Change `team_member_id` (assignee). |
| 18 | **Task status workflow** | Fixed statuses (e.g. todo → in_progress → done) and optional “archive”. |
| 19 | **Bulk update tasks** | e.g. update status or assignee for multiple task IDs. |

### Tier 4 – Collaboration & visibility

| # | Feature | Why |
|---|--------|-----|
| 20 | **Task comments** | New model `task_comments` (task_id, team_member_id, body, timestamps); CRUD API. |
| 21 | **Activity / audit log** | Optional: log “created”, “status changed”, “assigned to X”, “comment added” for tasks (and optionally teams). |
| 22 | **Optional: subtasks** | Table `subtasks` (task_id, title, completed_at); or keep it simple and skip. |

### Tier 5 – Reports & notifications

| # | Feature | Why |
|---|--------|-----|
| 23 | **Create/schedule report** | Explicit API to create a “report” record (e.g. team_id, frequency, next_run); then cron uses it. |
| 24 | **List reports** | e.g. `GET /teams/{team}/reports` – scheduled and sent reports. |
| 25 | **Email notifications** | e.g. “Task assigned to you”, “Due soon”, “Comment on your task” (via Mailhog in dev). |

### Tier 6 – Polish & API quality

| # | Feature | Why |
|---|--------|-----|
| 26 | **Consistent error format** | Same JSON shape for validation errors and 403/404/500. |
| 27 | **API versioning** | e.g. `/api/v1/...` for future compatibility. |
| 28 | **OpenAPI/Swagger** | Document endpoints for frontend or external use. |
| 29 | **Rate limiting** | Throttle auth and heavy endpoints. |

---

## Part 4: Summary Counts

| Category | Implemented | To implement (suggested) |
|----------|-------------|---------------------------|
| Auth | 4 | 0 (optional: password reset, email verify) |
| Profile | 2 | 0 |
| Teams | 6 | 0 |
| Invitations | 2 | 3 (list, revoke, resend) |
| Tasks | 6 | 6 (single get, filters, due date, sort, reassign, bulk) |
| Labels | 2 | 4 (update, delete, attach, detach) |
| Members | 0 | 4 (list, update role, remove, leave) |
| Reports | 5 | 2 (schedule, list) |
| Comments | 0 | 1 |
| Activity | 0 | 1 (optional) |
| Polish | 0 | 4 (errors, versioning, docs, rate limit) |

**Rough total:** ~27 features already in place, **~28 suggested** to reach a full-fledged task tracker (you can subset by tier).

---

## Part 5: Suggested order of work

1. **Fix bugs** (Report import, ReportService typo, TeamMember relationship, task-by-user logic).  
2. **Tier 1** – Finish labels (update, delete, attach, detach), single task GET, task filters, pagination.  
3. **Tier 2** – Team members (list, roles, remove, leave) and invitations (list, revoke, resend).  
4. **Tier 3** – Due dates, reassign, status workflow, bulk update.  
5. **Tier 4–6** – Comments, activity, report scheduling, notifications, then API polish.

If you tell me which tier or feature you want next, I can outline exact routes, request DTOs, and service/repository changes step by step.
