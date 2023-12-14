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
use App\Models\Card;
use Illuminate\Support\Collection;


class MemberDashBoardController extends Controller
{
    public function getDashboardCardCount(Request $request,CardService $cardService){

        $authId = auth('member')->user()->id ;
        // Fetch all active cards visible by default
        $cards = $this->findActiveCardsVisibleByDefault();
        // If user is authenticated, add followed cards to the collection and order by balance, issue_date
        if (auth('member')->check()) {

            $followedCards = $this->findActiveCardsFollowedByMember($authId);
            $cardsWithTransactions = $this->findActiveCardsWithMemberTransactions($authId);

            $cards = $cards->concat($followedCards)
                ->concat($cardsWithTransactions)
                ->unique('id')
                ->sortByDesc(function ($card) {
                    return [$card->getMemberBalance(null), $card->issue_date];
                });
        }

        return response()->json([
            'status' => 200,
            'followedCards' => $followedCards->count(),
            'cards'  => $cards->count(),

        ]);

    }

    public function findActiveCardsVisibleByDefault(): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Build the base query
        $query = Card::where('is_active', true)
            ->where('is_visible_by_default', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query and return the results
        return $query->get();
    }

    public function findActiveCardsFollowedByMember($member_id,$hideColumnsForPublic = false){

        $now = Carbon::now('UTC');

        // Build the query
        $query = Card::where('is_active', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->whereHas('members', function ($query) use ($member_id) {
                $query->where('members.id', $member_id);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }
        // Return the cards
        return $cards;
    }

    public function findActiveCardsWithMemberTransactions(int $member_id, bool $hideColumnsForPublic = false): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');
    
        // Build the query
        $query = Card::where('is_active', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->whereHas('transactions.member', function ($query) use ($member_id) {
                $query->where('members.id', $member_id);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }
        // Return the cards
        return $cards;
    }

    public function getLastSevenDaysData(string $locale, Request $request, CardService $cardService){

        $cards = $cardService->findActiveCardsVisibleByDefault();
        $authid = auth('member')->user()->id;
        // If user is authenticated, add followed cards to the collection and order by balance, issue_date
        if (auth('member')->check()) {

            $followedCards = $this->findActiveCardsFollowedByMember($authid);

            $last7DaysFollowedCardCount = $this->getLast7DaysCardData($followedCards);

            $cardsWithTransactions = $this->findActiveCardsWithMemberTransactions($authid);

            $cards = $cards->concat($followedCards)
                    ->concat($cardsWithTransactions)
                    ->unique('id')
                    ->sortByDesc(function ($card) {
                        return [$card->getMemberBalance(null), $card->issue_date];
                    });

            $last7DaysTrxCardsCount =  $this->getLast7DaysCardData($cards);
        }

        return response()->json([
            'last7DaysFollowedCardCount' => $last7DaysFollowedCardCount,
            'last7DaysTrxCardsCount' => $last7DaysTrxCardsCount,
        ]);

    }

    private function getLast7DaysCardData($items)
    {
        // Get the start and end dates for the last 7 days
        $endDate = now();
        $startDate = $endDate->copy()->subDays(6);

        // Generate an array of dates for the last 7 days
        $dates = collect(CarbonPeriod::create($startDate, $endDate)->toArray());

        // Initialize an array to store the result
        $result = [];

        // Iterate through each date and count items for that date
        foreach ($dates as $date) {
            $formattedDate = $date->format('Y-m-d');

            $count = $items->filter(function ($item) use ($formattedDate) {
                $createdAt = Carbon::parse($item->created_at)->format('Y-m-d');
                return $createdAt === $formattedDate;
            })->count();

            $result[] = [
                'date' => $formattedDate,
                'count' => $count,
            ];
        }

        return $result;
    }




}
