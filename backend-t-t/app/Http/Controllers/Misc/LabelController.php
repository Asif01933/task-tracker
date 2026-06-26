<?php

namespace App\Http\Controllers\Misc;

use App\Application\Services\Misc\LabelService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\LabelAttachRequest;
use App\Http\Requests\Misc\LabelCreateRequest;
use App\Http\Requests\Misc\LabelUpdateRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;

class LabelController extends Controller
{
    public function __construct(private LabelService $labelService){}

    public function store(LabelCreateRequest $request, Team $team)
    {
        Gate::authorize('create', [Label::class, $team]);

        return response()->json($this->labelService->store($request, $team));
    }

    public function index(Team $team)
    {
        Gate::authorize('viewAny', [Label::class, $team]);

        return response()->json($this->labelService->index($team));
    }

    public function update(LabelUpdateRequest $request, Team $team, Label $label)
    {
        Gate::authorize('update', [$label, $team]);

        return response()->json($this->labelService->update($request, $team, $label));
    }

    public function destroy(Team $team, Label $label)
    {
        Gate::authorize('delete', [$label, $team]);

        return response()->json($this->labelService->destroy($team, $label));
    }

    public function attach(LabelAttachRequest $request, Team $team, Task $task)
    {
        Gate::authorize('attach', [Label::class, $team, $task]);

        return response()->json($this->labelService->attach($request, $team, $task));
    }

    public function detach(Team $team, Task $task, Label $label)
    {
        Gate::authorize('detach', [$label, $team, $task]);

        return response()->json($this->labelService->detach($team, $task, $label));
    }
}
