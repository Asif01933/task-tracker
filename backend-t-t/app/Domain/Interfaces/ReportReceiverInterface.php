<?php 
namespace App\Domain\Interfaces;

interface ReportReceiverInterface{
    public function create(array $data);
    public function view(array $data);
}