<?php 
namespace App\Http\Controllers\Reports;

use App\Application\Services\Reports\ReportService;
use App\Models\Team;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller{

    public function __construct(private ReportService $reportService){}
    public function download(Team $team){
        
        $tasks = $team->tasks;

        $pdf = Pdf::loadView('reports.task_report', compact('team', 'tasks'));

        // Force download
        return $pdf->download("task-report-{$team->id}.pdf");
    }

    public function send(Report $report){
        
        return response()->json($this->reportService->send($report));
    }
}
