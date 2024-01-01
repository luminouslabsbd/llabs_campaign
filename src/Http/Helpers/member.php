<?php

use Illuminate\Support\Facades\Auth;
use Luminouslabs\Installer\Models\Member;

if (!function_exists('member')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function member($relationships = null)
    {
        $userId = Auth::id();
        // return Member::findOrFail($userId)->with(['campaigns']);
        // $userId = Auth::id();

        // // Retrieve a Member instance
        Member::findOrFail($userId);

        // // Eager load the specified relationships if provided
        if ($relationships) {
            $member = Member::with($relationships)->findOrFail($userId);
        } else {
            $member = Member::findOrFail($userId);
        }

        // // If 'campaigns' is specified in relationships or if no relationships are specified,
        // // load the 'campaigns' relationship using the relationship method
        // if (!$relationships || in_array('campaigns', $relationships)) {
        //     $member->load('campaigns');
        // }

        return $member;
    }

}
