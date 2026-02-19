<?php 

namespace App\Infrastructure\Repositories;

use App\Models\Task;
use App\Models\User;
use App\Models\TeamMember;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;

class EloquentTaskRepository implements TaskRepositoryInterface{
    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($task, array $data){
        $task->fill($data);
        $task->save();
        return $task;
    }

    public function delete($task){
        $task->delete();
    }

    public function getTasksByRange($startDate, $endDate, $teamId, $memberId){
        
        return Task::where('team_id', $teamId)
            ->where('team_member_id', $memberId)
            ->whereBetween('created_at', [$endDate, $startDate])
            ->get();
    }

    public function tasks($userId, $endDate, $startDate){
        return Task::query()
        ->when($userId, function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('created_at', '<=', $endDate);
        })
        ->latest()
        ->paginate(10);
    }

    public function listByTeam($teamId){
        return Task::where('team_id', $teamId)->get();
    }

    public function listByMember($memberId){
        return Task::where('team_member_id', $memberId)->get();
    }

}