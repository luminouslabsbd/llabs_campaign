<?php

namespace Luminouslabs\Installer\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberSpinHandlerController extends Controller
{
    // This will run when member will spin once.
    public function gotSpinned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|integer',
            'spinner_round' => 'required|integer',
            'rewards' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            // Attach campaign to member with specified data
            $member = member(['campaigns']);
            $e = $member->campaigns()->attach($request['campaign_id'], [
                'spinner_round' => $request['spinner_round'],
                'rewards' => json_encode($request['rewards']),
            ]);

            // Update spinner counts
            $spin = DB::table('member_spinner_count')
                ->where('member_id', Auth::id())
                ->where('campaign_id', $request['campaign_id'])
                ->first();
            if ($spin) {
                DB::table('member_spinner_count')
                    ->where('member_id', Auth::id())
                    ->where('campaign_id', $request['campaign_id'])
                    ->update([
                        'remaining_spin' => $spin->remaining_spin - 1,
                    ]);
            }

            DB::commit();

            return response()->json([
                "message" => "Spin round data successfully processed.",
                "status" => 200,
            ], 200);

        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();

            // Handle the exception as needed
            return response()->json([
                "error" => "An error occurred while processing the spin round data.",
                "status" => 202,
            ], 202);
        }

    }
}
