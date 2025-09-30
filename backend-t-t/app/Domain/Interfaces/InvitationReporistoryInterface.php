<?php 
namespace App\Domain\Interfaces;

use App\Models\Team;

interface InvitationReporistoryInterface{
    public function invite(array $data);
    public function findMemberByToken(string $token, string $email);
    public function acceptInvitation(array $data);
    
    
}