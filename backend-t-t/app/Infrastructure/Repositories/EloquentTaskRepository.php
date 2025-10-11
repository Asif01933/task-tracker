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
            ->where('member_id', $memberId)
            ->whereBetween('created_at', [$endDate, $startDate])
            ->get();
    }

}