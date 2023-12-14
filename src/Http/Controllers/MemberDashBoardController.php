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

        // Fetch all active cards visible by default
        $cards = $cardService->findActiveCardsVisibleByDefault();

        // If user is authenticated, add followed cards to the collection and order by balance, issue_date
        if (auth('member')->check()) {
            $followedCards = $cardService->findActiveCardsFollowedByMember(auth('member')->user()->id);
            $cardsWithTransactions = $cardService->findActiveCardsWithMemberTransactions(auth('member')->user()->id);

            $cards = $cards->concat($followedCards)
                ->concat($cardsWithTransactions)
                ->unique('id')
                ->sortByDesc(function ($card) {
                    return [$card->getMemberBalance(null), $card->issue_date];
                });
        }
       
        return response()->json([
            'status' => 200,
            'countDatas' => $countDatas,
            'cardsSums'  => $cardsSums,
            'staffsTotal' => $staffsTotal,
            'membersTotal' => $membersTotal,

        ]);

    }


}
