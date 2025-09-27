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

    public function reportReceivers($team){
        $receivers = $this->reportReceiverInterface->view($team);

        return [
            'data' => $receivers,
            'status' => true,
            'code' => 200,
            'message' => 'Vailable report reeivers'
        ];
    }

    public function update($request, $receiver){

        $this->reportReceiverInterface->update($request->validated(), $receiver);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'receiver updated successfully'
        ];
    }
}