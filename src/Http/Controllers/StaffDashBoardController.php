<?php

namespace Luminouslabs\Installer\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class StaffDashBoardController extends Controller
{
    public function getDashboardCardCount(Request $request){

        $authId = auth('staff')->user()->id;
        // Retrieve the table data for the data definition
        $totalMember = DB::table('transactions')->where('staff_id',$authId)->select('id')->count();
       
        return response()->json([
            'status' => 200,
            'totalMember' => $totalMember
        ]);

    }


}
