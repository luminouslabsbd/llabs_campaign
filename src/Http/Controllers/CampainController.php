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
use Luminouslabs\Installer\Models\SpinCampagin;
use Luminouslabs\Installer\Models\SpinMember;
use Luminouslabs\Installer\Models\SpinPoint;
use Luminouslabs\Installer\Models\SpinReward;
use Luminouslabs\Installer\Service\ApplePassService;
use Mockery\Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;


class CampainController extends Controller
{
    public $passKitDataProvider;

    public function __construct(ApplePassService $apple_pass)
    {
        $this->passKitDataProvider = $apple_pass;
    }

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
        $user= auth('partner')->user();
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $user->id)->select('id', 'name', 'unique_identifier')->get();

        if (! ($cards->count() > 0)){
            return redirect()->route('partner.data.list',['name' => 'cards'])->with("message","You doesn't have any Loyalty Loyalty card.So Please create a card and try again" );
        }

        try {
            $response = Http::get('https://keoswalletapi.luminousdemo.com/api/get-partner-template-for-user/'.$user->email);
            if ($response->successful()) {
                $templates = $response->json();
                return view('luminouslabs::form', compact('cards','templates'));
            } else {
                return "fail to fetch";
                return back()->withErrors(['error' => 'Failed to fetch templates']);
            }
        }catch (Exception $exception){
            return "keos wall api fetching error".$exception;
        }



        //return view('luminouslabs::form', compact('cards','tempaltes'));
    }


    public function store(Request $request)
    {

        $userid = auth('partner')->user()->id;
        $decotedTempalteResponse = json_decode($request->template_response_obj);
        $pass_data =  json_decode($decotedTempalteResponse->pass_data ?? null);
        $wallet_data = json_decode($request->wallet_obj);
        $responseData = null;
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

        if (empty($decotedTempalteResponse) && empty($wallet_data)){
            return redirect()->back()->with('template_validate','Please select or add a template');
        }

        //Campagin Validation
       if (isset($decotedTempalteResponse)){
           $google_pass_data = $pass_data->textModulesData ?? null;
           if ($wallet_data->waletType == 0){
               $pass_data->passDetails->passId = $decotedTempalteResponse->id ?? null;
           }else if($wallet_data->waletType == 1){
               if ($wallet_data->cardType == 0){
                   $pass_data->StoreCard->passDetails->passId = $decotedTempalteResponse->id ?? null;
               }else if ($wallet_data->cardType == 2){
                   $pass_data->Coupon->passDetails->passId = $decotedTempalteResponse->id ?? null;
               }else if($wallet_data->cardType == 1){
                   $pass_data->GenericPass->passDetails->passId = $decotedTempalteResponse->id ?? null;
               }else{
                   return "Invalide card type";
               }
           }else{
               return "Invalide Pusskit Type";
           }

           if ($wallet_data->waletType == 0){
               $pass_data->passDetails->cardTitle = $wallet_data->cardNameVal;
               $pass_data->passDetails->color = $wallet_data->backgroundColorVal;
               $pass_data->passDetails->labelColor = $wallet_data->labelColorVal;

               $pass_data->passDetails->logoImage = $wallet_data->uploadedLogo ?? $pass_data->passDetails->logoImage;
               $pass_data->passDetails->heroImage = $wallet_data->upladedHeroImg ?? $pass_data->passDetails->heroImage;

               $pass_data->passDetails->formate = $wallet_data->barcodeFormat ?? $pass_data->passDetails->formate;
               $pass_data->passDetails->barcodeValue = $wallet_data->barcodeValue ?? $pass_data->passDetails->barcodeValue;

               if ($google_pass_data->firstRowData){
                   //return "Esist First";
                   foreach ($google_pass_data->firstRowData as $key => $value){
                       //echo "Processing element $key...\n";
                       if ($key == 0){
//                    return "exist here fr1";
//                    echo "Updating label and display value for first element...\n";
                           $value->label->value = $wallet_data->firstRowFirstElementLabel;
                           $value->displayValue->value = $wallet_data->firstRowFirstElementVal;

                       } elseif ($key == 1 && isset($value)){
//                    return "exist here fr2";
//                    echo "Updating label and display value for second element...\n";
                           $value->label->value = $wallet_data->firstRowSecondElementLabel;
                           $value->displayValue->value = $wallet_data->firstRowSecondElementVal;
                       } else {
                           if (isset($value)){
//                        return "exist here fr3";
//                        echo "Updating label and display value for third element...\n";
                               $value->label->value = $wallet_data->firstRowThirdElementLabel;
                               $value->displayValue->value = $wallet_data->firstRowThirdElementVal;
                           }
                       }
                   }
               }
               if (isset($google_pass_data->secondRowData)){
                   foreach ($google_pass_data->secondRowData as $key => $value){
                       if ($key == 0 && isset($value)){
                           $value->label->value = $wallet_data->secondRowFirstElementLabel;
                           $value->displayValue->value = $wallet_data->secondRowFirstElementVal;
                       }elseif ($key == 1 && isset($value)){
                           $value->label->value = $wallet_data->secondRowSecondElementLabel;
                           $value->displayValue->value = $wallet_data->secondRowSecondElementVal;
                       }else{
                           if (isset($value)){
                               $value->label->value = $wallet_data->secondRowThirdElementLabel;
                               $value->displayValue->value = $wallet_data->secondRowThirdElementVal;
                           }
                       }
                   }
               }
               if (isset($google_pass_data->thirdRowData)){
                   foreach ($google_pass_data->thirdRowData as $key => $value){
                       if ($key == 0 && isset($value)){
                           $value->label->value = $wallet_data->thirdRowFirstElementLabel;
                           $value->displayValue->value = $wallet_data->thirdRowFirstElementVal;
                       }elseif ($key == 1 && isset($value)){
                           $value->label->value = $wallet_data->thirdRowSecondlementLabel;
                           $value->displayValue->value = $wallet_data->thirdRowSecondlementVal;
                       }else{
                           if (isset($value)){
                               $value->label->value = $wallet_data->thirdRowThirdElementLabel;
                               $value->displayValue->value = $wallet_data->thirdRowThirdElementVal;
                           }
                       }
                   }
               }
               if (isset($google_pass_data->fourthRowData)){
                   foreach ($google_pass_data->fourthRowData as $key => $value){
                       if ($key == 0 && isset($value)){
                           $value->label->value = $wallet_data->fourthRowFirstElementLabel;
                           $value->displayValue->value = $wallet_data->fourthRowFirstElementVal;
                       }elseif ($key == 1 && isset($value)){
                           $value->label->value = $wallet_data->fourthRowSecondElementLabel;
                           $value->displayValue->value = $wallet_data->fourthRowSecondElementVal;
                       }else{
                           if (isset($value)){
                               $value->label->value = $wallet_data->fourthRowThirdElementLabel;
                               $value->displayValue->value = $wallet_data->fourthRowThirdElementVal;
                           }
                       }
                   }
               }
           }else if($wallet_data->waletType == 1){
               if ($wallet_data->cardType == 0){
                   $pass_data->StoreCard->passDetails->logoImage = $wallet_data->uploadedLogo ?? $pass_data->StoreCard->passDetails->logoImage;
                   $pass_data->StoreCard->passDetails->heroImage = $wallet_data->upladedHeroImg ?? $pass_data->StoreCard->passDetails->heroImage;

                   $pass_data->StoreCard->passDetails->color = hexeToRgb($wallet_data->backgroundColorVal) ?? $pass_data->StoreCard->passDetails->color;
                   $pass_data->StoreCard->passDetails->labelColor = hexeToRgb($wallet_data->labelColorVal) ?? $pass_data->StoreCard->passDetails->labelColor;
                   $pass_data->StoreCard->passDetails->cardTitle = $wallet_data->cardNameVal ?? $pass_data->StoreCard->passDetails->cardTitle;

                   $pass_data->StoreCard->passDetails->formate = $wallet_data->barcodeFormat ?? $pass_data->StoreCard->passDetails->formate;
                   $pass_data->StoreCard->passDetails->barcodeValue = $wallet_data->barcodeValue ?? $pass_data->StoreCard->passDetails->barcodeValue;

                   if ($pass_data->StoreCard->secondaryFormsData){
                       foreach ($pass_data->StoreCard->secondaryFormsData as $key => $value){
                           if ($key == 0 && isset($value)){
                               $value->label->value = $wallet_data->firstRowFirstElementLabel ;
                               $value->displayValue->value = $wallet_data->firstRowFirstElementVal ;
                           }else if ($key == 1 && isset($value)){
                               $value->label->value = $wallet_data->firstRowSecondElementLabel ;
                               $value->displayValue->value = $wallet_data->firstRowSecondElementVal ;
                           }else{
                               if ($value){
                                   $value->label->value = $wallet_data->firstRowThirdElementLabel ;
                                   $value->displayValue->value = $wallet_data->firstRowThirdElementVal ;
                               }
                           }
                       }
                   }
               }else if($wallet_data->cardType == 2){
                   $pass_data->Coupon->passDetails->logoImage = $wallet_data->uploadedLogo ?? $pass_data->Coupon->passDetails->logoImage;
                   $pass_data->Coupon->passDetails->heroImage = $wallet_data->upladedHeroImg ?? $pass_data->Coupon->passDetails->heroImage;

                   $pass_data->Coupon->passDetails->color = hexeToRgb($wallet_data->backgroundColorVal) ?? $pass_data->Coupon->passDetails->color;
                   $pass_data->Coupon->passDetails->labelColor = hexeToRgb($wallet_data->labelColorVal) ?? $pass_data->Coupon->passDetails->labelColor;
                   $pass_data->Coupon->passDetails->cardTitle = $wallet_data->cardNameVal ?? $pass_data->Coupon->passDetails->cardTitle;

                   $pass_data->Coupon->passDetails->formate = $wallet_data->barcodeFormat ?? $pass_data->Coupon->passDetails->formate;
                   $pass_data->Coupon->passDetails->barcodeValue = $wallet_data->barcodeValue ?? $pass_data->Coupon->passDetails->barcodeValue;

                   if ($pass_data->Coupon->secondaryFormsData){
                       foreach ($pass_data->Coupon->secondaryFormsData as $key => $value){
                           if ($key == 0 && isset($value)){
                               $value->label->value = $wallet_data->firstRowFirstElementLabel ;
                               $value->displayValue->value = $wallet_data->firstRowFirstElementVal ;
                           }else if ($key == 1 && isset($value)){
                               $value->label->value = $wallet_data->firstRowSecondElementLabel ;
                               $value->displayValue->value = $wallet_data->firstRowSecondElementVal ;
                           }else{
                               if ($value){
                                   $value->label->value = $wallet_data->firstRowThirdElementLabel ;
                                   $value->displayValue->value = $wallet_data->firstRowThirdElementVal ;
                               }
                           }
                       }
                   }
               }else if($wallet_data->cardType == 1){
                   $pass_data->GenericPass->passDetails->logoImage = $wallet_data->uploadedLogo ?? $pass_data->GenericPass->passDetails->logoImage;
                   $pass_data->GenericPass->passDetails->heroImage = $wallet_data->upladedHeroImg ?? $pass_data->GenericPass->passDetails->heroImage;
                   $pass_data->GenericPass->passDetails->color = hexeToRgb($wallet_data->backgroundColorVal) ?? $pass_data->GenericPass->passDetails->color;
                   $pass_data->GenericPass->passDetails->labelColor = hexeToRgb($wallet_data->labelColorVal) ?? $pass_data->GenericPass->passDetails->labelColor;
                   $pass_data->GenericPass->passDetails->cardTitle = $wallet_data->cardNameVal ?? $pass_data->GenericPass->passDetails->cardTitle;

                   $pass_data->GenericPass->passDetails->formate = $wallet_data->barcodeFormat ?? $pass_data->GenericPass->passDetails->formate;
                   $pass_data->GenericPass->passDetails->barcodeValue = $wallet_data->barcodeValue ?? $pass_data->GenericPass->passDetails->barcodeValue;

                   if ($pass_data->GenericPass->primaryFormsData){
                       $pass_data->GenericPass->primaryFormsData->label->value  = $wallet_data->firstRowFirstElementLabel;
                       $pass_data->GenericPass->primaryFormsData->displayValue->value  = $wallet_data->firstRowFirstElementVal;
                   }

                   if ($pass_data->GenericPass->secondaryFormsData){
                       foreach ($pass_data->GenericPass->secondaryFormsData as $key => $value){
                           if ($key == 0 && isset($value)){
                               $value->label->value = $wallet_data->secondRowFirstElementLabel ;
                               $value->displayValue->value = $wallet_data->secondRowFirstElementVal ;
                           }else if ($key == 1 && isset($value)){
                               $value->label->value = $wallet_data->secondRowSecondElementLabel ;
                               $value->displayValue->value = $wallet_data->secondRowSecondElementVal ;
                           }else{
                               if ($value){
                                   $value->label->value = $wallet_data->secondRowThirdElementLabel ;
                                   $value->displayValue->value = $wallet_data->secondRowThirdElementVal ;
                               }
                           }
                       }
                   }

                   if ($pass_data->GenericPass->auxiliaryFormsData){
                       foreach ($pass_data->GenericPass->auxiliaryFormsData as $key => $value){
                           if ($key == 0 && isset($value)){
                               $value->label->value = $wallet_data->thirdRowFirstElementLabel ;
                               $value->displayValue->value = $wallet_data->thirdRowFirstElementVal ;
                           }else if ($key == 1 && isset($value)){
                               $value->label->value = $wallet_data->thirdRowSecondElementLabel ;
                               $value->displayValue->value = $wallet_data->thirdRowSecondElementVal ;
                           }else{
                               if ($value){
                                   $value->label->value = $wallet_data->thirdRowThirdElementLabel ;
                                   $value->displayValue->value = $wallet_data->thirdRowThirdElementVal ;
                               }
                           }
                       }
                   }

               }else{
                   return "Invalide card type";
               }
           }else{
               return "Invalide Pusskit Type";
           }
           try {
               if ($wallet_data->waletType == 0){
                   $url = "https://keoswalletapi.luminousdemo.com/api/google-generate-pass/loyalty";
               }else if($wallet_data->waletType == 1){
                   $url = "https://keoswalletapi.luminousdemo.com/api/apple-generate-pass/loyalty";
               }else{
                   return "invalide type";
               }
               $response = Http::post($url,$pass_data);
               $responseData = json_decode($response, true);
           }catch (Exception $exception){
               return $exception;
           }
       }
       else{

           $passFormatedData = $this->passKitDataProvider->getFormate($wallet_data);
           //return $passFormatedData;
           try {
               if ($wallet_data->waletType == 0){
                   $url = "https://keoswalletapi.luminousdemo.com/api/google-generate-pass/loyalty";
               }else if($wallet_data->waletType == 1){
                   $url = "https://keoswalletapi.luminousdemo.com/api/apple-generate-pass/loyalty";
               }else{
                   return "invalide type";
               }

               $response = Http::post($url,$passFormatedData);
               $responseData = json_decode($response, true);
               //return $responseData;
           }catch (Exception $exception){
               return $exception;
           }
       }

       if (isset($responseData['data']['id']) && isset($responseData['data']['pass_type'])){
           $updatedTemplateId = $responseData['data']['id'];
           $updatedTemplateType = $responseData['data']['pass_type'];
       }elseif (isset($responseData['data']['existingRecord_id']) && isset($responseData['data']['exits_pass_type'])){
           $updatedTemplateId = $responseData['data']['existingRecord_id'];
           $updatedTemplateType = $responseData['data']['exits_pass_type'];
       }

        /*$templateInfo = $request->template_info;
        $template_parts = explode('|', $templateInfo);
        $template_id = $updatedTemplateId ?? $template_parts[0] ?? null;
        $pass_type = $updatedTemplateType ?? $template_parts[1] ?? null;
        return $template_id;
        exit();*/

        //return $responseData;

        $campagin = Campaign::create([
            'name' => $request->name,
            'card_id' => $request->card_id,
            'template_id' => $updatedTemplateId ?? $decotedTempalteResponse->id,
            'template_pass_type' => $updatedTemplateType ?? $decotedTempalteResponse->pass_type,
            'price_check' => isset($request->campaign_type) && $request->campaign_type == 'only_prize' ? $request->campaign_type : '',
            'point_check' => isset($request->campaign_type) && $request->campaign_type == 'prize_and_point' ? $request->campaign_type : '',
            'created_by' =>  $userid,
            'tenant_id' => $userid,
            'campain_code' => bin2hex(random_bytes(10)),
            'status'    => 1,
            'unit_price_for_coupon' => $request->unit_price_for_coupon,
            'unit_price_for_point' => $request->unit_price_for_point,
            'campaign_type' => $request->campaign_type,
            'coupon' => isset($request->coupon) ? $request->coupon : null,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        foreach ($request->label_title as $i => $value){
            $data[] = [
                'cam_id' =>  $campagin->id,
                'campaign_id' => $campagin->id,
                'label_title' => $request->label_title[$i],
                'label_value' => $request->label_value[$i],
                'label_color' => $request->label_color[$i],
                'init_prize' => isset($request->init_prize[$i]) ? $request->init_prize[$i] : 0,
                'available_prize' => isset($request->available_prize[$i]) ? $request->available_prize[$i] : 0,
                'is_wining_label' => isset($request->is_wining_label[$i]) && $request->is_wining_label[$i] == 'on' ? true : false
            ];
        }

        $campagin->spinnerData()->createMany($data);

        /*try {
            $response = Http::get('https://keoswalletapi.luminousdemo.com/api/get-data-from-loyalty-for-edit',[
                'pass_id' => $template_id,
                'pass_type' => $pass_type
            ]);
        $res = json_decode($response);
        }catch (e){
            return e;
        }*/

        return redirect()->route('luminouslabs::partner.campain.manage');
    }
    public function CampaignStorge(Request $request)
    {
        //return $request->all();

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
        if (isset($campaignData['label_title']) && isset($campaignData['label_value']) && isset($campaignData['label_color']) && isset($campaignData['is_wining_label']) && isset($campaignData['init_prize']) && isset($campaignData['available_prize'])) {
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

//    public function edit($id)
//    {
//        $userId = auth('partner')->user()->id;
//        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $userId)->select('id', 'name', 'unique_identifier')->get();
//        $data = DB::table('campaigns')
//            ->where('campaigns.id',  request()->id)
//            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
//            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color','spiner_data.init_prize','spiner_data.available_prize','spiner_data.is_wining_label')
//            ->get();
//
//        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
//            return [
//                'id' => $groupedItems->first()->id,
//                'name' => $groupedItems->first()->name,
//                'card_id' => $groupedItems->first()->card_id,
//                'price_check' => $groupedItems->first()->price_check,
//                'point_check' => $groupedItems->first()->point_check,
//                'unit_price_for_coupon' => $groupedItems->first()->unit_price_for_coupon,
//                'unit_price_for_point' => $groupedItems->first()->unit_price_for_point,
//                'coupon' => $groupedItems->first()->coupon,
//
//                'spiner' => $groupedItems->map(function ($item) {
//                    return [
//                        'label_title' => $item->label_title,
//                        'label_value' => $item->label_value,
//                        'label_color' => $item->label_color,
//                        'spiner_data_id' => $item->spiner_data_id,
//                        'init_prize'=>$item->init_prize,
//                        'available_prize'=>$item->available_prize,
//                        'is_wining_label'=>$item->is_wining_label,
//                    ];
//                })->toArray(),
//            ];
//        })->values()->first();
//
//
//        return view('luminouslabs::edit', compact('result', 'cards'));
//    }
//
//    public function update(Request $request,)
//    {
//
//        $campaignData = $request->all();
//        $campaign_id = request()->id;
//
//        DB::table('campaigns')->where('id', $campaign_id)->update([
//            'name' => $campaignData['name'],
//            'card_id' => $campaignData['card_id'],
//            'price_check' => isset($request->campaign_type) && $request->campaign_type == 'only_prize' ? $request->campaign_type : '',
//            'point_check' => isset($request->campaign_type) && $request->campaign_type == 'prize_and_point' ? $request->campaign_type : '',
//            'unit_price_for_coupon' => $request->unit_price_for_coupon,
//            'unit_price_for_point' => $request->unit_price_for_point,
//            'coupon' => isset($request->coupon) ? $request->coupon : null,
//        ]);
//
//        foreach ($campaignData['label_title'] as $key => $item){
//            $checkBox = $campaignData['is_wining_label'][$key] == 1 ? true : false;
//            DB::table('spiner_data')->updateOrInsert(['id'=>$campaignData['spinner_id'][$key]],[
//                    'cam_id' => $campaign_id,
//                    'label_title' =>$campaignData['label_title'][$key],
//                    'label_value' =>$campaignData['label_value'][$key],
//                    'label_color' =>$campaignData['label_color'][$key],
//                    'init_prize' =>isset($campaignData['init_prize'][$key]) ? $campaignData['init_prize'][$key] : 0,
//                    'available_prize' =>isset($campaignData['available_prize'][$key]) ? $campaignData['available_prize'][$key] : 0,
//                    //'is_wining_label' =>isset($campaignData['is_wining_label'][$key]) && $campaignData['is_wining_label'][$key] == '1' ? true : false,
//                    'is_wining_label' =>$checkBox,
//                ]);
//        }
//
//        /*if (isset($campaignData['label_title']) && isset($campaignData['label_value']) && isset($campaignData['label_color']) && isset($campaignData['spiner_title_id']) && isset($campaignData['init_prize']) && isset($campaignData['available_prize']) && isset($campaignData['is_wining_label'])) {
//           return "return interif";
//            // Get the number of items in the label arrays
//            $numItems = count($campaignData['label_title']);
//            for ($i = 0; $i < $numItems; $i++) {
//                $spinerId = $campaignData['spiner_title_id'][$i];
//                $spinerData = [
//                    'cam_id' => $campaign_id,
//                    'label_title' => $campaignData['label_title'][$i],
//                    'label_value' => $campaignData['label_value'][$i],
//                    'label_color' => $campaignData['label_color'][$i],
//                    'is_wining_label' => isset($campaignData['is_wining_label'][$i]) && $campaignData['is_wining_label'][$i] == 'on' ? true : false,
//                    'init_prize' => isset($campaignData['init_prize'][$i]) ? $campaignData['init_prize'][$i] : 0,
//                    'available_prize' => isset($campaignData['available_prize'][$i]) ? $campaignData['available_prize'][$i] : 0,
//                ];
//
//                return $numItems;
//
//                DB::table('spiner_data')->updateOrInsert(
//                    ['id' => $spinerId, 'cam_id' => $campaign_id],
//                    $spinerData
//                );
//            }
//        }*/
//
//        //return "return outer if ";
//
//        return redirect()->route('luminouslabs::partner.campain.manage');
//    }


    public function edit($id)
    {

        $user = auth('partner')->user();
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $user->id)->select('id', 'name', 'unique_identifier')->get();

        $response = Http::get('https://keoswalletapi.luminousdemo.com/api/get-partner-template-for-user/'.$user->email);
        if ($response->successful()) {
            $templates = $response->json();
        } else {
            return "fail to fetch";
            return back()->withErrors(['error' => 'Failed to fetch templates']);
        }

        $data = DB::table('campaigns')
            ->where('campaigns.id',  request()->id)
            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color','spiner_data.init_prize','spiner_data.available_prize','spiner_data.is_wining_label')
            ->get();

        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
            return [
                'id' => $groupedItems->first()->id,
                'name' => $groupedItems->first()->name,
                'card_id' => $groupedItems->first()->card_id,
                'template_id' => $groupedItems->first()->template_id,
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
                        'init_prize' => $item->init_prize,
                        'available_prize' => $item->available_prize,
                        'is_wining_label' => $item->is_wining_label,
                        'spiner_data_id' => $item->spiner_data_id,
                    ];
                })->toArray(),
            ];
        })->values()->first();

        return view('luminouslabs::edit', compact('result', 'cards','templates'));
    }

    public function update(Request $request,)
    {
        $campaignData = $request->all();
        $campaign_id = request()->id;



        $templateInfo = $request->template_info;
        $template_parts = explode('|', $templateInfo);
        $template_id = $template_parts[0] ?? null;
        $pass_type = $template_parts[1] ?? null;

        DB::table('campaigns')->where('id', $campaign_id)->update([
            'name' => $campaignData['name'],
            'card_id' => $campaignData['card_id'],
            'template_id' => $template_id,
            'template_pass_type' => $pass_type,
            'price_check' => isset($request->campaign_type) && $request->campaign_type == 'only_prize' ? $request->campaign_type : '',
            'point_check' => isset($request->campaign_type) && $request->campaign_type == 'prize_and_point' ? $request->campaign_type : '',
            'unit_price_for_coupon' => $request->unit_price_for_coupon,
            'unit_price_for_point' => $request->unit_price_for_point,
            'coupon' => isset($request->coupon) ? $request->coupon : null,
            'campaign_type' => $request->campaign_type,
        ]);



        $campData = Campaign::findOrFail($campaign_id);

        foreach ($request->label_title as $i => $value){
            $data[] = [
                'cam_id' =>  $campaign_id,
                'campaign_id' => $campaign_id,
                'label_title' => $request->label_title[$i],
                'label_value' => $request->label_value[$i],
                'label_color' => $request->label_color[$i],
                'init_prize' => isset($request->init_prize[$i]) ? $request->init_prize[$i] : 0,
                'available_prize' => isset($request->available_prize[$i]) ? $request->available_prize[$i] : 0,
                'is_wining_label' => isset($request->is_wining_label[$i]) && $request->is_wining_label[$i] == 'on' ? true : false
            ];
        }


        $campData->spinnerData()->delete();
        $campData->spinnerData()->createMany($data);



//        if (isset($campaignData['label_title']) && isset($campaignData['label_value']) && isset($campaignData['label_color']) && isset($campaignData['spiner_title_id']) && isset($campaignData['init_prize']) && isset($campaignData['available_prize']) && isset($campaignData['is_wining_label'])) {
//            // Get the number of items in the label arrays
//            $numItems = count($campaignData['label_title']);
//            for ($i = 0; $i < $numItems; $i++) {
//                $spinerId = $campaignData['spiner_title_id'][$i] ?? null;
//                $spinerData = [
//                    'cam_id' => $campaign_id,
//                    'label_title' => $campaignData['label_title'][$i],
//                    'label_value' => $campaignData['label_value'][$i],
//                    'label_color' => $campaignData['label_color'][$i],
//                    'init_prize' => $campaignData['init_prize'][$i] ?? null,
//                    'available_prize' => $campaignData['available_prize'][$i] ??  null,
//                    'is_wining_label' => isset($campaignData['is_wining_label'][$i]) && $campaignData['is_wining_label'][$i] == 'on' ? true : false,
//                ];
//
//                DB::table('spiner_data')->updateOrInsert(
//                    ['id' => $spinerId, 'cam_id' => $campaign_id],
//                    $spinerData
//                );
//            }
//        }
//


        return redirect()->route('luminouslabs::partner.campain.manage');
    }


    public function view($id)
    {
        $user = auth('partner')->user();
        $cards = DB::table('cards')->where('is_active', 1)->where('created_by', $user->id)->select('id', 'name', 'unique_identifier')->get();
        $data = DB::table('campaigns')
            ->where('campaigns.id',  request()->id)
            ->leftJoin('spiner_data', 'campaigns.id', '=', 'spiner_data.cam_id')
            ->select('campaigns.*', 'spiner_data.id as spiner_data_id', 'spiner_data.label_title', 'spiner_data.label_value', 'spiner_data.label_color','spiner_data.init_prize','spiner_data.available_prize','spiner_data.is_wining_label')
            ->get();

        $response = Http::get('https://keoswalletapi.luminousdemo.com/api/get-partner-template-for-user/'.$user->email);
        if ($response->successful()) {
            $templates = $response->json();
        } else {
            return "fail to fetch";
            return back()->withErrors(['error' => 'Failed to fetch templates']);
        }


        $result = collect($data)->groupBy('id')->map(function ($groupedItems) {
            return [
                'id' => $groupedItems->first()->id,
                'name' => $groupedItems->first()->name,
                'card_id' => $groupedItems->first()->card_id,
                'template_id' => $groupedItems->first()->template_id,
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
                        'init_prize'=>$item->init_prize,
                        'available_prize'=>$item->available_prize,
                        'is_wining_label' => $item->is_wining_label,
                    ];
                })->toArray(),
            ];
        })->values()->first();

        return view('luminouslabs::view', compact('result', 'cards','templates'));
    }

    public function campainWinners(Request $request)
    {

        $campaginId = $request->id;

        $members = SpinReward::with(['member','memberPass'])->where('campaign_id',$campaginId)->get();

//        $memberCards = DB::table('member_cards')
//            ->select('member_cards.*','members.name','members.email')
//            ->join('members','member_cards.member_id','=','members.id')
//            ->where(['campagin_id'=>$campaginId , 'partner_id' => $partnerId])
//            ->get();

//        $campagin = DB::table('campaigns')
//            ->where(['id'=>$request->id])
//            ->first();
//
//        $campaginCard = DB::table('cards')
//            ->select('name')
//            ->where('id',$campagin->card_id)
//            ->first();
//
//
//        $winners = DB::table('campaign_member')
//            ->select('campaign_member.campaign_id','campaign_member.member_id','campaign_member.spinner_round','campaign_member.rewards','members.name','members.email','member_cards.spinner_point')
//            ->join('members','members.id','=','campaign_member.member_id')
//            ->join('member_cards','member_cards.member_id','=','campaign_member.member_id')
//            ->where('campaign_id',$request->id)
//            ->where('is_claimed',true)
//            ->get();

//        $winnerPoint = DB::table('member_cards')
//            ->where('member_id)

        return view('luminouslabs::winners',compact('members'));
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



    public function readGoogleJson($type)
    {
        if ($type == 'google'){
            $path = public_path('google.json');
        }else if($type == 'apple'){
            $path = public_path('apple.json');
        }else{
            return "Type Not Found";
        }

        if (file_exists($path)) {
           return  $contents = file_get_contents($path);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function templateCreate()
    {
        $type = request('type');
        $data = $this->readGoogleJson($type);

//        $jsonData = '{ "passDetails": { "logoImage": "", "heroImage": "", "color": "rgb(30, 49, 129)", "labelColor": "rgb(255, 255, 255)", "formate": "QR_CODE", "barcodeValue": "", "passTypeIdentifier": "", "passId": "", "header": "My Organization", "cardTitle": "My Loyalty Card", "activeCardName": "GenericPass" }, "textModulesData": { "firstRowData": [ { "id": 4, "expirySettings": { "label": "Expiry Settings", "value": "Not Expired" }, "displayValue": { "label": "Date Format", "value": "2025-02-11T09:54:44+00:00" }, "label": { "label": "Label", "value": "Expiry Date", "baseValue": "Expiry Date" } }, { "id": 14, "label": { "label": "Label", "value": "Name", "baseValue": "Name" }, "displayValue": { "label": "Display Value", "value": "Muilon" } }, { "id": 140, "label": { "label": "Label", "value": "Name", "baseValue": "Name" }, "displayValue": { "label": "Display Value", "value": "Rawshan" } } ], "secondRowData20": [ { "id": 132, "label": { "label": "Label", "value": "Mobile Number", "baseValue": "Mobile Number" }, "displayValue": { "label": "Display Value", "value": "0166" } }, { "id": 142, "label": { "label": "Label", "value": "Name", "baseValue": "Name" }, "displayValue": { "label": "Display Value", "value": "Sajjad" } } ], "secondRowData2": [ { "id": 130, "label": { "label": "Label", "value": "Mobile Number", "baseValue": "Mobile Number" }, "displayValue": { "label": "Display Value", "value": "0177" } }, { "id": 1400, "label": { "label": "Label", "value": "Name", "baseValue": "Name" }, "displayValue": { "label": "Display Value", "value": "Shakib" } } ], "secondRowData200": [ { "id": 1320, "label": { "label": "Label", "value": "Mobile Number", "baseValue": "Mobile Number" }, "displayValue": { "label": "Display Value", "value": "0199" } } ] }, "userId": 1, "id": "3388000000022308850", "classId": "3388000000022308850" }';
//        $data = json_decode($jsonData, true);

        return view('luminouslabs::create-template',[
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function templateStore(Request $request)
    {
        $type = $request->pass_type;
        $data = $request->google_data;
        $dataArray = json_decode($data, true);

        try {
            if ($type == 'google'){
                $url = "https://keoswalletapi.luminousdemo.com/api/google-generate-pass";
            }else if($type == 'apple'){
                $url = "https://keoswalletapi.luminousdemo.com/api/generate-pass";
            }else{
                return "invalide type";
            }

            $response = Http::post($url,$dataArray);

            if ($response->ok()){
                return back()->with('tempalte-create','Template Create Successfully');
            }
        }catch (e){
            return e;
        }
    }

    public function userTemplateDetails()
    {
        $memberId = request()->memberId;
        $member = SpinPoint::where('member_id',$memberId)->first();


        try {
            $response = Http::post('https://keoswalletapi.luminousdemo.com/api/download-pass-by-loyalty-user',[
                "pass_type" => $member->template_pass_type,
                "pass_id" => $member->template_id
            ]);

            if ($response->successful()) {
                $content = $response->body();
                $filename = '';

                if ($member->template_pass_type == 'apple') {
                    $filename = Str::random(7) . $memberId . '.pkpass';
                    $content_type = 'application/vnd.apple.pkpass';
                } elseif ($member->template_pass_type == 'google') {
                    $filename = Str::random(7) . $memberId . '.gpass';
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

        return $member;
    }

    public function getTemplateInfo(Request $request)
    {
        $templateId = $request->input('template_id');
        $passType = $request->input('pass_type');

        try {
            $response = Http::get('https://keoswalletapi.luminousdemo.com/api/get-data-from-loyalty-for-edit',[
                "pass_type" => $passType,
                "pass_id" => $templateId
            ]);

            if ($response->ok()){
               return json_decode($response->body());
            }
        }catch (e){
            return e;
        }


        return response()->json([
            'id' => $templateId,
            'type' => $passType,
        ]);
    }
}
