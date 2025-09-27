<?php 
namespace App\Infrastructure\Repositories;

use App\Models\ReportReceiver;
use App\Domain\Interfaces\ReportReceiverInterface;

class EloquentReportReceiverRepository implements ReportReceiverInterface{
    public function create(array $data){
        return ReportReceiver::create($data);
    }
    public function view($team){
        return $team->receivers;
    }

    public function update($data, $receiver){
        return $receiver->fill($data);
    }
}