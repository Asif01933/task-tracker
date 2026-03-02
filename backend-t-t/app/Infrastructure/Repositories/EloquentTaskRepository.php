<?php

namespace App\Infrastructure\Repositories;

use App\Models\Task;
use App\Models\User;
use App\Models\TeamMember;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;

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
            ->where('team_member_id', $memberId)
            ->whereBetween('created_at', [$endDate, $startDate])
            ->get();
    }

    public function tasks(array $filters = [])
    {
        $query = Task::query();

        if (array_key_exists('user_id', $filters) && $filters['user_id'] !== null) {
            $query->where('user_id', $filters['user_id']);
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
        return Task::where('team_member_id', $memberId)->get();
    }
}
