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

}