<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class TeamInvitation extends Model
{
    use HasFactory;
    use HasUuids;

    // Fillable fields for mass assignment
    protected $fillable = [
        'team_id', 'email', 'token', 'status'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
