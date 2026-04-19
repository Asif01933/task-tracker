<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class TeamMember extends Model{
    use HasFactory;
    use HasUuids;

    // Fillable fields for mass assignment
    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'status'
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class, 'team_member_id');
    }

    public function memberDailyTasks()
    {
        return $this->hasMany(MemberDailyTask::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}