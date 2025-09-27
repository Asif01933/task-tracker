<?php 
namespace App\Domain\Interfaces;

interface ReportReceiverInterface{
    public function create(array $data);
    public function view(array $data);

    public function update(array $data, $receiver);
}