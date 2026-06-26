<?php

namespace App\Infrastructure\Repositories;

use App\Models\MemberDailyTask;
use App\Models\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($task, array $data)
    {
        $task->fill($data);
        $task->save();
        return $task;
    }

    public function delete($task)
    {
        $task->delete();
    }

    public function getTasksByRange($startDate, $endDate, $teamId, $memberId)
    {

        return Task::where('team_id', $teamId)
            ->where('assigned_to', $memberId)
            ->whereBetween('created_at', [$endDate, $startDate])
            ->get();
    }

    public function tasks(array $filters = [])
    {
        $query = Task::query();

        if (array_key_exists('assigned_to', $filters) && $filters['assigned_to'] !== null) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (array_key_exists('team_id', $filters) && $filters['team_id'] !== null) {
            $query->where('team_id', $filters['team_id']);
        }

        if (array_key_exists('priority', $filters) && $filters['priority'] !== null && $filters['priority'] !== '') {
            $query->where('priority', $filters['priority']);
        }

        if (array_key_exists('status', $filters) && $filters['status'] !== null && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (array_key_exists('category', $filters) && $filters['category'] !== null && $filters['category'] !== '') {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->latest()->paginate(10);
    }

    public function listByTeam($teamId)
    {
        return Task::where('team_id', $teamId)->get();
    }

    public function listByMember($memberId)
    {
        return Task::where('assigned_to', $memberId)->get();
    }

    public function findInTeam($taskId, $teamId)
    {
        return Task::query()
            ->whereKey($taskId)
            ->where('team_id', $teamId)
            ->first();
    }

    public function memberDailyTaskExistsForMemberTaskAndDate($teamMemberId, $taskId, $planDate, $exceptId = null)
    {
        $query = MemberDailyTask::query()
            ->where('team_member_id', $teamMemberId)
            ->where('task_id', $taskId)
            ->whereDate('plan_date', $planDate);

        if ($exceptId !== null) {
            $query->whereKeyNot($exceptId);
        }

        return $query->exists();
    }

    public function createMemberDailyTask(array $data)
    {
        $memberDailyTask = MemberDailyTask::create($data);

        $memberDailyTask->load($this->memberDailyTaskRelations());

        return $memberDailyTask;
    }

    public function listMemberDailyTasks($teamMemberId, array $filters = [])
    {
        $query = MemberDailyTask::query()
            ->where('team_member_id', $teamMemberId)
            ->with($this->memberDailyTaskRelations());

        if (! empty($filters['plan_date'])) {
            $query->whereDate('plan_date', $filters['plan_date']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query
            ->orderBy('plan_date')
            ->latest()
            ->get();
    }

    public function updateMemberDailyTask($memberDailyTask, array $data)
    {
        $memberDailyTask->fill($data);
        $memberDailyTask->save();

        return $memberDailyTask->load($this->memberDailyTaskRelations());
    }

    public function deleteMemberDailyTask($memberDailyTask)
    {
        return $memberDailyTask->delete();
    }

    public function completeMemberDailyTask($memberDailyTask)
    {
        $memberDailyTask->fill([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        $memberDailyTask->save();

        return $memberDailyTask->load($this->memberDailyTaskRelations());
    }

    private function memberDailyTaskRelations(): array
    {
        return [
            'task' => fn ($q) => $q->select('id', 'team_id', 'title', 'status', 'category', 'priority'),
        ];
    }
}
