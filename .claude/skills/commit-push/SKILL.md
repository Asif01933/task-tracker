---
name: commit-push
description: Stage and commit the current changes with a well-formed message, then push to the remote automatically without asking for confirmation. Use when the user says "commit and push", "commit this", or "/commit-push".
---

# Commit and push

Follow these steps in order.

## 1. Gather context

Run in parallel:
- `git status` (never `-uall`)
- `git diff` (unstaged) and `git diff --staged` (already staged)
- `git log --oneline -10` to match this repo's commit message style
- `git branch --show-current` and check whether it tracks a remote (`git rev-parse --abbrev-ref --symbolic-full-name @{u}` — ignore failure, it just means no upstream yet)

## 2. Stage changes

Add files by name based on `git status` output. Never use `git add -A` or `git add .`. Skip anything that looks like a secret (`.env`, credentials, keys) and warn the user if such a file appears modified.

## 3. Draft the commit message

1-2 sentences focused on *why*, not a restatement of the diff. Match the repo's existing style (imperative mood, no trailing period is fine if that's the norm — check the log output from step 1).

## 4. Commit

Use a heredoc so formatting is preserved:

```bash
git commit -m "$(cat <<'EOF'
<summary line>

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```

If a pre-commit hook fails, fix the underlying issue, re-stage, and create a **new** commit — never `--amend`, never `--no-verify`.

Run `git status` after committing to confirm success.

## 5. Push automatically

Push immediately after committing, without asking for confirmation.

- If the branch has no upstream, run `git push -u origin <branch>`.
- If it has an upstream, run `git push`.
- Never force-push (`--force`/`--force-with-lease`) — that always requires explicit user instruction in the same request, regardless of this skill's auto-push default.

After pushing, report what was pushed (branch name, commit(s)).
