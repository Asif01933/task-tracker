<?php 
namespace App\Http\Controllers\Invitation;

use App\Application\Services\Invitation\InvitationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\TeamInviteRequest;
use App\Http\Requests\Teams\TeamInvitationAcceptRequest;

class InvitationController extends Controller{

    public function __construct(private InvitationService $invitationService){}

    public function invite(TeamInviteRequest $request){
        return response()->json($this->invitationService->invite($request));
    }

    public function acceptInvitation(TeamInvitationAcceptRequest $teamInvitationAcceptRequest){
        return response()->json($this->invitationService->acceptInvitation($teamInvitationAcceptRequest));
    }
}