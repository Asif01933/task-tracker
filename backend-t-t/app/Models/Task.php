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
        'team_member_id',
        'title',
        'description',
        'category',
        'status'
    ];

    public function teamMember(){
        return $this->belongsTo(TeamMember::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function labels(){
        return $this->belongsToMany(Label::class);
    }
    
}
