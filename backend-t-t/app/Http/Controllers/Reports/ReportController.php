<?php 
namespace App\Http\Controllers\Reports;

use App\Models\Team;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller{

    public function download(Team $team){
        
        $tasks = $team->tasks;

        $pdf = Pdf::loadView('reports.task_report', compact('team', 'tasks'));

        // Force download
        return $pdf->download("task-report-{$team->id}.pdf");
    }
}
