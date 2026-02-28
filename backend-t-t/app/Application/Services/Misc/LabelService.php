<?php
namespace App\Application\Services\Misc;

use App\Domain\Interfaces\LabelRepositoryInterface;
class LabelService{

    public function __construct(private LabelRepositoryInterface $labelRepository){}

    public function store($request, $team){
        $label = $this->labelRepository->store($request->validated(), $team);
        if(!$label){
            throw new \Exception("Label is not created");
        }
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label created successfully',
            'data' => $label
        ];
    }

    public function index($team){
        $labels = $this->labelRepository->index($team);
        if(!$labels){
            throw new \Exception("Labels are not found");
        }
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Labels fetched successfully',
            'data' => $labels
        ];
    }
}