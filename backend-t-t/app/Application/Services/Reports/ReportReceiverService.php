<?php 
namespace App\Application\Services\Reports;

use App\Domain\Interfaces\ReportReceiverInterface;


class ReportReceiverService{

    public function __construct(private ReportReceiverInterface $reportReceiverInterface){}
    public function create($request){
        
        $this->reportReceiverInterface->create($request->validated());

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Email is added successfully'
        ];
    }
}