<?php

namespace App\Console\Commands;

use App\Application\Services\Reports\ReportService;
use App\Domain\Interfaces\ReportRepositoryInterface;
use App\Infrastructure\Mail\SendReminderMail;
use Illuminate\Console\Command;
use App\Models\Team;
use App\Mail\TeamReportMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


class SendReportReminder extends Command
{
    public function __construct(private ReportRepositoryInterface $reportRepositoryInterface
    , private ReportService $reportService){}
    protected $signature = 'reports:send-teams';
    protected $description = 'Send scheduled team reports automatically';

    public function handle()
    {
        $today = Carbon::today();

        $teams = Team::whereNotNull('report_frequency')
            ->whereNotNull('last_email_sent')
            ->get();

        foreach ($teams as $team) {
            $daysSinceLast = Carbon::parse($team->last_email_sent)->diffInDays($today);

            if ($daysSinceLast == $team->report_frequency) {
                $members = $team->members()->where('status', 'active')->pluck('email')->toArray();

                if (empty($members)) {
                    $this->warn("No active members for team {$team->name}");
                    continue;
                }
                $startDate = Carbon::parse($team->last_email_sent);
                $endDate = $today;
                foreach($members as $member){
                    $data = [
                        'team_id' => $team->id,
                        'member_id' => $member->id,
                        'due_date'=> $today,
                        'report_frequency' => $team->report_frequency
                    ];
                    $this->reportRepositoryInterface->create($data);

                    $reports = $this->reportService->generateReport($startDate, $endDate, $team->id, $member->id);
                    // Generate PDF
                    $pdf = Pdf::loadView('emails.reports.report-pdf', $data);
                    $filePath = "reports/{$team->id}_report_" . now()->format('Ymd') . ".pdf";
                    Storage::disk('public')->put($filePath, $pdf->output());
                    
                    Mail::to($member->user)->send(new SendReminderMail($team, $reports, $startDate, $endDate, $pdf));

                    $team->update(['last_email_sent' => $today]);

                    $this->info("Report email sent to team {$team->name}");
                }

            }
        }

        $this->info('Reports sent successfully!');
        return Command::SUCCESS;
    }

    
}
