<?php 

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\LabelRepositoryInterface;

class EloquentLabelRepository implements LabelRepositoryInterface
{
    public function store(array $data, $team)
    {
        return $team->labels()->create($data);
    }

    public function index($team)
    {
        return $team->labels;
    }

    public function findById($team, string $labelId)
    {
        return $team->labels()->find($labelId);
    }

    public function update($label, array $data)
    {
        $label->fill($data);
        $label->save();

        return $label;
    }

    public function delete($label)
    {
        return $label->delete();
    }

    public function attachLabelToTask($task, string $labelId)
    {
        $task->labels()->syncWithoutDetaching([$labelId]);

        return $task->labels()->fresh();
    }

    public function detachLabelFromTask($task, string $labelId)
    {
        $task->labels()->detach($labelId);

        return $task->labels()->fresh();
    }
}
