<?php 
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportReceiverCreateRequest;
use App\Application\Services\Reports\ReportReceiverService;

class ReportReceiverController extends Controller{

    public function __construct(private ReportReceiverService $reportReceiverService){}
    public function create(ReportReceiverCreateRequest $request){
        return response()->json($this->reportReceiverService->create($request));
    }
}