<?php 
namespace App\Domain\Interfaces;

interface ReportRepositoryInterface{
    public function create();
    public function update($report);
}