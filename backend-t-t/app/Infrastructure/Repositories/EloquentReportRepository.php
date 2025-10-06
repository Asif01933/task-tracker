<?php 
namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\ReportRepositoryInterface;

class EloquentReportRepository implements ReportRepositoryInterface{

    public function create(){}
    public function update($report){
        $report->is_sent = true;
        $report->sent_at = now();
        $report->save();
    }
}