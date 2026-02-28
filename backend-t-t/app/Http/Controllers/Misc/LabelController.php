<?php

namespace App\Http\Controllers\Misc;

use App\Application\Services\Misc\LabelService;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Http\Requests\Misc\LabelCreateRequest;

class LabelController extends Controller
{
    public function __construct(private LabelService $labelService){}

    public function store(LabelCreateRequest $request, Team $team){
        return response()->json($this->labelService->store($request, $team));
    }


    public function index(Team $team){
        return response()->json($this->labelService->index($team));
    }
}