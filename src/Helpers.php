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

if (!function_exists('hexeToRgb')){
    function hexeToRgb($hexColor)
    {
        list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");
        return "rgb(" . implode(', ', [$r, $g, $b]) . ")";
    }
}
