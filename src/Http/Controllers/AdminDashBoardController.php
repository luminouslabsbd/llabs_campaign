<?php

namespace Luminouslabs\Installer\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminDashBoardController extends Controller
{
    public function getDashboardCardCount(Request $request){

        // dashboard
        $viewsTableQuery = DB::table('cards')->select('views', 'number_of_points_issued', 'number_of_rewards_redeemed');
        
        $data = $viewsTableQuery->get(['views', 'number_of_points_issued', 'number_of_rewards_redeemed'])->toArray();
        $rewardViews = DB::table('rewards')->select('views as reward_views')->get()->toArray();

        $totalRewardViews = collect($rewardViews)->sum('reward_views');
        
        $totalCards = $viewsTableQuery->count() ?? 0;
        
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
        
        $staffsTotal = DB::table('staff')->count();
        $membersTotal = DB::table('members')->count();
        $totalPartners = DB::table('partners')->count();

        return response()->json([
            'status' => 200,
            'totalCards' => $totalCards,
            'totalRewardViews' => $totalRewardViews,
            'cardsSums' => $cardsSums,
            'staffsTotal' => $staffsTotal,
            'membersTotal' => $membersTotal,
            'totalPartners' => $totalPartners,
        ]);

    }

    public function getLastSevenDaysData()
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays(6);

        $dates = collect(CarbonPeriod::create($startDate, $endDate)->toArray());

        $staffArr = $this->getQueryResult('staff', $dates);
        $membersArr = $this->getQueryResult('members', $dates);
        $partnersArr = $this->getQueryResult('partners', $dates);
        $cardsArr = $this->getQueryResult('cards', $dates);
        $rewardViewArr = $this->getTotalRewardViews($dates);
        $cardsDataArr = $this->getCardsData($dates);

        return response()->json([
            'staffData' => $staffArr,
            'membersData' => $membersArr,
            'partnersData' => $partnersArr,
            'totalCardsData' => $cardsArr,
            'rewardViewsData' => $rewardViewArr,
            'cardsData' => $cardsDataArr,
        ]);
    }

    private function getQueryResult($table, $dates)
    {
        return $dates->map(function ($date) use ($table) {
            return [
                'date' => $date->toDateString(),
                'count' => DB::table($table)->whereDate('created_at', $date)->count(),
            ];
        })->toArray();
    }

    private function getTotalRewardViews($dates)
    {
        return $dates->map(function ($date) {
            return [
                'date' => $date->toDateString(),
                'count' => DB::table('rewards')->whereDate('created_at', $date)->sum('views'),
            ];
        })->toArray();
    }

    private function getCardsData($dates)
    {
        return $dates->map(function ($date) {
            $query = DB::table('cards')->whereDate('created_at', $date)->get(['views', 'number_of_points_issued', 'number_of_rewards_redeemed']);

            return [
                'date' => $date->toDateString(),
                'views' => $query->sum('views'),
                'points_issued' => $query->sum('number_of_points_issued'),
                'rewards_redeemed' => $query->sum('number_of_rewards_redeemed'),
            ];
        })->toArray();
    }


}
