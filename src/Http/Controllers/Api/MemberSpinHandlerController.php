<?php

namespace Luminouslabs\Installer\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberSpinHandlerController extends Controller
{
    // This will run when member will spin once.
    public function gotSpinned(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|integer',
            'rewards' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            // $spin = DB::table('member_spinner_count')
            //     ->where([
            //         'member_id' => Auth::id(),
            //         'campaign_id' => $request['campaign_id'],
            //     ])
            //     ->first();
            $spinner_count = DB::table('member_spinner_count')
                ->where('campaign_id', $request['campaign_id'])
                ->where('member_id', Auth::id())->first();

            if ($spinner_count->remaining_spin > 0) {
                $spinner_round = $spinner_count->total_spin - $spinner_count->remaining_spin;

                if ($spinner_count) {
                    // Make modifications
                    $spinner_count->remaining_spin = $spinner_count->remaining_spin - 1;

                    // Save the changes
                    DB::table('member_spinner_count')
                        ->where('campaign_id', $request['campaign_id'])
                        ->where('member_id', Auth::id())
                        ->update([
                            'remaining_spin' => $spinner_count->remaining_spin,
                        ]);
                }

                if (isset($spinner_count)) {
                    //Attach all rewards for the member for specific campaign
                    $e = member(['campaigns'])->campaigns()->attach($request['campaign_id'], [
                        'rewards' => $request['rewards'],
                        'spinner_round' => $spinner_round,
                    ]);
                    DB::commit();
                    return response()->json([
                        'message' => 'Spin round data successfully processed.',
                        'status' => 200
                    ], 200);
                } else {
                    return response()->json('Something went wrong.', 404);
                }
            } else {
                return response()->json(['message' => 'No available spin'], 404);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }
    }
}
