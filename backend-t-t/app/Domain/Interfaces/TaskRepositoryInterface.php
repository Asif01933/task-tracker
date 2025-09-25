<?php 
namespace App\Domain\Interfaces;


interface TaskRepositoryInterface{
    public function create(array $data);
    public function update($task, array $data);
    public function delete($task);
    
}