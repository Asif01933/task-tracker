<?php 
namespace App\Http\Controllers\TeamMembers;

use Illuminate\Http\Request;
use App\Application\DTOs\MemberDTO;
use App\Http\Controllers\Controller;
use App\Application\Services\TeamMembers\MemberService;
use App\Http\Requests\TeamMembers\ProfileUpdateRequest;

class MemberController extends Controller{

    public function __construct(private MemberService $memberService){}

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myProfile(Request $request){
        
        return response()->json($this->memberService->myProfile($request));
    }

    public function myProfileUpdate(ProfileUpdateRequest $request){


        return response()->json($this->memberService->myProfileUpdate($request));
    }
}