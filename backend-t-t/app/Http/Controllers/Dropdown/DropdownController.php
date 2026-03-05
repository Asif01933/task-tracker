<?php 
namespace App\Http\Controllers\Dropdown;
use App\Http\Controllers\Controller;
use App\Http\Services\Dropdown\DropdownService;


class DropdownController extends Controller{
    public function __construct(private DropdownService $dropDownService){}
    public function getRoles(){
        return response()->json($this->dropDownService->getRoles());
    }
}