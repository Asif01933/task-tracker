<?php 
namespace App\Domain\Interfaces;

use App\Models\Team;

interface TeamRepositoryInterface{
    public function create(array $data);
    
    public function update(array $data, $team);

    public function findById($id);
    
}