<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * Relationship: Team belongs to an owner (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function receivers(){
        return $this->hasMany(ReportReceiver::class);
    }
}
