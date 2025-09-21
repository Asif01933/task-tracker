<?php 
namespace App\Domain\Interfaces;

interface MemberRepositoryInterface
{
    public function create(array $data);
    public function update($user, array $data);
}