<?php

namespace App\Console\Commands;

use App\Application\Services\Reports\ReportService;
use App\Domain\Interfaces\ReportRepositoryInterface;
use App\Infrastructure\Mail\SendReminderMail;
use Illuminate\Console\Command;
use App\Models\Team;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SendReportReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled team reports automatically';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private ReportRepositoryInterface $reportRepositoryInterface,
        private ReportService $reportService
    ) {
        // ✅ Always call parent constructor
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $teams = Team::whereNotNull('report_frequency')
            ->whereNotNull('last_email_sent')
            ->get();

        foreach ($teams as $team) {
            $daysSinceLast = Carbon::parse($team->last_email_sent)->diffInDays($today);

            if ($daysSinceLast == $team->report_frequency) {
                $members = $team->members()
                    ->where('status', 'active')
                    ->get();

                if ($members->isEmpty()) {
                    $this->warn("No active members for team {$team->name}");
                    continue;
                }

                $startDate = Carbon::parse($team->last_email_sent);
                $endDate = $today;

                foreach ($members as $member) {
                    // Save new report record
                    $data = [
                        'team_id' => $team->id,
                        'team_member_id' => $member->id,
                        'due_date' => $today,
                        'report_frequency' => $team->report_frequency,
                    ];
                    $this->reportRepositoryInterface->create($data);

                    // Generate report data
                    $reports = $this->reportService->generateReport(
                        $startDate,
                        $endDate,
                        $team->id,
                        $member->id
                    );

                    // Generate PDF
                    $pdfData = [
                        'team' => $team,
                        'reports' => $reports,
                        'startDate' => $startDate->format('d M Y'),
                        'endDate' => $endDate->format('d M Y'),
                    ];

                    $pdf = Pdf::loadView('emails.reports.report-pdf', $pdfData);
                    $filePath = "reports/{$team->id}_report_" . now()->format('Ymd') . ".pdf";
                    Storage::disk('public')->put($filePath, $pdf->output());

                    // Send email
                    Mail::to($member->email)->send(
                        new SendReminderMail($team, $reports, $startDate, $endDate, $pdf)
                    );

                    $this->info("Report email sent to {$member->email} for team {$team->name}");
                }

                // Update last_email_sent
                $team->update(['last_email_sent' => $today]);
            }
        }

        $this->info('Reports sent successfully!');
        return Command::SUCCESS;
    }
}
