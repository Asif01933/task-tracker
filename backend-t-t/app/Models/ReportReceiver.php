<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportReceiver extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'email',
        'team_id',
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }
}
