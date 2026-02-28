<?php

namespace App\Domain\Interfaces;


interface LabelRepositoryInterface{
    public function store(array $data, $team);
    public function index($team);
}