<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function tasks(){
        return $this->belongsToMany(Task::class);
    }
}