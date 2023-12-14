<?php

namespace Luminouslabs\Installer\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\Card\CardService;

class MemberDashBoardController extends Controller
{
    public function getDashboardCardCount(Request $request,CardService $cardService){

        
       
        return response()->json([
            'status' => 200,
            

        ]);

    }


}
