<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    use HasUuids;
    // Fillable fields for mass assignment
    protected $fillable = [
        'uuid',
        'team_id',
        'assigned_to',
        'title',
        'problem_description',
        'category',
        'priority',
        'status',
        'solution_description',
        'reporter_name',
        'reporter_type',
        'raised_at',
        'entry_maker',
    ];

    public function teamMember(){
        return $this->belongsTo(TeamMember::class, 'assigned_to');
    }

    public function entryMaker()
    {
        return $this->belongsTo(TeamMember::class, 'entry_maker');
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function labels(){
        return $this->belongsToMany(Label::class);
    }

    public function memberDailyTasks()
    {
        return $this->hasMany(MemberDailyTask::class);
    }
}
