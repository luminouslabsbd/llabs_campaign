<?php

// use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Luminouslabs\Installer\Models\Member;

if (!function_exists('member')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function member(array $relationships = null)
    {
        if (isset($relationships)) {
            $member = Member::with($relationships)->findOrFail(Auth::id());
        } else {
            $member = Member::findOrFail(Auth::id());
        }

        return $member;
    }
}
