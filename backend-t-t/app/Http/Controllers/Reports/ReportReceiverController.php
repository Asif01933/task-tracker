<?php 
namespace App\Http\Controllers\Reports;

use App\Models\Team;
use App\Models\ReportReceiver;
use Illuminate\Http\Client\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportReceiverCreateRequest;
use App\Http\Requests\Reports\ReportReceiverUpdateRequest;
use App\Application\Services\Reports\ReportReceiverService;

class ReportReceiverController extends Controller{

    public function __construct(private ReportReceiverService $reportReceiverService){}
    public function create(ReportReceiverCreateRequest $request){
        return response()->json($this->reportReceiverService->create($request));
    }

    public function view(Team $team){
        return response()->json($this->reportReceiverService->reportReceivers($team));
    }

    public function update(ReportReceiverUpdateRequest $request, ReportReceiver $receiver){
        return response()->json($this->reportReceiverService->update($request, $receiver));
    }
}