<?php

namespace Luminouslabs\Installer\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PartnerDashBoardController extends Controller
{
    public function getDashboardCardCount(Request $request){

        $userId = auth('partner')->user()->id;

        $viewsTableQuery = DB::table('cards')->where('created_by', $userId)->select('views', 'number_of_points_issued', 'number_of_rewards_redeemed');

        $data = $viewsTableQuery->get(['views', 'number_of_points_issued', 'number_of_rewards_redeemed'])->toArray();

        $countDatas['totalCards'] = $viewsTableQuery->count() ?? 0;

        // Define the attributes you want to sum
        $attributes = ['views', 'number_of_points_issued', 'number_of_rewards_redeemed'];

        // Initialize an array to store the sums
        $cardsSums = array_fill_keys($attributes, 0);

        // Use array_map to iterate through the array and sum up the values for each attribute
        array_map(function ($item) use (&$cardsSums) {
            foreach ($cardsSums as $attribute => &$cardsSums) {
                $cardsSums += $item->$attribute;
            }
        }, $data);

        $staffsTotal = DB::table('staff')->where('created_by',$userId)->count();
        $membersTotal = DB::table('members')->where('created_by',$userId)->count();

        $countDatas['rewardViews'] = DB::table('rewards')->where('created_by', $userId)->select(DB::raw('SUM(views) as totalViews'), DB::raw('SUM(points) as totalPoints'))->get()->toArray() ?? ['totalViews' => 0, 'totalPoints' => 0];
       
        return response()->json([
            'status' => 200,
            'countDatas' => $countDatas,
            'cardsSums'  => $cardsSums,
            'staffsTotal' => $staffsTotal,
            'membersTotal' => $membersTotal,

        ]);

    }


}
