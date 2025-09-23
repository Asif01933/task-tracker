<?php 
namespace App\Domain\Interfaces;


interface TaskRepositoryInterface{
    public function create(array $data);
    
}