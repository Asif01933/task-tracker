<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDailyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'task_id',
        'plan_date',
    ];

    protected function casts(): array
    {
        return [
            'plan_date' => 'date',
        ];
    }

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
