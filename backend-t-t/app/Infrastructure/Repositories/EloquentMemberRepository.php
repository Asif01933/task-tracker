<?php 

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Models\User;
class EloquentMemberRepository implements MemberRepositoryInterface{
    public function create(array $data)
    {
        return User::create($data);
    }
}