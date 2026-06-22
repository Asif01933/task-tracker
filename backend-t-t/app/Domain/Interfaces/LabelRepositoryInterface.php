<?php

namespace App\Domain\Interfaces;

interface LabelRepositoryInterface
{
    public function store(array $data, $team);

    public function index($team);

    public function findById($team, string $labelId);

    public function update($label, array $data);

    public function delete($label);

    public function attachLabelToTask($task, string $labelId);

    public function detachLabelFromTask($task, string $labelId);
}