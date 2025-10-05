<?php 

namespace App\Application\Services\Reports;

use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;

class ReportService{

    public function __construct(private TeamRepositoryInterface $teamRepositoryInterface,
    private TaskRepositoryInterface $taskRepositoryInterface){}
    public function send($report){
        if($report->is_sent){
            throw new \Exception("Your report has already been sent");
            
        }

        //extracting last 'n' days report
        $team = $this->teamRepositoryInterface->findById($report->team_id);
        $frequency = $team->report_frequency;

        $startDate = $report->due_date;
        $endDate = \Carbon\Carbon::parse($report->due_date)->subDays($frequency);

        $rawReports = $this->taskRepositoryInterface->getTasksByRange($startDate, $endDate, $team->id, $report->memberId);
        

    }
}