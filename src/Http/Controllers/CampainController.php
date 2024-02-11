<?php

namespace Luminouslabs\Installer\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Notifications\Member\Registration;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;


class CampainController extends Controller
{
    public function cardsManage(Request $request)
    {
        // $url = 'https://keoswalletapi.luminousdemo.com/api/get-user-cards?email=' . auth()->user()->email;
        $url = config('api.wallet_api_endpoint') . '?email=' . auth('partner')->user()->email;

        $response = Http::get($url);

        if ($response->successful()) {
            $data = json_decode($response->body(), true);
            // dd($data);
            return view('luminouslabs::components.member.cards', ['cards' => $data]);
        } else {
            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        }
    }

    public function storeCard(Request $request)
    {
        // dd(auth('partner')->user());
        $validator =  Validator::make(
            $request->all(),
            [
                'selected_card' => 'required'
            ],
            [
                'selected_card' => 'This select field is required'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::table('partners')
            ->where('id', auth('partner')->user()->id)
            ->update([
                'member_card' => $request->input('selected_card'),
            ]);

        return redirect()->back()->with('success', 'Card data saved successfully.');
    }


    public function getComapin(Request $request)
    {

        $userId = auth('partner')->user()->id;
        $routeDataDefinition = 'campain';

        if ($request->search) {
            $campainData = DB::table('campaigns')->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('card_id', 'like', '%' . $request->search . '%');
            })->paginate(15);
        } else {
            $campainData = DB::table('campaigns')->where('created_by', $userId)->paginate(15);
        }

        return view('luminouslabs::index', compact('routeDataDefinition', 'campainData'));
    }

    public function create(Request $request)
    {

        $userId = auth('partner')->user()->id;
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $userId)->select('id', 'name', 'unique_identifier')->get();
        return view('luminouslabs::form', compact('cards'));
    }

    public function CampaignStorge(Request $request)
    {
        // dd($request->all());
        $userid = auth('partner')->user()->id;
        $campaignData = $request->all();

        $rules = [
            'name' => 'required|string|max:255',
            'card_id' => 'required|numeric', // Adjust this rule based on your requirements
            'unit_price_for_coupon' => 'required|numeric',
            'unit_price_for_point' => 'required|numeric',
            'coupon'  => 'required'
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'coupon.required' => 'The coupon field is required.',
            'card_id.required' => 'The card ID field is required.',
            'card_id.numeric' => 'The card ID must be a number.',
            'unit_price_for_coupon.required' => 'The unit price for coupon field is required.',
            'unit_price_for_coupon.numeric' => 'The unit price for coupon must be a number.',
            'unit_price_for_point.required' => 'The unit price for point field is required.',
            'unit_price_for_point.numeric' => 'The unit price for point must be a number.',
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $campaignArray  = [
            'name' => $request->name,
            'card_id' => $request->card_id,
            'price_check' => isset($request->campaign_type) && $request->campaign_type == 'only_prize' ? $request->campaign_type : '',
            'point_check' => isset($request->campaign_type) && $request->campaign_type == 'prize_and_point' ? $request->campaign_type : '',
            'created_by' =>  $userid,
            'tenant_id' => $userid,
            'campain_code' => bin2hex(random_bytes(10)),
            'status'    => 1,
            'unit_price_for_coupon' => $request->unit_price_for_coupon,
            'unit_price_for_point' => $request->unit_price_for_point,
            'coupon' => isset($request->coupon) ? $request->coupon : null,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $lastInsertedId = DB::table('campaigns')->insertGetId($campaignArray);
        $this->spinerDataStore($campaignData, $lastInsertedId);
        return redirect()->route('luminouslabs::partner.campain.manage');
    }

    public function spinerDataStore($campaignData, $campaign_id)
    {
        if (isset($campaignData['label_title']) && isset($campaignData['label_value']) && isset($campaignData['label_color'])) {
            // Get the number of items in the label arrays
            $numItems = count($campaignData['label_title']);
            // Iterate over the items and create labels
            for ($i = 0; $i < $numItems; $i++) {
                DB::table('spiner_data')->insert([

                    'cam_id' => $campaign_id,
                    'label_title' => $campaignData['label_title'][$i],
                    'label_value' => $campaignData['label_value'][$i],
                    'label_color' => $campaignData['label_color'][$i],
                    'is_wining_label' => isset($campaignData['is_wining_label'][$i]) && $campaignData['is_wining_label'][$i] == 'on' ? true : false,
                    'init_prize' => isset($campaignData['init_prize'][$i]) ? $campaignData['init_prize'][$i] : 0,
                    'available_prize' => isset($campaignData['available_prize'][$i]) ? $campaignData['available_prize'][$i] : 0,
                ]);
            }
            return true;
        }
        return false;
    }

    public function edit($id)
    {

        $userId = auth('partner')->user()->id;
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $userId)->select('id', 'name', 'unique_identifier')->get();
        $data = DB::table('campaigns')
            ->where('campaigns.id',  request()->id)
            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color')
            ->get();

        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
            return [
                'id' => $groupedItems->first()->id,
                'name' => $groupedItems->first()->name,
                'card_id' => $groupedItems->first()->card_id,
                'price_check' => $groupedItems->first()->price_check,
                'point_check' => $groupedItems->first()->point_check,
                'unit_price_for_coupon' => $groupedItems->first()->unit_price_for_coupon,
                'unit_price_for_point' => $groupedItems->first()->unit_price_for_point,
                'coupon' => $groupedItems->first()->coupon,

                'spiner' => $groupedItems->map(function ($item) {
                    return [
                        'label_title' => $item->label_title,
                        'label_value' => $item->label_value,
                        'label_color' => $item->label_color,
                        'spiner_data_id' => $item->spiner_data_id,
                    ];
                })->toArray(),
            ];
        })->values()->first();

        return view('luminouslabs::edit', compact('result', 'cards'));
    }

    public function update(Request $request,)
    {

        $campaignData = $request->all();
        $campaign_id = request()->id;

        DB::table('campaigns')->where('id', $campaign_id)->update([
            'name' => $campaignData['name'],
            'card_id' => $campaignData['card_id'],
            'price_check' => isset($request->campaign_type) && $request->campaign_type == 'only_prize' ? $request->campaign_type : '',
            'point_check' => isset($request->campaign_type) && $request->campaign_type == 'prize_and_point' ? $request->campaign_type : '',
            'unit_price_for_coupon' => $request->unit_price_for_coupon,
            'unit_price_for_point' => $request->unit_price_for_point,
            'coupon' => isset($request->coupon) ? $request->coupon : null,
        ]);

        if (isset($campaignData['label_title']) && isset($campaignData['label_value']) && isset($campaignData['label_color']) && isset($campaignData['spiner_title_id'])) {
            // Get the number of items in the label arrays
            $numItems = count($campaignData['label_title']);
            for ($i = 0; $i < $numItems; $i++) {
                $spinerId = $campaignData['spiner_title_id'][$i];
                $spinerData = [
                    'cam_id' => $campaign_id,
                    'label_title' => $campaignData['label_title'][$i],
                    'label_value' => $campaignData['label_value'][$i],
                    'label_color' => $campaignData['label_color'][$i],
                ];

                DB::table('spiner_data')->updateOrInsert(
                    ['id' => $spinerId, 'cam_id' => $campaign_id],
                    $spinerData
                );
            }
        }
        return redirect()->route('luminouslabs::partner.campain.manage');
    }

    public function view($id)
    {

        $userId = auth('partner')->user()->id;
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $userId)->select('id', 'name', 'unique_identifier')->get();
        $data = DB::table('campaigns')
            ->where('campaigns.id',  request()->id)
            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color')
            ->get();

        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
            return [
                'id' => $groupedItems->first()->id,
                'name' => $groupedItems->first()->name,
                'card_id' => $groupedItems->first()->card_id,
                'price_check' => $groupedItems->first()->price_check,
                'point_check' => $groupedItems->first()->point_check,
                'unit_price_for_coupon' => $groupedItems->first()->unit_price_for_coupon,
                'unit_price_for_point' => $groupedItems->first()->unit_price_for_point,
                'coupon' => $groupedItems->first()->coupon,
                'campain_code' => $groupedItems->first()->campain_code,

                'spiner' => $groupedItems->map(function ($item) {
                    return [
                        'label_title' => $item->label_title,
                        'label_value' => $item->label_value,
                        'label_color' => $item->label_color,
                        'spiner_data_id' => $item->spiner_data_id,
                    ];
                })->toArray(),
            ];
        })->values()->first();

        return view('luminouslabs::view', compact('result', 'cards'));
    }

    public function delete($id)
    {

        try {
            DB::table('campaigns')->where('id', request()->id)->delete();
            DB::table('spiner_data')->where('cam_id', request()->id)->delete();
            return response()->json(['message' => 'Item deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle the exception or log it for debugging
            return response()->json(['error' => 'Failed to delete item'], 500);
        }

        return redirect()->route('luminouslabs::partner.campain.manage');
    }

    public function getSpinarData(Request $request)
    {
        // Convert the associative array to a JSON string
        $jsonData = $request->json()->all();

        $validator = Validator::make($request->json()->all(), [
            'id' => 'required',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation errors
            return response()->json(['error' => $validator->errors()], 422);
            // 422 Unprocessable Entity status code is often used for validation errors
        }

        $data = DB::table('campaigns')
            ->where('campaigns.id',  request()->id)
            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color')
            ->get();

        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
            return [
                'id' => $groupedItems->first()->id,
                'name' => $groupedItems->first()->name,
                'card_id' => $groupedItems->first()->card_id,
                'price_check' => $groupedItems->first()->price_check,
                'point_check' => $groupedItems->first()->point_check,
                'unit_price_for_coupon' => $groupedItems->first()->unit_price_for_coupon,
                'unit_price_for_point' => $groupedItems->first()->unit_price_for_point,
                'coupon' => $groupedItems->first()->coupon,

                'spiner' => $groupedItems->map(function ($item) {
                    return [
                        'label_title' => $item->label_title,
                        'label_value' => $item->label_value,
                        'label_color' => $item->label_color,
                        'spiner_data_id' => $item->spiner_data_id,
                    ];
                })->toArray(),
            ];
        })->values()->first();

        return response()->json(['data' => $result]);
    }

    public function campain_spiner_id_remove($id)
    {
        try {
            DB::table('spiner_data')->where('id', request()->id)->delete();
            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete data', 'message' => $e->getMessage()], 500);
        }
    }
}
