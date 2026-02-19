<?php 

namespace App\Infrastructure\Repositories;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamInvitation;
use App\Domain\Interfaces\TeamRepositoryInterface;

class EloquentTeamRepository implements TeamRepositoryInterface{
    public function create(array $data)
    {
        $team = Team::create($data);
        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $team->owner_id,
            'role' => 'admin',
            'status' => 'active'
        ]);
        
        return $team;
    }

    public function update(array $data, $team){
        $team->fill($data);
        $team->save();
        return $team;
    }

    public function findById($id){
        return Team::find($id);
    }

    public function myTeams($id){
        $members = TeamMember::where('user_id', $id)->get();

        $teams = [];

        foreach($members as $member){
            $teams[] = $member->team;
        }
        return $teams;
    }


    public function delete($team){
        $team->delete();
        
    }

    public function list(){
        return Team::all();
    }



    
}