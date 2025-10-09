<?php 
namespace App\Domain\Interfaces;

interface ReportRepositoryInterface{
    public function create(array $data);
    public function update($report);
}