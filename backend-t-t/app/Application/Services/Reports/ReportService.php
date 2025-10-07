<?php 

namespace App\Application\Services\Reports;

use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\ReportReceiverInterface;
use Illuminate\Support\Facades\Mail;
use App\Infrastructure\Mail\SendReportMail;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Domain\Interfaces\ReportRepositoryInterface;

class ReportService{

    public function __construct(private TeamRepositoryInterface $teamRepositoryInterface,
        private TaskRepositoryInterface $taskRepositoryInterface,
        private ReportRepositoryInterface $reportRepositoryInterface,
        private ReportReceiverInterface $reportReceiverInterface,
        private MemberRepositoryInterface $memberRepositoryInterface
    ){}
    public function send($report){

        if($report->is_sent){
            throw new \Exception("Your report has already been sent");
            
        }

        //extracting last 'n' days report
        $team = $this->teamRepositoryInterface->findById($report->team_id);
        $member = $this->memberRepositoryInterface->findTeamMember($report->team_id, $report->member_id);
        

        $startDate = $report->due_date;
        $endDate = \Carbon\Carbon::parse($report->due_date)->subDays($report->report_frequency);

        $reports = $this->generateReport($startDate, $endDate, $team->id, $member->id);

        $reportReceivers = $this->reportReceiverInterface->view($team);

        $emails = [];
        if($member->want_report){
            $emails[] = $member->user->email;
        }
        foreach($reportReceivers as $receiver){
            $emails[] = $receiver->email;
        }

        foreach ($emails as $email) {
            Mail::to($email)->send(new SendReportMail($reports['categoryWise'], $reports['dateWise'], $startDate, $endDate));
        }

        
        $this->reportRepositoryInterface->update($report);
        

        return [
            'status' => true,
            'code' => 200,
            'message' => 'mail sent successfully'
        ];


    }

    public function generateReport($startDate, $endDate, $teamId, $memberId){

        $rawReports = $this->taskRepositoryInterface->getTasksByRange($startDate, $endDate, $teamId, $memberId);
        
        $dateWiseReport = [];

        foreach($rawReports as $report){
            $dateWiseReport[$report->created_at][] = [
                'date' => $report->created_at,
                'task' => $report->title,
                'description' => $report->description,
                'status' => $report->status
            ];
        }

        $categoryWiseReport = [];

        foreach($rawReports as $report){
            $categoryWiseReport[$report->category][] = [
                'task' => $report->title,
                'status' => $report->status
            ];
        }
        return [
            'dateWise' => $dateWiseReport,
            'categoryWise' => $categoryWiseReport
        ];
    }
}