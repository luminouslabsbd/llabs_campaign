<?php

namespace App\Http\Controllers\Api;

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

        $e = member(['campaigns'])->campaigns()->attach($request['campaign_id'], [
            // 'member_id' => Auth::id(),
            'spinner_round' => $request['spinner_round'],
            'rewards' => json_encode($request['rewards']),
        ]);

        $spin = DB::table('member_spinner_count')
            ->where([
                'campaign_id' => $request['campaign_id'],
            ])
            ->first();

        if ($spin) {
            DB::table('member_spinner_count')
                ->where('campaign_id', $request['campaign_id'])
                ->update([
                    'total_spin' => $spin->total_spin - 1,
                    'remaining_spin' => $spin->remaining_spin - 1,
                ]);
        }

        return response()->json('Spin round data successfully processed.', 200);
    }
}
