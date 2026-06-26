<?php 
namespace App\Http\Controllers\Reports;

use App\Models\Team;
use App\Models\ReportReceiver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportReceiverCreateRequest;
use App\Http\Requests\Reports\ReportReceiverUpdateRequest;
use App\Application\Services\Reports\ReportReceiverService;
use Illuminate\Support\Facades\Gate;

class ReportReceiverController extends Controller{

    public function __construct(private ReportReceiverService $reportReceiverService){}
    public function create(ReportReceiverCreateRequest $request){
        $team = Team::findOrFail($request->validated()['team_id']);
        Gate::authorize('create', [ReportReceiver::class, $team]);

        return response()->json($this->reportReceiverService->create($request));
    }

    public function view(Team $team){
        Gate::authorize('viewAny', [ReportReceiver::class, $team]);

        return response()->json($this->reportReceiverService->reportReceivers($team));
    }

    public function update(ReportReceiverUpdateRequest $request, ReportReceiver $report_receiver){
        Gate::authorize('update', $report_receiver);

        return response()->json($this->reportReceiverService->update($request, $report_receiver));
    }
}
