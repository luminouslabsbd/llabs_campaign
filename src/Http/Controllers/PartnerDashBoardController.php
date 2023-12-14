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

    public function getLastSevenDaysData()
    {
        $userId = auth('partner')->user()->id;

        $endDate = now();
        $startDate = $endDate->copy()->subDays(6);

        $dates = collect(CarbonPeriod::create($startDate, $endDate)->toArray());

        $staffArr = $this->getQueryResult('staff', $dates, $userId);
        $membersArr = $this->getQueryResult('members', $dates, $userId);
        $pointsArr = $this->getTotalRewardData($dates, $userId, 'points');
        $cardsArr = $this->getQueryResult('cards', $dates, $userId);
        $rewardViewArr = $this->getTotalRewardData($dates, $userId, 'views');
        $cardsDataArr = $this->getCardsData($dates, $userId);

        return response()->json([
            'staffData' => $staffArr,
            'membersData' => $membersArr,
            'pointsData' => $pointsArr,
            'totalCardsData' => $cardsArr,
            'rewardViewsData' => $rewardViewArr,
            'cardsData' => $cardsDataArr,
        ]);
    }

    private function getQueryResult($table, $dates, $userId)
    {
        return $dates->map(function ($date) use ($table, $userId) {
            return [
                'date' => $date->toDateString(),
                'count' => DB::table($table)->whereDate('created_at', $date)->where('created_by', $userId)->count(),
            ];
        })->toArray();
    }

    private function getTotalRewardData($dates, $userId, $column)
    {
        return $dates->map(function ($date) use ($userId, $column){
            return [
                'date' => $date->toDateString(),
                'count' => DB::table('rewards')->whereDate('created_at', $date)->where('created_by', $userId)->sum($column),
            ];
        })->toArray();
    }

    private function getCardsData($dates, $userId)
    {
        return $dates->map(function ($date) use ($userId){
            $query = DB::table('cards')->whereDate('created_at', $date)->where('created_by', $userId)->get(['views', 'number_of_points_issued', 'number_of_rewards_redeemed']);

            return [
                'date' => $date->toDateString(),
                'views' => $query->sum('views'),
                'points_issued' => $query->sum('number_of_points_issued'),
                'rewards_redeemed' => $query->sum('number_of_rewards_redeemed'),
            ];
        })->toArray();
    }
    


}
