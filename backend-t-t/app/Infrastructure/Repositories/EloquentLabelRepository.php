<?php 


namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\LabelRepositoryInterface;


class EloquentLabelRepository implements LabelRepositoryInterface{
    public function store(array $data, $team){
        return $team->labels()->create($data);
    }

    public function index($team){
        return $team->labels;
    }
}