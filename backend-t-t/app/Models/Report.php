<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    use HasUuids;
    // Fillable fields for mass assignment
    protected $fillable = [
        'team_id',
        'team_member_id',
        'due_date',
        'report_frequency',
    ];

    
    
}
