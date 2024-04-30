<?php

namespace Luminouslabs\Installer\Http\Controllers;

use App\Models\MemberCard;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Notifications\Member\Registration;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Luminouslabs\Installer\Models\Campaign;
use Luminouslabs\Installer\Models\Member;
use Luminouslabs\Installer\Models\SpinCampagin;
use Luminouslabs\Installer\Models\SpinMember;
use Luminouslabs\Installer\Models\SpinPoint;
use Luminouslabs\Installer\Models\SpinReward;
use Luminouslabs\Installer\Service\ApplePassService;
use Mockery\Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;


class PartnerController extends Controller
{
    public function getPartnerMembers()
    {
        $partner = auth('partner')->user();
        $members = Member::where('partner_id',$partner->id)->get();

        return view('luminouslabs::partner.member.index', compact('members'));
    }

    public function loginAsAMember($lang,$id)
    {
        $user = Auth::loginUsingId($id);
        if ($user){
            return redirect()->route('member.dashboard');
        }
    }

    public function memberPasskits()
    {
        $id = auth('member')->id();
        $passkits = SpinPoint::select('template_id', DB::raw('MAX(id) as id'), DB::raw('MAX(campaign_id) as campaign_id'), DB::raw('MAX(member_id) as member_id'), DB::raw('MAX(template_pass_type) as template_pass_type'), DB::raw('MAX(card_id) as card_id'), DB::raw('SUM(point) as point'), DB::raw('MAX(created_at) as created_at'), DB::raw('MAX(updated_at) as updated_at'))
            ->where('member_id', $id)
            ->groupBy('template_id')
            ->get();
        return view('luminouslabs::member.passkit.passkits',compact('passkits'));
    }

    public function downloadPasskitTemplate()
    {
        $templateId =  request()->template_id;
        $templateType =  request()->template_type;

        try {
            $response = Http::post('https://keoswalletapi.luminousdemo.com/api/download-pass-by-loyalty-user',[
                "pass_type" => $templateType,
                "pass_id" => $templateId
            ]);

            if ($response->successful()) {
                $content = $response->body();
                $filename = '';
                if ($templateType == 'apple') {
                    $filename = Str::random(7) . '.pkpass';
                    $content_type = 'application/vnd.apple.pkpass';
                } elseif ($templateType == 'google') {
                    $filename = Str::random(7) . '.gpass';
                    $content_type = 'application/vnd.google.pass';
                } else {
                    return response()->json(['error' => 'Unsupported pass type'], 400);
                }

                $headers = [
                    'Content-Type' => $content_type,
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];

                return response($content, 200, $headers);
                //return back();
            } else {
                return response()->json(['error' => 'Failed to download .pkpass file'], 500);
            }
        }catch (e){
            return e;
        }
    }
}
