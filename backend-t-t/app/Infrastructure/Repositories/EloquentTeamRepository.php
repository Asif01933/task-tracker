<?php 

namespace App\Infrastructure\Repositories;

use App\Models\Team;
use App\Domain\Interfaces\TeamRepositoryInterface;

class EloquentTeamRepository implements TeamRepositoryInterface{
    public function create(array $data)
    {
        return Team::create($data);
    }

    
}